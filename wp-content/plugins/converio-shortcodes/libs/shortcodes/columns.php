<?php

// add shortcode for column
add_filter('the_content', "converio_columns_replace", 9);
function converio_columns_replace($content)
{
	$arr_content = explode('[columns', $content);
	$new_content = '';

	for($i = count($arr_content) - 1; $i > 0; $i--)
	{
		$new_content = '[columns'.$i.$arr_content[$i].$new_content;
		add_shortcode('columns'.$i, 'converio_columns');
	}
	$new_content = $arr_content[0].$new_content;

	for($i = count($arr_content) - 1; $i > 0; $i--)
	{
		$arr1 = explode('[columns'.$i , $new_content, 2);
		$arr2 = explode('[/columns]' , $arr1[1], 2);
		$new_content = $arr1[0].'[columns'.$i.$arr2[0].'[/columns'.$i.']'.$arr2[1];
	}
	
	return $new_content;
}


add_shortcode('one_full','converio_one_full');
add_shortcode('one_half','converio_one_half');
add_shortcode('one_third','converio_one_third');
add_shortcode('one_fourth','converio_one_fourth');
add_shortcode('one_fifth','multipurpose_one_fifth');
add_shortcode('one_sixth','multipurpose_one_sixth');
add_shortcode('three_fourths','converio_three_fourths');
add_shortcode('two_thirds','converio_two_thirds');

add_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_full', 10, 2);

function converio_columns($atts, $content = null) {
    $output = '';
	remove_all_filters('converio_filters_columns_class');
	$output_content = do_shortcode($content);
	$output .= '<section class="columns'.apply_filters('converio_filters_columns_class', '');
	if (isset($atts['class']) && !empty($atts['class']))
		$output .= ' '.converio_arrangement_shortcode_value($atts['class']);
	$output .= '">';
	$output.= $output_content;
    $output.= '</section>';

    return trim($output);
}

function converio_one_full($atts, $content = null) {   
	remove_all_filters('converio_filters_team_class');

	remove_all_filters('converio_filters_article_tag');
	if (isset($atts['div']) && $atts['div'] == 'yes') 
		add_filter('converio_filters_article_tag', 'converio_filter_article_div', 10);
	else
		add_filter('converio_filters_article_tag', 'converio_filter_article_tag', 10);

	$output_content = do_shortcode($content);

	$output = '';
    $output .= 'class="col col1'.apply_filters('converio_filters_team_class', '');
	if (isset($atts['class']) && !empty($atts['class']))
		$output .= ' '.converio_arrangement_shortcode_value($atts['class']);
	$output .= '">';
	$output .= $output_content;
    $output_filter = apply_filters('converio_filters_article_tag', trim($output));
	return $output_filter."<div class='clearfix'></div>";
}

function converio_filter_article_tag($content) {
	return '<article '.$content.'</article>';
}

function converio_filter_article_div($content) {
	return '<div '.$content.'</div>';
}

