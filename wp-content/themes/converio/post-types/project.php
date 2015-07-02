<?php
	
function converio_project_taxonomies() {
	register_taxonomy('project-categories', 'project', array(
		'label' => esc_attr__('Project categories', 'converio'),
		'labels' => array(
			'name' => esc_attr__('Project categories', 'converio'),
			'singular_name' => esc_attr__('Project category', 'converio'),
			'menu_name' => esc_attr__('Project categories', 'converio'),
			'all_item' => esc_attr__('All project categories', 'converio'),
			'edit_item' => esc_attr__('Edit project category', 'converio'),
			'view_item' => esc_attr__('View project category', 'converio'),
			'update_item' => esc_attr__('Update project category', 'converio'),
			'add_new_item' => esc_attr__('Add new project category', 'converio'),
			'new_item_name' => esc_attr__('New project category name', 'converio'),
			'parent_item' => esc_attr__('Parent project category', 'converio'),
			'parent_item_colon' => esc_attr__('Parent project category:', 'converio'),
			'search_items' => esc_attr__('Search project categories', 'converio'),
			'popular_items' => esc_attr__('Popular project categories', 'converio'),
			'separate_items_with_commas' => esc_attr__('Separate project categories with commas', 'converio'),
			'add_or_remove_items' => esc_attr__('Add or remove project categories', 'converio'),
			'choose_from_most_used' => esc_attr__('Choose from most used project categories', 'converio'),
			'not_found' => esc_attr__('Project category not found', 'converio')
		),
		'hierarchical' => true
	));
	register_taxonomy('project-skills', 'project', array(
		'label' => esc_attr__('Project skills', 'converio'),
		'labels' => array(
			'name' => esc_attr__('Project skills', 'converio'),
			'singular_name' => esc_attr__('Project skill', 'converio'),
			'menu_name' => esc_attr__('Project skills', 'converio'),
			'all_item' => esc_attr__('All project skills', 'converio'),
			'edit_item' => esc_attr__('Edit project skill', 'converio'),
			'view_item' => esc_attr__('View project skill', 'converio'),
			'update_item' => esc_attr__('Update project skill', 'converio'),
			'add_new_item' => esc_attr__('Add new project skill', 'converio'),
			'new_item_name' => esc_attr__('New project skill name', 'converio'),
			'parent_item' => esc_attr__('Parent project skill', 'converio'),
			'parent_item_colon' => esc_attr__('Parent project skill:', 'converio'),
			'search_items' => esc_attr__('Search project skills', 'converio'),
			'popular_items' => esc_attr__('Popular project skills', 'converio'),
			'separate_items_with_commas' => esc_attr__('Separate project skills with commas', 'converio'),
			'add_or_remove_items' => esc_attr__('Add or remove project skills', 'converio'),
			'choose_from_most_used' => esc_attr__('Choose from most used project skills', 'converio'),
			'not_found' => esc_attr__('Project skill not found', 'converio')
		),
		'hierarchical' => true
	));
}	
add_action('init', 'converio_project_taxonomies');

function converio_register_project() {
	$project_slug = get_theme_mod('project_slug') ? get_theme_mod('project_slug') : 'project';
	register_post_type('project', array(
		'label' => esc_attr__('Projects', 'converio'),
		'labels' => array(
			'name' => esc_attr__('Projects', 'converio'),
			'singular_name' => esc_attr__('Project', 'converio'),
			'menu_name' => esc_attr__('Projects', 'converio'),
			'all_items' => esc_attr__('All projects', 'converio'),
			'add_new' => esc_attr__('Add new', 'converio'),
			'add_new_item' => esc_attr__('Add new project', 'converio'),
			'edit_item' => esc_attr__('Edit project', 'converio'),
			'new_item' => esc_attr__('New project', 'converio'),
			'view_item' => esc_attr__('View project', 'converio'),
			'search_items' => esc_attr__('Search projects', 'converio'),
			'not_found' => esc_attr__('No projects found', 'converio'),
			'not_found_in_trash' => esc_attr__('No projects found in trash', 'converio'),
			'parent_item_colon' => esc_attr__('Parent project:', 'converio')
			),
		'description' => esc_attr__('An item representing a project in a portfolio', 'converio'),
		'public' => true,
		'menu_position' => 10,
		'capability_type' => 'post',
		'register_meta_box_cb' => 'converio_project_metaboxes',
		'has_archive' => true,
		'supports' => array('title', 'editor', 'thumbnail', 'excerpt',  'custom-fields', 'post-formats', /*'page-attributes'*/),
		'taxonomies' => array('project-categories', 'project-skills'),
		'rewrite' => array('slug' => $project_slug, 'with_front' => true)
	));	
	flush_rewrite_rules(false);
}
add_action('init', 'converio_register_project');
add_image_size( 'project-thumbnail', 810, 9999, false); //featured image
add_image_size( 'project-thumbnail-masonry', 560, 9999, false ); //portfolio masonry

