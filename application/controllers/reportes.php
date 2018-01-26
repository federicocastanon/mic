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
        $this->template_type = 'admin';
        $reportes = $this->reportes_model->obtenerTodos();
        $vars = array();
        $vars['micSeleccionada'] = 'reporte';
        $vars['reportes'] = $reportes;
        $this->template('reportes/listado', $vars);
    }

    public function ejecutarReporte($idReporte){
        $this->template_type = 'admin';
        $nombreStore = $this->input->post('store');

        $parametros = $this->reportes_model->obtenerDefectoParametrosPorReporteId($idReporte);
        $respuesta =  $this->reportes_model->ejecutarReporte($nombreStore, $parametros);

        $vars = array();
        $vars['micSeleccionada'] = 'reporte';
        $vars['titulo'] = $this->input->post('nombre') . ' - ' .  $this->input->post('etiqueta');
        $vars['respuesta'] = $respuesta;
        $vars['volver'] = 'reportes';
        $vars['parametrosUsados'] = $parametros;
        $vars['columnas'] = array_keys(get_object_vars($respuesta[0]));
        $this->template('reportes/resultado', $vars);
    }

    public function ejecutarReporteConParametros($idReporte){
        $this->template_type = 'admin';
        $nombreStore = $this->input->post('store');
        $parametros = $this->input->post('parametros');
        $par = $this->reportes_model->obtenerParametrosPorReporteId($idReporte);
        $respuesta =  $this->reportes_model->ejecutarReporte($nombreStore, $parametros);

        $vars = array();
        $vars['micSeleccionada'] = 'reporte';
        $titulo = $this->input->post('nombre') . ' - ' ;
        foreach ($par as $i => $p){

            $titulo = $titulo . $p->eti . ' '. $parametros[$i] . ' ';
        }
        $vars['titulo'] = $titulo ;
        $vars['respuesta'] = $respuesta;
        $vars['parametrosUsados'] = $parametros;
        $vars['volver'] = 'reportes/seleccionarParametros/' . $idReporte;
        $vars['columnas'] = array_keys(get_object_vars($respuesta[0]));
        $this->template('reportes/resultado', $vars);

    }

    public function seleccionarParametros($idReporte){
        $parametros = $this->reportes_model->obtenerParametrosPorReporteId($idReporte);

        $reporte = $this->reportes_model->obtenerReporte($idReporte);

        $vars = array();
        $vars['micSeleccionada'] = 'reporte';
        $vars['reporte'] = $reporte;
        $vars['parametros'] = $parametros;

        $this->template('reportes/seleccionarParametros', $vars);
    }


}