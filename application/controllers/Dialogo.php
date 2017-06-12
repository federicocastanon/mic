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
        if($this->user->has_permission('admin')){
            $vars = array('prismas' => $this->dialogo_model->obtenerTodosLosPrismas());
        }
        else
        {
            $vars = array('prismas' => $this->dialogo_model->obtenerTodosLosPrismasPorUsuario($this->user->get_id()));
        };


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
            if ($prisma->creador != $this->user->get_id() && !$this->user->has_permission('admin')) die("Acceso no permitido");

            $vars['prisma'] = $prisma;
        }


        #echo '<pre>';print_r($vars['imgs']);die();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nombre', 'Nombre', 'required|min_length[5]|max_length[200]');
        $this->form_validation->set_rules('descripcion', 'Descripción', 'required|min_length[5]');
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
                //($id,$nombre, $descripcion, $profesional, $secundario)
                $this->dialogo_model->editarPrisma($prismaId,$data['nombre'],$data['descripcion'],
                    $data['profesional'],$data['secundario']);
                $cantDialogos = intval($this->dialogo_model->cantidadDialogos($prismaId)->cant);
                $dialogosACrear = intval($data['dialogos']);

                if($dialogosACrear > $cantDialogos){
                    $this->dialogo_model->crearDialogos($prismaId, $dialogosACrear - $cantDialogos);
                }
                $this->session->set_flashdata('success_message', 'El Ejercicio fue actualizado éxitosamente.');
                redirect("/dialogo/");
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

    public function publicar($id, $status) {
        if (!$this->user->has_permission('dialogos')) redirect('/');
        $this->dialogo_model->publicar($id, $status);
        if ($status) {
            $msg = "El link publico ha sido activado";
        } else {
            $msg = "El link publico ha sido desactivado";
        }
        $this->session->set_flashdata('success_message', $msg);
        redirect('/dialogo/');
    }

    public function borrar($id){
        $this->dialogo_model->borrarPrisma($id);
        $this->session->set_flashdata('success_message', 'El Ejercicio fue eliminado con éxito.');
        redirect("/dialogo/");
    }
    private function is_mine($id) {
        if ($this->user->has_permission('admin')) return true;
        $ej = $this->dialogo_model->get($id);
        return ($ej->id_user && $ej->id_user === $this->user->get_id());
    }

    public function duplicar($id)
    {
        if (!$this->user->has_permission('arquetipos')) redirect('/');
        if (!$this->is_mine($id)) die('Operacion no permitida');
        $nu_id = $this->dialogo_model->duplicar($id);
        $this->session->set_flashdata('success_message', 'Actividad duplicada');
        redirect('/dialogo/editar/' . $nu_id);

    }

    function dialogosPorPrismaAlumno(){
        if(!isset($_SESSION)){             session_start();         }

        $id= $_POST['id'];
        $alias= trim($_POST['alias']);
        if(strlen($alias)>=1) {
            $_SESSION["alias"] = $alias;
        }else{
            $_SESSION["alias"] = 'Anonimo-' . rand(1,10000);
        }
        $this->dialogosPorPrisma($id);
    }

    function recepcionPrisma($prismaId = null){

        if(!$prismaId) {
            $prismaId = $_POST['id'];
            $alias= trim($_POST['alias']);
            if(strlen($alias)>=1) {
                $_SESSION["alias"] = $alias;
            }else{
                $_SESSION["alias"] = 'Anonimo-' . rand(1,10000);
            }
        }

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
        }else if(!isset($_SESSION["alias"]) || strlen($_SESSION["alias"]) < 1 ){
            unset($_SESSION["alias"]);
            //si la sesión no tiene alias asignado lo mandamos a la págian de elegir ALIAS
            //esto quiere decir que entró por link_publico
            $vars['urlDestino'] = base_url(). 'dialogo/recepcionPrisma/' . $prismaId;
            $this->template('account/solicitarAlias', $vars);
            return;
        }

        if ($this->user->get_id()){
            $this->template_type = 'admin';
            if(!isset($_SESSION)){
                session_start();
            }
            $_SESSION["alias"] = trim($this->user->get_email());
        }

        $user_id = ($this->user->has_permission('admin'))?null:$this->user->get_id();
        $prisma = $this->dialogo_model->obtenerPrisma($prismaId);
        if(!$prisma){
            $this->session->set_flashdata('error_message', 'El diálogo seleccionado no existe');

            $this->template('/account/home');
            return;
        }

        $vars = array();
        $dialogoPendiente =$this->dialogo_model->obtenerDialogoPendiente($prismaId, $_SESSION["alias"]);
        if($dialogoPendiente){
            $vars['pen'] = $dialogoPendiente->id;
        }else{
            $dialogoProfesional = $this->dialogo_model->obtenerPrimerDialogoSinRolPorPrisma($prismaId, true);
            $dialogoSecundario = $this->dialogo_model->obtenerPrimerDialogoSinRolPorPrisma($prismaId, false);
            if($dialogoProfesional){
                $vars['pro'] = $dialogoProfesional->id;
            }
            if($dialogoSecundario){
                $vars['sec'] = $dialogoSecundario->id;
            }
        }

        $vars['prisma'] = $prisma;

        $this->template('dialogos/recepcion_prisma', $vars);
    }

    function calificarLanding($prismaId){
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
        }else if(!isset($_SESSION["alias"]) || strlen($_SESSION["alias"]) < 1 ){
            unset($_SESSION["alias"]);
            //si la sesión no tiene alias asignado lo mandamos a la págian de elegir ALIAS
            //esto quiere decir que entró por link_publico
            $vars['urlDestino'] = base_url(). 'dialogo/calificarLanding/' . $prismaId;
            $this->template('account/solicitarAlias', $vars);
            return;
        }
        if ($this->user->get_id()){
            $this->template_type = 'admin';
            if(!isset($_SESSION)){
                session_start();
            }
            $_SESSION["alias"] = trim($this->user->get_email());
        }
        $dialogos = $this->dialogo_model->obtenerDialogosPorPrismaCalificables($prismaId,$_SESSION["alias"]);
        if ($this->user->get_id()){
        //Es docente
            for($j = 0; $j < sizeof($dialogos); $j++){
                if($dialogos[$j]->evaluacion > 0){
                    $dialogos[$j]->calificado = true;
                }
            }

        }else{
            $dialogosCalificados = $this->dialogo_model->obtenerDialogosPorPrismaCalificados($prismaId,$_SESSION["alias"]);
            for($i = 0; $i < sizeof($dialogosCalificados); $i++){
                for($j = 0; $j < sizeof($dialogos); $j++){
                    if($dialogos[$j]->id == $dialogosCalificados[$i]->id){
                        $dialogos[$j]->calificado = true;
                    }
                }
            }
        }




        $prisma = $this->dialogo_model->obtenerPrisma($prismaId);
        $vars = array('dialogos' => $dialogos);
        $vars['prisma'] = $prisma->id;

        $this->template('dialogos/calificar_landing', $vars);
    }

    function dialogosPorPrisma($prismaId){

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
        }else if(!isset($_SESSION["alias"]) || strlen($_SESSION["alias"]) < 1 ){
            unset($_SESSION["alias"]);
            //si la sesión no tiene alias asignado lo mandamos a la págian de elegir ALIAS
            //esto quiere decir que entró por link_publico
            $vars['urlDestino'] = base_url(). 'dialogo/dialogosPorPrisma/' . $prismaId;
            $this->template('account/solicitarAlias', $vars);
            return;
        }

        if ($this->user->get_id()){
            $this->template_type = 'admin';
            if(!isset($_SESSION)){
                session_start();
            }
            $_SESSION["alias"] = trim($this->user->get_email());
        }
        $user_id = ($this->user->has_permission('admin'))?null:$this->user->get_id();
        $prisma = $this->dialogo_model->obtenerPrisma($prismaId);
        if(!$prisma){
            $this->session->set_flashdata('error_message', 'El diálogo seleccionado no existe');

            $this->template('/account/home');
            return;
        }
        $dialogos = $this->dialogo_model->obtenerDialogosPorPrisma($prismaId);
        $vars = array('dialogos' => $dialogos);
        $vars['prisma'] = $prisma;

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
        $alias = $_SESSION["alias"];
        if($alias){
            $vars['alias'] = $alias;

        }

        $this->template('dialogos/elegir_dialogo', $vars);
    }

    function sentarse(){
        if(!isset($_SESSION)){             session_start();         }
        if ($this->user->get_id())
            $this->template_type = 'admin';
        $dialogoId = $_POST['dialogoId'];
        $profesional= $_POST['profesional'];
        $alias = $_POST['alias'];

        $_SESSION["alias"] =trim($alias) ;
        if ($profesional ==  'true'){
            $_SESSION["profesional"] =1;
        }else{
            $_SESSION["profesional"] = 0;
        }

        $this->dialogo_model->tomarRol($dialogoId, $alias, $_SESSION["profesional"] );
        //$this->lobbyDialogos(7);
        $this->armarDialogo($dialogoId);
    }

    function armarDialogo($dialogoId){
        if(!isset($_SESSION)){             session_start();         }
        $alias = $_SESSION["alias"] ;
        $vars = array('intervenciones' =>  $this->dialogo_model->obtenerIntervencionesPorDialogo($dialogoId));
        $dialogo = $this->dialogo_model->obtenerDialogosPorId($dialogoId) ;
        $prisma = $this->dialogo_model->obtenerPrisma($dialogo->prisma);
        $_SESSION["profesional"] = 1;
        if($dialogo->evaluado != $alias){
            $_SESSION["profesional"] = 0;
        }

        $vars['dialogo'] = $dialogo;
        $vars['prisma'] = $prisma;
        if ($this->user->get_id()){
            $this->template_type = 'admin';
            $evaluacion = $this->dialogo_model->obtenerEvaluacionDocente($dialogoId);
        }else{
            $evaluacion = $this->dialogo_model->obtenerMiEvaluacion($dialogoId,$alias);
        }
            

        if($evaluacion){
            $vars['evaluacion'] = $evaluacion;
        }

        $evaluaciones = $this->dialogo_model->obtenerEvaluacionesPorDialogo($dialogoId);
        $cantidad = count($evaluaciones);
        $dialogo->promedio = 0;
        $dialogo->tuPuntaje = 0;
        $dialogo->sugerencias = [];
        $dialogo->positivos = [];
        $dialogo->aclaraciones = [];
        if($cantidad>0) {
            $suma = 0;
            foreach ($evaluaciones as $e) {
                $suma += $e->puntaje;
                if($e->alias == $alias){
                    //esta es tu evaluación
                    $dialogo->tuPuntaje = $e->puntaje;
                }
                if(strlen($e->sugerencia)>0){
                    array_push($dialogo->sugerencias, $e->sugerencia );
                }
                if(strlen($e->positivo)>0){
                    array_push($dialogo->positivos, $e->positivo);
                }
                if(strlen($e->aclaracion)>0){
                    array_push($dialogo->aclaraciones, $e->aclaracion );
                }
            }
            $dialogo->promedio = $suma / $cantidad;
        }

        $this->template('dialogos/ver_dialogo', $vars);
    }

    function intervenir($dialogoId){
        if(!isset($_SESSION)){
            session_start();
        }
        $profesional = $_SESSION["profesional"];

        $alias = $_SESSION["alias"] ;
        $intervencion= $_POST['intervencion'];

        $tipo = 2;
        if($profesional){
            $tipo =1 ;
        }

        $this->dialogo_model->insertarIntervencion($dialogoId, $alias, $intervencion, $profesional,$tipo);
        $this->armarDialogo($dialogoId);
    }

    function levantarse($dialogoId){
        if(!isset($_SESSION)){             session_start();         }
        $alias = $_SESSION["alias"] ;

        $dialogo = $this->dialogo_model->obtenerDialogosPorId($dialogoId) ;

        if($dialogo->terminado == 0){
            //Si está terminado no te borro del dialogo
            $this->dialogo_model->levantarse($dialogoId,$alias, $alias == $dialogo->evaluado );
        }


        $this->recepcionPrisma($dialogo->prisma);

    }

    function terminar($dialogoId){
        if(!isset($_SESSION)){             session_start();         }
        $this->dialogo_model->terminarConversacion($dialogoId);
        $dialogo = $this->dialogo_model->obtenerDialogosPorId($dialogoId) ;

        $this->recepcionPrisma($dialogo->prisma);
    }


    function calificar($dialogoId){
        if(!isset($_SESSION)){             session_start();         }
        if ($this->user->get_id())
            $this->template_type = 'admin';

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $calificacion = $_POST['calificacion'];
            $alias = $_SESSION["alias"] ;
            $dialogo = $this->dialogo_model->obtenerDialogosPorId($dialogoId) ;
            $sugerencias= $_POST['sugerencia'];
            $valoracionPositiva=$_POST['positiva'];
            $aclaraciones=$_POST['aclaracion'];
            if ($this->user->get_id()){
                //calificaDocente
                $this->dialogo_model->crearEvaluacionDocente($dialogoId,$this->user->get_email(),$calificacion,$sugerencias, $valoracionPositiva, $aclaraciones);
            }else{
                //calificaPar
                $this->dialogo_model->insertarEvaluacionPar($dialogoId, $alias, $calificacion, $sugerencias, $valoracionPositiva, $aclaraciones) ;
           }

            $this->verCalificaciones($dialogo->prisma);
        }else{

            $this->armarDialogo($dialogoId);
        }

    }

    function verCalificaciones($prismaId){
        if(!isset($_SESSION)){             session_start();         }
        if ($this->user->get_id())
            $this->template_type = 'admin';

        $alias = $_SESSION["alias"] ;
        $dialogos = $this->dialogo_model->obtenerDialogosPorPrismaTerminados($prismaId);

        foreach($dialogos as $d){
            $evaluaciones = $this->dialogo_model->obtenerEvaluacionesPorDialogo($d->id);
            $cantidad = count($evaluaciones);
            $d->promedio = 0;
            $d->tuPuntaje = 0;
            $d->sugerencias = [];
            $d->positivos = [];
            $d->aclaraciones = [];
            if($cantidad>0) {
                $suma = 0;
                foreach ($evaluaciones as $e) {
                    $suma += $e->puntaje;
                    if($e->alias == $alias){
                        //esta es tu evaluación
                        $d->tuPuntaje = $e->puntaje;
                    }
                    if(strlen($e->sugerencia)>0){
                        array_push($d->sugerencias, $e->sugerencia );
                    }
                    if(strlen($e->positivo)>0){
                        array_push($d->positivos, $e->positivo);
                    }
                    if(strlen($e->aclaracion)>0){
                        array_push($d->aclaraciones, $e->aclaracion );
                    }
                }
                $d->promedio = $suma / $cantidad;
            }
        }
        $vars = array('dialogos' => $dialogos);
        $vars['prismaId'] = $prismaId;
        //print json_encode($dialogos);
        //exit;
        $this->template('dialogos/ver_calificaciones', $vars);
    }
    public function cambiarAlias($public_id){
        if(!isset($_SESSION)){
            session_start();
        }
        unset($_SESSION["alias"]);
        $vars['urlDestino'] = base_url(). 'dialogo/recepcionPrisma/' . $public_id;
        $this->template('account/solicitarAlias', $vars);
        return;
    }
    public function intervenirAjax($dialogoId){
       // $dialogoId = $this->input->post('dialogoId');
        $intervencion =$this->input->post('intervencion');
        $ultimoId =$this->input->post('ultimoId');
        if(!isset($_SESSION)){
            session_start();
        }
        $profesional = $_SESSION["profesional"];

        $alias = $_SESSION["alias"] ;
        $tipo = 2;
        if($profesional == '1'){
            $tipo =1 ;
        }

        $this->dialogo_model->insertarIntervencion($dialogoId, $alias, $intervencion, $profesional,$tipo);
        $nuevas = $this->dialogo_model->obtenerNuevasIntervenciones($dialogoId,$ultimoId);
        $output = array('intervenciones' => $nuevas);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }

    public function recargaAjax(){
    $dialogo = $this->input->post('dialogoId');
    $ultimoId =$this->input->post('ultimoId');
    $nuevas = $this->dialogo_model->obtenerNuevasIntervenciones($dialogo,$ultimoId);
    $output = array('intervenciones' => $nuevas);

    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($output));
}
}
