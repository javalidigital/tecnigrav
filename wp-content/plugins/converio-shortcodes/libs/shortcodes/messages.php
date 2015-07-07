<?php

// shortcode for Messages
add_shortcode('messages', 'converio_messages');
function converio_messages($atts, $content=null){
    $box_type = '';
	 extract(shortcode_atts(array(  
        'box_type' => ''
    ), $atts));  
	if (isset($atts["hide_text"])) { 
		$hide_text = $atts["hide_text"];
	} else {
		$hide_text = '';
	}
	if($box_type){
		return  '<p class="msg '.$box_type.'"><a class="hide" href="#">'.$hide_text.'</a> '.do_shortcode($content).'</p>';
	}else{
		return '<div class="messages">'.$content.'</div>';
	}
} 
