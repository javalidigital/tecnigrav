<?php

// shortcode Fullwidth
add_shortcode('fullwidth','converio_fullwidth');

function converio_fullwidth($atts,$content = null) {
	$class_output = "";

	if(isset($atts) && !empty($atts))
		array_walk($atts, 'converio_arrangement_shortcode_arr_value');

	extract(shortcode_atts(array(  
		'padding_top'=> '',
		'padding_bottom'=> '',
		'background_color'=> '',
		'background_image'=> '',
		'background_position'=> '',
		'background_repeat'=> '',
		'background_class'=> '',
		'top_border'=> '',
		'shadow'=> '',
		'parallax'=> '',
		'bottom_margin'=> '',
		'background_image'=> '',
		'pattern'=> '',
		'opacity'=> ''

    ), $atts));  

	if ($background_class != '')
		$class_output .= " ".$background_class;

	if ($top_border == 'yes')
		$class_output .= " top-border-enabled";

	if ($shadow == 'yes')
		$class_output .= " shadow-enabled";

	if ($bottom_margin == 'no')
		$class_output .= " no-bottom-margin";

	

	$seperate_div_output = '';

	if ($pattern != '' || $background_image != '')
	{
		$seperate_div_output = '<div class="custom-bg';
		if ($parallax == 'yes')
			$seperate_div_output .= " parallax";

		if ($background_image != 'Put Image Url' && $background_image != '')
			$seperate_div_output .= " bgsIE";
		
		if ($pattern != '')
			$seperate_div_output .= " p".$pattern;

		if ($background_image != 'Put Image Url' && $background_image != '')
			$seperate_div_output .= " bg-image";
	
		$seperate_div_output .= '"';

		if ($background_image != 'Put Image Url' && $background_image != '') {
			
			$seperate_div_output .= ' style="';
			
			if ($background_color != '')
				$seperate_div_output .= 'background-color: '.$background_color.';';
			if ($background_image != '' && $background_image != 'Put Image Url')
				$seperate_div_output .= "background-image: url('".$background_image."');";
			if ($background_position != '')
				$seperate_div_output .= 'background-position: '.$background_color.';';
			if ($background_repeat != '')
				$seperate_div_output .= 'background-repeat: '.$background_color.';';
			if ($opacity != '')
				$seperate_div_output .= 'filter:alpha(opacity='.$opacity.');';
			$seperate_div_output .= '"';
		} else if ($pattern != '') {
			if ($opacity != '') {
				$seperate_div_output .= ' style="';
				$seperate_div_output .= 'filter:alpha(opacity='.($opacity * 100).'); opacity:'.$opacity.';';

				$seperate_div_output .= '"';
			}
		}
		$seperate_div_output .= '></div>';
	}


	$output = '<div class="full-width-bg'.$class_output.'"';

	$output_style = '';

	if ($padding_top != '')
		$output_style .= 'padding-top: '.$padding_top.'px;';
	if ($padding_bottom != '')
		$output_style .= 'padding-bottom: '.$padding_bottom.'px;';
	if ($background_color != '')
		$output_style .= 'background-color: '.$background_color.';';

	if ($output_style != '')
		$output .= ' style="'.$output_style.'"';

	$output .= '>';

	$output .= $seperate_div_output;
	
	$output .= '<div class="content-container">'.do_shortcode($content).'</div></div>';

	return $output;
}
