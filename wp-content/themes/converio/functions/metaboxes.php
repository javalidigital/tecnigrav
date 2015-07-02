<?php 
add_action('add_meta_boxes', 'converio_page_metaboxes', 10, 2);
add_action('save_post', 'converio_save_display_metabox');

function converio_page_metaboxes() {
	$post_types = array('page', 'post', 'project');
	foreach($post_types as $type) add_meta_box('display-options', 'Display options', 'converio_draw_display_metabox', $type, 'side', 'default');
}

function converio_draw_display_metabox($post) {
	global $post; 
	$data = get_post_custom($post->ID);
	$hide_title = isset($data['hide_title']) ? esc_attr($data['hide_title'][0]) : 0;
	$hide_breadcrumb = isset($data['hide_breadcrumb']) ? esc_attr($data['hide_breadcrumb'][0]) : 0;
	$sidebar_position = isset($data['sidebar_position']) ? esc_attr($data['sidebar_position'][0]) : 0;
	
	//Revolution Slider
	if ( is_plugin_active( 'revslider/revslider.php' ) ) {
		$revolution_slider = isset($data['revolution_slider']) ? esc_attr($data['revolution_slider'][0]) : '';
	}

	wp_nonce_field('converio_display_metabox_nonce', 'display_metabox_nonce'); 
	?>
	<p><label for="hide_title"><?php esc_attr_e('Show title?', 'converio'); ?></label> <select name="hide_title" id="hide_title">
		<option value="0" <?php if($hide_title == 0) echo 'selected="selected"'; ?>><?php esc_attr_e('Show','converio');?></option>
		<option value="1" <?php if($hide_title == 1) echo 'selected="selected"'; ?>><?php esc_attr_e('Don\'t show','converio');?></option>
	</select></p>
	<p><label for="hide_breadcrumb"><?php esc_attr_e('Show breadcrumb?', 'converio'); ?></label> <select name="hide_breadcrumb" id="hide_breadcrumb">
		<option value="0" <?php if($hide_breadcrumb == 0) echo 'selected="selected"'; ?>><?php esc_attr_e('Show','converio');?></option>
		<option value="1" <?php if($hide_breadcrumb == 1) echo 'selected="selected"'; ?>><?php esc_attr_e('Don\'t show','converio');?></option>
		<option value="2" <?php if($hide_breadcrumb == 2) echo 'selected="selected"'; ?>><?php esc_attr_e('Show without headline','converio');?></option>
		<option value="3" <?php if($hide_breadcrumb == 3) echo 'selected="selected"'; ?>><?php esc_attr_e('As set in Theme Customizer','converio');?></option>
	</select></p>
	<p><label for="sidebar_position"><?php esc_attr_e('Sidebar position', 'converio'); ?></label> <select name="sidebar_position" id="sidebar_position">
		<option value="0" <?php if($sidebar_position == 0) echo 'selected="selected"'; ?>><?php esc_attr_e('Right (default)','converio');?></option>
		<option value="1" <?php if($sidebar_position == 1) echo 'selected="selected"'; ?>><?php esc_attr_e('Left','converio');?></option>
		<option value="2" <?php if($sidebar_position == 2) echo 'selected="selected"'; ?>><?php esc_attr_e('Don\'t show','converio');?></option>
		<option value="3" <?php if($sidebar_position == 3) echo 'selected="selected"'; ?>><?php esc_attr_e('As set in Theme Customizer','converio');?></option>
	</select></p>

<?php
	//Revolution Slider
	if ( is_plugin_active( 'revslider/revslider.php' ) ) {
		$slider = new RevSlider();
		// Array of current slider "alias" names
		$arrSliders = $slider->getArrSlidersShort();
?>
		<p><label for="revolution_slider"><?php esc_attr_e('Revolution Slider', 'converio'); ?></label> <select name="revolution_slider" id="revolution_slider">
			<option value="" <?php if($revolution_slider == '') echo 'selected="selected"'; ?>><?php esc_attr_e('None','converio');?></option>
			<?php foreach($arrSliders as $rev_id => $rev_slider) { ?>
    		<option value="<?php echo esc_attr( $rev_id );?>" <?php if($revolution_slider == $rev_id) echo 'selected="selected"'; ?>><?php echo esc_attr($rev_slider);?></option>
			<?php } ?>
		</select></p>
	<?php
	}//end of Revolution Slider	
} 

