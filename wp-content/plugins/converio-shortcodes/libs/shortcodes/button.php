<?php

// shortcode for button
add_shortcode('button', 'converio_button');
function converio_button($attr, $content=""){
	if(isset($attr) && !empty($attr))
		array_walk($attr, 'converio_arrangement_shortcode_arr_value');

	extract(shortcode_atts(array(  
        'color' => '',
		'size' => '',
		'light' => '',
		'icon' => '',
		'open_in_new_window' => '#',
		'link' => ''
    ), $attr));  

//	$arr_color = array('orange', 'green', 'turquoise', 'blue', 'purple', 'pink', 'red', 'dark-gray', 'light-gray');
	$arr_size = array('large', 'small');

	if($link == '')
		$link = '#';

	$output = '<a href="'.$link.'" class="btn'.(in_array($size, $arr_size) ? ' '.$size : '').($light == "yes" ? ' light' : '').($color != '' ? ' '.$color : '').'"'.($open_in_new_window == "yes" ? ' target="_blank"' : '').'>';
	if (!empty($icon))
	$output .= '<i class="fa'.(!empty($icon) ? ' '.$icon : '').'"></i>';
	
	$output .= $content.'</a>';

	return apply_filters("converio_filters_button_code", $output);
}

