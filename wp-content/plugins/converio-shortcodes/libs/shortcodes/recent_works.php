<?php

// add shortcode recent_works
add_shortcode('recent_works','converio_recent_works');
function converio_recent_works($attr, $content = null) {
    $output = '<div class="page-portfolio"><section class="columns portfolio hp-recent-work content-slider">';
	
	extract(shortcode_atts(array('title' => 'Recent Works', 'number_posts' => '6', 'number_columns' => '3', 'view_anchor_text' => 'yes', 'headline' => 'yes', 'category_name' => ''), $attr));
	
    if($title) {
        $output .= '<h3>'.$title.'</h3>';
    } 
	
    $output .='<div class="slider-box"><div>';
    $args = array(
        'posts_per_page'  => $number_posts,
        'orderby'         => 'post_date',
        'order'           => 'DESC',
        'post_type'       => 'project',
        'post_status'     => 'publish',
		'project-categories' => $category_name,
        'suppress_filters' => true,
		'meta_key' => '_thumbnail_id'
	);
    $posts_array = get_posts( $args );
    if(!empty($posts_array)) {
        foreach($posts_array as $key_data => $val_data) {         
			$output .= '<article class="col col'.converio_arrangement_shortcode_value($number_columns).'">';
			$output .= '<div>';

			if(has_post_thumbnail($val_data->ID)) {  
				$url = wp_get_attachment_image_src( get_post_thumbnail_id($val_data->ID), 'project-thumbnail');
				$output .= '<div class="img">';
				$output .= '<a href="'.get_permalink($val_data->ID).'">'.get_the_post_thumbnail($val_data->ID, 'project-thumbnail-medium').'</a>';
				
				if($view_anchor_text) {
					$output .= '<div>';
					$output .= '<ul>';
					$output .= '<li><a href="'.$url[0].'" class="action view" title="'.$val_data->post_title.'">'.$view_anchor_text.'</a></li>';
					$output .= '</ul> </div>';
				}
				$output .= '</div>';  
			}
			
			if($headline == 'yes') {
				$categories = get_the_terms($val_data->ID, 'project-categories');
				$output .= '<h3><a href="'.get_permalink($val_data->ID).'">'.$val_data->post_title.'<br>';
				$output .= '<span>';
				if (is_array($categories)) {
					foreach ( $categories as $category ) {
						$output .= $category->name.' ';
					}
				}
				else {
					if (is_object ($categories))
						$output .= $categories->name;
				}
				$output .= '</span>';
				$output .= '</a></h3>';
			}
			$output .= '</div>';
			$output .= '</article>';

        }
    }
    $output .= '</div></div></section></div>';
    return $output;
}
