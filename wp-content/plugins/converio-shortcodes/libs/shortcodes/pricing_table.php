<?php

// shortcode for table
add_shortcode('pricing_table', 'converio_pricing_table');
add_shortcode('pricing_table_title', 'converio_pricing_table_title');
add_shortcode('pricing_table_content', 'converio_pricing_table_content');
add_shortcode('sup', 'converio_sup');
add_shortcode('link', 'converio_link_href');
add_shortcode('option', 'converio_option');
add_shortcode('pricing_open_row', 'converio_pricing_open_row');
$table = '';
function converio_pricing_table( $atts, $content = null ) {
    global $table;
    $output = '<div class="table"><table class="pricing">';
    $output.= do_shortcode($content);
    $output.='</table></div>';
    return $output;
}

function converio_pricing_table_title($atts, $content = null) {
    global $table;
    $output = '
        <th>'.do_shortcode($content).'</th>';
    return $output;
}

function converio_pricing_table_content($atts, $content = null) {
    global $table;
    $output = '
        <td>'.do_shortcode($content).'</td>';
    return $output;
}

function converio_sup($atts, $content = null) {
    return '<sup>'.do_shortcode($content).'</sup>';
}

function converio_link_href($atts, $content = null) {
	if(!isset($atts["type"]) && empty($atts["type"])) {
    $output = '<a href="'.$atts["link_url"].'">'.do_shortcode($content).'</a>';
	} else {
		$output = '<a href="'.$atts["link_url"].'" class="'.$atts["type"].'">'.do_shortcode($content).'</a>';
	}
	
	return $output;
}

function converio_span($atts, $content = null) {
    if(empty($atts["class"]) || $atts['class'] == 'no') {
        $class = 'no';
    } else {
        $class = 'yes';
    }
    return '<span class="'.$class.'">'.do_shortcode($content).'</span>';
}

function converio_option($atts, $content = null) {
	$option = str_replace('"','',substr($atts[0],1));
    return '<span class="'.$option.'">'.do_shortcode($content).'</span>';
}


function converio_pricing_open_row($atts, $content = null) { 
    return '<tr>'.do_shortcode($content).'</tr>';
}
