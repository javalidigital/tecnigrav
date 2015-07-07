<?php

// shortcode Divider
add_shortcode('divider','converio_divider');

function converio_divider2($atts,$content = null) {	
	$arr_type = array('1', '2', '3', '4', '5', '6', '7');
	$type = '1';

	if (isset($atts['type']) && !empty($atts['type'])) {
		$type = in_array(converio_arrangement_shortcode_value($atts['type']), $arr_type) ? converio_arrangement_shortcode_value($atts['type']) : '1';
	}

	if (($type == '5' || $type == '6') && (isset($atts['text']) && !empty($atts['text']))) {
		$output = '<h6 class="divider'.$type.'"><span>'.$atts['text'].'</span></h6>';
	} else if ($type == '7') {
		$output = '<h6 class="divider7"><span><i class="fa'.(isset($atts['fa_icon']) ? ' '.converio_arrangement_shortcode_value($atts['fa_icon']) : "").'"'.(isset($atts['fa_icon_color']) ? ' style="color: '.converio_arrangement_shortcode_value($atts['fa_icon_color']).'"' : "").'></i></span></h6>';
	} else {
		$output = '<hr class="divider'.$type.'">';
	}

	return $output;
}

function converio_divider($atts,$content = '') {	
	$arr_type = array('1', '2');
	$type = '';
	$output = '';
	if (isset($atts['type']) && !empty($atts['type'])) {
		$type = in_array(converio_arrangement_shortcode_value($atts['type']), $arr_type) ? converio_arrangement_shortcode_value($atts['type']) : '1';
	}
	
	if ($content == '') {
		$output .= '<hr';

		if ($type == '2' || (isset($atts['width']) && !empty($atts['width'])))
			$output .= ' class="';
	
	} else {
		$output .= '<div class="divider';
	}

	if ($type == '2') {

		if ($content != '') 
			$output .= ' ';

		$output .= 'divider-2';	
	}

	if (isset($atts['width']) && !empty($atts['width'])) {
		if (($content != '' or $type == '2') and ($atts['width'] == 'small' or $atts['width'] == 'medium'))
			$output .= ' ';

		if ($atts['width'] == 'small')
			$output .= 'divider-sm';
		else if ($atts['width'] == 'medium')
			$output .= 'divider-md';
	}

	if ($content == '') {
		if ($type == '2' || (isset($atts['width']) && !empty($atts['width'])))
			$output .= '"';

		$output .= ' />';
	} else {
		$output .= '"><div class="divider-content">';
		$output .= do_shortcode($content);
		$output .= '</div></div>';
	}

	
	return $output;
}
