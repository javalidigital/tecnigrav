<?php


// shortcode Social
add_shortcode('social_icons','converio_social_icons');
add_shortcode('social','converio_social');

function converio_social_icons($atts,$content = null) {
	if(isset($atts) && !empty($atts))
		array_walk($atts, 'converio_arrangement_shortcode_arr_value');

	extract(shortcode_atts(array(  
		'style'=> '',
		'colored'=> ''
    ), $atts));  

	$output = '<ul class="social';
	
	if ($style == 'light')
		$output .= ' social-light';

	if ($colored == 'yes')
		$output .= ' social-colored';

	$output .= '">'.do_shortcode($content).'</ul>';

	return $output;
}

function converio_social($atts, $content = null) {
	if(isset($atts) && !empty($atts))
		array_walk($atts, 'converio_arrangement_shortcode_arr_value');

	extract(shortcode_atts(array(  
		'link'=> '',
		'type'=> '',
		'title'=> '',
		'open_in_new_window'=> ''
    ), $atts));  

	$output = '';
	if (empty($link) || ($link == 'Link'))
		$link = '#';

	$output .= '<li><a href="'.$link.'" class="'.strtolower($type).'"';

	if	(!empty($title))
		$output .= ' title="'.$title.'"';

	if (empty($type))
		$type = 'etc';

	if ($open_in_new_window == 'yes')
		$output .= ' target="_blank"';

    $output .= '>'.do_shortcode($content).'</a></li>';

    return $output;
}