add_image_size( 'project-thumbnail-tiny', 78, 78, true); //widgets
add_image_size( 'project-thumbnail-medium', 560, 384, true); //portfolio 3 cols
add_image_size( 'project-thumbnail-large', 1110, 650, false); //single project without sidebar
add_image_size( 'project-thumbnail-related', 560, 370, true);

add_action('save_post', 'converio_save_project_data_metabox');
add_action('save_post', 'converio_save_project_video_metabox');
add_action('save_post', 'converio_save_project_testimonial_metabox');
add_action('save_post', 'converio_save_project_team_metabox');

function converio_project_metaboxes() {
	add_meta_box('project-video', 'Video', 'converio_draw_project_video_metabox', 'project', 'normal', 'default');
	add_meta_box('testimonial', 'Testimonial', 'converio_draw_project_testimonial_metabox', 'project', 'normal', 'default');
	add_meta_box('project-team', 'Team', 'converio_draw_project_team_metabox', 'project', 'normal', 'default');
	add_meta_box('project-data', 'Additional project data', 'converio_draw_project_data_metabox', 'project', 'normal', 'default');
}

//project data

function converio_draw_project_data_metabox($post) {
	global $post; 
	$data = get_post_custom($post->ID);
	$url = isset($data['project_meta_url']) ? esc_attr($data['project_meta_url'][0]) : '';

	wp_nonce_field( 'converio_project_metabox_nonce', 'project_metabox_nonce' ); 
	?>
	<p><label for="project_url"><?php esc_attr_e('Project URL:', 'converio'); ?></label><input name="project_url" id="project_url" value="<?php echo esc_url($url) ?>" class="widefat" /></p>
	<?php
}

function converio_save_project_data_metabox($project_id) {
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if(!isset($_POST['project_metabox_nonce']) || !wp_verify_nonce($_POST['project_metabox_nonce'], 'converio_project_metabox_nonce' )) return; 
	if(!current_user_can('edit_post', $project_id)) return;
	
	if(isset($_POST['project_url'])) {
		update_post_meta($project_id, 'project_meta_url', strip_tags($_POST['project_url']));
	}
}

//video

function converio_draw_project_video_metabox($post) {
	global $post; 
	$data = get_post_custom($post->ID);
	$video = isset($data['project_meta_video']) ? esc_attr($data['project_meta_video'][0]) : '';

	wp_nonce_field( 'converio_project_video_metabox_nonce', 'project_video_metabox_nonce' ); 
	?>
	<p><label for="project_video"><?php esc_attr_e('Put the video embedding code here:', 'converio'); ?></label><textarea name="project_video" id="project_video" rows="5" cols="30" class="widefat"><?php echo converio_sanitize_text_decode($video); ?></textarea></p>
	<?php
}

function converio_save_project_video_metabox($project_id) {
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if(!isset($_POST['project_video_metabox_nonce']) || !wp_verify_nonce($_POST['project_video_metabox_nonce'], 'converio_project_video_metabox_nonce' )) return; 
	if(!current_user_can('edit_post', $project_id)) return; 

	if(isset($_POST['project_video'])) {
		update_post_meta($project_id, 'project_meta_video', converio_sanitize_text($_POST['project_video']) );
	}
}


//testimonial

