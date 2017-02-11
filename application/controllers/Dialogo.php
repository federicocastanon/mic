<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dialogo extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        // Load the Library
        $this->load->helper('url');
        $this->load->model('dialogo_model');
    }

    public function testing(){
        $result = $this->dialogo_model->editarCantidadDialogos(2);
        print_r($result);
        exit;
    }

    public function index(){
        //devuelve todos los prismas

        $this->user->on_invalid_session('account/home');
        if (!$this->user->has_permission('dialogos')) redirect('/');
        $this->template_type = 'admin';
        $user_id = ($this->user->has_permission('admin'))?null:$this->user->get_id();
        $vars = array('prismas' => $this->dialogo_model->obtenerTodosLosPrismas());

        $this->template('dialogos/listado', $vars);
    }

    public function editar($prismaId = null)
    {
        if (!$this->user->has_permission('dialogos')) redirect('/');
        $this->load->helper('form');
        $this->load->library('form_validation');

        if ($prismaId) {
            $prisma = $this->dialogo_model->obtenerPrisma($prismaId);

            if (!$prisma) die("Acceso no permitido");
            if ($prisma->creador != $this->user->get_id()) die("Acceso no permitido");

            $vars['prisma'] = $prisma;
        }


        #echo '<pre>';print_r($vars['imgs']);die();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nombre', 'Nombre', 'required|min_length[5]|max_length[200]');
        $this->form_validation->set_rules('descripcion', 'Descripción', 'required|min_length[5]|max_length[200]');
        $this->form_validation->set_rules('profesional', 'Profesional', 'required|min_length[5]|max_length[200]');
        $this->form_validation->set_rules('secundario', 'Secundario', 'required|min_length[5]|max_length[200]');
        $vars['extra_errors'] = '';

        if ($this->input->post()  && $this->form_validation->run() === True && !$vars['extra_errors']) {
            $data = $this->input->post();
            /*print_r($this->input->post('pregunta'));
            print_r( $vars['preguntas']);
            exit;*/
            $data['id_user'] = $this->user->get_id();
            unset($data['imgs']);

            unset($data['titulo_imagen']);
            unset($data['file']);
            if ($prismaId) {
                //EDITANDO

                $this->session->set_flashdata('success_message', 'El Ejercicio fue actualizado éxitosamente.');
                redirect("/arquetipos");
            } else {
                //CREANDO NUEVO
                //dialogos
                $prismaId = $this->dialogo_model->crearPrisma($data['nombre'],$data['descripcion'],
                    $this->user->get_id(),$data['profesional'],$data['secundario']);
                $this->dialogo_model->crearDialogos($prismaId, $data['dialogos']);

                $this->session->set_flashdata('success_message', 'El Ejercicio fue creado con éxito.');
                redirect("/dialogo/");
            }

        }
        $this->template_type = 'admin';
        #echo '<pre>';print_r($vars);echo '</pre>';
        $this->template('dialogos/editar', $vars);
    }

    function dialogosPorPrismaAlumno(){
        session_start();

        $id= $_POST['id'];
        $email= trim($_POST['email']);
        $_SESSION["email"] =$email ;
        $this->dialogosPorPrisma($id);
    }

    function dialogosPorPrisma($prismaId){

        if ($this->user->get_id()){
            $this->template_type = 'admin';
            if(!isset($_SESSION)){
                session_start();
            }
            $_SESSION["email"] = trim($this->user->get_email());
        }
        $user_id = ($this->user->has_permission('admin'))?null:$this->user->get_id();
        $vars = array('dialogos' => $this->dialogo_model->obtenerDialogosPorPrisma($prismaId));
        $vars['prisma'] = $this->dialogo_model->obtenerPrisma($prismaId);

        $this->template('dialogos/elegir_dialogo', $vars);
    }

    function lobbyDialogos($prismaId){
        if ($this->user->get_id())
            $this->template_type = 'admin';
        if(!isset($_SESSION)){
            session_start();
        }

        $vars = array('dialogos' => $this->dialogo_model->obtenerDialogosPorPrisma($prismaId));
        $vars['prisma'] = $this->dialogo_model->obtenerPrisma($prismaId);
        $email = $_SESSION["email"];
        if($email){
            $vars['email'] = $email;

        }

        $this->template('dialogos/elegir_dialogo', $vars);
    }

    function sentarse(){
        session_start();
        if ($this->user->get_id())
            $this->template_type = 'admin';
        print json_encode($_POST);
        $dialogoId = $_POST['dialogoId'];
        $profesional= $_POST['profesional'];
        $email = $_POST['email'];

        $_SESSION["email"] =trim($email) ;
        $_SESSION["profesional"] =$profesional ==  'true';

        $this->dialogo_model->tomarRol($dialogoId, $email, $profesional);
        //$this->lobbyDialogos(7);
        $this->armarDialogo($dialogoId);
    }

    function armarDialogo($dialogoId){
        if ($this->user->get_id())
            $this->template_type = 'admin';
        $email = $_SESSION["email"] ;
        $vars = array('intervenciones' =>  $this->dialogo_model->obtenerIntervencionesPorDialogo($dialogoId));

        $dialogo = $this->dialogo_model->obtenerDialogosPorId($dialogoId) ;
        $vars['dialogo'] = $dialogo;
        $evaluacion = $this->dialogo_model->obtenerMiEvaluacion($dialogoId,$email);
        if($evaluacion){
            $vars['evaluacion'] = $evaluacion;
        }
        $this->template('dialogos/ver_dialogo', $vars);
    }

    function intervenir($dialogoId){
        session_start();
        $profesional = $_SESSION["profesional"];
        $email = $_SESSION["email"] ;
        $intervencion= $_POST['intervencion'];

        $this->dialogo_model->insertarIntervencion($dialogoId, $email, $intervencion, $profesional);
        $this->armarDialogo($dialogoId);
    }

    function levantarse($dialogoId){
        session_start();
        $email = $_SESSION["email"] ;
        $profesional = $_SESSION["profesional"]  ;
        $dialogo = $this->dialogo_model->obtenerDialogosPorId($dialogoId) ;

        if($dialogo->terminado == 0){
            //Si está terminado no te borro del dialogo
            $this->dialogo_model->levantarse($dialogoId,$profesional );
        }
        unset($_SESSION["profesional"], $profesional);

        $this->lobbyDialogos($dialogo->prisma);

    }

    function terminar($dialogoId){
        session_start();
        $this->dialogo_model->terminarConversacion($dialogoId);
        $dialogo = $this->dialogo_model->obtenerDialogosPorId($dialogoId) ;

        $this->lobbyDialogos($dialogo->prisma);
    }


    function calificar($dialogoId){
        session_start();
        if ($this->user->get_id())
            $this->template_type = 'admin';

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $calificacion = $_POST['calificacion'];
            $email = $_SESSION["email"] ;
            $dialogo = $this->dialogo_model->obtenerDialogosPorId($dialogoId) ;
            if ($this->user->get_id()){
                //calificaDocente
                $this->dialogo_model->crearEvaluacionDocente($dialogoId,$calificacion,$this->user->get_email());
            }else{
                //calificaPar
                $sugerencias= $_POST['sugerencia'];
                    $valoracionPositiva=$_POST['positiva'];
                        $aclaraciones=$_POST['aclaracion'];
                $this->dialogo_model->insertarEvaluacionPar($dialogoId, $email, $calificacion, $sugerencias, $valoracionPositiva, $aclaraciones) ;
           }

            $this->lobbyDialogos($dialogo->prisma);
        }else{

            $this->armarDialogo($dialogoId);
        }

    }

    function verCalificaciones($prismaId){
        session_start();
        if ($this->user->get_id())
            $this->template_type = 'admin';

        $email = $_SESSION["email"] ;
        $dialogos = $this->dialogo_model->obtenerDialogosPorPrisma($prismaId);

        foreach($dialogos as $d){
            $evaluaciones = $this->dialogo_model->obtenerEvaluacionesPorDialogo($d->id);
            $cantidad = count($evaluaciones);
            $d->promedio = 0;
            $d->tuPuntaje = 0;
            if($cantidad>0) {
                $suma = 0;
                foreach ($evaluaciones as $e) {
                    $suma += $e->puntaje;
                    if($e->email == $email){
                        //esta es tu evaluación
                        $d->tuPuntaje = $e->puntaje;
                    }
                }
                $d->promedio = $suma / $cantidad;
            }
        }
        $vars = array('dialogos' => $dialogos);
        $vars['prismaId'] = $prismaId;
        $this->template('dialogos/ver_calificaciones', $vars);
    }

}
