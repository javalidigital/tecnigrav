<?php

function converio_testimonial($attr, $content = null) {
	extract(shortcode_atts(array('name' => false, 'org' => false, 'avatar' => false, 'avatar_position' => false, 'font_size' => false, 'quote' => false, 'class' => false), $attr));
	$html = '<div class="testimonial';
	if($font_size) {
		$html .= ' font-size-'.$font_size.'x';
	}
	if($quote == 'yes') {
		$html .= ' quote';
	}
	if ($class) {
		$html .= ' '.$class;
	}
	
	$html .= '">';
	if ($avatar && $avatar_position == 'left') {
		$html .= '<img src="'.$avatar.'" alt="'.$name.'">';
	}
	$html .= '<blockquote>';
	$html .= wpautop(do_shortcode($content));
	$html .= '</blockquote>';
	if ($avatar && $avatar_position == 'bottom') {
		$html .= '<img class="avatar-bottom" src="'.$avatar.'" alt="'.$name.'">';
	}
	$html .= '<p><span class="name">'.$name.'</span>';
	if ($org) {
		if ($avatar && $avatar_position == 'bottom') {
		$html .= '<br>';
		}
		$html .= '<span class="org">'.$org.'</span>';
	}
	$html .= '</p></div>';
	return $html;
}

function converio_testimonial_slider($attr, $content = null) {
	extract(shortcode_atts(array('title' => false, 'delay' => false, 'duration' => false, 'auto' => false, 'bottom_paginator' => false, 'class' => false, 'animation' => false), $attr));
	$html = '<div class="flexslider';
	if ($animation == 'slide') {
	$html .= ' flexslider-slide';
	} else {
		$html .= ' flexslider-fade';
	}
	if ($bottom_paginator == 'yes') {
		$html .= ' flexslider-fadeshow-control';
	}
	if ($title) {
		$html .= ' flexslider-fadeshow-direction';
	}
	if ($class) {
		$html .= ' '.$class;
	}	
	$html .= '" data-delay="'.$delay.'" data-duration="'.$duration.'" data-auto="'.$auto.'" data-animation="'.$animation.'">';
	if ($title) {
	$html .= '<h3 class="light">'.$title.'</h3>';
	}
	$html .= '<div class="slider-box">';
	$html .= str_replace('<br />', '', do_shortcode($content));
	$html .= '</div>';
	$html .= '</div>';
	return $html;
}

function converio_testimonial_shortcodes() {
	add_shortcode('testimonial', 'converio_testimonial');
	add_shortcode('testimonial_slider', 'converio_testimonial_slider');
}

add_action('init', 'converio_testimonial_shortcodes');