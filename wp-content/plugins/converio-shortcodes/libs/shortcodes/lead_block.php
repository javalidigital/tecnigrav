<?php
// shortcode for Lead Block

add_shortcode('lead_block', 'converio_lead_block');

function converio_lead_block($attr, $content=""){
	$output = '<div class="lead-block';
	if (isset($attr["class"]) && !empty($attr["class"]))
		$output .= ' '.converio_arrangement_shortcode_value($attr["class"]);

	$output .= '">';
	$output .= isset($attr["title"]) && !empty($attr["title"]) ? '<h2 class="alt">'.converio_arrangement_shortcode_value($attr["title"]).'</h2>' : '';
	
	if (isset($attr["text"]) && !empty($attr["text"]))
		$output .= '<p class="muted">'.converio_arrangement_shortcode_value($attr["text"]).'</p>';
	
	$output .= do_shortcode($content);
	$output .= '</div>';

	return $output;
}
