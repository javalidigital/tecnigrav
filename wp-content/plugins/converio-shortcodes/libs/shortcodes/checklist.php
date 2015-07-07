<?php
// shortcode for Checklist
add_shortcode('checklist', 'converio_checklist');
function converio_checklist( $atts, $content = null ) {
	if(isset($atts) && !empty($atts))
		array_walk($atts, 'converio_arrangement_shortcode_arr_value');

    extract(shortcode_atts(array(  
        'style' => ''
    ), $atts));  

	$arr_style = array('1', '2', '3', '4');
	$output_content = do_shortcode($content);
		
	if (!in_array($style, $arr_style))
		$style = '1';

	$output = preg_replace('/<ul>/', '<ul class="checklist tick'.$style.'">', $output_content);
	return $output;  
}  

