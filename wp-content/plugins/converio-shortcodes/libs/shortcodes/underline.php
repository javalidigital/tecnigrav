<?php
// shortcode Underline
add_shortcode('underline','converio_underline');

function converio_underline($atts, $content = null) {
	$output = '<span class="underline">'.$content.'</span>';
	return $output;
}
