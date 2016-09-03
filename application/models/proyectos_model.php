<?php 
class Proyectos_model extends My_Model {

    public $before_create = array( 'created_at', 'updated_at' );
    public $before_update = array( 'updated_at' );
  	public $protected_attributes = array('id');
    protected $soft_delete = TRUE;

    function get_all($id_user = null) { 
    	$query = $this->db
            ->select('p.*, u.name as autor')
            ->select('(select count(*) from proyectos_colaboradores pc join users u on u.id = pc.user_id where pc.proyecto_id = p.id and u.deleted = 0) as colaboradores', false)
            ->join('proyectos_colaboradores pc', 'pc.proyecto_id=p.id' ,'left')
            ->where(["p.saved" => 1])
            ->join('users u', 'u.id = p.id_user')
            ->where(array('p.deleted' => 0))
    		    ->group_by('p.id');
        
        if ($id_user) $query = $query->where("(p.id_user = $id_user OR pc.user_id = $id_user)", null, false);
        $query = $query->get('proyectos p');
        #echo '<pre>';print_r($this->db->last_query());die();
    	return $query->result();
    }

    function insert_subsecciones_default($id) { 
      $query = "INSERT INTO proyecto_subsecciones (id_proyecto, nombre, descripcion, seccion, tipo)
                    SELECT $id, nombre, descripcion, seccion, tipo FROM proyecto_subsecciones_default";
      return $this->db->query($query);

    }

    // Locking functions

    function clean_heartbeats() { 
      // clear expired heartbeats with a timeout of 5 minutes;
      $this->db->where('heartbeat_time + interval 5 minute < now()', null, false )
               ->set(['heartbeat_time'=>null, 'heartbeat_user_id' => null])
               ->update('proyecto_subsecciones');
    }

    function get_subseccion_owner($id) { 
      $this->clean_heartbeats();
      return $this->db
        ->select('u.name, u.id, u.email')
        ->join('users u', 'u.id = ps.heartbeat_user_id')
        ->where(['ps.id'=>$id])
        ->get('proyecto_subsecciones ps')
        ->row();
    }

    function send_heartbeat($id, $user_id) { 
      $this->clean_heartbeats();
      $this->db->query("UPDATE proyecto_subsecciones set heartbeat_user_id = $user_id, heartbeat_time = now()
        WHERE (id = $id) and ((heartbeat_time is null and heartbeat_user_id is null) or (heartbeat_user_id = $user_id))");
    }


    // end locking functions

    function get($id_proyecto) { 
      $ret = array();
      $ret['proyecto'] = $this->db->get_where('proyectos', ['id'=>$id_proyecto])->row();
      $secciones = $this->db->get_where('proyecto_subsecciones', ['id_proyecto'=>$id_proyecto])->result();
      foreach ($secciones as $s) { 
        $ret['secciones'][$s->seccion][] = $s;
      }
      $ret['colaboradores'] = $this->db
              ->select('u.id, u.name, u.email')
              ->join('users u', 'u.id = pc.user_id')
              ->where(['proyecto_id' => $id_proyecto, 'u.deleted' => 0])
              ->get('proyectos_colaboradores pc')->result();
      return $ret;
    }

    function borrar_subseccion($id) { 
      return $this->db->where(['id' => $id])->delete('proyecto_subsecciones');
    }

    function borrar_colaborador($proyecto_id, $id) { 
      return $this->db->where(['user_id' => $id, 'proyecto_id' => $proyecto_id])->delete('proyectos_colaboradores');
    }

    function get_subseccion($id) { 
      return $this->db->get_where('proyecto_subsecciones', ['id'=> $id])->row();
    }

    function editar_subseccion($data) { 
      #print_r($data);die('aca');
      if ($data->id) { 
        return $this->db->where(['id' => $data->id])->update('proyecto_subsecciones', $data);
      } else { 
        return $this->db->insert('proyecto_subsecciones', $data);
      }
    }

    function agregar_comentario($data) { 
      return $this->db->insert('proyecto_subseccion_comentarios', $data);
    }

    function listar_comentarios($id_subseccion) { 
     $q = $this->db->select('p.*, u.name')
                   ->where(['id_proyecto_subseccion' => $id_subseccion])
                   ->join('users u', 'u.id = p.id_user')
                   ->get('proyecto_subseccion_comentarios p');
      return $q->result();
    }
    

    function get_colaborador_by_id($proyecto_id, $user_id) { 
      return $this->db->get_where('proyectos_colaboradores', ['user_id' => $user_id, 'proyecto_id' => $proyecto_id])->row();
    }

    function agregar_colaborador($proyecto_id, $user_id) { 
        $data = array(
            'proyecto_id' => $proyecto_id,
            'user_id' => $user_id, 
        );
      return $this->db->insert('proyectos_colaboradores', $data);
    }
}