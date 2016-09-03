<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proyectos extends MY_Controller {
	function __construct(){
		parent::__construct();
		// Load the Library
        $this->load->helper('url');
		$this->load->model('Proyectos_model');
	}


	public function index()
	{
		$this->user->on_invalid_session('account/index');
		//if (!$this->user->has_permission('proyectos')) redirect('/');		
		$this->template_type = 'admin';
		$user_id = ($this->user->has_permission('admin'))?null:$this->user->get_id();
		$vars = array('proyectos' => $this->Proyectos_model->get_all($user_id));
		$this->template('proyectos/listado', $vars);
	}
	
	public function upload_from_editor() {	
		if (!$this->user->has_permission('arquetipos')) die("Not allowed");		
		$upload_path_url = base_url().'uploads/';
	
		$config['upload_path'] = BASEPATH . '../assets/uploads/from_editor/';
		$config['allowed_types'] = '*';
		$config['max_size']	= '100000';
		
	  	$this->load->library('upload', $config);
		
		$funcNum = $this->input->get('CKEditorFuncNum');

	  	if ( ! $this->upload->do_upload('upload')) {
	  		$message = $this->upload->display_errors();
	  		$url = "";
		} else {
			#die('2');
			$data = $this->upload->data();
			$url = assets_url('/uploads/from_editor/'. $data['file_name']);
			$message = "";
		}
		$this->output
		    ->set_output("<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>");
	}

	public function do_upload() {	
		if (!$this->user->has_permission('arquetipos')) redirect('/');		
		$upload_path_url = base_url().'uploads/';
	
		$config['upload_path'] = BASEPATH . '../assets/uploads/arquetipos/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '100000';
		$config['max_width']  = '280';
		$config['max_height']  = '280';
		
	  	$this->load->library('upload', $config);
		
	  	if ( ! $this->upload->do_upload('file')) {
	  		$error = array('error' => $this->upload->display_errors());
	  		echo json_encode(array($error));
		} else {
			#die('2');
			$data = $this->upload->data();
			//set the data for the json array	
			$info = array();
			$info['name'] = $data['file_name'];
	        $info['size'] = $data['file_size'];
			$info['type'] = $data['file_type'];
		    $info['url'] = assets_url('/uploads/arquetipos/'. $data['file_name']);
			
			//this is why we put this in the constants to pass only json data
			if (IS_AJAX) {
				echo json_encode($info);
				//this has to be the only data returned or you will get an error.
				//if you don't give this a json array it will give you a Empty file upload result error
				//it you set this without the if(IS_AJAX)...else... you get ERROR:TRUE (my experience anyway)

			// so that this will still work if javascript is not enabled
			} else {
		  		$file_data['upload_data'] = $this->upload->data();
			  	$this->load->view('admin/upload_success', $file_data);
			}
		}
	}
	
	/* edita el titulo y la descripcion del una subseccion, no el contenido */ 
	public function ajax_editar_subseccion($id_proyecto, $titulo='', $id_subseccion=0) { 
		if (!$this->user->has_permission('proyectos')) redirect('/');	
		if (!$this->is_mine($id_proyecto)) die('Operacion no permitida');	
		$this->load->helper('form');
		$this->load->library('form_validation');
		$vars = ['id_proyecto' => $id_proyecto, 'titulo' => $titulo, 'id_subseccion' => $id_subseccion];
		$this->form_validation->set_rules('nombre', 'Nombre', 'required|max_length[200]');

		if ($this->input->post() && $this->form_validation->run() === True) { 
			$fields = ['nombre','descripcion'];
			# <lazy block>
			$data = new stdClass();
			foreach ($fields as $key) $data->$key = $this->input->post($key);
			foreach ($vars as $key=>$value) $data->$key = $value;
			$data->seccion = urldecode($data->titulo);
			unset($data->titulo);
			$data->id = $data->id_subseccion;
			unset($data->id_subseccion);
			$this->Proyectos_model->editar_subseccion($data);
			if ($data->id) {
				$this->session->set_flashdata('success_message', 'La subsección fue editada con éxito.');
			} else { 
				$this->session->set_flashdata('success_message', 'La subsección fue creada éxito.');
			}
			$url = "/proyectos/editar/$id_proyecto";
			if (urldecode($titulo) == 'gestión') $url.='#tab2';
			if (urldecode($titulo) == 'contexto') $url.='#tab3';
			redirect($url);		
		}
		if ($id_subseccion) $vars['subseccion'] = $this->Proyectos_model->get_subseccion($id_subseccion);
		$this->load->view('/proyectos/ajax_editar_subseccion', $vars);
	}

	public function borrar_subseccion($id) { 
		if (!$this->user->has_permission('proyectos')) redirect('/');	
		$subseccion = $this->Proyectos_model->get_subseccion($id);
		if (!$this->is_mine($subseccion->id_proyecto)) die('Operacion no permitida');	
		if ($this->Proyectos_model->borrar_subseccion($id)) { 
			$this->session->set_flashdata('success_message', 'La subsección fue borrada.');
		} else {
			$this->session->set_flashdata('error_message', 'La subsección no pudo ser borrada.');
		}
		$url = "/proyectos/editar/{$subseccion->id_proyecto}";
		if (urldecode($subseccion->seccion) == 'gestión') $url.='#tab2';
		if (urldecode($subseccion->seccion) == 'contexto') $url.='#tab3';
		redirect($url);		
	}

	public function nuevo() { 
		if (!$this->user->has_permission('proyectos')) redirect('/');		
		$data = (object)['saved' => 0, 'id_user' => $this->user->get_id()];
		$id = $this->Proyectos_model->insert($data);
		$this->Proyectos_model->insert_subsecciones_default($id);
		redirect("/proyectos/editar/$id");

	}

	public function visualizar($proyecto_id) {
		if (!$this->user->has_permission('proyectos') and !$this->user->has_permission('proyecto_colaborador')) redirect('/');		
		if (!$this->is_mine($proyecto_id)) die('Operacion no permitida');
		$this->template_type = 'admin';
		$proyecto = $this->Proyectos_model->get($proyecto_id);
		$colores = [
	        'diseño'=>['F23A65','D70060','E54028','F18D05','FFCC00','D0D102','61AE24','32742C','00CCCC','0099FF','3333FF','113F8C','6600CC','CC33FF'],
	        'gestión' => ['ECEDED','D9DADB','C6C7C8','87888A','707173','58585A','','B1B3B4'],
	        'contexto' => ['F8E8D8','F0D5CF','E8D0C0','E2C6B4','D3B6A2','BB9C86','D29489','987764']
    	];
    	$vars['proyecto'] = $proyecto['proyecto'];
    	$vars['subsecciones'] = [];
    	$total = 0;
    	$completed = 0;
    	foreach ($proyecto['secciones'] as $seccion=>$subsecciones) { 
    		foreach ($subsecciones as $i=>$subseccion) { 
    			$total++;
    			$vars['subsecciones'][] = [
    				'id' => $subseccion->id,
    				'section' => ucfirst($seccion),   
    				'name' => $subseccion->nombre, 
    				'completed' => (int) (bool) $subseccion->contenido, 
    				'color' => '#' . $colores[$seccion][$i]
    			];
    			if ($subseccion->contenido) $completed++;
    		}
    	}
    	$vars['percentage'] = number_format(($completed/$total) * 100,1);
    	#echo '<pre>';print_r($vars);die();
		$this->template('proyectos/visualizar', $vars);
	}

	public function fichas($proyecto_id) {
		if (!$this->user->has_permission('proyectos') and !$this->user->has_permission('proyecto_colaborador')) redirect('/');
		if (!$this->is_mine($proyecto_id)) die('Operacion no permitida');
		$this->template_type = 'admin';
		$vars = $this->Proyectos_model->get($proyecto_id);
    	#echo '<pre>';print_r($vars);echo '</pre>';
		$this->template('proyectos/fichas', $vars);
	}

	public function colaboradores($proyecto_id) { 
		if (!$this->user->has_permission('proyectos')) redirect('/');		
		if (!$this->is_mine($proyecto_id)) die('Operacion no permitida');
		$this->template_type = 'admin';
		if ($this->input->post()) { 
			foreach ($this->input->post('colaborador') as $id => $status) { 
				if (strtoupper($status) != "ON") continue;
				$this->Proyectos_model->borrar_colaborador($proyecto_id, $id);
			}
		}
		$vars = $this->Proyectos_model->get($proyecto_id);
		$vars['proyecto_id'] = $proyecto_id;
		#echo '<pre>';print_r($vars);echo '</pre>';
		$this->template('proyectos/colaboradores', $vars);
		//die('aca');
	}

	private function _invitar_colaborador($proyecto_id, $colaborador, $msj_custom) { 
		if (!$this->user->has_permission('proyectos')) redirect('/');		
		if (!$this->is_mine($proyecto_id)) die('Operacion no permitida');

		$this->load->library(array('user', 'user_manager'));
        $this->load->model('Usuarios_model');
        $this->load->model('Email_model');

        # escearios: 
        # 1- Usuario no existe: agrego usuarios y permisos
        # 2- Usuario existe, no tiene permisos
        # 3- Usuario existe, tiene permisos, no tiene linkeado el proyecto
        $new_user = false;
        $usuario = (array) $this->Usuarios_model->get_by_email($colaborador['email']);
        if (!$usuario) { 
            # creo el usuario. 
            $CI = & get_instance();
            $password = substr(md5(time() . trim($colaborador['email']) . rand(0,10000)),0,8);
            $usuario = [
              'name' => $colaborador['primer_nombre'] . ' ' . $colaborador['ultimo_nombre'],
              'email' => $colaborador['email'],
              'login' => $colaborador['email'],
              'password' => $CI->bcrypt->hash($password)
            ];
            $usuario['id'] = $this->Usuarios_model->insert($usuario);
            $new_user = true;            
        }

        # tiene permisos de proyecto_colaborador? si no, los agregamos.
        if (!$this->user_manager->user_has_permission($usuario['id'], 5)) { 
            $this->user_manager->add_permission($usuario['id'], 5);
        }

        if (!$this->Proyectos_model->get_colaborador_by_id($proyecto_id, $usuario['id'])) {
            $this->Proyectos_model->agregar_colaborador($proyecto_id, $usuario['id']);
        }   
        $link = base_url();     
        if ($new_user) { 
			$vars = array(
				'link' => $link, 
				'name' => $usuario['name'], 
				'usuario' => $usuario['email'],
				'password' => $password,
				'msj_custom' => $msj_custom,
			);
			$message = $this->load->view('/emails/invitacion_proyecto_usuario_nuevo',$vars,true) ;
		} else { 
			$vars = array(
				'link' => $link, 
				'name' => $usuario['name'], 
				'msj_custom' => $msj_custom,
			);
			$message = $this->load->view('/emails/invitacion_proyecto',$vars,true) ;
		}
		$email = ['from' => '<no_reply@citep.mailgun.com> CITEP MIC', 
				  'to' => $usuario['email'],
				  'subject' => "Has sido invitado a colaborar en un proyecto",
				  'message' => $message
				  ];
		$this->Email_model->batch($email);
    }

	public function invitar($proyecto_id) { 
		if (!$this->user->has_permission('proyectos')) redirect('/');		
		if (!$this->is_mine($proyecto_id)) die('Operacion no permitida');
		$this->load->helper('email');
		$this->template_type = 'admin';
		$vars = $this->Proyectos_model->get($proyecto_id);
		if ($this->input->post()) { 
			$vars['error'] = '';
			$tmp = str_replace("\r","\n", $this->input->post('colaboradores'));
			$tmp = str_replace("\n\n","\n", $tmp);
			$invitados = array();
			$emails = array();
			foreach (explode("\n",$tmp) as $linea_orig) { 
				$linea = explode(",", $linea_orig);
				if (count($linea) != 3) {
					$vars['error'] = "La linea '$linea_orig' no es valida";
					break;
				}
				if (!valid_email(trim($linea[2]))) {
					$vars['error'] = "La linea '$linea_orig' contiene un email no valido";
					break;
				}
				if (@$emails[trim($linea[2])]) { 
					$vars['error'] = "La linea '$linea_orig' contiene un email duplicado en el mismo archivo";
					break;
				};
				$emails[trim($linea[2])] = True;
				$invitados[] = array(
					'primer_nombre' => trim($linea[0]),
					'ultimo_nombre' => trim($linea[1]),
					'email' => trim($linea[2]),
				);
			};
			if (!$vars['error']) { 
				$msj_custom = ($this->input->post('mensaje'))?$this->input->post('mensaje'):'';
				foreach($invitados as $e) { 
					$this->_invitar_colaborador($proyecto_id,$e,$msj_custom);
				}
				$this->session->set_flashdata('success_message', 'Colaboradores invitados');
				redirect("/proyectos/colaboradores/$proyecto_id");
			}
		}
		
		$vars['proyecto_id'] = $proyecto_id;
		$this->template('proyectos/colaboradores', $vars);
	}

	public function ajax_send_heartbeat($id) { 
		if (!$this->user->has_permission('proyectos') and !$this->user->has_permission('proyecto_colaborador')) redirect('/');
		$this->load->library('form_validation'); 
		$subseccion = $this->Proyectos_model->get_subseccion($id);
		if (!$this->is_mine($subseccion->id_proyecto)) die('Operacion no permitida');
		$this->Proyectos_model->send_heartbeat($id, $this->user->get_id());
		$obj_owner = $this->Proyectos_model->get_subseccion_owner($id, $this->user->get_id());
		if (!$obj_owner) {
			$return = ['allowed' => false];
		} else { 
			$obj_owner->allowed = (bool)($obj_owner->id == $this->user->get_id());
			$return = $obj_owner;
		}
		$this -> output -> set_content_type('application/json') -> set_output(json_encode($return));

	}
	/* editar CONTENIDO de subseccion */
	public function editar_subseccion($id) {
		if (!$this->user->has_permission('proyectos') and !$this->user->has_permission('proyecto_colaborador')) redirect('/');
		$this->load->library('form_validation'); 
		$subseccion = $this->Proyectos_model->get_subseccion($id);
		if (!$this->is_mine($subseccion->id_proyecto)) die('Operacion no permitida');
		$this->Proyectos_model->send_heartbeat($id, $this->user->get_id());
		$obj_owner = $this->Proyectos_model->get_subseccion_owner($id, $this->user->get_id());
		$save_avail = (bool) ($obj_owner and ($obj_owner->id == $this->user->get_id()));

		if ($subseccion->tipo != 'texto') { 
			redirect("/proyectos/{$subseccion->tipo}/{$subseccion->id_proyecto}");
		}
		if ($this->input->post()) { 
			#var_dump($save_avail);die();
			if ($save_avail) { 
				$data = (object) ['id' => $id, 'contenido' => $this->input->post('contenido')];
				if ($this->Proyectos_model->editar_subseccion($data)) { 
					$this->session->set_flashdata('success_message', 'Datos guardados');
				} else { 
					$this->session->set_flashdata('error_message', 'Problema guardando datos');
				}
			} else { 
				$this->session->set_flashdata('error_message', "Datos no guardados - Esta subsección esta siendo editada por {$obj_owner->name} ({$obj_owner->email})");
			}
			redirect('/proyectos/visualizar/' . $subseccion->id_proyecto);
		}
		$this->template_type = 'admin';
		$vars = [
					'subseccion' => $subseccion, 
				 	'comentarios' => $this->Proyectos_model->listar_comentarios($subseccion->id),
				 	'save_avail' => $save_avail
				 ];
		$this->template('proyectos/editar_subseccion', $vars);
	}

	public function enviar_comentario($id_subseccion) { 
		$this->load->helper('form');
		$this->load->library('form_validation'); 
		if (!$this->user->has_permission('proyectos') and !$this->user->has_permission('proyecto_colaborador')) redirect('/');
		$subseccion = $this->Proyectos_model->get_subseccion($id_subseccion);
		if (!$this->is_mine($subseccion->id_proyecto)) die('Operacion no permitida');
		if ($this->input->post()) { 
			$data = (object) [
				'id_proyecto_subseccion' => $id_subseccion, 
				'comentario' => $this->input->post('comentario'), 
				'id_user' => $this->user->get_id(), 
				'created_at' => date('Y-m-d H:i:s')
			];
			if ($this->Proyectos_model->agregar_comentario($data)) { 
				$this->session->set_flashdata('success_message', 'Comentario guardados');
			} else { 
				$this->session->set_flashdata('error_message', 'Problema guardando comentario');
			}
			redirect('/proyectos/editar_subseccion/' . $subseccion->id);
		} else { 
			die('Operación no permitida');
		}
	}
	public function editar($proyecto_id)
	{
		if (!$this->user->has_permission('proyectos')) redirect('/');		
		if (!$this->is_mine($proyecto_id)) die('Operacion no permitida');
		$this->load->helper('form');
		$this->load->library('form_validation');
		#$list = scandir()
		
		$this->form_validation->set_rules('nombre', 'Nombre', 'required|min_length[5]|max_length[200]');
		$vars['extra_errors'] = '';
		
		if ($this->input->post()  && $this->form_validation->run() === True && !$vars['extra_errors']) { 
			$data = $this->input->post(); 
			$data['saved'] = 1;
			$this->Proyectos_model->update($proyecto_id, $data);
			$this->session->set_flashdata('success_message', 'El proyecto fue actualizado éxitosamente.');
			redirect("/proyectos/visualizar/$proyecto_id");
		}

		
		$this->template_type = 'admin';
		$vars = array_merge($vars,$this->Proyectos_model->get($proyecto_id));
		#echo '<pre>';print_r($vars);echo '</pre>';
		$this->template('proyectos/editar', $vars);
	}

	public function duplicar($arquetipo_id)
	{
		if (!$this->user->has_permission('arquetipos')) redirect('/');		
		if (!$this->is_mine($arquetipo_id)) die('Operacion no permitida');
		$nu_id = $this->Arquetipos_model->duplicar($arquetipo_id);
		$this->session->set_flashdata('success_message', 'Ejercicio duplicado');
		redirect('/arquetipos/editar/' . $nu_id);
	
	}

	public function borrar($proyecto_id)
	{
		if (!$this->user->has_permission('proyectos')) redirect('/');		
		if (!$this->is_mine($proyecto_id)) die('Operacion no permitida');
		if ($this->Proyectos_model->delete($proyecto_id)) { 
			$this->session->set_flashdata('success_message', 'Proyecto eliminado');
		} else { 
			$this->session->set_flashdata('error_message', 'Problemas eliminando el proyecto');
		}
		redirect('/proyectos/');
	}

	public function ajax_respuestas($arquetipo_id, $alumno_id) { 
		if (!$this->user->has_permission('arquetipos')) redirect('/');		
		if (!$this->is_mine($arquetipo_id)) die('Operacion no permitida');
		$listado = $this->Arquetipos_model->alumno_respuestas($arquetipo_id, $alumno_id);
		$vars = array();
		foreach ($listado as $l) { 
			$vars['respuestas'][$l->imagen_id][] = $l;
		}
		$this->load->view("arquetipos/ajax_respuestas", $vars);
		#echo "<h1>Vengo de ajax $alumno_id</h1>";
	}


	public function estado($estado, $proyecto_id) {
		if (!$this->user->has_permission('proyectos')) redirect('/');		
		if (!$this->is_mine($proyecto_id)) die('Operacion no permitida');
		if ($this->Proyectos_model->update($proyecto_id, array('status' => $estado))) { 
			$this->session->set_flashdata('success_message', 'Procesamos el cambio de estado');
		} else { 
			$this->session->set_flashdata('error_message', 'No pudimos cambiar el estado');
		}
		redirect('/proyectos/');
	}

	public function gantt($proyecto_id = Null) {
		if (!$this->user->has_permission('proyectos')) redirect('/');		
		if (!$this->is_mine($proyecto_id)) die('Operacion no permitida');
		$this -> load -> model('Gantt_model');
		$this -> template_type = 'admin';
		$rdo = '';
		$row = $this -> Gantt_model -> get_gantt_id($proyecto_id);
		$total = count($row);
		for ($i = 0; $i < $total; $i++) {
			if ($row[$i] -> endIsMilestone) {
				$endMilestone = "true";
			} else {
				$endMilestone = "false";
			}
			if ($row[$i] -> startIsMilestone) {
				$startMilestone = "true";
			} else {
				$startMilestone = "false";
			}
			if ($i == 0) {
				$coma = '';
			} else {
				$coma = ",";
			}
			$aux = $i + 1;
			$var_aux = "-" . $aux;

			if ($row[$i] -> id != '') { 

				$rdo .= $coma . '{"id":' . $var_aux . ',"name":"' . $row[$i] -> name . '","code":"' . $row[$i] -> code . '","level":' . $row[$i] -> level . ',"status":"' . $row[$i] -> status . '","start":' . $row[$i] -> start . ',"duration":' . $row[$i] -> duration . ',"end":' . $row[$i] -> end . ',"startIsMilestone":' . $startMilestone . ',"endIsMilestone":' . $endMilestone . ',"assigs":[],"description":"'.$row[$i] -> description.'","progress":' . $row[$i] -> progress .', "depends":"'.$row[$i] -> depends.'"}';
			}

		}
		
		$vars = array("rdo" => $rdo, "id" => $proyecto_id);
		$this -> template('proyectos/gantt', $vars);
	}

	public function GuardarGantt() {
		$proyecto_id = $this->input->post('id');
		if (!$this->user->has_permission('proyectos')) redirect('/');		
		if (!$this->is_mine($proyecto_id)) die('Operacion no permitida');
		
		$this -> load -> model('Gantt_model');
		$respuesta = array("men" => "Guardado");
		
		$data = json_decode($this->input->post("json"),true);
		
		
		$this -> Gantt_model -> unset_gantt($proyecto_id);
		foreach ($data["tasks"] as $key=>$value) {
			if (is_array($value)) {
				foreach ($value as $subKey=>$subValue){
					$array[$subKey] = $subValue;
				}
				$array["assigs"] = "";
				$array["id_proyecto"] = $proyecto_id;
				
				$array["inicio"]  = date("Y-m-d",($array["start"]/1000)-10000);
				$array["fin"]     = date("Y-m-d",($array["end"]/1000)-20000);
				if (isset($array["id"]) && !empty($array["id"])) {
						$this -> Gantt_model -> set_gantt($array);
					}
				//print_r($array);
			}

		}

		$this -> output -> set_content_type('application/json') -> set_output(json_encode($respuesta));
	}

	public function calendario($proyecto_id) { 
		if (!$this->user->has_permission('proyectos')) redirect('/');		
		if (!$this->is_mine($proyecto_id)) die('Operacion no permitida');
		$vars = ['proyecto_id' => $proyecto_id];
		$vars['_css'] = array(assets_url("bower_components/fullcalendar/fullcalendar.css"));
		$this -> template_type = 'admin';
		$this -> template('proyectos/calendario', $vars);
	}

	public function eventos_ajax($proyecto_id) { 
		$this -> load -> model('Gantt_model');
		$tareas = $this->Gantt_model->get_gantt_calendar($proyecto_id);
		$this -> output -> set_content_type('application/json') -> set_output(json_encode($tareas));
	}
	
	private function is_mine($proyecto_id) { 
		if ($this->user->has_permission('admin')) return true;
		$ej = $this->Proyectos_model->get($proyecto_id);
		if (!$ej) return false;
		$allowed = ($ej['proyecto']->id_user === $this->user->get_id());
		if (!$allowed) {
			foreach ($ej['colaboradores'] as $e) { 
				if ($e->id == $this->user->get_id()) $allowed = true;
			}
		}
		return $allowed;
	}

}
