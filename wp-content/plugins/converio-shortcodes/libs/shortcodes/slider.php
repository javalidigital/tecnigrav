<?php

// shortcode Sliders
add_shortcode('slider', 'converio_slider');
add_shortcode('slide', 'converio_slide');
add_shortcode('slide_content', 'converio_slide_content');

function converio_slider( $atts, $content = null ) {
    $output= '<section class="portfolio-slider slider">';  
    $output.=do_shortcode($content);
    $output.='</section>';
    return $output;  
}  

function converio_slide($atts, $content = null) {
    $output = '<article>';
    if(!empty($atts['image_url']) && $atts['image_url'] != 'Image url') {
    $output .= '<img src="'.$atts["image_url"].'" alt="'.$atts['image_alt'].'">';
    }
    $output .= '<div><h3>'.$atts["title"].'</h3>'.do_shortcode($content).'
				</div>
			</article>';
  
    return $output;
}

function converio_slide_content($atts, $content = null) {
    $output = $content;   
    return $output;
}

