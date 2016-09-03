<?php 
class MY_Controller extends CI_Controller {
	public $template_type = 'public';
    function __construct() {
        parent::__construct();
        #die("aca = " . var_dump($this->user->get_id()));
        $this->user->validate_session();
    }

    function template($template_name, $vars = array(), $location = '') { 
        switch ($this->template_type) {
            case 'public':
                $vars['_template_menu'] = "templates/menu_public";
                break;
            case 'admin':
                $vars['_template_menu'] = "templates/menu_admin";
                $vars['_hide_footer'] = true;
                break;
            case 'arquetipo': 
            case 'dialogo':
                $vars['_template_menu'] = "templates/menu_arquetipos";
                $vars['_hide_footer'] = true;   
                break;
        } 
        $vars['_title'] = "CITEP";
        $vars['_is_admin'] = $this->user->has_permission('admin');
        $vars['_me'] = "me";
        if (!$location) { 
        	$vars['_location'] = $this->uri->segment(1);
        } else { 
			$vars['_location'] = $location;
        }

        $this->load->template($template_name, $vars);
    }
}
