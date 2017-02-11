<?php 
class Dialogos_model extends My_Model {

    public $before_create = array( 'created_at', 'updated_at' );
    public $before_update = array( 'updated_at' );
  	public $protected_attributes = array('id');
    protected $soft_delete = TRUE;

    function get_ejercicio_by_public_id($id) { 
      return $this->db->get_where('dialogos', ['public_id' => $id])->row();
    }

    function get_dialogo_id_by_hash($hash) { 
      $query = $this->db->select('dialogo_id')
               ->where(array('hash' => $hash))
               ->get('dialogo_alumnos aa',1);
      $tmp = $query->result();
      if (!$tmp) return false;
      return $tmp[0]->dialogo_id;
    }

    function get_respuesta_by_id($id) { 
      return $this->db->get_where('dialogo_respuestas', ['id'=>$id])->row();
    }
    function get_alumno_id_by_hash($hash) { 
      $query = $this->db->select('id')
               ->where(array('hash' => $hash))
               ->get('dialogo_alumnos aa',1);
      $tmp = $query->result();
      if (!$tmp) return false;
      return $tmp[0]->id;
    }

    function invitar_alumno($dialogo_id, $alumno) { 
      $data = array('dialogo_id' => $dialogo_id, 
                    'primer_nombre' => $alumno['primer_nombre'],
                    'ultimo_nombre' => $alumno['ultimo_nombre'],
                    'email' => $alumno['email'],
                    'hash' => $alumno['hash'],
                  );
      return $this->db->insert('dialogo_alumnos', $data);
    }

    function get_all($id_user = null) { 
    	$query = $this->db
            ->select('d.id, d.nombre, d.created_at, d.status, d.public_id, d.public_id_enabled')
            ->select('count(distinct dr.id) as respuestas')
            ->select('(select count(*) from dialogo_respuestas dr2 where dr2.dialogo_id = d.id and dr2.calificacion is null) as pendientes', false)
            ->select('count(distinct da.id) as invitados')
            ->select('u.name as autor')
    		    ->join('dialogo_respuestas dr', 'dr.dialogo_id = d.id', 'left')
            ->join('dialogo_alumnos da', 'da.dialogo_id = d.id', 'left')
            ->join('users u', 'u.id = d.id_user')
        ->where(array('d.deleted' => 0))
    		->group_by('d.id');
        
        if ($id_user) $query = $query->where(array('id_user' => $id_user));
        $query = $query->get('dialogos d');
    	return $query->result();
    }

  
    function duplicar($id_dialogo) { 
        $query = "INSERT INTO dialogos (nombre, consigna, desarrollo, id_user, pregunta_1, pregunta_2, pregunta_3, created_at, updated_at, status, public_id) 
                  SELECT concat('Copia de ', nombre), consigna, desarrollo, id_user, pregunta_1, pregunta_2, pregunta_3, now(), now(), status, now()
                  FROM dialogos where id = $id_dialogo";
        $this->db->query($query);
        $nu_arq_id = $this->db->insert_id();
        return $nu_arq_id;
    }

    function get_alumno_by_email($id_dialogo, $email) { 
      $query = $this->db->get_where('dialogo_alumnos', array('dialogo_id' => $id_dialogo, 'email' => $email));
      if ($query->num_rows()) return $query->row();
      return false;
    }

    function cerrar_ejercicio_alumno($dialogo_id, $alumno_id) { 
      return $this->db->where(['dialogo_id' => $dialogo_id, 'id' => $alumno_id])
        ->update('dialogo_alumnos', ['status' => 'cerrado']);
    }

    function listado_respuestas($id_dialogo) { 
        $query = "select concat_ws(' ', primer_nombre, ultimo_nombre) as nombre, da.id as alumno_id, 
                        min(dr.created_at) as fecha, count(distinct dr.id) as cant, 
                        sum(if(calificacion = 'rojo',1,0)) as rojo,
                        sum(if(calificacion = 'amarillo',1,0)) as amarillo,
                        sum(if(calificacion = 'verde',1,0)) as verde,
                        sum(if(calificacion is null and dr.id is not null,1,0)) as pendiente,
                        max(calificacion_cuando) as fecha,
                        da.status
                  from dialogo_alumnos da 
                  left join dialogo_respuestas dr on dr.dialogo_alumno_id = da.id 
                  where da.dialogo_id = $id_dialogo 
                  group by da.id 
                  order by nombre";
        return $this->db->query($query)->result();
    }

    function respuestas($id_dialogo, $id_alumno = '') { 
      $q = $this->db->where(['dr.dialogo_id' => $id_dialogo])->select('dr.* '); 
      if ($id_alumno) {
        $q->where(['dialogo_alumno_id' => $id_alumno]); 
      } else { 
        $q->join('dialogo_alumnos da', 'dr.dialogo_alumno_id = da.id')
        ->select('concat_ws(" ", primer_nombre, ultimo_nombre) as nombre', false)
        ->order_by('nombre , da.id');
      }
      $res = $q->get('dialogo_respuestas dr')->result();
      return $res;
    }
    
    function get_ejercicio_by_respuesta_id($id) {
      return $this->db->select('d.*')->join('dialogos d', 'd.id = dr.dialogo_id')->
        get_where('dialogo_respuestas dr', ['dr.id' => $id])->row();
    }
    function get_ejercicio_by_alumno_id($id) { 
      return $this->db->select('d.*')->join('dialogos d', 'd.id = da.dialogo_id')->
        get_where('dialogo_alumnos da', ['da.id' => $id])->row();
    }
    function get_alumno_by_id($id) { 
      return $this->db->get_where('dialogo_alumnos', ['id'=> $id])->row();
    }

    function get_alumno_by_respuesta_id($id) { 
      return $this->db->select('da.*')->join('dialogo_alumnos da', 'da.id = dr.dialogo_alumno_id')->
        get_where('dialogo_respuestas dr', ['dr.id' => $id])->row();
    }

    function calificar($id, $texto, $status) {
      $data = [
        'calificacion_cuando' => date('Y-m-d H:i:s'), 
        'calificacion_text'   => $texto, 
        'calificacion'        => $status
      ];
      return $this->db->where(['id' => $id])->update('dialogo_respuestas', $data);
    }

    function responder($data) { 
      return $this->db->insert('dialogo_respuestas', $data);

    }
    function detalle_respuestas($id_dialogo, $id_alumno=0) { 
        $query = "select dr.*, concat_ws(' ', primer_nombre, ultimo_nombre) as nombre, da.id as alumno_id
                  from dialogo_alumnos da 
                  join dialogo_respuestas dr on dr.dialogo_alumno_id = da.id 
                  where da.dialogo_id = $id_dialogo ";
        if ($id_alumno) { 
          $query.=" and da.id = $id_alumno ";
        }
        $query.= " order by dr.created_at desc ";
        #$query.= "group by da.id order by nombre, ai.id, ap.id";
        return $this->db->query($query)->result();
    }

}