function converio_draw_project_testimonial_metabox($post) {
	global $post; 
	$data = get_post_custom($post->ID);
	$testimonial = isset($data['project_meta_testimonial']) ? esc_attr($data['project_meta_testimonial'][0]) : '';
	$testimonial_author = isset($data['project_meta_testimonial_author']) ? esc_attr($data['project_meta_testimonial_author'][0]) : '';
	$testimonial_company = isset($data['project_meta_testimonial_company']) ? esc_attr($data['project_meta_testimonial_company'][0]) : '';
	$testimonial_job = isset($data['project_meta_testimonial_job']) ? esc_attr($data['project_meta_testimonial_job'][0]) : '';

	wp_nonce_field( 'converio_project_metabox_nonce', 'project_metabox_nonce' ); 
	?>
	<p><label for="project_testimonial"><?php esc_attr_e('Testimonial:', 'converio'); ?></label><textarea name="project_testimonial" id="project_testimonial" rows="5" cols="30" class="widefat"><?php echo esc_attr($testimonial);?></textarea></p>
	<p><label for="project_testimonial_author"><?php esc_attr_e('Name and surname:', 'converio'); ?></label><input name="project_testimonial_author" id="project_testimonial_author" value="<?php echo esc_attr($testimonial_author);?>" class="widefat" /></p>
	<p><label for="project_testimonial_company"><?php esc_attr_e('Company:', 'converio'); ?></label><input name="project_testimonial_company" id="project_testimonial_company" value="<?php echo esc_attr($testimonial_company); ?>" class="widefat" /></p>
	<p><label for="project_testimonial_job"><?php esc_attr_e('Job:', 'converio'); ?></label><input name="project_testimonial_job" id="project_testimonial_job" value="<?php echo esc_attr($testimonial_job); ?>" class="widefat" /></p>
	
	<?php
}

function converio_save_project_testimonial_metabox($project_id) {
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if(!isset($_POST['project_metabox_nonce']) || !wp_verify_nonce($_POST['project_metabox_nonce'], 'converio_project_metabox_nonce' )) return; 
	if(!current_user_can('edit_post', $project_id)) return;
	
	if(isset($_POST['project_testimonial'])) {
		update_post_meta($project_id, 'project_meta_testimonial', strip_tags($_POST['project_testimonial']));
	}
	if(isset($_POST['project_testimonial_author'])) {
		update_post_meta($project_id, 'project_meta_testimonial_author', strip_tags($_POST['project_testimonial_author']));
	}
	if(isset($_POST['project_testimonial_company'])) {
		update_post_meta($project_id, 'project_meta_testimonial_company', strip_tags($_POST['project_testimonial_company']));
	}
	if(isset($_POST['project_testimonial_job'])) {
		update_post_meta($project_id, 'project_meta_testimonial_job', strip_tags($_POST['project_testimonial_job']));
	}
}



//Project team

function converio_draw_project_team_metabox($post) {
	global $post; 
	$data = get_post_custom($post->ID);
	$team = isset($data['project_meta_team']) ? esc_attr($data['project_meta_team'][0]) : '';

	wp_nonce_field( 'converio_project_team_metabox_nonce', 'project_team_metabox_nonce' ); 
	?>
	<p><label for="project_team"><?php esc_attr_e('Put the shortcodes for project team members, for example: <br>[project_team_member image_url="http://example.com/avatar1.jpg" image_alt="Avatar" name="John" job="Developer"]', 'converio'); ?></label><textarea name="project_team" id="project_team" rows="5" cols="30" class="widefat"><?php echo esc_attr($team);?></textarea></p>
	<?php
}

function converio_save_project_team_metabox($project_id) {
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if(!isset($_POST['project_team_metabox_nonce']) || !wp_verify_nonce($_POST['project_team_metabox_nonce'], 'converio_project_team_metabox_nonce' )) return; 
	if(!current_user_can('edit_post', $project_id)) return; 

	if(isset($_POST['project_team'])) {
		update_post_meta($project_id, 'project_meta_team', trim($_POST['project_team']));
	}
}

// custom columns in admin project list

add_filter('manage_project_posts_columns', 'converio_project_table_head');
function converio_project_table_head( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => 'Project title',
		'category' => 'Project category',
		'thumbnail' => 'Thumbnail',
		'date' => 'Date'
	);
    
    return $columns;
}

add_action( 'manage_project_posts_custom_column', 'converio_project_table_content', 10, 2 );
function converio_project_table_content( $column_name, $post_id ) {
    if ($column_name == 'category') {
    	$cats = array();
    	$categories = wp_get_post_terms($post_id, 'project-categories');
    	foreach ($categories as $c) {
    		$cats[] = $c->name;
    	}
    	echo implode(", ", $cats);
    }	
    if ($column_name == 'thumbnail') {
    	echo get_the_post_thumbnail( $post_id, 'admin-thumbnail');
    }
}