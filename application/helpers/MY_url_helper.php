<?php 
function assets_url($what = '') { 
	$config =& get_config();
	return $config['assets_url'] . $what;
}