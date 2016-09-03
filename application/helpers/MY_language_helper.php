<?php
function l($token, $print = True) { 
	$str = lang($token);
	if (!$str) $str = $token;

	if ($print) { 
		echo $str;
	} else { 
		return $str;
	}
}

