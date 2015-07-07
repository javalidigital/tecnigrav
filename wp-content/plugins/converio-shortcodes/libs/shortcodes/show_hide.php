<?php

// shortcode for Show/Hide Content
add_shortcode('show_hide', 'converio_show_hide');
function converio_show_hide( $atts, $content = null ) {
	if(isset($atts) && !empty($atts))
		array_walk($atts, 'converio_arrangement_shortcode_arr_value');

    extract(shortcode_atts(array(  
        'button_text' => '',
        'button_color'=>'',
		'button_size'=>'',
		'button_light'=>'',
		'button_icon'=>''
    ), $atts));  

	if (!empty($box_type) && ($alternative_style == 'yes'))
		$box_type .= '2';

	$output = '<div class="show-hide">';
	$output .= do_shortcode($content);
	$output .= '<a href="#" class="btn-show-hide btn';
	if (!empty($button_color)) 
		$output .= ' '.$button_color;
	if (!empty($button_size)) 
		$output .= ' '.$button_size;
	if ($button_light == 'yes') 
		$output .= ' light';
	$output .= '">';

	if (!empty($button_icon))
		$output .= '<i class="fa '.$button_icon.'"></i>';
	$output .= $button_text.'</a>';
		
	return $output;  
}  

