<?php

// shortcode Image Slider
add_shortcode('image_slider','converio_image_slider');
add_shortcode('image','converio_image');

function converio_image_slider($atts,$content = '') {
	if(isset($atts) && !empty($atts))
		array_walk($atts, 'converio_arrangement_shortcode_arr_value');

	extract(shortcode_atts(array(  
		'type'=> '',
		'translate_next'=> '',
		'translate_previous'=> ''
    ), $atts));  
	
	remove_all_filters('converio_filters_image_slider', 10);
	
	if ($type == '2') {
		add_filter('converio_filters_image_slider', 'converio_filter_image_slider_two', 10);
		$output = '<div class="slider-image">';
		$output .= '<div class="container">';
		$output .= '<p class="number"><span></span>/<b></b></p>';
		$output .= '<ul id="slides">';
		$output .= '<a class="slidesjs-previous slidesjs-navigation" href="#" title="Previous">'.$translate_next.'</a> <a class="slidesjs-next slidesjs-navigation" href="#" title="Next">'.$translate_previous.'</a>';
		$output	.= do_shortcode($content);
		$output .= '</ul>';
		$output .= '</div>';
		$output .= '</div>';
	} else {
		add_filter('converio_filters_image_slider', 'converio_filter_image_slider_one', 10);
		$output = '<section class="slider3">';
		$output .= '<div class="slider">';
		$output	.= do_shortcode($content);
		$output .= '</div>';
		$output .= '</section>';
	}
	return $output;
}



function converio_image($atts,$content = '') {
	$output = '';
	$output .= '<img';
	
	if (isset($atts['alt']) && !empty($atts['alt']))
		$output .= ' alt="'.converio_arrangement_shortcode_value($atts['alt']).'"';

	if (isset($atts['url']) && !empty($atts['url']))
		$output .= ' src="'.converio_arrangement_shortcode_value($atts['url']).'"';

	if (isset($atts['width']) && !empty($atts['width']))
		$output .= ' width="'.converio_arrangement_shortcode_value($atts['width']).'"';

	if (isset($atts['height']) && !empty($atts['height']))
		$output .= ' height="'.converio_arrangement_shortcode_value($atts['height']).'"';
	
	$output .= '/>';

	return apply_filters('converio_filters_image_slider', $output);
}

function converio_filter_image_slider_one($content) {
	return '<article>'.$content.'</article>';
}

function converio_filter_image_slider_two($content) {
	return '<li>'.$content.'</li>';
}