function converio_save_display_metabox($page_id) {
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if(!isset($_POST['display_metabox_nonce']) || !wp_verify_nonce($_POST['display_metabox_nonce'], 'converio_display_metabox_nonce' )) return; 
	if(!current_user_can('edit_pages', $page_id)) return; 

	if(isset($_POST['hide_title'])) {
		update_post_meta($page_id, 'hide_title', strip_tags($_POST['hide_title']));
	}
	if(isset($_POST['hide_breadcrumb'])) {
		update_post_meta($page_id, 'hide_breadcrumb', strip_tags($_POST['hide_breadcrumb']));
	}
	if(isset($_POST['sidebar_position'])) {
		update_post_meta($page_id, 'sidebar_position', strip_tags($_POST['sidebar_position']));
	}

	//Revolution Slider
	if ( is_plugin_active( 'revslider/revslider.php' ) ) {
		if(isset($_POST['revolution_slider'])) {
			update_post_meta($page_id, 'revolution_slider', strip_tags($_POST['revolution_slider']));
		}
	}//end of Revolution Slider
	
}

/*Additional fields for post*/


add_action('add_meta_boxes', 'converio_post_metaboxes', 1, 2);
add_action('save_post', 'converio_save_display_post_metabox');

function converio_post_metaboxes() {
	$post_types = array('post');
	foreach($post_types as $type) add_meta_box('post-content-type', 'Post Content Type', 'converio_draw_display_post_metabox', $type, 'normal', 'default');
}



function converio_draw_display_post_metabox($post) {
	global $post; 
	$data = get_post_custom($post->ID);
	$video_iframe = isset($data['single_meta_video_iframe']) ? $data['single_meta_video_iframe'][0] : '';
	$audio_iframe = isset($data['single_meta_audio_iframe']) ? $data['single_meta_audio_iframe'][0] : '';
	$quote_content = isset($data['single_meta_quote_content']) ? esc_attr($data['single_meta_quote_content'][0]) : '';
	$quote_author = isset($data['single_meta_quote_author']) ? esc_attr($data['single_meta_quote_author'][0]) : '';
	
	wp_nonce_field('converio_display_metabox_nonce', 'display_metabox_nonce'); 
?>	

		<p><label for="single_video_iframe"><?php esc_attr_e('Video iframe (YouTube, Vimeo)', 'converio'); ?></label><textarea name="single_video_iframe" id="single_video_iframe" rows="5" cols="30" class="widefat"><?php echo converio_sanitize_text_decode($video_iframe);?></textarea></p>
		<p><label for="single_audio_iframe"><?php esc_attr_e('Music iframe (SoundCloud)', 'converio'); ?></label><textarea name="single_audio_iframe" id="single_audio_iframe" rows="5" cols="30" class="widefat"><?php echo converio_sanitize_text_decode($audio_iframe);?></textarea></p>
		<p><label for="single_quote_content"><?php esc_attr_e('Quote', 'converio'); ?></label><textarea name="single_quote_content" id="single_quote_content" rows="5" cols="30" class="widefat"><?php echo esc_attr($quote_content);?></textarea></p>
		<p><label for="single_quote_author"><?php esc_attr_e('Quote Author', 'converio'); ?></label><input name="single_quote_author" id="single_quote_author" type="text" class="widefat" value="<?php echo esc_attr($quote_author);?>"></p>

<?php	
} 

function converio_save_display_post_metabox($page_id) {
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if(!isset($_POST['display_metabox_nonce']) || !wp_verify_nonce($_POST['display_metabox_nonce'], 'converio_display_metabox_nonce' )) return; 
	if(!current_user_can('edit_pages', $page_id)) return; 

	if(isset($_POST['single_video_iframe'])) {
		update_post_meta($page_id, 'single_meta_video_iframe', converio_sanitize_text($_POST['single_video_iframe']));
	}
	if(isset($_POST['single_audio_iframe'])) {
		update_post_meta($page_id, 'single_meta_audio_iframe', converio_sanitize_text($_POST['single_audio_iframe']));
	}
	if(isset($_POST['single_quote_content'])) {
		update_post_meta($page_id, 'single_meta_quote_content', strip_tags($_POST['single_quote_content']));
	}
	if(isset($_POST['single_quote_author'])) {
		update_post_meta($page_id, 'single_meta_quote_author', strip_tags($_POST['single_quote_author']));
	}	
	
}