<?php


// shortcode for Icon
add_shortcode('icon', 'converio_icon');
function converio_icon($atts, $content=null){
    if(isset($atts) && !empty($atts))
		array_walk($atts, 'converio_arrangement_shortcode_arr_value');

	extract(shortcode_atts(array(  
		'name'=> '',
		'size'=> '',
		'color'=> '',
		'background_color'=> '',
		'circled_background'=> '',
		'color_on_hover'=> '',
		'background_color_on_hover'=> ''
    ), $atts));  

	$arr_size = array('1', '2', '3', '4', '5', '6');
	$output = '<i class="fa ';
	$output .= !empty($name) ? $name.' ' : '';
	$output .= in_array($size, $arr_size) ? 'fa-'.$size.'x': 'fa-1x';
	$output .= $circled_background == 'yes' ? ' fa-radius': '';
	$output .= '"';
	
	if ((!empty($color) && ($color != 'empty')) || (!empty($background_color) && ($background_color != 'empty')))
	{
		$output .= ' style="';
		$output .= (!empty($color) && ($color != 'empty')) ? 'color: '.$color.';' : '';
		$output .= (!empty($background_color) && ($background_color != 'empty')) ? 'background-color: '.$background_color.';' : '';
		$output .= '"';
	}
	
	if ($background_color_on_hover != '')
		$output .= ' data-background-color-hover="'.preg_replace('/^#/', '', $background_color_on_hover).'"';
	if ($color_on_hover != '')	
		$output .= ' data-color-hover="'.preg_replace('/^#/', '', $color_on_hover).'"';

	$output .= '></i>';

    return $output;
} 

