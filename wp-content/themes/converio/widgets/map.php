<?php
add_action('widgets_init', 'converio_map_widget_register');

function converio_map_widget_register() {
	register_widget('Converio_Map_Widget');
}

class Converio_Map_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'map_widget',
			esc_attr__('Converio Map Widget', 'converio'),
			array('description' => esc_attr__('Widget with a Google map inside', 'converio'))
		);
	}

	public function widget( $args, $instance ) {
		$title = apply_filters('widget_title', $instance['title']); 
	    $location = $instance['location']; 
	      
	    echo $args['before_widget'];  
	    if (!empty($title)) {
	    	echo $args['before_title'] . esc_attr($title) . $args['after_title'];  
	    }  
	    if (!empty($location)) {
			echo '<div class="gmap" id="map" data-marker="'.get_template_directory_uri().'/images/map-markers/map-marker';
			$color_scheme = get_theme_mod('color_scheme');
			if($color_scheme) {
				echo '-'.esc_attr($color_scheme);
			} 
			echo '.svg"><div class="info-contact"><span class="address">'. esc_attr($location) . '</span></div></div>';
	    }  
	    echo $args['after_widget'];  
	}

	public function form($instance) {
		if (isset($instance['title'])) {
			$title = $instance['title'];
		} else {
			$title = esc_attr__('Map', 'converio');
		}
		?>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'converio'); ?></label> 
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<?php 
		if (isset($instance['location'])) {
			$location = $instance['location'];
		} else {
			$location = '';
		}
		?>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('location')); ?>"><?php esc_attr_e('Location:', 'converio'); ?></label> 
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('location')); ?>" name="<?php echo esc_attr($this->get_field_name('location')); ?>" type="text" value="<?php echo esc_attr($location); ?>" />
		</p>
		<?php
	}

	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['location'] = ( ! empty( $new_instance['location'] ) ) ? strip_tags( $new_instance['location'] ) : '';
		return $instance;
	}
	
}


?>