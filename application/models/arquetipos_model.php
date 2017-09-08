<?php 
class Arquetipos_model extends My_Model {

    public $before_create = array( 'created_at', 'updated_at' );
    public $before_update = array( 'updated_at' );
  	public $protected_attributes = array('id');
    protected $soft_delete = TRUE;

    function get_ejercicio_by_public_id($id) { 
      return $this->db->get_where('arquetipos', ['id' => $id])->row();
    }

    function get_ejercicio_by_alumno_id($id) { 
      return $this->db->select('a.*')->join('arquetipos a', 'a.id = aa.arquetipo_id')->
        get_where('arquetipo_alumnos aa', ['aa.id' => $id])->row();
    }
    function get_alumno_by_id($id) { 
      return $this->db->get_where('arquetipo_alumnos', ['id'=> $id])->row();
    }

    function get_arquetipo_id_by_hash($hash) { 
      $query = $this->db->select('arquetipo_id')
               ->where(array('hash' => $hash))
               ->get('arquetipo_alumnos aa',1);
      $tmp = $query->result();
      if (!$tmp) return false;
      return $tmp[0]->arquetipo_id;
    }
    function get_alumno_id_by_hash($hash) { 
      $query = $this->db->select('id')
               ->where(array('hash' => $hash))
               ->get('arquetipo_alumnos aa',1);
      $tmp = $query->result();
      if (!$tmp) return false;
      return $tmp[0]->id;
    }

    function agregar_respuesta($arquetipo_id, $pregunta_id, $imagen_id, $respuesta, $nombre, $email) {
      $data = array(
        'arquetipo_id' => $arquetipo_id,
        'arquetipo_pregunta_id' => $pregunta_id,
        'arquetipo_imagen_id' => $imagen_id, 
        'respuesta' => $respuesta, 
        'created_at' => date('Y-m-d H:i:s'),
        'nombre' => $nombre,
        'email' => $email
      );
      #print_r($data);
      return $this->db->insert('arquetipo_respuestas', $data);
    }

    function get_max_id(){
        $query = "SELECT  `id`+ 1 as id FROM `arquetipos` order by id desc LIMIT 1 ";
        $tmp =  $this->db->query($query)->result();
        if (!$tmp) return false;
        return $tmp[0]->id;
    }

    function get_all($id_user = null) { 
    	$query = $this->db
            ->select('a.id, a.nombre, a.created_at, a.status, a.public_id, a.public_id_enabled')
            ->select('count(distinct ar.id) as respuestas')
            ->select('count(distinct ar.email) as alumnos')
            ->select('u.name as autor')
    		->join('arquetipo_respuestas ar', 'ar.arquetipo_id = a.id', 'left')
            ->join('arquetipo_alumnos aa', 'aa.arquetipo_id = a.id', 'left')
            ->join('users u', 'u.id = a.id_user')
        ->where(array('a.deleted' => 0))
    		->group_by('a.id')
        ->order_by('a.id desc');
        
        if ($id_user) $query = $query->where(array('id_user' => $id_user));
        $query = $query->get('arquetipos a');
    	return $query->result();
    }

    function get_questions($id_arquetipo) { 
        $query = $this->db->where(array('arquetipo_id' => $id_arquetipo))->get('arquetipo_preguntas');
        return $query->result();   
    }

    function get_images($id_arquetipo) { 
        $query = $this->db->where(array('arquetipo_id' => $id_arquetipo))->order_by('id')->get('arquetipo_imagenes');
        return $query->result();   
    }

    function duplicar($id_arquetipo) { 
        $public_id = uniqid();
        $query = "INSERT INTO arquetipos (nombre, consigna, desarrollo, id_user, created_at, updated_at, status, public_id) 
                  SELECT concat('Copia de ', nombre), consigna, desarrollo, id_user, now(), now(), status, '$public_id'
                  FROM arquetipos where id = $id_arquetipo";
        $this->db->query($query);
        $nu_arq_id = $this->db->insert_id();
        $query = "INSERT INTO arquetipo_preguntas (arquetipo_id, pregunta) 
                  SELECT $nu_arq_id, pregunta from arquetipo_preguntas where arquetipo_id = $id_arquetipo ";
        #print $query;
        $this->db->query($query);
        $query = "INSERT INTO arquetipo_imagenes (arquetipo_id, imagen_ubicacion) 
                  SELECT $nu_arq_id, imagen_ubicacion from arquetipo_imagenes where arquetipo_id = $id_arquetipo ";
        #print $query;
        $this->db->query($query);
        return $nu_arq_id;
    }

    function agregar_preguntas($id_arquetipo, $preguntas) { 
        foreach ($preguntas as $p) {
            $data = array('arquetipo_id' => $id_arquetipo, 'pregunta' => $p);
            $this->db->insert('arquetipo_preguntas', $data);
        }
    }

    function agregar_imagenes($id_arquetipo, $imagenes) { 
        foreach ($imagenes as $i) {
            $data = array('arquetipo_id' => $id_arquetipo, 'imagen_ubicacion' => $i['url'], 'titulo' => $i['titulo']);
            $this->db->insert('arquetipo_imagenes', $data);
        }
    }

