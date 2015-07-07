<?php
// shortcode for combined notifications
add_shortcode('combined_notifications', 'converio_combined_notifications');
function converio_combined_notifications( $atts, $content = null ) {
	if(isset($atts) && !empty($atts))
		array_walk($atts, 'converio_arrangement_shortcode_arr_value');

    extract(shortcode_atts(array(  
        'box_type' => '',
        'alternative_style'=>'',
		'hide_text'=>''
    ), $atts));  

	if (!empty($box_type) && ($alternative_style == 'yes'))
	{
		$box_type .= '2';
		$hide_type = '2';
	}
	else
	{
		$hide_type = '';
	}

	$output = '<div class="box';

	if (!empty($box_type))
		$output .= ' '.$box_type;
	
	$output .= '">';
	$output .= '<a class="hide'.$hide_type.'" href="#">'.$hide_text.'</a>';
	$output .= do_shortcode($content);
	$output .= '</div>';
					
	return $output;  
}  
