<?php


// shortcode for tabs
add_shortcode('tabs', 'converio_tabs');
add_shortcode('tab', 'converio_tab');
$tabs_divs = '';
function converio_tabs( $atts, $content = null ) {
    global $tabs_divs;
    
    // reset divs
    $tabs_divs = '';

	if(isset($atts) && !empty($atts))
		array_walk($atts, 'converio_arrangement_shortcode_arr_value');

    extract(shortcode_atts(array(  
        'vertical'=>'',
		'style' => '',
		'title_align' => ''
    ), $atts));  
 
	$vertical = $vertical == "yes" ? " alt" : "";
	$arr_style = array('2', '3');

	if (in_array($style, $arr_style))
		$vertical .= ' alt'.$style;

	$arr_title_align = array('centered', 'right');
	$title_align = in_array($title_align, $arr_title_align) ? ' '.$title_align : '';
		
   	$output= '<div class="tabbed'.$vertical.$title_align.'"><ul class="tabs">'.do_shortcode($content).'</ul>'.$tabs_divs.'</div>';
    return $output;  
}  

function converio_tab($atts, $content = null) {
    global $tabs_divs;

	if(isset($atts) && !empty($atts))
		array_walk($atts, 'converio_arrangement_shortcode_arr_value');

    extract(shortcode_atts(array(  
        'title' => '',
        'icon'=>'' 
    ), $atts));  

    $output = '<li><a href="#">';

	if (!empty($icon))
		$output .= '<i class="fa '.$icon.'"></i> ';

	$output .= $title.'</a></li>';
    $tabs_divs .= '<div class="tab-content">'.do_shortcode($content).'</div>';
    return $output;
}

