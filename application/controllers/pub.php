<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pub extends MY_Controller {

	public function index()
	{
		redirect('/account/home');
		#$this->template('account/index');
	}
}