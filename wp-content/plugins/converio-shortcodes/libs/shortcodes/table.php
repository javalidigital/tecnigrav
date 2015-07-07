<?php

// shortcode for table
add_shortcode('table', 'converio_table');
add_shortcode('table_title', 'converio_table_title');
add_shortcode('table_content', 'converio_table_content');
add_shortcode('table_open_row','converio_table_open_row');
$table = '';
function converio_table_open_row($atts, $content = null) {
    return '<tr>'.do_shortcode($content).'</tr>';
}
function converio_table( $atts, $content = null ) {
    global $table;
    // reset divs
	if(isset($atts["style"]) && $atts["style"] == 'style1') {
    $output= '<table>';
	} else {
	$output= '<table class="alt">';
	}

    $output.=do_shortcode($content);
    $output.='</table>';
    return $output;  
}  

function converio_table_title($atts, $content = null,$table_content) {
    global $table;
    $output = '<th>'.do_shortcode($content).'</th>';
    return $output;
}

function converio_table_content($atts, $content = null) {
    global $table;  
    $output = '<td>'.do_shortcode($content).'</td>';
    return $output;
}
