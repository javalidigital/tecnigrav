<?php

// shortcode for accordings
add_shortcode('accordions', 'converio_accordions_group');
add_shortcode('accordion', 'converio_accordion');
$accordion = '';
function converio_accordions_group( $atts, $content = null ) {
    global $accordion;
    // reset divs
    $accordion = '';	
    $output= '<ul class="accordion">';    
    $output.=do_shortcode($content);
    $output.='</ul>';
    return $output;  
}  

function converio_accordion($atts, $content = null) {
    global $accordion;
    $title = '';
    extract(shortcode_atts(array(  
        'title' => ''
    ), $atts));  
    $output = '
        <li><a href="#">'.$title.'</a><div>'.do_shortcode($content).'</div></li>';
    return $output;
}
