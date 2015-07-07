<?php

// add shortcode for Recent Posts in Columns
add_shortcode('recent_posts', 'converio_recent_posts');

function converio_recent_posts($atts, $content = null) {
    if (isset($atts) && !empty($atts))
        array_walk($atts, 'converio_arrangement_shortcode_arr_value');

    extract(shortcode_atts(array(
        'number_of_columns' => '4',
		'number_of_posts' => '4',
        'excerpt_words' => '40',
        'strip_html' => 'yes',
        'title' => '',
		'category_name' => ''
    ), $atts));

    global $wpdb;

    $args = array(
        'posts_per_page' => $number_of_posts,
        'orderby' => 'post_date',
        'order' => 'DESC',
        'post_type' => 'post',
        'post_status' => 'publish',
		'category_name' => $category_name,
        'suppress_filters' => true);

    $posts_array = get_posts($args);

    $output = '<section class="columns postlist masonry postlist-blog">';
	if( !empty($title) ) {
		$output .='<h3>' . $title . '</h3>';
	}

    if (!empty($posts_array)) {
        foreach ($posts_array as $key_data => $val_data) {

            $comment = get_comment_count($val_data->ID);
            $output .='<article class="col col' . $number_of_columns . ' item">';


			if (has_post_thumbnail($val_data->ID)) {
				$output .= '<a href="' . get_permalink($val_data->ID) . '">' . get_the_post_thumbnail($val_data->ID, 'thumbnail-medium', $val_data->post_title) . '</a>';
			}

            $output .= '<h2 class="post-headline"><a href="' . get_permalink($val_data->ID) . '">' . $val_data->post_title . '</a></h2>';
			
			if (!has_post_thumbnail($val_data->ID)) {
            	//show the excerp if there is no featured image
				if (!empty($val_data->post_excerpt)) {
                	$output .= '<p>' . converio_cut_character_word($excerpt_words, $val_data->post_excerpt, $strip_html) . '</p>';
            	} elseif (!empty($val_data->post_content)) {
                	$output .= '<p>' . converio_cut_character_word($excerpt_words, $val_data->post_content, $strip_html) . '</p>';
            	}
			}
			
            $output.= '</article>';
        }
    }

    $output .= '</section>';

    return $output;
}


function converio_get_excerpt_max_charlength($charlength, $str, $strip_html = true) {
    if ($strip_html == 'yes') {
        $excerpt = strip_tags($str);
    } else {
        $excerpt = $str;
    }
    $string = strip_shortcodes($excerpt);
    $charlength++;

    if (mb_strlen($string, 'utf8') > $charlength) {
        //$last_space = strrpos( substr( $string, 0, $charlength + 1 ), ' ' );
        $str = substr($string, 0, $charlength);
        if (!empty($str)) {
            $str = $str . '[...]';
        }
    } else {
        $str = $string;
    }
    return $str;
}

function converio_cut_character_word($number = 0, $str = '', $strip_html = true) {
    if ($strip_html == 'yes') {
		$excerpt = strip_tags($str);
    } else {
        $excerpt = $str;
    }
    $string = strip_shortcodes($excerpt);

    $string = explode(' ', $string);
    $str = '';
    $i = 0;

    foreach ($string as $key => $value) {

        if ($i == $number) {
            return $str;
        } else {
            $str.= $value . ' ';
        }
        $i++;
    }
    return $str;
}
