<?php

// shortcode dropcap
add_filter('the_content', "converio_dropcap_remove", 9);
add_filter('the_content', "converio_dropcap_replace", 10);

function converio_dropcap_remove($content)
{
	$new_content = preg_replace('/\[dropcap(.*?)\]/is', '<dropcap$1>', $content);
	return $new_content;
}

function converio_dropcap_replace($content)
{
	$new_content = preg_replace('/<dropcap(.*?)>/', '[dropcap$1]', $content);
	return $new_content;
}

add_shortcode('dropcap','converio_dropcap');

function converio_dropcap($atts, $content = null) {
	$arr_style = array('default', 'circle', 'square');
	$arr_size = array('normal', 'small');

	$size = 'normal';
	$style = 'default';

	if(isset($atts["style"]) && !empty($atts["style"]))
		if(in_array(converio_arrangement_shortcode_value($atts["style"]), $arr_style))
			$style = converio_arrangement_shortcode_value($atts["style"]);

	if(isset($atts["size"]) && !empty($atts["size"]))
		if(in_array(converio_arrangement_shortcode_value($atts["size"]), $arr_size))
			$size = converio_arrangement_shortcode_value($atts["size"]);
	
	$arr_size_class = array('normal' => 'dc', 'small' => 'dc-small');
	$arr_style_class = array('normal' => array('default' => '', 'circle' => ' dc-circle', 'square' => ' dc-square'), 'small' => array('default' => ' dc-normal-small', 'circle' => ' dc-circle-small', 'square' => ' dc-square-small'));

	$output = '<span class="'.$arr_size_class[$size].$arr_style_class[$size][$style].'">'.converio_arrangement_shortcode_value($content).'</span>';

	return $output;
}
