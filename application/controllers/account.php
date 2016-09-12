<?php

class Account extends MY_Controller {
	
	function __construct(){
		parent::__construct();
		// Load the Library
		$this->load->library(array('user', 'user_manager'));
        $this->load->helper('url');
		$this->template_type = 'admin';
	}
	
	function index()
	{		
		// If user is already logged in, send it to main
		$this->user->on_valid_session('admin/');
		redirect('account/login');
	}

    function home()
    {
        // If user is already logged in, send it to main
        //$this->user->on_valid_session('admin/');
        $this->template_type = 'public';
        $this->template('account/home');
    }

	function login()
	{
		// If user is already logged in, send it to main
		$this->user->on_valid_session('admin/');
		$this->template_type = 'public';		
	
		// Loads the login view
		$this->template('account/login');
	}
	
	function validate()
	{
		// Receives the login data
		$login = $this->input->post('login');
		$password = $this->input->post('password');
		#die("login = $login");	
		/* 
		 * Validates the user input
		 * The user->login returns true on success or false on fail.
		 * It also creates the user session.
		*/
		if($this->user->login($login, $password)){
			// Success
			redirect('admin/');
		} else {
			// Oh, holdon sir.
			$this->session->set_flashdata('error_message', 'Email o password invalido.');
			redirect('account/login');
		}
	}
	
	public function forgot() { 
		$this->template_type = 'public';
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|exists[users.email]');

		if ($this->form_validation->run() == TRUE)
		{
			$this->load->model('Usuarios_model');
			$user = $this->Usuarios_model->get_by_email($this->input->post('email'));
			# send the email
			$hash = $this->user_manager->get_password_hash($this->input->post('email'));
			$vars['forgot_link'] = base_url('/account/recover_password/' . urlencode($this->input->post('email')) . '/' . $hash);
			$vars['name'] = $user->name;
			$this->load->library('email');
			$this->email->from('no_reply@citep.mailgun.com', 'CITEP MIC');
			$this->email->to($this->input->post('email')); 
			$this->email->subject('Recordatorio de contraseña');
			$this->email->message($this->load->view('/emails/forgot',$vars,true));	
			$this->email->send();
			$this->session->set_flashdata('success_message', 'Por favor revise su email');
			redirect('/account/login');
			
		}
		$this->template('account/forgot');
		
	}

	public function recover_password($email, $hash) {
		$this->template_type = 'public'; 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$email = urldecode($email);
		if ($id_user = $this->user_manager->verify_password_hash($email, $hash)) { 
			if ($this->input->post()) { 
				$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
				$this->form_validation->set_rules('password_confirmation', 'Confirmación de password', 'required|matches[password]');
				if ($this->form_validation->run() === True) { 
					$this->load->model('Usuarios_model');
					$this->user_manager->clear_password_hash($email);
					$CI = & get_instance();
					$data = array();
					$data['password'] = $CI->bcrypt->hash($this->input->post('password'));
					$this->Usuarios_model->update($id_user, $data);
					$this->session->set_flashdata('success_message', 'Nuevo password aceptado');
					redirect('/account/login');
				}
			}
		} else {
			$this->session->set_flashdata('error_message', 'link invalido');
			redirect('/account/login');
		}
		$this->template('account/recover_password');
	}

	// Simple logout function
	function logout()
	{
		// Remove user session.
		$this->user->destroy_user();
		
		// Bye, thanks! :)
		$this->session->set_flashdata('success_message', 'Te has desconectado.');
		redirect('account/home');
	}

	function edit() { 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('Usuarios_model');
		$this->form_validation->set_rules('name', 'Nombre', 'required|min_length[6]');
		if ($this->input->post()  && $this->form_validation->run() === True) { 
			$this->Usuarios_model->update($this->user->get_id(), $this->input->post());
			$this->session->set_flashdata('success_message', 'Usuario modificado');
			redirect('/admin');
		}
		$vars = array('usuario' => $this->Usuarios_model->get($this->user->get_id()));
		$this->template('account/edit', $vars);
	}

	function change_password() { 
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('Usuarios_model');
		$this->form_validation->set_rules('passwordOrig', 'Password Original', 'required');
		$this->form_validation->set_rules('password', 'Password nuevo', 'required|min_length[6]');
		$this->form_validation->set_rules('passwordConf', 'Confirmar Password Nuevo', 'required|matches[password]');

		$vars['extra_errors'] = '';
		if ($this->input->post()) { 
			$CI = & get_instance();
			$usuario = $this->Usuarios_model->get($this->user->get_id());
			if (!$CI->bcrypt->compare($this->input->post('passwordOrig'), $usuario->password)) {
				$vars['extra_errors'] = "El password original no es correcto";
			}
			if ($this->form_validation->run() === True && !$vars['extra_errors']) { 
				$data = array('password' => $CI->bcrypt->hash($this->input->post('password')));
				$this->Usuarios_model->update($this->user->get_id(), $data);
				$CI->session->set_userdata(array('pw'=>$data['password']));
				$this->user->user_data->password = $data['password'];
				$this->session->set_flashdata('success_message', 'Password modificado');
				redirect('/admin');
			}
		}
		$this->template('account/change_password', $vars);
	}
}
?>