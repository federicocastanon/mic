<?php
class Gantt_model extends My_Model {

	public $before_create = array('created_at', 'updated_at');
	public $before_update = array('updated_at');
	public $protected_attributes = array('id');
	protected $soft_delete = TRUE;

	function get_gantt_id($id) {
		//return $this->db->get('tareas')->row();
		$query = <<<QUERY
		SELECT * FROM tareas WHERE id_proyecto='{$id}'
QUERY;
	 	return $this -> db -> query($query) -> result();
	}

	function unset_gantt($id) {
		$query =<<<QUERY
			DELETE FROM tareas WHERE id_proyecto='{$id}'
QUERY;
		return $this -> db -> query($query);
		
	}

	function set_gantt($data) {
		
		return $this -> db -> insert('tareas', $data);
	}

	function get_gantt_calendar($proyecto_id) { 
		$query = <<<QUERY
		SELECT inicio as start, fin as end, name as title FROM tareas WHERE id_proyecto='{$proyecto_id}'
QUERY;
	 	return $this -> db -> query($query) -> result();

	}

}
