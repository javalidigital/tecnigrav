<?php

// shortcode Animated Circle Chart
add_shortcode('animated_circle_chart','converio_animated_circle_chart');

function converio_animated_circle_chart($atts,$content = '') {
	$output = '<canvas class="circle-counter"';
	
	if (isset($atts['width']) && !empty($atts['width']))
		$output .= ' width="'.converio_arrangement_shortcode_value($atts['width']).'"';

	if (isset($atts['height']) && !empty($atts['height']))
		$output .= ' height="'.converio_arrangement_shortcode_value($atts['height']).'"';

	if (isset($atts['percent']) && !empty($atts['percent']))
		$output .= ' data-percent="'.converio_arrangement_shortcode_value($atts['percent']).'"';

	if (isset($atts['color']) && !empty($atts['color']))
		$output .= ' data-color="'.converio_arrangement_shortcode_value($atts['color']).'"';
	
	$output .= '></canvas>';

	$output .= '<div class="circle-counter-text">'.converio_arrangement_shortcode_value($content).'</div>';

	return $output;
}
