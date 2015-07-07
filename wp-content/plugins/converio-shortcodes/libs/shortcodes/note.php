<?php

// shortcode for Note
add_shortcode('note', 'converio_note');
function converio_note( $atts, $content = null ) {
	if(isset($atts) && !empty($atts))
		array_walk($atts, 'converio_arrangement_shortcode_arr_value');

    extract(shortcode_atts(array(  
        'size' => ''
    ), $atts));  
	
	$output = '<p class="';

	if ($size == 'small') 
		$output .= 'note-small';
	else
		$output .= 'note';

	$output .= '">'.do_shortcode($content).'</p>';
	return $output;  
}  
