<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reportes extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        // Load the Library
        $this->load->helper('url');
        $this->load->model('reportes_model');
    }

    public function index(){
        //devuelve todos los prismas

        $this->user->on_invalid_session('account/home');
        if (!$this->user->has_permission('admin')) redirect('/');
        $reportes = $this->reportes_model->obtenerTodos();
        $vars = array();
        $vars['micSeleccionada'] = 'reporte';
        $vars['reportes'] = $reportes;
        $this->template('reportes/listado', $vars);
    }

    public function ejecutarReporte($nombreStore){
        $this->template_type = 'admin';
        $parametros = array();
       $respuesta =  $this->reportes_model->ejecutarReporte($nombreStore, $parametros);
        $vars = array();
        $vars['micSeleccionada'] = 'reporte';
        $vars['nombre'] = $this->input->post('nombre');
        $vars['respuesta'] = $respuesta;
        $this->template('reportes/resultado', $vars);
    }

}