<?php

// shortcode for content_box
add_shortcode('content_box', 'converio_content_box');
function converio_content_box($atts, $content=null){
    $box_type = ''; $title = '';
	 extract(shortcode_atts(array(  
        'box_type' => '',
		'title' => '',
    ), $atts));  
    $output ='<div class="box '.$box_type.'">';
	 if(!empty($title)) {
		$output.='<h4>'.$title.'</h4>';
	 }
    if($content) {
	    $output.='<p>'.do_shortcode($content).'</p>';
    }
	$output .= '</div>';
        return  $output;
}

