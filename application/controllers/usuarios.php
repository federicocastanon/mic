<?php

class Usuarios extends MY_Controller {
	
	function __construct(){
		parent::__construct();
		// Load the Library
		$this->load->library(array('user', 'user_manager'));
        $this->load->helper('url');
		$this->template_type = 'admin';
		$this->user->on_invalid_session('account/index');
		if (!$this->user->has_permission('admin')) redirect('/');
		$this->load->model('Usuarios_model');
	}
	
	function index()
	{
		$this->template_type = 'admin';		
		$vars = array('users' => $this->Usuarios_model->get_all());
		$this->template('user/user_list', $vars);
	}


	function add() { 
		redirect("/usuarios/edit/0");
	}


	private function notify_permissions_change($id_user, $old_perms, $nu_perms) { 
		$data = $this->Usuarios_model->get($id_user);
		$perms_list = [];
		foreach ($this->Usuarios_model->get_all_permissions() as $e) { 
			$perms_list[$e->id] = $e->description;
		}
		$quitados = [];
		foreach(array_diff($old_perms, $nu_perms) as $id) { 
			$quitados[] = $perms_list[$id];
		}
		$agregados = [];
		foreach(array_diff($nu_perms, $old_perms) as $id) { 
			$agregados[] = $perms_list[$id];
		};
		if ($agregados or $quitados) { 
			$this->load->library('email');
			$this->email->from('no_reply@citep.mailgun.com', 'CITEP MIC');
			$this->email->to($data->email); 
			$this->email->subject('Cambios en su cuenta de CITEP MIC');
			$vars = array('agregados' => $agregados, 'quitados' => $quitados);
			#print_r($vars);die();
			$this->email->message($this->load->view('/emails/usuario_modificado',$vars,true));	
			$this->email->send();
		}
	}

	private function notify_new_user($id_user, $password) { 
		$user_perms = [];
		foreach ($this->Usuarios_model->get_permissions($id_user) as $p) { 
			$user_perms[] = $p->description;
		}
		$data = $this->Usuarios_model->get($id_user);
		$this->load->library('email');
		$this->email->from('no_reply@citep.mailgun.com', 'CITEP MIC');
		$this->email->to($data->email); 
		$this->email->subject('Tienes un usuario creado en CITEP MIC');
		$vars = array('email' => $data->email, 'password' => $password, 'perms' => $user_perms);
		$this->email->message($this->load->view('/emails/usuario_creado',$vars,true));	
		$this->email->send();
	}


	function edit($id_user) { 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$vars = array('custom_errors' => '');
		# validation rules
		$this->form_validation->set_rules('name', 'Nombre', 'required|min_length[6]');
		if ($id_user) { 
			$vars['permisos_usuarios'] = $this->Usuarios_model->get_permissions($id_user);	
			$vars['usuario'] = $this->Usuarios_model->get($id_user);
		} else { 
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
		}
		if (!$id_user) {
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
		}
		# validate permissions

		if ($this->input->post()) {
			#print_r($this->input->post());die();
			if (!$this->input->post('permiso')) {
				$vars['custom_errors'] = "Seleccionar al menos un permiso";
			}
		}

		if ($this->input->post()  && $this->form_validation->run() === True && !$vars['custom_errors']) { 
			$permissions = array();
			foreach ($this->input->post('permiso') as $key => $value) { 
				$permissions[] = $key;
			}
			$new_user = false;
			#print_r($permissions);die();
			if (!$id_user) { 	
				$CI = & get_instance();
				$data['name'] = $this->input->post('name');
				$data['login'] = $data['email'] = $this->input->post('email');
				$data['password'] = $CI->bcrypt->hash($this->input->post('password'));
				$id_user = $this->Usuarios_model->insert($data);
				$new_user = true;
			} else { 
				$data = array('name' => $this->input->post('name')); # nuke em from orbit, it's the only way to be safe
				$this->Usuarios_model->update($id_user, $data);
			}
			
			$old_perms = [];
			foreach ($this->Usuarios_model->get_permissions($id_user) as $p) { 
				$old_perms[] = $p->id;
			}

			$this->Usuarios_model->update_permissions($id_user, $permissions);
			if ($new_user) { 
				$this->notify_new_user($id_user, $this->input->post('password'));
				$this->session->set_flashdata('success_message', 'Usuario creado');
			} else {
				$this->notify_permissions_change($id_user, $old_perms, $permissions);
				$this->session->set_flashdata('success_message', 'Usuario modificado');
			}
			
			redirect('/usuarios');
		}

		$vars['permisos'] = $this->Usuarios_model->get_all_permissions();
		
		
		$this->template('user/edit', $vars);
		#die('<h1>Coming soon</h1>');
	}
	
	function cambiar_password() { 
		$post = $this->input->post();
		$CI = & get_instance();
		$data = array();
		$data['password'] = $CI->bcrypt->hash($post['password']);
		$this->Usuarios_model->update($this->input->post('id_user'), $data);

		if ($this->user->get_id() == $this->input->post('id_user')) { 
			$CI->session->set_userdata(array('pw'=>$data['password']));
			$this->user->user_data->password = $data['password'];
		}
		$this->session->set_flashdata('success_message', 'Password cambiado');
		redirect("/usuarios");
	}

	function delete($id_user) { 
		if ($this->user->get_id() == $id_user) { 
			$this->session->set_flashdata('error_message', 'No se puede borrar el usuario actual');
		} else { 
			$this->Usuarios_model->delete($id_user);
			$this->session->set_flashdata('success_message', 'Usuario borrado');
		}
		redirect('/usuarios');
	}


}
?>
