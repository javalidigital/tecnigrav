<?php

// shortcode Clients

add_shortcode('clients', 'converio_clients');
add_shortcode('client', 'converio_client');

function converio_clients( $atts, $content = null ) {
	if(isset($atts) && !empty($atts))
		array_walk($atts, 'converio_arrangement_shortcode_arr_value');

	extract(shortcode_atts(array(  
		'title'=> '',
		'title_size'=> ''
    ), $atts));    
	
	$arr_title_size = array('2', '3', '4');

	$output = '<article class="content-slider hp-our-clients">'; 

	if ($title != '') {
		if (!in_array($title_size, $arr_title_size))
			$title_size = '3';
		$output .= '<h'.$title_size.'>'.$title.'</h'.$title_size.'>';
	}

	$output .= '<div class="slider-box"><ul>';
    $output .= do_shortcode($content);
    $output .= '</ul></div></article>';
    return $output;  
}  

function converio_client($attr, $content = null) {
	extract(shortcode_atts(array('link' => '', 'image_alt' => '', 'image_url' => '', 'open_in_new_window' => '', 'image_width' => '', 'image_height' => ''), $attr));
	
	//image width and height
	if($image_width) {
		$image_width = ' width="'.$image_width.'"';
	}
	if($image_height) {
		$image_height = ' height="'.$image_height.'"';
	}	
	
	//linked image
    if ($link != '' && $link != "Link" ) {
		$output = '<li><a href="'.$link.'"';
		if($open_in_new_window == 'yes') {
			$output .= ' target="_blank"';
		}
		$output .= '>';
		$output .= '<img src="'.$image_url.'" alt="'.$image_alt.'"';
		$output .= $image_width.$image_height.' class="logo"></a></li>';
	} 
	else {
	//not linked image
		$output = '<li><img src="'.$image_url.'" alt="'.$image_alt.'"';		
		$output .= $image_width.$image_height.' class="logo"></li>';
	}
    return $output;
}
