<?php

// shortcode for progress
add_shortcode('progress', 'converio_progress');
function converio_progress($atts, $content=null){
    $percent= '';
	extract(shortcode_atts(array(  
        'percent' => ''
    ), $atts));  

	$size = '';

	if(isset($atts["size"]) && !empty($atts["size"]))
		if(converio_arrangement_shortcode_value($atts["size"]) == 'small')
			$size = ' small';

    return '<p class="progress'.$size.'"><span class="fill" data-width="'.intval($percent).'"><span><strong>'.$percent.'</strong>'.do_shortcode($content).'</span></span></p>';
} 


