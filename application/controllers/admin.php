<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller {
	function __construct(){
		parent::__construct();
		// Load the Library
        $this->load->helper('url');
		$this->load->model('Arquetipos_model');
		$this->user->on_invalid_session('account/login');
		$this->template_type = 'admin';
	}

	public function index()
	{
		$this->template('admin/index');
	}
}