<?php

// shortcode Box1
add_shortcode('box','converio_box1');

function converio_box1($attr, $content = '') {
	extract(shortcode_atts(array('icon' => '', 'icon_color' => '', 'icon_background' => '', 'title' => '', 'link' => '' ), $attr));	
	
	$output = '<div class="box-1 txt-center">';
	$output .= '<i class="fa'. (!empty($icon) ? ' '.converio_arrangement_shortcode_value($icon) : '') . ' fa-5x" style="';
	$output .= ''. (!empty($icon_color) ? 'color:'.converio_arrangement_shortcode_value($icon_color).';' : '');
	$output .= (!empty($icon_background) ? 'background-color:'.converio_arrangement_shortcode_value($icon_background).';' : '').'"></i>';

	if ($title) {
		if ($link) {
			$output .= '<h2><a href="'.$link.'">'.converio_arrangement_shortcode_value($title).'</a></h2>';
		} else {
			$output .= '<h2>'.converio_arrangement_shortcode_value($title).'</h2>';
		}
	}
	
	$output .= '<p>'.$content.'</p>';
	$output .= '</div>';
	return $output;
}

// shortcode Box2
add_shortcode('box2','converio_box2');

function converio_box2($atts,$content = '') {
	$output = '<div class="box-2 txt-center">';

	$output .= '<span class="fa-stack fa-3x"><i class="fa'.((isset($atts['icon']) && !empty($atts['icon'])) ? ' '.converio_arrangement_shortcode_value($atts['icon']) : '').' fa-stack-1x fa-inverse fa-radius" style="';
	$output .= ((isset($atts['icon_background']) && !empty($atts['icon_background'])) ? 'background-color:'.converio_arrangement_shortcode_value($atts['icon_background']).';' : '').'"></i></span>';

	if (isset($atts['title']) && !empty($atts['title']))
		$output .= '<h4>'.converio_arrangement_shortcode_value($atts['title']).'</h4>';

	$output .= '<p>'.$content.'</p>';
	$output .= '</div>';
	return $output;
}

// shortcode Box Default
add_shortcode('box_default','converio_box_default');

function converio_box_default($atts,$content = '') {
	$output = '<div class="box-default';
	$output .= (isset($atts['border_position']) && !empty($atts['border_position'])) ? ' '.converio_arrangement_shortcode_value($atts['border_position']) : '';
	$output .= '"';
	$output_style = '';
	if (isset($atts['border_color']) && !empty($atts['border_color']))
		$output_style .= 'border-color:'.converio_arrangement_shortcode_value($atts['border_color']).';';

	if (isset($atts['border_width']) && !empty($atts['border_width']))
		$output_style .= 'border-width:'.converio_arrangement_shortcode_value($atts['border_width']).';';

	if ($output_style != '')
		$output .= ' style="'.$output_style.'"';

	$output .= '>';

	$output .= do_shortcode($content);
	$output .= '</div>';
	return $output;
}

// shortcode Box Icon Left
add_shortcode('box_icon_left','converio_box_icon_left');

function converio_box_icon_left($atts,$content = '') {
	$output = '<div class="box-icon-left">';
	
	if (isset($atts['icon_size']) && !empty($atts['icon_size'])) {
		$icon_size = converio_arrangement_shortcode_value($atts['icon_size']);
		if ($atts['icon_size'] != '2' && $atts['icon_size'] != '3')
			$icon_size = '2';
	} else {
		$icon_size = '2';
	}


	$output .= '<span class="fa-stack fa-'.$icon_size.'x"><i class="fa'.((isset($atts['icon']) && !empty($atts['icon'])) ? ' '.converio_arrangement_shortcode_value($atts['icon']) : '').' fa-stack-1x fa-inverse fa-radius"';
	
	if ((isset($atts['icon_color']) && !empty($atts['icon_color'])) || (isset($atts['icon_background']) && !empty($atts['icon_background']))) {
	$output .= ' style="';
	$output .= (isset($atts['icon_color']) && !empty($atts['icon_color'])) ? 'color:'.converio_arrangement_shortcode_value($atts['icon_color']).'; ' : '';
	$output .= ((isset($atts['icon_background']) && !empty($atts['icon_background'])) ? 'background:'.converio_arrangement_shortcode_value($atts['icon_background']).';' : '').'"';
	}

	$output .= '></i></span>';

	$output .= do_shortcode($content);
	$output .= '</div>';
	return $output;
}

// shortcode Box Colored
add_shortcode('box_colored','converio_box_colored');

function converio_box_colored($atts,$content = '') {
	$output = '<div class="box-colored txt-center"';
	$output .= (isset($atts['background']) && !empty($atts['background'])) ? ' style="background-color:'.converio_arrangement_shortcode_value($atts['background']).';"' : '';
	$output .= '>';

	$output .= '<i class="fa'.((isset($atts['icon']) && !empty($atts['icon'])) ? ' '.converio_arrangement_shortcode_value($atts['icon']) : '').' fa-4x" style="';
	$output .= ''.((isset($atts['icon_color']) && !empty($atts['icon_color'])) ? 'color:'.converio_arrangement_shortcode_value($atts['icon_color']).';' : '');
	$output .= '"></i>';

	if (isset($atts['title']) && !empty($atts['title']))
		$output .= '<h2>'.converio_arrangement_shortcode_value($atts['title']).'</h2>';

	$output .= do_shortcode($content);
	$output .= '</div>';
	return $output;
}

// shortcode Box Colored Alternative
add_shortcode('box_colored_alternative','converio_box_colored_alternative');

function converio_box_colored_alternative($atts,$content = '') {
	$output = '<div class="box-colored alt"';
	$output .= (isset($atts['border_color']) && !empty($atts['border_color'])) ? ' style="border-color:'.converio_arrangement_shortcode_value($atts['border_color']).';"' : '';
	$output .= '>';
	
	$output .= '<span class="fa-stack fa-3x"><i class="fa'.((isset($atts['icon']) && !empty($atts['icon'])) ? ' '.converio_arrangement_shortcode_value($atts['icon']) : '').' fa-stack-1x fa-inverse fa-radius" style="';
	$output .= ''.((isset($atts['icon_background']) && !empty($atts['icon_background'])) ? 'background:'.converio_arrangement_shortcode_value($atts['icon_background']).';' : '');
	$output .= '"></i></span>';

	if (isset($atts['title']) && !empty($atts['title']))
		$output .= '<h4>'.converio_arrangement_shortcode_value($atts['title']).'</h4>';

	$output .= do_shortcode($content);
	$output .= '</div>';
	return $output;
}
