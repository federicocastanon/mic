<?php 
class Email_model extends My_Model {

    public $before_create = array( 'created_at', 'updated_at' );
    public $before_update = array( 'updated_at' );
  	public $protected_attributes = array('id');
    //protected $soft_delete = TRUE;

    function batch($email) { 
      $email['status'] = 'created';
      $status = $this->db->insert('emails', $email);
      return $status;
    }

    function update_status($id, $status) { 
      return $this->db->where(['id' => $id])->update('emails', ['status' => $status]);
      #echo $this->db->last_query(); die('in update_status');
    }

    function get_batched_emails() { 
      return $this->db->get_where('emails', ['status' => 'created'])->result();
    }
}
