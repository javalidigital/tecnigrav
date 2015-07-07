<?php

// shortcode for Image Caption
add_shortcode('image_caption', 'converio_image_caption');
function converio_image_caption( $atts, $content = null ) {
   	$output = '<p class="wp-caption-text">'.do_shortcode($content).'</p>';
  
	return $output;  
}  

