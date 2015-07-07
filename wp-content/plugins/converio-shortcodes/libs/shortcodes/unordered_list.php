<?php
// shortcode for Unordered list
add_shortcode('unordered_list', 'converio_unordered_list');
function converio_unordered_list( $atts, $content = null ) {
	if(isset($atts) && !empty($atts))
		array_walk($atts, 'converio_arrangement_shortcode_arr_value');

    extract(shortcode_atts(array(  
        'style' => ''
    ), $atts));  

	$arr_style = array('custom' => 'custom', 'unstyled' => '', 'font-awesome-list' => 'font-awesome-list');
	$output_content = do_shortcode($content);
		
	if (in_array($style, array_keys($arr_style)))
		$output = preg_replace('/<ul>/', '<ul class="'.$arr_style[$style].'">', $output_content);
	else
		$output = $output_content;

	return $output;  
}  

