<?php

// shortcode for Lead
add_shortcode('lead', 'converio_lead');
function converio_lead($atts, $content=null){
    if(isset($atts) && !empty($atts))
		array_walk($atts, 'converio_arrangement_shortcode_arr_value');

	extract(shortcode_atts(array(  
		'image_url'=> '',
		'image_alt'=> '',
		'class' => ''
    ), $atts));  
	$output = '<section class="full-width-bg lead';
	
	if($class != '')
		$output .= ' '.$class;

	$output .= '">';
	$output .= '<div>';
	$output .= do_shortcode($content);
	$output .= '<img src="'.$image_url.'" alt="'.$image_alt.'" >';
	$output .= '</div>';
	$output .= '<div class="clear"></div>';
	$output .= '</section>';
    return $output;
} 


