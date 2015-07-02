<?php

function converio_breadcrumb()
{
	global $converio_breadcrumb_header;
    $breadcrumb_disabled = get_theme_mod('breadcrumb_disabled');
	
    if ($breadcrumb_disabled == 1) return; //optional

    $pattern = get_theme_mod('breadcrumb_pattern');
	if (empty($pattern)) $pattern = 2;
    if ($pattern == -1) {
        $pattern_class = '';
    } else {
        if ($pattern < 10) {
            $pattern = "0" . $pattern;
        }
        $pattern_class = "p".$pattern;
    }

    $color = esc_attr(get_theme_mod('breadcrumb_color'));
    if ($color) $style = 'style="background-color: ' . $color . '"';
    else $style = '';

    $main = esc_attr__('Home', 'converio');
    if(is_day() || is_month() || is_year()) {
        $arc_year = get_the_time('Y');
        $arc_month = get_the_time('F');
        $arc_day = get_the_time('d');
        $arc_day_full = get_the_time('l');
        $url_year = get_year_link($arc_year);
        $url_month = get_month_link($arc_year,$arc_month);
    }
	
	if(is_search() | is_home() | is_archive() ) {
		$hide_breadcrumb = $breadcrumb_disabled;
		if ($hide_breadcrumb == 1) return;
	}
	
    if (!is_front_page()) {

    if(is_single() | is_page()) {
        $id = get_the_ID();
        $hide_breadcrumb = get_post_meta($id, 'hide_breadcrumb', true);
		if($hide_breadcrumb == 3) {$hide_breadcrumb = $breadcrumb_disabled;}
		if ($hide_breadcrumb == 1) return;
    }
	
	//fix for woocommerce - show products with without-headline setting
	if (class_exists('Woocommerce')) {
		if(is_product() ) {
			$hide_breadcrumb = $breadcrumb_disabled;
		}
	}
	//end of fix for woocommerce
	if($hide_breadcrumb == 2) {
		$without_headline = ' without-headline';
	} else {
		$without_headline = '';
	}
    echo '<section class="breadcrumb '.esc_attr($without_headline).'" '.$style.'>';
	if ($pattern_class != '') {
		$breadcrumb_pattern_opacity = get_theme_mod('breadcrumb_pattern_opacity');
	    if ($breadcrumb_pattern_opacity != '') {
			$style = 'style="opacity: ' . esc_attr($breadcrumb_pattern_opacity) . '"';
		}
	    else {
	    	$style = '';
	    }
		
		echo '<div class="custom-bg '.esc_attr($pattern_class).'" '.$style.'></div>';
	}
	echo '<div class="content-container"><p>';

    global $post, $cat;
    $home_link = esc_url(home_url());
    $delimiter = ' <span>/</span> ';
	$link_before = '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
	$link_after = '</span>';
    echo $link_before . '<a href="' . esc_url(home_url()) . '" itemprop="url"><span itemprop="title">' . $main . '</span></a>' . $delimiter . $link_after;

    if (is_single()) {
        $category = get_the_category();
        $num_cat = count($category);
        $type = get_post_type();
        switch($type) {
        case 'project':
            echo $link_before. '<a href="'.get_post_type_archive_link($type).'" itemprop="url"><span itemprop="title">'.esc_attr__('Portfolio', 'converio').'</span></a>' . $delimiter . $link_after;
            the_title();
            break;

        case 'product':
			echo $link_before . get_shop_link() . $delimiter .  $link_after;
            if ( $terms = wc_get_product_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
                $main_term = $terms[0];
                $ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
                $ancestors = array_reverse( $ancestors );
                foreach ( $ancestors as $ancestor ) {
                    $ancestor = get_term( $ancestor, 'product_cat' );
                    if ( ! is_wp_error( $ancestor ) && $ancestor )
                        echo $link_before . '<a href="' . get_term_link( $ancestor->slug, 'product_cat' ) . '" itemprop="url"><span itemprop="title">' . $ancestor->name . '</span></a>' . $delimiter . $link_after;
                }
                echo $link_before . '<a href="' . get_term_link( $main_term->slug, 'product_cat' ) . '" itemprop="url"><span itemprop="title">' . $main_term->name . '</span></a>' . $delimiter . $link_after;
            }

            the_title();
			$converio_breadcrumb_header = esc_attr__('Shop', 'converio');
            break;

        case 'events':
            printf(
                '<a href="%s" itemprop="url">%s</a>%s%s',
                get_post_type_archive_link($type),
                esc_attr__('Events', 'converio'),
                $delimiter,
                get_the_title()
            );
			$converio_breadcrumb_header = esc_attr__('Events', 'converio');
            break;
        default:
            if ($num_cat <= 1 && $category[0]) {
                echo $link_before . get_category_parents($category[0],  true, ' ') . $delimiter . $link_after . get_the_title();
            } else {
				
                echo $link_before . the_category(', ') . $delimiter . $link_after . trim(get_the_title());
            }
        }
    } elseif(is_post_type_archive('events')) {
        esc_attr_e('Events', 'converio');
    } elseif ( is_tax( 'event_category' ) ) {
        printf(
            '<a href="%s/events/" itemprop="url">%s</a>%s%s',
            get_site_url(),
            esc_attr__('Events','converio'),
            $delimiter,
            single_cat_title( '', false )
        );
    } elseif (is_category()) {
        echo esc_attr__('Archive category', 'converio').': ' . get_category_parents($cat, true,' ');
		$converio_breadcrumb_header = get_category_parents($cat, false,' ');
    } elseif ( is_tag() ) {
        echo esc_attr__('Posts tagged', 'converio').': ' . single_tag_title("", false);
		$converio_breadcrumb_header = single_tag_title("", false);
    } elseif ( is_tax('product_cat') ) {
        echo get_shop_link() . $delimiter;
        $current_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
        $ancestors = array_reverse( get_ancestors( $current_term->term_id, get_query_var( 'taxonomy' ) ) );
        foreach ( $ancestors as $ancestor ) {
            $ancestor = get_term( $ancestor, get_query_var( 'taxonomy' ) );
            echo $link_before . '<a href="' . get_term_link( $ancestor->slug, get_query_var( 'taxonomy' ) ) . '" itemprop="url"><span itemprop="title">' . esc_html( $ancestor->name ) . '</span></a>' . $delimiter . $link_after;
        }
        echo esc_html( $current_term->name );
		$converio_breadcrumb_header = $current_term->name;
    } elseif ( is_tax('product_tag') ) {
        global $wp_query;
        $queried_object = $wp_query->get_queried_object();
        echo esc_attr__( 'Products tagged &ldquo;', 'woocommerce' ) . $queried_object->name . '&rdquo;';
    }   elseif ( is_day()) {
        echo $link_before . '<a href="' . esc_url($url_year) . '" itemprop="url"><span itemprop="title">' . $arc_year . '</span></a>' . $delimiter .  $link_after;
        echo $link_before . '<a href="' . esc_url($url_month) . '" itemprop="url"><span itemprop="title">' . $arc_month . '</span></a> ' . $delimiter . $link_after . $arc_day . ' (' . $arc_day_full . ')';
		$converio_breadcrumb_header = $arc_month.' '.$arc_day.', '.$arc_year;
    } elseif ( is_month() ) {
        echo $link_before . '<a href="' . esc_url($url_year) . '" itemprop="url"><span itemprop="title">' . $arc_year . '</span></a> ' . $delimiter .  $link_after . $arc_month;
		$converio_breadcrumb_header = $arc_month.', '.$arc_year;
    } elseif ( is_year() ) {
        echo $arc_year;
		$converio_breadcrumb_header = $arc_year;
    } elseif ( is_search() ) {
        echo esc_attr__('Search results for', 'converio').': "' . get_search_query() . '"';
		$converio_breadcrumb_header = esc_attr__('Search results for', 'converio').': "' .get_search_query(). '"';
    } elseif ( is_page() && !$post->post_parent ) {
        echo get_the_title();
    } elseif ( 'project' == get_post_type()) {
		$converio_breadcrumb_header = esc_attr__('Portfolio', 'converio');
        esc_attr_e('Portfolio', 'converio');
    } elseif ( is_singular( 'events' )) {
        if ( class_exists( 'MotiveEvents' ) ) {
            global $motive_events;
            $page_ID = $motive_events->get_option('page_events');
            if ( !empty($page_ID) ) {
                $page = get_post( $page_ID );
                echo $motive_events->get_term_by_obj_id(get_the_ID());
                echo $delimiter;
            }
        }
    } elseif ( is_page() && $post->post_parent ) {
        $post_array = get_post_ancestors($post);
        krsort($post_array);
        foreach($post_array as $key=>$postid){
            $post_ids = get_post($postid);
            $title = $post_ids->post_title;
            echo $link_before . '<a href="' . get_permalink($post_ids) . '" itemprop="url"><span itemprop="title">' . esc_attr($title) . '</span></a>' . $delimiter . $link_after;
        }
        the_title();
    } elseif ( is_author() ) {
        global $author;
        $user_info = get_userdata($author);
        echo  esc_attr__('Articles by', 'converio').': ' . $user_info->display_name;
		$converio_breadcrumb_header = esc_attr__('Articles by', 'converio').': ' . $user_info->display_name;
    } elseif ( is_404() ) {
        esc_attr_e('Error 404 - Not Found.', 'converio');
    } elseif ( ( ! is_home() && ! is_front_page() && ! ( is_post_type_archive() && get_option( 'page_on_front' ) == woocommerce_get_page_id('shop')))) {
        $permalinks   = get_option( 'woocommerce_permalinks' );
        $shop_page_id = woocommerce_get_page_id( 'shop' );
        $shop_page    = get_post( $shop_page_id );
        //echo $shop_page->post_title;
		echo esc_attr__('Shop', 'converio');
		$converio_breadcrumb_header =  esc_attr__('Shop', 'converio');
    }

    echo '</p>';
		if($hide_breadcrumb == 0) {
    		echo '<h1>';
    		if(is_post_type_archive('events')) {
        		_e('Events', 'converio');
    		} elseif ( is_tax( 'event_category' ) ) {
        		echo single_cat_title( '', false );
    		} elseif ($converio_breadcrumb_header != '') {
        		echo $converio_breadcrumb_header;
    		} else {
        		echo the_title();
    		}
    		echo '</h1>';
		}

    echo '</div></section>';
}
}

function get_shop_link() {
    $permalinks   = get_option( 'woocommerce_permalinks' );
    $shop_page_id = woocommerce_get_page_id( 'shop' );
    $shop_page    = get_post( $shop_page_id );
    return '<a href="' . get_permalink( $shop_page ) . '" itemprop="url"><span itemprop="title">' . esc_attr($shop_page->post_title) . '</span></a> ';
}