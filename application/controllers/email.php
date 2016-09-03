<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email extends MY_Controller {
	function send() { 
		echo "[" . date("Y-m-d H:i:s") . "] Starting email send \n";
		$this->load->model('Email_model');
		$this->load->library('email');
		$emails = $this->Email_model->get_batched_emails();
		echo "[" . date("Y-m-d H:i:s") . "] Found " . count($emails) . " to be sent \n";
		foreach ($emails as $email) { 
			echo "[" . date("Y-m-d H:i:s") . "] sending " . print_r($email, true) . "\n";
			#print_r($email);
			$this->email->from($email->from);
			$this->email->to($email->to); 
			$this->email->subject($email->subject);
			$this->email->message($email->message);	
			if ($this->email->send()) { 
				echo "[" . date("Y-m-d H:i:s") . "] sent ok\n";
				$this->Email_model->update_status($email->id, 'sent');
			} else { 
				echo "[" . date("Y-m-d H:i:s") . "] failed\n";
			}
		}
		echo "[" . date("Y-m-d H:i:s") . "] finished with all pending\n";
	}
}
