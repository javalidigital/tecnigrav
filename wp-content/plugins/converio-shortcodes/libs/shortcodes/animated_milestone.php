<?php

// shortcode for Animated Milestone

add_shortcode('animated_milestone', 'converio_animated_milestone');

function converio_animated_milestone($attr, $content=""){
	if(isset($attr["value_suffix"]) && !empty($attr["value_suffix"])) {
		$output = "<span>".converio_arrangement_shortcode_value($attr['value_suffix'])."</span>";
	} else {
		$output = "";
	}
	return '<div class="animated-milestone" data-value="'.(isset($attr["value"]) ? converio_arrangement_shortcode_value($attr["value"]) : '').'" data-speed="'.(isset($attr["speed"]) ? converio_arrangement_shortcode_value($attr["speed"]) : '').'" data-color="'.(isset($attr["color"]) ? converio_arrangement_shortcode_value($attr["color"]) : '').'">'.$content.$output.'</div>';
}

