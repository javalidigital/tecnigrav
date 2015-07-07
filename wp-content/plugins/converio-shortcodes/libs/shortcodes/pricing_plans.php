<?php


// add shortcode pricing plans
add_shortcode('pricing_plans','converio_pricing_plans');
add_shortcode('pricing_plan','converio_pricing_plan');
add_shortcode('price_item','converio_price_item');
add_shortcode('strong','converio_strong');
function converio_pricing_plans($atts, $content = null) {
    $output ='<div class="pricing-plans">';
    $output .= do_shortcode($content);
    $output .= '</div>';
    return $output;
}

function converio_pricing_plan($atts, $content = null) {
    if($atts['is_selected'] == 'yes') {
       $output = '<div class="pricing-plan selected">';
    } else {
       $output = '<div class="pricing-plan">';
    }

    $output .= do_shortcode($content);

	$output .='</div>';
    return  $output;
}

function converio_price_item($atts, $content = null) {
    $output ='<li>';
    $output .= do_shortcode($content);
    $output .= '</li>';
    return $output;
}

function converio_strong($atts, $content = null) {
    return '<strong>'.do_shortcode($content).'</strong>';
}

add_shortcode('price','converio_price');
add_shortcode('currency','converio_currency');

function converio_price($atts, $content = '') {
	remove_all_filters('converio_filters_price_class');
	$output_content = do_shortcode($content);
	$output = '<p class="price'.apply_filters('converio_filters_price_class', '');
	$output .= '">';
	$output .= $output_content;
	$output .= '</p>';
    return $output;
}

function converio_currency($atts, $content = '') {
	$output = '';
	if ($content == '')
		add_filter('converio_filters_price_class', 'converio_filter_price_free', 10);
	else
		$output = '<span>'.do_shortcode($content).'</span>';

	return $output;
}

function converio_filter_price_free($content) {
	return ' free';
}

add_shortcode('subtitle','converio_subtitle');
add_shortcode('pricing_lead','converio_pricing_lead');

function converio_subtitle($atts, $content = '') {
	$output ='<p class="subtitle">'.$content.'</p>';
    return $output;
}

function converio_pricing_lead($atts, $content = '') {
	$output = '<div class="pricing-lead">'.do_shortcode($content).'</div>';
    return $output;
}

