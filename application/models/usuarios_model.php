<?php 
class Usuarios_model extends My_Model {

    public $before_create = array( 'created_at', 'updated_at' );
    public $before_update = array( 'updated_at' );
    public $before_delete = array('handle_delete(id)');
	public $protected_attributes = array('id');
    public $_table = 'users';
    protected $soft_delete = TRUE;

    function update_permissions($uid,  $permissions) { 
        $this->db->where(array('user_id' => $uid))->delete('users_permissions');
        foreach ($permissions as $p) { 
            $this->db->insert('users_permissions', array('user_id' => $uid, 'permission_id' => $p));
        }
    }

    function get_by_email($email) { 
        return $this->db->get_where('users', array('email' => $email))->row();
    }

    function get_all() { 
    	$query = $this->db
            ->select('u.*')
            ->select('group_concat(p.description) as permissions')
    		->join('users_permissions up', 'up.user_id = u.id')
            ->join('permissions p', 'p.id = up.permission_id')
            ->where(array('deleted' => 0))
    		->group_by('u.id')
    		->get('users u');

    	return $query->result();
    }

    function get_all_permissions() { 
        return $this->db->get('permissions')->result();
    }

    function get_permissions($uid) { 
        $query = $this->db->select('p.description, p.id')
                 ->join('permissions p', 'p.id = permission_id')
                 ->get_where('users_permissions', array('user_id' => $uid));

        return $query->result();
    }

    function handle_delete($id) { 
        $user = $this->db->get_where('users' , array('id' => $id))->row();
        $this->update($id, array('email' => $user->email . 'DISABLED', 'login' => $user->email . 'DISABLED'));
        return true;
        #$this->update($id)
    }
}