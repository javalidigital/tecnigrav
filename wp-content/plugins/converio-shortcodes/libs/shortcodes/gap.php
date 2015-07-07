<?php

// shortcode for gap
add_shortcode('gap', 'converio_gap');
function converio_gap($atts, $content=null){
    if(isset($atts) && !empty($atts))
		array_walk($atts, 'converio_arrangement_shortcode_arr_value');

	extract(shortcode_atts(array(  
		'id'=> '',
		'size'=> '',
		'style' => '',
		'class' => ''
    ), $atts));  
	$output = '<hr class="gap';
	if ($class != '')
		$output .= ' '.$class;

	$output .= '"';

	if ($id != '')
		$output .= ' id="'.$id.'"';
	
	if ($style != '' or $size != '')
	{
		$output .= ' style="';
		if ($size != '')
			$output .= 'margin:'.$size.' 0 0 0;';
		if ($style != '')
			$output .= $style;
		$output .= '"';
	}

	$output .= '>';
	

    return $output;
} 
