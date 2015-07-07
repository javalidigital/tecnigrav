<?php


// shortcode for Icon Colored On Hover
add_shortcode('icon_colored_on_hover', 'converio_icon_colored_on_hover');
function converio_icon_colored_on_hover($atts, $content=null){
	if(isset($atts) && !empty($atts))
		array_walk($atts, 'converio_arrangement_shortcode_arr_value');

	extract(shortcode_atts(array(  
        'size' => '',
		'name' => '',
		'color' => '',
		'background_color' => '',
		'color_on_hover' => '',
		'background_color_on_hover' => '',
		'circled_background' => ''
    ), $atts));  
	
	$arr_size = array('1', '2', '3', '4', '5');

	$output = '<span class="fa-stack fa-'.(in_array($size, $arr_size) ? $size : '1').'x">';
	$output .= '<i class="fa'.($name != '' ? ' '.$name : '').' fa-stack-1x fa-inverse'.($circled_background == 'yes' ? ' fa-radius' : '').'"';
	
	if ($color != '' || $background_color != '')
		$output .= ' style="'.($color != '' ? 'color:'.$color.';' : '').($background_color != '' ? 'background:'.$background_color.';' : '').'"';
	if ($background_color_on_hover != '')
		$output .= ' data-background-color-hover="'.preg_replace('/^#/', '', $background_color_on_hover).'"';
	if ($color_on_hover != '')	
		$output .= ' data-color-hover="'.preg_replace('/^#/', '', $color_on_hover).'"';
	$output .= '></i>';
	$output .= '</span>';

    return $output;
} 

