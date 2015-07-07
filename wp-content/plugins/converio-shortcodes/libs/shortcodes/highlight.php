<?php

// shortcode for highlight
add_shortcode('highlight', 'converio_highlight');
function converio_highlight($atts, $content=null){
    if(isset($atts) && !empty($atts))
		array_walk($atts, 'converio_arrangement_shortcode_arr_value');

	extract(shortcode_atts(array(  
		'background_color'=> '',
		'color'=> ''
    ), $atts));  

	$output = '<span class="highlight"';

	if ((!empty($background_color) && ($background_color != 'empty')) || (!empty($color) && ($color != 'empty')))
	{
		$output .= ' style="';

		if (!empty($background_color) && ($background_color != 'empty'))
			$output .= 'background-color: '.$background_color.';';
		if (!empty($color) && ($color != 'empty'))
			$output .= 'color: '.$color.';';

		$output .= '"';
	}
	$output .= '>'.do_shortcode($content).'</span>';

    return $output;
} 


