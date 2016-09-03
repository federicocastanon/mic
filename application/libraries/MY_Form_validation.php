<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

 	/**
	 * exissts in the database
	 *
	 * @access	public
	 * @param	string
	 * @param	field
	 * @return	bool
	 */
	public function exists($str, $field)
	{
		list($table, $field)=explode('.', $field);
		$query = $this->CI->db->limit(1)->get_where($table, array($field => $str));
		
		return $query->num_rows() > 0;
    }


	public function valid_url($str)
	{
		$to_test = $str;
		if (substr($to_test,0,7) != 'http://') $to_test = 'http://' . $to_test;
		$ok = filter_var($to_test, FILTER_VALIDATE_URL);
		if ($ok) { 
			return $str;
		} else { 
			return False;
		}
    }
}