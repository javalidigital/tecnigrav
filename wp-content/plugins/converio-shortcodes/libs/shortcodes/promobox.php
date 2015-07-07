<?php

// shortcode Promobox
add_shortcode('promobox','converio_promobox');
add_shortcode('slogan','converio_slogan');

function converio_promobox($atts,$content = null) {
    if(isset($atts['button_link']) && $atts['button_link'] != 'Button link') {
        $link = $atts['button_link'];
    } else {
        $link = '#';
    }
	$output = '';
	$arr_pattern = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20');
	
	$class_output = '';
	$div_class_output = '';


	if (isset($atts['pattern']) && !empty($atts['pattern'])) {
		$div_class_output = in_array(converio_arrangement_shortcode_value($atts['pattern']), $arr_pattern) ? ' p'.converio_arrangement_shortcode_value($atts['pattern']) : ' p01';
	} else{ $div_class_output = ' p01';}

	if (isset($atts['fullwidth']) && !empty($atts['fullwidth'])) {
		$class_output .= (converio_arrangement_shortcode_value($atts['fullwidth']) == 'yes') ? ' full-width-bg' : '';
	}

	if (isset($atts['bottom_margin']) && !empty($atts['bottom_margin'])) {
		$class_output .= (converio_arrangement_shortcode_value($atts['bottom_margin']) == 'no') ? ' no-bottom-margin' : '';
	}

	if (isset($atts['top_margin']) && !empty($atts['top_margin'])) {
		$class_output .= (converio_arrangement_shortcode_value($atts['top_margin']) == 'no') ? ' no-top-margin' : '';
	}

	$style_output = '';
	if (isset($atts['background_color']) && !empty($atts['background_color'])) {
		$style_output = ' style="background-color:	'.converio_arrangement_shortcode_value($atts['background_color']).'"';
	}

	$style_output2 = '';
	if (isset($atts['opacity']) && !empty($atts['opacity'])) {
		$style_output2 = ' style="filter:alpha(opacity='.(converio_arrangement_shortcode_value($atts['opacity']) * 100).'); opacity:'.converio_arrangement_shortcode_value($atts['opacity']).';"';
	}

	$output .= '<section class="hp-intro'.$class_output.'"'.$style_output.'>
	<div class="custom-bg'.$div_class_output.'"'.$style_output2.'></div>
	<div class="content-container">';
	add_filter("converio_filters_button_code", "converio_filter_button_promobox", 10);
	$output .= do_shortcode($content);
	$output .= '</div>
</section>';
	remove_all_filters("converio_filters_button_code");
	return $output;
}

function converio_filter_button_promobox($content){ 
	return '<p class="cta">'.$content.'</p>';
}

function converio_slogan($atts,$content = '') {
	$output = '<p class="slogan"';
	if (isset($atts['color']) && !empty($atts['color'])) {
		$output .= ' style="color:'.converio_arrangement_shortcode_value($atts['color']).'"';
	}
	$output .= '>';

	return $output.do_shortcode($content).'</p>';
}
