<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dialogos extends MY_Controller {
	function __construct(){
		parent::__construct();
		// Load the Library
        $this->load->helper('url');
		$this->load->model('Dialogos_model');
	}

	public function publicar($id, $status) { 
		if (!$this->is_mine($id)) die('Operacion no permitida');
		if (!$this->user->has_permission('dialogos')) redirect('/');		
		$this->Dialogos_model->update($id, ['public_id_enabled'=> $status]);
		if ($status) { 
			$msg = "El link publico ha sido activado";
		} else { 
			$msg = "El link publico ha sido desactivado";
		}
		$this->session->set_flashdata('success_message', $msg);
		redirect('/dialogos/');
	}

	public function link_publico($public_id) { 
		$this->template_type ='dialogo'; 
		$ejercicio = $this->Dialogos_model->get_ejercicio_by_public_id($public_id);
		if (!$ejercicio or !$ejercicio->public_id_enabled) die('Link publico no disponible');
		
		/*$vars['alumnos'] = array();
		$tmp_respuestas = $this->Arquetipos_model->detalle_respuestas($ejercicio->id);
		foreach ($tmp_respuestas as $e) {
			#if (!isset($vars['respuestas'][$e->alumno_id])) $vars['respuestas'][$e->alumno_id] = array();
			$vars['respuestas'][$e->imagen_id][$e->alumno_id][$e->pregunta_id] = $e;
			$vars['alumnos'][$e->alumno_id] = $e;
		}
		$vars['imagenes'] = array();
		$tmp_imagenes = $this->Arquetipos_model->get_images($ejercicio->id);
		foreach ($tmp_imagenes as $imagen) { 
			$vars['imagenes'][$imagen->id] = array('url' => $imagen->imagen_ubicacion,
												   'titulo' => $imagen->titulo);
		}*/
		$vars['respuestas'] = $this->Dialogos_model->respuestas($ejercicio->id);
		$vars['ejercicio'] = $ejercicio;
		//echo '<pre>';print_r($vars);echo '</pre>';
		$this->template('dialogos/alumnos_resultados', $vars);

	}

	public function ajax_respuesta($hash) { 
		if (!preg_match('/^\w+$/', $hash)) die('Caracteres no permitidos');
		$dialogo_id = $this->Dialogos_model->get_dialogo_id_by_hash($hash);
		$alumno_id = $this->Dialogos_model->get_alumno_id_by_hash($hash);
		if (!$dialogo_id) die('Link no valido');
		$imagen_id = $this->input->post('img_id');
		$tmp = $this->input->post('respuesta');
		$respuestas = $tmp[$imagen_id];
		$output = array('ok' => true);
		if (!is_array($respuestas)) {
			$output = array('ok' => false);
		} else { 
			foreach ($respuestas as $pregunta_id => $r) {
				$this->Dialogos_model->agregar_respuesta($dialogo_id, $pregunta_id, $alumno_id, $imagen_id, $r);
			}
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($output));
	}
	
	public function alumno($hash) {
		if (!preg_match('/^\w+$/', $hash)) die('Caracteres no permitidos');
		$dialogo_id = $this->Dialogos_model->get_dialogo_id_by_hash($hash);
		if (!$dialogo_id) die('Link no valido');
		$this->template_type ='dialogo'; 
		$vars = array();
		$vars['ejercicio'] = $this->Dialogos_model->get($dialogo_id);
		$vars['hash'] = $hash;
		#echo '<pre>';print_r($vars);die();
		$this->template('dialogos/alumno_entrar', $vars);
	}

	public function alumno_ejercicio($hash) {
		if (!preg_match('/^\w+$/', $hash)) die('Caracteres no permitidos');
		$dialogo_id = $this->Dialogos_model->get_dialogo_id_by_hash($hash);
		$alumno_id = $this->Dialogos_model->get_alumno_id_by_hash($hash);
		$alumno = $this->Dialogos_model->get_alumno_by_id($alumno_id);
		#print_r($alumno);
		if (!$dialogo_id) die('Link no valido');
		$this->template_type ='dialogo'; 
		$vars = array();
		$vars['ejercicio'] = $this->Dialogos_model->get($dialogo_id);
		$vars['respuestas'] = $this->Dialogos_model->detalle_respuestas($dialogo_id, $alumno_id);
		$vars['hash'] = $hash;
		if ($alumno->status == 'cerrado') { 
			$vars['puede_plantear_dialogo'] = false;
			$vars['razon_no_dialogo'] = "Ejercicio finalizado por el profesor";
		} elseif (!$vars['respuestas']) { 
			$vars['puede_plantear_dialogo'] = true;
		} else { 
			$vars['puede_plantear_dialogo'] = (bool)$vars['respuestas'][0]->calificacion;
			$vars['razon_no_dialogo'] = "Tiene un dialogo pendiente de análisis";
		}
		#echo '<pre>';print_r($vars);die();
		$this->template('dialogos/alumno_ejercicio', $vars);
	}

	public function alumno_responder($hash) {
		if (!preg_match('/^\w+$/', $hash)) die('Caracteres no permitidos');
		$dialogo_id = $this->Dialogos_model->get_dialogo_id_by_hash($hash);
		$alumno_id = $this->Dialogos_model->get_alumno_id_by_hash($hash);
		if (!$dialogo_id) die('Link no valido');
		$this->template_type ='dialogo'; 
		$data = $this->input->post();
		$data['dialogo_alumno_id'] = $alumno_id;
		$data['dialogo_id'] = $dialogo_id;
		$data['created_at'] = date('Y-m-d H:i:s');
		if ($this->Dialogos_model->responder($data)) { 
			$this->session->set_flashdata('success_message', 'Respuesta almacenada. Espere la respuesta del profesor');	
		} else { 
			$this->session->set_flashdata('error_message', 'No pudimos almacenar la respuesta');	
		}
		redirect('/dialogos/alumno_ejercicio/' . $hash);
		#$this->template('dialogos/alumno_ejercicio', $vars);
	}

	public function ejercicio_get_description($hash) { 
		if (!preg_match('/^\w+$/', $hash)) die('Caracteres no permitidos');
		$dialogo_id = $this->Dialogos_model->get_dialogo_id_by_hash($hash);
		if (!$dialogo_id) die('Link no valido');
		$ejercicio = $this->Dialogos_model->get($dialogo_id);
		$this->output->set_output($ejercicio->desarrollo);
	}

	public function index()
	{
		$this->user->on_invalid_session('account/index');
		if (!$this->user->has_permission('dialogos')) redirect('/');		
		$this->template_type = 'admin';
		$user_id = ($this->user->has_permission('admin'))?null:$this->user->get_id();
		$vars = array('ejercicios' => $this->Dialogos_model->get_all($user_id));
		$this->template('dialogos/listado', $vars);
	}
	

	public function cerrar_ejercicio($dialogo_id, $alumno_id) { 
		if (!$this->is_mine($dialogo_id)) die('Operacion no permitida');
		if (!$this->user->has_permission('dialogos')) redirect('/');		
		if ($this->Dialogos_model->cerrar_ejercicio_alumno($dialogo_id, $alumno_id)) { 
			$this->session->set_flashdata('success_message', 'Evaluación cerrada');
		} else { 
			$this->session->set_flashdata('errror_message', 'Problema impactando el cambio');
		}
		redirect("/dialogos/resumen_respuestas/$dialogo_id");

	}
	public function resumen_respuestas($dialogo_id)
	{
		if (!$this->is_mine($dialogo_id)) die('Operacion no permitida');
		if (!$this->user->has_permission('dialogos')) redirect('/');		
		$vars = array();
		$vars['respuestas'] = $this->Dialogos_model->listado_respuestas($dialogo_id);
		$vars['dialogo_id'] = $dialogo_id;
		#echo '<pre>';print_r($vars);echo '</pre>';
		$this->template_type = 'admin';
		$this->template('dialogos/resumen_respuestas', $vars);
	}

	public function upload_from_editor() {	
		if (!$this->user->has_permission('dialogos')) die("Not allowed");		
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



	public function editar($dialogo_id = null)
	{
		if (!$this->user->has_permission('dialogos')) redirect('/');		
		$this->load->helper('form');
		$this->load->library('form_validation');
		#$list = scandir()
		$vars['preguntas'] = array();
		if ($dialogo_id) { 
			$dialogo = $this->Dialogos_model->get($dialogo_id);
			if (!$dialogo) die("Acceso no permitido");
			if ($dialogo->id_user != $this->user->get_id()) die("Acceso no permitido");
			$vars['dialogo'] = $dialogo;
		}
		#echo '<pre>';print_r($vars['imgs']);die();
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nombre', 'Nombre', 'required|min_length[5]|max_length[200]');
		$this->form_validation->set_rules('pregunta_1', 'Idea 1', 'required|min_length[5]|max_length[200]');
		$this->form_validation->set_rules('consigna', 'Consigna', 'required|min_length[5]|max_length[200]');
		$this->form_validation->set_rules('desarrollo', 'Desarrollo', 'required|min_length[10]|max_length[50000]');
		if ($this->input->post()  && $this->form_validation->run() === True) { 
			$data = $this->input->post(); 
			$data['id_user'] = $this->user->get_id();
			if ($dialogo_id) { 
				$this->Dialogos_model->update($dialogo_id, $data);
				$this->session->set_flashdata('success_message', 'El Ejercicio fue actualizado éxitosamente.');
				redirect("/dialogos");
			} else { 
				$data['public_id'] = uniqid();
				$data['status'] = 'habilitado';
				$dialogo_id = $this->Dialogos_model->insert($data);
				$this->session->set_flashdata('success_message', 'El Ejercicio fue creado con éxito.');
				redirect("/alumnos/invitar/dialogos/$dialogo_id");
			}

		}

		if (!$this->is_mine($dialogo_id)) die('Operacion no permitida');
		$this->template_type = 'admin';
		#echo '<pre>';print_r($vars);echo '</pre>';
		$this->template('dialogos/editar', $vars);
	}

	public function duplicar($dialogo_id)
	{
		if (!$this->user->has_permission('dialogos')) redirect('/');		
		if (!$this->is_mine($dialogo_id)) die('Operacion no permitida');
		$nu_id = $this->Dialogos_model->duplicar($dialogo_id);
		$this->session->set_flashdata('success_message', 'Ejercicio duplicado');
		redirect('/dialogos/editar/' . $nu_id);
	
	}

	public function borrar($dialogo_id)
	{
		if (!$this->user->has_permission('dialogos')) redirect('/');		
		if (!$this->is_mine($dialogo_id)) die('Operacion no permitida');
		if ($this->Dialogos_model->delete($dialogo_id)) { 
			$this->session->set_flashdata('success_message', 'Un ejercicio fue eliminado');
		} else { 
			$this->session->set_flashdata('error_message', 'Problemas eliminando el ejercicio');
		}
		redirect('/dialogos/');
	}



	public function enviar_devolucion() { 
		if (!$this->user->has_permission('dialogos')) redirect('/');
		$ejercicio = $this->Dialogos_model->get_ejercicio_by_alumno_id($this->input->post('alumno_id'));
		$alumno = $this->Dialogos_model->get_alumno_by_id($this->input->post('alumno_id'));
		if (!$this->is_mine($ejercicio->id)) die('Operacion no permitida');
		$this->load->model('Email_model');
		$vars['profesor_nombre'] = $this->user->user_data->name;
		$vars['alumno_nombre'] = $alumno->primer_nombre . ' ' . $alumno->ultimo_nombre;
		$vars['texto'] = $this->input->post('texto');
		$email = ['from' => '<no_reply@citep.mailgun.com> CITEP MIC', 
				  'to' => $alumno->email,
				  'subject' => "Prismas entramados: Devolución del profesor",
				  'message' => $this->load->view('/emails/prismas_devolucion',$vars,true) 
				  ];
		$this->Email_model->batch($email);
		$this->session->set_flashdata('success_message', 'Mensaje enviado');
		redirect('/dialogos/resumen_respuestas/' . $ejercicio->id);
	}

	public function calificar() { 
		if (!$this->user->has_permission('dialogos')) redirect('/');
		$ejercicio = $this->Dialogos_model->get_ejercicio_by_respuesta_id($this->input->post('id'));
		$alumno = $this->Dialogos_model->get_alumno_by_respuesta_id($this->input->post('id'));
		$respuesta = $this->Dialogos_model->get_respuesta_by_id($this->input->post('id'));
		if (!$this->is_mine($ejercicio->id)) die('Operacion no permitida');
		if (!$respuesta->calificacion) { 
			$this->Dialogos_model->calificar($this->input->post('id'), $this->input->post('texto'), $this->input->post('status'));
			if ($this->input->post('finalizar')) $this->Dialogos_model->cerrar_ejercicio_alumno($ejercicio->id, $alumno->id);
			$this->session->set_flashdata('success_message', 'Ejercicio calificado');
		} else { 
			$this->session->set_flashdata('error_message', 'Ejercicio ya calificado, no se puede cambiar la calificación');
		}
		$this->load->library('user_agent');
	    if ($this->agent->is_referral()) redirect($this->agent->referrer());
	    redirect('/');
	}

	public function ver_respuestas($dialogo_id, $alumno_id = "") { 
		if (!$this->user->has_permission('dialogos')) redirect('/');		
		if (!$this->is_mine($dialogo_id)) die('Operacion no permitida');
		$this->template_type = 'admin';
		$vars = array(
			'respuestas' => $this->Dialogos_model->respuestas($dialogo_id, $alumno_id),
			'dialogo_id' => $dialogo_id,
			'alumno_id' => $alumno_id
			);
		$this->template('dialogos/ver_respuestas', $vars);
	}


	public function estado($estado, $dialogo_id) {
		if (!$this->user->has_permission('dialogos')) redirect('/');		
		if (!$this->is_mine($dialogo_id)) die('Operacion no permitida');
		if ($this->Dialogos_model->update($dialogo_id, array('status' => $estado))) { 
			$this->session->set_flashdata('success_message', 'Procesamos el cambio de estado');
		} else { 
			$this->session->set_flashdata('error_message', 'No pudimos cambiar el estado');
		}
		redirect('/dialogos/');
	}

	private function is_mine($dialogo_id) { 
		if ($this->user->has_permission('admin')) return true;
		$ej = $this->Dialogos_model->get($dialogo_id);
		return ($ej->id_user && $ej->id_user === $this->user->get_id());
	}
}
