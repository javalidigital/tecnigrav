<?php

// shortcode blockquote
add_shortcode('quote', 'converio_quote_group');
add_shortcode('quote_content', 'converio_quote_content');
add_shortcode('quote_signature', 'converio_quote_signature');

function converio_quote_group( $atts, $content = null ) {
    global $quote;
    $quote = '';	
    $output= '<blockquote class="quote">';    
    $output.=do_shortcode($content);
    $output.='</blockquote>';
    return $output;  
}  

function converio_quote_content($atts, $content = null) {
    global $quote;
    if($content) {
    $output = '<p>'.do_shortcode($content).'</p>';
    }
    return $output;
}
function converio_quote_signature($atts, $content = null) {
    global $quote;
    $name = '';
	 extract(shortcode_atts(array(  
        'name' => ''
    ), $atts));  
    $output = '<p class="signature"><span>'.$name.'</span> '.do_shortcode($content).'</p>';   
    return $output;
}

