<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Alumnos extends MY_Controller {
	public function invitar($tipo, $ejercicio_id) { 
		if (!$this->user->has_permission($tipo)) redirect('/');		
		if (!$this->is_mine($tipo, $ejercicio_id)) die('Operacion no permitida');
		switch ($tipo) { 
			case 'arquetipos': 
				$this->load->model('Arquetipos_model', 'model'); 
				$subject = "Focos en juego";
				break;
			case 'dialogos': 
				$this->load->model('Dialogos_model', 'model'); 
				$subject = "Prismas entramados";
				break;
		}

		$this->load->helper('email');
		$this->template_type = 'admin';
		$vars = array();
		if ($this->input->post()) { 
			$vars['error'] = '';
			$tmp = str_replace("\r","\n", $this->input->post('invitados'));
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
				$this->load->model('Email_model');
				foreach($invitados as $e) { 
					$tmp = $this->model->get_alumno_by_email($ejercicio_id, $e['email']);
					if ($tmp) { 
						$e['hash'] = $tmp->hash;
					} else { 
						$e['hash'] = md5(time() . trim($e['email']) . rand(0,10000));
						$this->model->invitar_alumno($ejercicio_id, $e);
					}
					$link = base_url("/$tipo/alumno/$e[hash]");
					$vars = array('link' => $link, 'name' => $e['primer_nombre'] . ' ' . $e['ultimo_nombre']);
					$email = ['from' => '<no_reply@citep.mailgun.com> CITEP MIC', 
							  'to' => $e['email'],
							  'subject' => "$subject: Has sido invitado a realizar un ejercicio",
							  'message' => $this->load->view('/emails/invitacion_ejercicio',$vars,true) 
							  ];
					$this->Email_model->batch($email);
				}
				$this->session->set_flashdata('success_message', 'Alumnos invitados');
				redirect("/$tipo");
			}
		}
		$vars['tipo'] = $tipo;
		$this->template('alumnos/invitar', $vars, $tipo);	
	}

	private function is_mine($tipo, $id) { 
		if ($this->user->has_permission('admin')) return true;
		# TODO: Add real code here. 
		return false;
	}
}