<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Arquetipos extends MY_Controller {
	function __construct(){
		parent::__construct();
		// Load the Library
        $this->load->helper('url');
		$this->load->model('Arquetipos_model');
	}
    public function printClose($imprimible){
        print_r($imprimible);
        exit;
    }
	public function enviar_devolucion() { 
		if (!$this->user->has_permission('arquetipos')) redirect('/');
		$ejercicio = $this->Arquetipos_model->get_ejercicio_by_alumno_id($this->input->post('alumno_id'));
		$alumno = $this->Arquetipos_model->get_alumno_by_id($this->input->post('alumno_id'));
		if (!$this->is_mine($ejercicio->id)) die('Operacion no permitida');
		$this->load->model('Email_model');
		$vars['profesor_nombre'] = $this->user->user_data->name;
		$vars['alumno_nombre'] = $alumno->primer_nombre . ' ' . $alumno->ultimo_nombre;
		$vars['texto'] = $this->input->post('texto');
		$email = ['from' => '<no_reply@citep.mailgun.com> CITEP MIC', 
				  'to' => $alumno->email,
				  'subject' => "Focos en juego: Devolución del profesor",
				  'message' => $this->load->view('/emails/prismas_devolucion',$vars,true) 
				  ];
		$this->Email_model->batch($email);
		$this->session->set_flashdata('success_message', 'Mensaje enviado');
		redirect('/arquetipos/ver_respuestas/' . $ejercicio->id);
	}


	
	public function alumno($hash) {
		if (!preg_match('/^\w+$/', $hash)) die('Caracteres no permitidos');
		$arquetipo_id = $this->Arquetipos_model->get_arquetipo_id_by_hash($hash);
		if (!$arquetipo_id) die('Link no valido');
		$this->template_type ='arquetipo'; 
		$vars = array();
        $vars['micSeleccionada'] = 'FOCOS';
		$vars['ejercicio'] = $this->Arquetipos_model->get($arquetipo_id);
		$vars['hash'] = $hash;
		$this->template('arquetipos/alumno_entrar', $vars);
	}

	

	public function alumno_ejercicio($public_id, $respuestas = null) {

        if(!isset($_SESSION)){
            session_start();
        }
        if(!isset($_SESSION["alias"])&& $_SERVER['REQUEST_METHOD'] === 'POST'){

            $alias= $_POST['alias'];
            if(strlen($alias)>=1) {
                $_SESSION["alias"] = $alias;
            }else{
                $_SESSION["alias"] = 'Anonimo-' . rand(1,10000);
            }
        }else if(!isset($_SESSION["alias"])){
            //si la sesión no tiene alias asignado lo mandamos a la págian de elegir ALIAS
            //esto quiere decir que entró por link_publico
            $vars['urlDestino'] = base_url(). 'arquetipos/alumno_ejercicio/' . $public_id;
            $this->template('account/solicitarAlias', $vars);
            return;
        }


        $ejercicio = $this->Arquetipos_model->get_ejercicio_by_public_id($public_id);

		if (!$ejercicio) die('El código de ejercicio que ingresaste no existe,' . '---' . $public_id);
        $arquetipo_id = $ejercicio->id ;
        $vars = array();
        $vars['micSeleccionada'] = 'FOCOS';
		if (isset($_POST['obtenerRespuestas'])) {
            $obtResp = $this->input->post('obtenerRespuestas');
            if ($obtResp != 1) {
                foreach ($this->input->post('respuesta') as $pregunta_id => $texto) {
                    $this->Arquetipos_model->agregar_respuesta($arquetipo_id, $pregunta_id, $this->input->post('img_id'), $texto);
                }
            }else{
                $vars['respuestasAnteriores'] = $respuestas;
            }
		}
		//$this->template_type ='arquetipo';
		$vars['imagenes'] = $this->Arquetipos_model->get_images($arquetipo_id);
		$vars['preguntas'] = $this->Arquetipos_model->get_questions($arquetipo_id);
		$vars['ejercicio'] = $ejercicio;
        $vars['alias'] = $_SESSION["alias"];

		$vars['public_id'] = $public_id;
		$this->load->library('user_agent');
		#echo '<pre>';print_r($vars);die();
		$this->template('arquetipos/alumno_ejercicio', $vars);
		
	}

    public function ajax_respuesta($public_id) {
        if(!isset($_SESSION)){
            session_start();
        }
        $ejercicio = $this->Arquetipos_model->get_ejercicio_by_public_id($public_id);
        $arquetipo_id = $ejercicio->id ;
        if (!$ejercicio) die('Link no valido' . '---' . $public_id);
        $nombre = $this->input->post('nombre');
        $alias =  $_SESSION["alias"];
        $imagen_id = $this->input->post('img_id');
        $tmp = $this->input->post('respuesta');
        $respuestas = $tmp[$imagen_id];
        $tmp2 = $this->input->post('pregunta');
        $preguntas = $tmp2[$imagen_id];
        $output = array('ok' => true);
        $mensaje = "";
        if (!is_array($respuestas)) {
            $output = array('ok' => false);
        } else {
            foreach ($respuestas as $pregunta_id => $r) {
                $this->Arquetipos_model->agregar_respuesta($arquetipo_id, $pregunta_id, $imagen_id, $r, $nombre, $alias);
                $mensaje .= '<b>'. $preguntas[$pregunta_id] .'</b>'. $r . '  <br/>';
            }
        }
        if($mensaje != ""){
            /*
                $this->load->library('email');
                $this->email->from('info@mic.en-construccion.net', 'CITEP MIC');
                $this->email->to($email);
                $this->email->subject('Tus respuestas en Focos en Juego');
                $this->email->message($mensaje);

                $this->email->send();
            */
        }


        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }

	public function alumno_consigna($hash) { 
		if (!preg_match('/^\w+$/', $hash)) die('Caracteres no permitidos');
		$arquetipo_id = $this->Arquetipos_model->get_arquetipo_id_by_hash($hash);
		if (!$arquetipo_id) die('Link no valido');
		$vars['ejercicio'] = $this->Arquetipos_model->get($arquetipo_id);
        $vars['micSeleccionada'] = 'FOCOS';
		$vars['hash'] = $hash;
		$this->template_type ='arquetipo'; 
		$this->template('arquetipos/alumno_consigna', $vars);
	}

	public function ejercicio_get_description($hash) { 
		if (!preg_match('/^\w+$/', $hash)) die('Caracteres no permitidos');
		$arquetipo_id = $this->Arquetipos_model->get_arquetipo_id_by_hash($hash);
		if (!$arquetipo_id) die('Link no valido');
		$ejercicio = $this->Arquetipos_model->get($arquetipo_id);
		$this->output->set_output($ejercicio->desarrollo);
	}


	public function publicar($id, $status) { 
		if (!$this->is_mine($id)) die('Operacion no permitida');
		if (!$this->user->has_permission('arquetipos')) redirect('/');		
		$this->Arquetipos_model->update($id, ['public_id_enabled'=> $status]);
		if ($status) { 
			$msg = "El link publico ha sido activado";
		} else { 
			$msg = "El link publico ha sido desactivado";
		}
		$this->session->set_flashdata('success_message', $msg);
		redirect('/arquetipos/');
	}

	public function preview($id) { 
		if (!$this->is_mine($id)) die('Operacion no permitida');
		if (!$this->user->has_permission('arquetipos')) redirect('/');
		$tmp = $this->Arquetipos_model->get_alumno_by_email($id, $this->user->get_email());

		if ($tmp) { 
			$e = (array) $tmp;
		} else { 
			$e = ['primer_nombre' => $this->user->get_name(),
                    'ultimo_nombre' => '',
                    'email' => $this->user->get_email(),
                    'hash' => md5(time() . trim($this->user->get_email()) . rand(0,10000)),
                    'ocultar' => true,
                 ];
            $this->Arquetipos_model->invitar_alumno($id, $e);
		}

		$link = base_url("/arquetipos/alumno/$e[hash]");
		$vars = array('link' => $link, 'name' => $this->user->get_name());
		$email = ['from' => '<no_reply@citep.mailgun.com> CITEP MIC', 
				  'to' => $e['email'],
				  'subject' => "Focos: Previsualización ejercicio",
				  'message' => $this->load->view('/emails/invitacion_ejercicio',$vars,true) 
				  ];
		$this->load->model('Email_model');
		$this->Email_model->batch($email);
		$this->session->set_flashdata('success_message', "Correo con link de preview enviado");
		redirect('/arquetipos/');
	}

	public function link_publico($public_id) {


        if (!$this->user->has_permission('arquetipos')) {
            $this->template_type = 'admin';
        }
        if(!isset($_SESSION)){
            session_start();
        }
		$ejercicio = $this->Arquetipos_model->get_ejercicio_by_public_id($public_id);
        $preguntas = $this->Arquetipos_model->get_questions($public_id);
        $ejercicio->preguntas = $preguntas;
		if (!$ejercicio or !$ejercicio->public_id_enabled) die('El código de ejercicio que ingresaste no existe');
        $alias= $_SESSION["alias"];
        $vars['alias'] = $alias;
		$vars['respuestas'] = array();
        $vars['micSeleccionada'] = 'FOCOS';
		$tmp_respuestas = $this->Arquetipos_model->detalle_respuestas($ejercicio->id, true);
        $crudoRespuestas = "";


		foreach ($tmp_respuestas as $e) {
            //print "Hay respuestas";
			#if (!isset($vars['respuestas'][$e->alumno_id])) $vars['respuestas'][$e->alumno_id] = array();
            if (!isset($vars['respuestas'][$e->imagen_id][$e->pregunta_id])){
                $vars['respuestas'][$e->imagen_id][$e->pregunta_id] = array();
            }
			//array_push($vars['respuestas'][$e->imagen_id], $e);
            $crudoRespuestas = $crudoRespuestas . ' '.  $e->respuesta;
            array_push($vars['respuestas'][$e->imagen_id][$e->pregunta_id], $e);
		}
        $crudoRespuestas = $crudoRespuestas . ' ';
        $vars['crudoRespuestas'] = $crudoRespuestas;
		$vars['imagenes'] = array();
		$tmp_imagenes = $this->Arquetipos_model->get_images($ejercicio->id);
		foreach ($tmp_imagenes as $imagen) { 
			$vars['imagenes'][$imagen->id] = array('url' => $imagen->imagen_ubicacion,
												   'titulo' => $imagen->titulo);
            //print $imagen->id . '---';
            //232---233---234---
		}
		$vars['ejercicio'] = $ejercicio;

       // print json_encode($vars);
      // exit;
        if ($this->user->has_permission('arquetipos')) {
            $this->template_type = 'admin';
        }
		$this->template('arquetipos/alumnos_resultados', $vars);

	}

	public function index()
	{

		$this->user->on_invalid_session('/');
		if (!$this->user->has_permission('arquetipos')) redirect('/');		
		$this->template_type = 'admin';
		$user_id = ($this->user->has_permission('admin'))?null:$this->user->get_id();
		$vars = array('ejercicios' => $this->Arquetipos_model->get_all($user_id) );
        $vars['micSeleccionada'] = 'FOCOS';
		$this->template('arquetipos/listado', $vars);
	}

    public function alumnoHome(){
        $this->template('alumnos/home', "");
    }

    public function ingresoAlumno(){
        session_start();
        $alias= trim($_POST['alias']);
        if(strlen($alias)<1){
            $alias = 'Anonimo-' . rand(1,10000);
        }
        $_SESSION["alias"] =$alias ;
        $publica = $this->input->post('id');
        $this->alumno_ejercicio($publica,null);
    }
	
	public function ver_respuestas($arquetipo_id)
	{
		if (!$this->is_mine($arquetipo_id)) die('Operacion no permitida');
		if (!$this->user->has_permission('arquetipos')) redirect('/');

        $vars = array();
        $vars['micSeleccionada'] = 'FOCOS';
        $ejercicio = $this->Arquetipos_model->get_ejercicio_by_public_id($arquetipo_id);
        $preguntas = $this->Arquetipos_model->get_questions($arquetipo_id);
        $ejercicio->preguntas = $preguntas;
        $vars['ejercicio'] = $ejercicio;
        $vars['respuestas'] = array();
        $tmp_respuestas = $this->Arquetipos_model->detalle_respuestas($ejercicio->id, false);
        $crudoRespuestas = "";

        foreach ($tmp_respuestas as $e) {
            //print "Hay respuestas";
            #if (!isset($vars['respuestas'][$e->alumno_id])) $vars['respuestas'][$e->alumno_id] = array();
            if (!isset($vars['respuestas'][$e->imagen_id][$e->pregunta_id])){
                $vars['respuestas'][$e->imagen_id][$e->pregunta_id] = array();
            }
            array_push($vars['respuestas'][$e->imagen_id][$e->pregunta_id], $e);
            $crudoRespuestas = $crudoRespuestas . ' '.  $e->respuesta;
        }
        $crudoRespuestas = $crudoRespuestas . ' ';
        $vars['crudoRespuestas'] = $crudoRespuestas;
        $vars['imagenes'] = array();

        $tmp_imagenes = $this->Arquetipos_model->get_images($ejercicio->id);
        foreach ($tmp_imagenes as $imagen) {
            $vars['imagenes'][$imagen->id] = array('url' => $imagen->imagen_ubicacion,
                'titulo' => $imagen->titulo);
            //print $imagen->id . '---';
            //232---233---234---
        }
		/*$vars['respuestas'] = array();
		$tmp_respuestas = $this->Arquetipos_model->listado_respuestas($arquetipo_id);
		foreach ($tmp_respuestas as $e) {
			if (!isset($vars['respuestas'])) $vars['respuestas'] = array();
			$vars['respuestas'][$e->imagen_id] = $e;
		}
		$vars['imagenes'] = $this->Arquetipos_model->get_images($arquetipo_id);
		$vars['arquetipo_id'] = $arquetipo_id;
		#echo '<pre>';print_r($vars);echo '</pre>';*/
		$this->template_type = 'admin';
		$this->template('arquetipos/listado_respuestas', $vars);
	}

	public function ver_respuestas_listado($arquetipo_id)
	{
		if (!$this->is_mine($arquetipo_id)) die('Operacion no permitida');
		if (!$this->user->has_permission('arquetipos')) redirect('/');		
		$vars = array();
		$vars['respuestas'] = array();
		$vars['alumnos'] = array();
        $vars['micSeleccionada'] = 'FOCOS';
		$tmp_respuestas = $this->Arquetipos_model->detalle_respuestas($arquetipo_id, false);
		foreach ($tmp_respuestas as $e) {
			#if (!isset($vars['respuestas'][$e->alumno_id])) $vars['respuestas'][$e->alumno_id] = array();
			$vars['respuestas'][$e->alumno_id][$e->imagen_id][$e->pregunta_id] = $e;
			$vars['alumnos'][$e->alumno_id] = $e;
		}
		$vars['imagenes'] = array();
		$tmp_imagenes = $this->Arquetipos_model->get_images($arquetipo_id);
		foreach ($tmp_imagenes as $imagen) { 
			$vars['imagenes'][$imagen->id] = array('url' => $imagen->imagen_ubicacion,
												   'titulo' => $imagen->titulo);
		}
		$vars['ejercicio'] = $this->Arquetipos_model->get($arquetipo_id);
		#echo '<pre>';print_r($vars);echo '</pre>';die();
		$this->template_type = 'admin';
		$this->template('arquetipos/detalle_respuestas', $vars);
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

	public function editar($arquetipo_id = null)
	{
		if (!$this->user->has_permission('arquetipos')) redirect('/');		
		$this->load->helper('form');
		$this->load->library('form_validation');
		#$list = scandir()
		$vars = array('stock_imgs' => array());
        $vars['micSeleccionada'] = 'FOCOS';
		$tmp = scandir(BASEPATH . '../assets/img/stock_arquetipos/'); 
		foreach ($tmp as $file) { 
			if (strlen($file) > 2) $vars['stock_imgs'][] = assets_url('/img/stock_arquetipos/' . $file);
		}/*
        print_r($vars['stock_imgs']);

        $tmp = scandir(BASEPATH . '../assets/uploads/arquetipos/');
        foreach ($tmp as $file) {
            if (strlen($file) > 2) $vars['stock_imgs'][] = assets_url('/img/stock_arquetipos/' . $file);
        }*/
		$vars['_css'] = array(assets_url("css/jquery.fileupload-ui.css"));
		$vars['preguntas'] = array();
		$vars['imagenes'] = array();
		if ($arquetipo_id) { 
			$arquetipo = $this->Arquetipos_model->get($arquetipo_id);
			if (!$arquetipo) die("Acceso no permitido");
			//if ($arquetipo->id_user != $this->user->get_id()) die("Acceso no permitido");
			$vars['preguntas'] = $this->Arquetipos_model->get_questions($arquetipo_id);
			$vars['arquetipo'] = $arquetipo;
			$tmp = $this->Arquetipos_model->get_images($arquetipo_id);
			$tmp_imagenes = array();
			foreach($tmp as $e) { 
				$source = "custom";
				// delete the ones we use here. 
				if (($key = array_search($e->imagen_ubicacion, $vars['stock_imgs'])) !== false) {
					$source = 'stock';
					unset($vars['stock_imgs'][$key]);
				}
				$tmp_imagenes[] = array(
						'url'=> $e->imagen_ubicacion,
                  		'source' => $source,
                  		'selected' => true,
                  		'titulo' => $e->titulo
                );
			}			
		}
		foreach ($vars['stock_imgs'] as $url) { 
			$tmp_imagenes[] = array(
					'url'=> $url,
              		'source' => 'stock',
              		'selected' => false,
            );
		}
		#echo '<pre>';print_r($tmp_imagenes);die();
		if ($this->input->post('imgs')) { 
			$vars['imgs'] = json_decode($this->input->post('imgs'), true);
		} else { 
			$vars['imgs'] = $tmp_imagenes;
		}
		foreach ($vars['imgs'] as &$img) { 
			if ($img['source'] == 'stock') $img['titulo'] = $this->Arquetipos_model->get_stock_image_name($img['url']);
		}
		#echo '<pre>';print_r($vars['imgs']);die();
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nombre', 'Nombre', 'required|min_length[5]|max_length[200]');
		$this->form_validation->set_rules('pregunta[]', 'Pregunta ', 'required|min_length[5]|max_length[200]');
		/*$this->form_validation->set_rules('pregunta[1]', 'Pregunta 2', 'required|min_length[5]|max_length[200]');
		$this->form_validation->set_rules('pregunta[2]', 'Pregunta 3', 'required|min_length[5]|max_length[200]');
		*/
        $this->form_validation->set_rules('consigna', 'Consigna', 'required|min_length[5]|max_length[200]');
		$this->form_validation->set_rules('desarrollo', 'descripción', 'required|min_length[10]|max_length[50000]');
		$vars['extra_errors'] = '';
		if ($this->input->post()) { 
			$imgs = json_decode($this->input->post('imgs'), true);
			$count = 0;
			foreach ($imgs as $e) {
				if ($e['selected']) $count++;
				if ($e['selected'] and $e['source'] == 'custom' and !$e['titulo']) $vars['extra_errors'] = "Falta el titulo de una de las imagenes subidas";
			}
			if ($count > 9) $vars['extra_errors'] = "Se pueden seleccionar sólo hasta 9 imagenes";
			if ($count < 1) $vars['extra_errors'] = "Se debe elegir por lo menos una imagen";
			#if ($count < 3) $vars['extra_errors'] = "Se deben seleccionar por lo menos 3 imagenes";
		}
		if ($this->input->post()  && $this->form_validation->run() === True && !$vars['extra_errors']) { 
			$data = $this->input->post();
            /*print_r($this->input->post('pregunta'));
            print_r( $vars['preguntas']);
            exit;*/
			$data['id_user'] = $this->user->get_id();
			unset($data['imgs']);

			unset($data['titulo_imagen']);
			unset($data['file']);
			if ($arquetipo_id) {
                $this->Arquetipos_model->actualizarPreguntas($arquetipo_id, $vars['preguntas'],$this->input->post('pregunta'));
                unset($data['pregunta']);
				$this->Arquetipos_model->update($arquetipo_id, $data);

				$this->session->set_flashdata('success_message', 'El Ejercicio fue actualizado éxitosamente.');
				redirect("/arquetipos");
			} else {
                unset($data['pregunta']);
				//$data['public_id'] = uniqid();
                $data['public_id'] = $this->Arquetipos_model->get_max_id();
				$data['status'] = 'habilitado';
				$arquetipo_id = $this->Arquetipos_model->insert($data);
				$this->Arquetipos_model->agregar_preguntas($arquetipo_id, $this->input->post('pregunta'));
				$imagenes = array();
				foreach (json_decode($this->input->post('imgs'), true) as $i) { 
					if (!$i['selected']) continue;
					if ($i['source'] == 'stock') $i['titulo'] = $this->Arquetipos_model->get_stock_image_name($i['url']);
					$imagenes[] = array('url' => $i['url'], 'titulo' => $i['titulo']);
				}
				$this->Arquetipos_model->agregar_imagenes($arquetipo_id, $imagenes);				
				$this->session->set_flashdata('success_message', 'El Ejercicio fue creado con éxito.');
				//redirect("/alumnos/invitar/arquetipos/$arquetipo_id");
                redirect("/arquetipos");
			}

		}


		if (!$this->is_mine($arquetipo_id)) die('Operacion no permitida');
		$this->template_type = 'admin';
		#echo '<pre>';print_r($vars);echo '</pre>';
		$this->template('arquetipos/editar', $vars);
	}

	public function duplicar($arquetipo_id)
	{
		if (!$this->user->has_permission('arquetipos')) redirect('/');		
		if (!$this->is_mine($arquetipo_id)) die('Operacion no permitida');
		$nu_id = $this->Arquetipos_model->duplicar($arquetipo_id);
		$this->session->set_flashdata('success_message', 'Ejercicio duplicado');
		redirect('/arquetipos/editar/' . $nu_id);
	
	}

	public function borrar($arquetipo_id)
	{
		if (!$this->user->has_permission('arquetipos')) redirect('/');		
		if (!$this->is_mine($arquetipo_id)) die('Operacion no permitida');
		if ($this->Arquetipos_model->delete($arquetipo_id)) { 
			$this->session->set_flashdata('success_message', 'Un ejercicio fue eliminado');
		} else { 
			$this->session->set_flashdata('error_message', 'Problemas eliminando el ejercicio');
		}
		redirect('/arquetipos/');
	}

	public function ajax_respuestas($arquetipo_id) {

		if (!$this->user->has_permission('arquetipos')) redirect('/');		
		if (!$this->is_mine($arquetipo_id)) die('Operacion no permitida');
		$listado = $this->Arquetipos_model->alumno_respuestas($arquetipo_id);
		$vars = array();
		foreach ($listado as $l) { 
			$vars['respuestas'][$l->imagen_id][] = $l;
		}
		$this->load->view("arquetipos/ajax_respuestas", $vars);
		#echo "<h1>Vengo de ajax $alumno_id</h1>";
	}


	public function estado($estado, $arquetipo_id) {
		if (!$this->user->has_permission('arquetipos')) redirect('/');		
		if (!$this->is_mine($arquetipo_id)) die('Operacion no permitida');
		if ($this->Arquetipos_model->update($arquetipo_id, array('status' => $estado))) { 
			$this->session->set_flashdata('success_message', 'Procesamos el cambio de estado');
		} else { 
			$this->session->set_flashdata('error_message', 'No pudimos cambiar el estado');
		}
		redirect('/arquetipos/');
	}

	private function is_mine($arquetipo_id) { 
		if ($this->user->has_permission('admin')) return true;
		$ej = $this->Arquetipos_model->get($arquetipo_id);
		return ($ej->id_user && $ej->id_user === $this->user->get_id());
	}

    public function hacer_publica($arquetipo_id, $respuesta_id){
        $this->Arquetipos_model->actualizar_publico($respuesta_id,1);
        $this->ver_respuestas($arquetipo_id);
    }
    public function ocultar($arquetipo_id,$respuesta_id){
        $this->Arquetipos_model->actualizar_publico($respuesta_id,0);
        $this->ver_respuestas($arquetipo_id);
    }

    public function publicarTodas($arquetipo_id ){
        $imagen_id = $this->input->post('imgId');
        // Oculto todas y pongo publicas solo las que me chequearon
        $ejercicio = $this->Arquetipos_model->ocultarTodas($arquetipo_id, $imagen_id);
        $listaChequeados = $this->input->post('pub');
        //print_r($listaChequeados);
        //exit;
        if($listaChequeados) {
            foreach ($listaChequeados as $l) {
                $this->Arquetipos_model->actualizar_publico($l, 1);
            }
        }
        $this->ver_respuestas($arquetipo_id);
    }

    public function respuestasAnteriores($arquetipo_id){
        if(!isset($_SESSION)){
            session_start();
        }
        $alias= $_SESSION["alias"];
        $vars['alias'] = $alias;
        $respuestas = $this->Arquetipos_model->listado_respuestas_por_mail($arquetipo_id, $alias);
        $vars['respuestasAnteriores'] = $respuestas;
        $ejercicio = $this->Arquetipos_model->get_ejercicio_by_public_id($arquetipo_id);
        $preguntas = $this->Arquetipos_model->get_questions($arquetipo_id);
        $ejercicio->preguntas = $preguntas;
        $vars['ejercicio'] = $ejercicio;
        $vars['imagenes'] = $this->Arquetipos_model->get_images($arquetipo_id);
        $vars['micSeleccionada'] = 'FOCOS';
        $this->template_type ='arquetipo';
        //$this->alumno_ejercicio($arquetipo_id, $respuestas);
        $this->template('arquetipos/respuestas_anteriores', $vars);
        //$this->printClose($respuestas);
    }

    public function cambiarAlias($public_id){
        if(!isset($_SESSION)){
            session_start();
        }
        unset($_SESSION["alias"]);
        $vars['urlDestino'] = base_url(). 'arquetipos/alumno_ejercicio/' . $public_id;
        $vars['micSeleccionada'] = 'FOCOS';
        $this->template('account/solicitarAlias', $vars);
        return;
    }

    public function publicar_nube($arquetipo_id){
        $this->Arquetipos_model->nube($arquetipo_id, 1);
        redirect('/arquetipos/ver_respuestas/' . $arquetipo_id);
    }

    public function ocultar_nube($arquetipo_id){
        $this->Arquetipos_model->nube($arquetipo_id, 0);
        redirect('/arquetipos/ver_respuestas/' . $arquetipo_id);
    }
}
