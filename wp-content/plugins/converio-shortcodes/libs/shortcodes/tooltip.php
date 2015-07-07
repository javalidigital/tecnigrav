<?php

// shortcode for tooltip
add_shortcode('tooltip', 'converio_tooltip');
function converio_tooltip($atts, $content=null){
    if(isset($atts) && !empty($atts))
		array_walk($atts, 'converio_arrangement_shortcode_arr_value');

	extract(shortcode_atts(array(  
		'title'=> ''
    ), $atts));  

    return '<span class="tooltip-text dark" title="'.$title.'">'.do_shortcode($content).'</span>';
} 