    function invitar_alumno($id_arquetipo, $alumno) { 
      $data = array('arquetipo_id' => $id_arquetipo, 
                    'primer_nombre' => $alumno['primer_nombre'],
                    'ultimo_nombre' => $alumno['ultimo_nombre'],
                    'email' => $alumno['email'],
                    'hash' => $alumno['hash'],
                    'ocultar' => (isset($alumno['ocultar']) && $alumno['ocultar'])
                  );
      return $this->db->insert('arquetipo_alumnos', $data);
    }

    function get_alumno_by_email($id_arquetipo, $email) { 
      $query = $this->db->get_where('arquetipo_alumnos', array('arquetipo_id' => $id_arquetipo, 'email' => $email));
      if ($query->num_rows()) return $query->row();
      return false;
    }

    function listado_respuestas($id_arquetipo) { 
        $query = "select ai.id as imagen_id, group_concat(ar.arquetipo_pregunta_id) as respuestas
                  from  arquetipo_respuestas ar
                  left join arquetipo_imagenes ai on ai.id = arquetipo_imagen_id
                  where ar.arquetipo_id = $id_arquetipo
                  group by ai.id
                  order by nombre, ai.id";
        return $this->db->query($query)->result();
    }
/*
    function alumno_respuestas($id_arquetipo, $id_alumno) { 
      $query = "SELECT ai.id as imagen_id, ai.titulo as imagen_titulo, imagen_ubicacion,  ap.pregunta,  respuesta 
                FROM arquetipo_alumnos aa 
                JOIN arquetipo_respuestas ar ON ar.arquetipo_alumno_id = aa.id 
                JOIN arquetipo_preguntas ap ON ap.id = ar.arquetipo_pregunta_id 
                JOIN arquetipo_imagenes ai on ai.id = ar.arquetipo_imagen_id 
                WHERE aa.id = $id_alumno AND aa.arquetipo_id = $id_arquetipo
                and ar.publico != 0
                ORDER BY ai.id, ap.id";
      return $this->db->query($query)->result();           
    }*/
    function detalle_respuestas($id_arquetipo, $soloPublico) {
        $query = "select ap.id as pregunta_id, ai.id as imagen_id, ar.id as respuesta_id, respuesta, pregunta, publico, ar.email as email
                  from arquetipo_respuestas ar
                  join arquetipo_imagenes ai on ai.id = ar.arquetipo_imagen_id
                  join arquetipo_preguntas ap on ap.id = ar.arquetipo_pregunta_id
                  where ar.arquetipo_id = $id_arquetipo";
        if($soloPublico){

            $query =$query . " and ar.publico = $soloPublico ";
        }

        $query.= " order by ai.id, ap.id";
        return $this->db->query($query)->result();
    }
    function listado_respuestas_por_mail($id_arquetipo, $mail) {
        $query = "select ap.id as pregunta_id, ai.id as imagen_id, ar.id as respuesta_id, respuesta, pregunta, publico, ar.email as email
                  from arquetipo_respuestas ar
                  join arquetipo_imagenes ai on ai.id = ar.arquetipo_imagen_id
                  join arquetipo_preguntas ap on ap.id = ar.arquetipo_pregunta_id
                  where ar.arquetipo_id = $id_arquetipo and ar.email like '$mail'";

        $query.= " order by ap.id";
        return $this->db->query($query)->result();
    }

    function get_stock_image_name($url) { 
      $names = array(
        'ancianidad.jpg' => 'Ancianidad',
        'discapacidad.jpg' => 'Necesidades especiales',
        'juventud.jpg' => 'Juventud', 
        'legalidad.jpg' => 'Legalidad',
        'mujer.jpg' => 'Mujer',
        'ninez.jpg' => 'Niñez', 
        'pobreza.jpg' => 'Pobreza',
        'religion.jpg' => 'Religión',
        'rural.jpg' => 'Rural',
        'varon.jpg' => 'Varón'
      );
      preg_match("/.+\/(\w+\.jpg)$/", $url, $matches);
      #print_r($matches);
      return @$names[$matches[1]];
    }

    function actualizar_publico($arquetipo_respuesta_id, $estado){

        $query = "UPDATE arquetipo_respuestas  SET publico=$estado    where id = $arquetipo_respuesta_id";
        $this->db->query($query);
    }

    function ocultarTodas($arquetipoId, $imagenId){
        $query = "UPDATE arquetipo_respuestas  SET publico=FALSE    where arquetipo_id = $arquetipoId and arquetipo_imagen_id = $imagenId";
        $this->db->query($query);
    }

    function actualizarPreguntas($arquetipo_id,$listaPreguntasViejas,$listaPreguntasActualizada){

        foreach($listaPreguntasViejas as $pregunta){
            if($listaPreguntasActualizada[$pregunta->id]){
                //es un update
                $laPregunta = $listaPreguntasActualizada[$pregunta->id];
                $elId = $pregunta->id;
                $query = "UPDATE arquetipo_preguntas  SET pregunta='$laPregunta' where id = $elId";

                $this->db->query($query);
                unset($listaPreguntasActualizada[$pregunta->id]);
            }
        }
        if(count($listaPreguntasActualizada) > 0){
            $this->agregar_preguntas($arquetipo_id,$listaPreguntasActualizada);
        }


    }

    function nube($ejercicioId, $valor){

        if($valor == 1){
            $query = "UPDATE arquetipos  SET nube=TRUE    where id=$ejercicioId";
        }else{
            $query = "UPDATE arquetipos  SET nube=FALSE    where id=$ejercicioId";
        }


        $this->db->query($query);

    }

}