function converio_one_half($atts, $content = null) {
    $output = '';

	remove_all_filters('converio_filters_article_tag');
	if (isset($atts['div']) && $atts['div'] == 'yes') 
		add_filter('converio_filters_article_tag', 'converio_filter_article_div', 10);
	else
		add_filter('converio_filters_article_tag', 'converio_filter_article_tag', 10);

	if (has_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_full', 10))
		remove_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_full', 10);
	
	add_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_medium', 10, 2);

	remove_all_filters('converio_filters_team_class');
	$output_content = do_shortcode($content);

    $output .= 'class="col col2'.apply_filters('converio_filters_team_class', '');
	if (isset($atts['class']) && !empty($atts['class']))
		$output .= ' '.converio_arrangement_shortcode_value($atts['class']);
	$output .= '">';
	$output .= $output_content;

	remove_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_medium', 10);
	add_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_full', 10, 2);

    $output_filter = apply_filters('converio_filters_article_tag', trim($output));
	return $output_filter;
}

function converio_one_third($atts, $content = null) {
	remove_all_filters('converio_filters_article_tag');
	if (isset($atts['div']) && $atts['div'] == 'yes') 
		add_filter('converio_filters_article_tag', 'converio_filter_article_div', 10);
	else
		add_filter('converio_filters_article_tag', 'converio_filter_article_tag', 10);

	if (has_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_full', 10))
		remove_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_full', 10);
	
	add_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_small', 10, 2);

    $output = '';

	remove_all_filters('converio_filters_team_class');
	$output_content = do_shortcode($content);

    $output .= 'class="col col3'.apply_filters('converio_filters_team_class', '');
	if (isset($atts['class']) && !empty($atts['class']))
		$output .= ' '.converio_arrangement_shortcode_value($atts['class']);
	$output .= '">';
	$output .= $output_content;

	remove_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_small', 10);
	add_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_full', 10, 2);

    $output_filter = apply_filters('converio_filters_article_tag', trim($output));
	return $output_filter;
}

function converio_one_fourth($atts, $content = null) {
    $output = '';

	remove_all_filters('converio_filters_article_tag');
	if (isset($atts['div']) && $atts['div'] == 'yes') 
		add_filter('converio_filters_article_tag', 'converio_filter_article_div', 10);
	else
		add_filter('converio_filters_article_tag', 'converio_filter_article_tag', 10);

	if (has_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_full', 10))
		remove_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_full', 10);
	
	add_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_small', 10, 2);


	remove_all_filters('converio_filters_team_class');
	$output_content = do_shortcode($content);

    $output .= 'class="col col4'.apply_filters('converio_filters_team_class', '');
	if (isset($atts['class']) && !empty($atts['class']))
		$output .= ' '.converio_arrangement_shortcode_value($atts['class']);
	$output .= '">';
	$output .= $output_content;

	remove_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_small', 10);
	add_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_full', 10, 2);

    $output_filter = apply_filters('converio_filters_article_tag', trim($output));
	return $output_filter;
}

function multipurpose_one_fifth($atts, $content = null) {
    $output = '';

	remove_all_filters('converio_filters_article_tag');
	if (isset($atts['div']) && $atts['div'] == 'yes') 
		add_filter('converio_filters_article_tag', 'converio_filter_article_div', 10);
	else
		add_filter('converio_filters_article_tag', 'converio_filter_article_tag', 10);

	remove_all_filters('converio_filters_team_class');
	remove_all_filters('converio_filters_recent_posts_class');
	$output_content = do_shortcode($content);

    $output .= 'class="col col5'.apply_filters('converio_filters_team_class', '').apply_filters('converio_filters_recent_posts_class', '');
	if (isset($atts['class']) && !empty($atts['class']))
		$output .= ' '.$atts['class'];
	$output .= '">';
	$output .= $output_content;

    $output_filter = apply_filters('converio_filters_article_tag', trim($output));
	return $output_filter;
}

function multipurpose_one_sixth($atts, $content = null) {
    $output = '';

	remove_all_filters('converio_filters_article_tag');
	if (isset($atts['div']) && $atts['div'] == 'yes') 
		add_filter('converio_filters_article_tag', 'converio_filter_article_div', 10);
	else
		add_filter('converio_filters_article_tag', 'converio_filter_article_tag', 10);

	remove_all_filters('converio_filters_team_class');
	remove_all_filters('converio_filters_recent_posts_class');
	$output_content = do_shortcode($content);

    $output .= 'class="col col6'.apply_filters('converio_filters_team_class', '').apply_filters('converio_filters_recent_posts_class', '');
	if (isset($atts['class']) && !empty($atts['class']))
		$output .= ' '.$atts['class'];
	$output .= '">';
	$output .= $output_content;

    $output_filter = apply_filters('converio_filters_article_tag', trim($output));
	return $output_filter;
}

function converio_three_fourths($atts, $content = null) {
    $output = '';

	remove_all_filters('converio_filters_article_tag');
	if (isset($atts['div']) && $atts['div'] == 'yes') 
		add_filter('converio_filters_article_tag', 'converio_filter_article_div', 10);
	else
		add_filter('converio_filters_article_tag', 'converio_filter_article_tag', 10);

	if (has_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_full', 10))
		remove_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_full', 10);
	
	add_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_medium', 10, 2);

	remove_all_filters('converio_filters_team_class');
	$output_content = do_shortcode($content);

    $output .= 'class="col col34'.apply_filters('converio_filters_team_class', '');
	if (isset($atts['class']) && !empty($atts['class']))
		$output .= ' '.converio_arrangement_shortcode_value($atts['class']);
	$output .= '">';
	$output .= $output_content;

	remove_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_medium', 10);
	add_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_full', 10, 2);

	$output_filter = apply_filters('converio_filters_article_tag', trim($output));
	return $output_filter;
}

function converio_two_thirds($atts, $content = null) {
    $output = '';

	remove_all_filters('converio_filters_article_tag');
	if (isset($atts['div']) && $atts['div'] == 'yes') 
		add_filter('converio_filters_article_tag', 'converio_filter_article_div', 10);
	else
		add_filter('converio_filters_article_tag', 'converio_filter_article_tag', 10);

	if (has_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_full', 10))
		remove_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_full', 10);
	
	add_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_medium', 10, 2);

	remove_all_filters('converio_filters_team_class');
	$output_content = do_shortcode($content);

    $output .= 'class="col col23'.apply_filters('converio_filters_team_class', '');
	if (isset($atts['class']) && !empty($atts['class']))
		$output .= ' '.converio_arrangement_shortcode_value($atts['class']);
	$output .= '">';
	$output .= $output_content;

	remove_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_medium', 10);
	add_filter('converio_filters_testimonial_slider', 'converio_filter_testimonial_slider_full', 10, 2);

    $output_filter = apply_filters('converio_filters_article_tag', trim($output));
	return $output_filter;
}
