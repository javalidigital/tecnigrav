<?php

// shortcode blockquote
add_shortcode('blockquote','converio_blockquote');

function converio_blockquote($atts, $content = null) {
	if(isset($atts) && !empty($atts))
		array_walk($atts, 'converio_arrangement_shortcode_arr_value');

	extract(shortcode_atts(array(  
        'type' => '',
		'position' => '',
		'author' => ''
    ), $atts));  

	$arr_position = array('left' => 'blockquote-left', 'right' => 'blockquote-right');
		
	$output = '<blockquote';
	if (in_array($position, array_keys($arr_position)) || $type == '2')
	{
		$output .= ' class="';
		$output .= !empty($arr_position[$position]) ? $arr_position[$position].' ' : '';
		$output .= $type == '2' ? 'quote-typography' : '';	
		$output .= '"';
	}
	$output .= '>';
	$output .= '<p>';
	$output .= do_shortcode($content);
	$output .= '</p>';
	$output .= !empty($author) ? '<small>'.$author.'</small>' : '';
	$output .= '</blockquote>';

	return $output;
}

