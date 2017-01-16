<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dialogo extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        // Load the Library
        $this->load->helper('url');
        $this->load->model('DialogoModel');
    }

    public function testing(){
        $result = $this->DialogoModel->editarCantidadDialogos(2);
        print_r($result);
        exit;
    }

    public function index(){
        //devuelve todos los prismas

        $this->user->on_invalid_session('account/home');
        if (!$this->user->has_permission('dialogos')) redirect('/');
        $this->template_type = 'admin';
        $user_id = ($this->user->has_permission('admin'))?null:$this->user->get_id();
        $vars = array('dialogos' => $this->DialogoModel->obtenerTodosLosPrismas());

        $this->template('dialogos/listado', $vars);
    }

}