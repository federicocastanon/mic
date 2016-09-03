<?php 
function my_number_format($number,$decimals = 0) {
	$ci = & get_instance();
	$p = $ci->publisher;
    return number_format($number, $decimals, $p->decimal_separator, $p->thousand_separator);
}

function my_money_format($number, $decimals = 0) { 
	$ci = & get_instance();
	return $ci->publisher->currency_denominator . ' ' . my_number_format($number, $decimals);
}

function my_date_format($date) { 
	$ci = & get_instance();
	return date($ci->publisher->date_format, strtotime($date));
}