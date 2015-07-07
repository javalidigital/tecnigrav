<?php
// shortcode headings 
add_shortcode('special_heading','converio_special_heading');

function converio_special_heading($atts, $content = null) {
	$output = '';
	if(isset($atts["type"])) {
		$output = '<'.$atts["type"].'><span>'.do_shortcode($content).'</span></'.$atts["type"].'>';
	}
	return $output;
}

add_shortcode('heading','converio_header_default');
function converio_header_default($atts, $content = null) {
	$output = '';
	if(isset($atts["size"]) && !empty($atts["size"])) {
		$output = '<h'.$atts["size"];
		if(isset($atts["color"]) && !empty($atts["color"]))
			$output .= ' style="'.converio_arrangement_shortcode_value($atts["color"]).';"';
		$output .= '>'.do_shortcode($content).'</h'.$atts["size"].'>';
	} 
	return $output;
}

