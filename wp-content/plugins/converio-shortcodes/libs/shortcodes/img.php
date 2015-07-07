<?php 
function converio_img($attr) {
	extract(shortcode_atts(array(
		'src' => false,
		'alt' => false,
		'title' => false,
		'width' => false,
		'height' => false
		), $attr));
		
		$output = '<img '.(!empty($attr["src"]) ? 'src="'.$src.'"' : '');
		$output .= (!empty($attr["alt"]) ? ' alt="'.$alt.'"' : '');
		$output .= (!empty($attr["title"]) ? ' title="'.$title.'"' : '');
		$output .= (!empty($attr["width"]) ? ' width="'.$width.'"' : '');
		$output .= (!empty($attr["height"]) ? ' height="'.$height.'"' : '');
		$output .= '/>';
			
	return $output;
}

add_shortcode('img', 'converio_img');