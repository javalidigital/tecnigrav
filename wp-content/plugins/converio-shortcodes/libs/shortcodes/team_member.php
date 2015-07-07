<?php
// shortcode Team Member

add_shortcode('team_member','converio_team_member');
add_shortcode('team_slider','converio_team_slider');

function converio_team_slider($atts, $content){
	add_filter('converio_filters_columns_class', 'converio_filter_team_slider_class', 10);
	add_filter('converio_filters_team_member', 'converio_filter_team_slider_member', 10);
	$output = '<div class="slider-box"><div>'.do_shortcode($content).'</div></div>';
	remove_all_filters('converio_filters_team_member');
	return $output;
}

function converio_filter_team_slider_member($content) {
	return '<div>'.$content.'</div>';
}

function converio_filter_team_slider_class($content) {
	return $content.' team-slider content-slider';
}

function converio_filter_team_class_default($content) {
	return $content.' team';
}

function converio_filter_team_class_boxed($content) {
	return $content.' boxed';
}

function converio_filter_team_class_circled($content) {
	return $content.' circled';
}

function converio_filter_team_class_centered($content) {
	return $content.' centered';
}

function converio_filter_team_member($content, $atts) {
	add_filter('converio_filters_team_class', 'converio_filter_team_class_default', 10);


	$output = '';
	if (isset($atts['boxed']) && !empty($atts['boxed'])) 
		if (converio_arrangement_shortcode_value($atts['boxed']) == "yes")
		{
			add_filter('converio_filters_team_class', 'converio_filter_team_class_boxed', 10);
			$output .= '<div>';
		}

	if (isset($atts['circled_image']) && !empty($atts['circled_image'])) 
		if (converio_arrangement_shortcode_value($atts['circled_image']) == "yes")
			add_filter('converio_filters_team_class', 'converio_filter_team_class_circled', 10);

	if (isset($atts['centered']) && !empty($atts['centered'])) 
		if (converio_arrangement_shortcode_value($atts['centered']) == "yes")
			add_filter('converio_filters_team_class', 'converio_filter_team_class_centered', 10);

	

	if (isset($atts['social_vertical']) && !empty($atts['social_vertical'])) 
		if (converio_arrangement_shortcode_value($atts['social_vertical']) == "yes") {
			//link for img start
			if (isset($atts['link']) && !empty($atts['link'])) {
				$output .= '<a href="'.$atts['link'].'">';
			}
				
			$output .= '<img';

			if (isset($atts['image_url']) && !empty($atts['image_url'])) 	
				$output .= ' src="'.$atts['image_url'].'"';

			if (isset($atts['image_alt']) && !empty($atts['image_alt'])) 	
				$output .= ' alt="'.$atts['image_alt'].'"';

			$output .= '>';
			
			//link for img end
			if (isset($atts['link']) && !empty($atts['link'])) {
				$output .= '</a>';
			}
		
			$output .= '<ul class="social';

			if (isset($atts['social_colored']) && !empty($atts['social_colored'])) 
				if (converio_arrangement_shortcode_value($atts['social_colored']) == "yes") 
					$output .= ' social-colored';

			$output .= ' vertical">';

			foreach($atts as $key => $att) {
				if ($key != 'image_url' and $key != 'image_alt' and $key != 'name' and $key != 'position' and $key != 'divider' and $key != 'social_colored' and $key != 'centered' and $key != 'boxed' and $key != 'circled_image' and $key != 'social_vertical') {
					$class_name = $key;
					$text_name = ucwords($key);
					if ($key == 'google') {
						$class_name = "googleplus";
						$text_name = "Google+";
					}
					if ($key == 'mail') {
						$class_name = "email";
						$text_name = "E-mail";
					}
					if ($key == 'linkedin') {
						$text_name = "LinkedIn";
					}
					$output .= !empty($att) ? '<li><a href="'.converio_arrangement_shortcode_value($att).'" class="'.$class_name.'">'.$text_name.'</a></li>' : "";
				}
			}

			$output .= '</ul>';

			$output .= '<h3>'.(isset($atts['name']) && !empty($atts['name']) ? converio_arrangement_shortcode_value($atts['name']) : "").'</h3>';
			$output .= '<p class="position">'.(isset($atts['position']) && !empty($atts['position']) ? converio_arrangement_shortcode_value($atts['position']) : "").'</p>';
			
			if (isset($atts['divider']) && !empty($atts['divider'])) 
				if (converio_arrangement_shortcode_value($atts['divider']) == "yes") 
					$output .= '<hr>';
			
			$output .= '<p>'.$content.'</p>';
			
		} else {
						
			//link for img start
			if (isset($atts['link']) && !empty($atts['link'])) {
				$output .= '<a href="'.$atts['link'].'">';
			}
			
			$output .= '<img';

			if (isset($atts['image_url']) && !empty($atts['image_url'])) 	
				$output .= ' src="'.$atts['image_url'].'"';

			if (isset($atts['image_alt']) && !empty($atts['image_alt'])) 	
				$output .= ' alt="'.$atts['image_alt'].'"';

			$output .= '>';
			
			//link for img end
			if (isset($atts['link']) && !empty($atts['link'])) {
				$output .= '</a>';
			}

			$output .= '<h3>'.(isset($atts['name']) && !empty($atts['name']) ? converio_arrangement_shortcode_value($atts['name']) : "").'</h3>';
			$output .= '<p class="position">'.(isset($atts['position']) && !empty($atts['position']) ? converio_arrangement_shortcode_value($atts['position']) : "").'</p>';
			
			if (isset($atts['divider']) && !empty($atts['divider'])) 
				if (converio_arrangement_shortcode_value($atts['divider']) == "yes") 
					$output .= '<hr>';

			$output .= '<ul class="social';

			if (isset($atts['social_colored']) && !empty($atts['social_colored'])) 
				if (converio_arrangement_shortcode_value($atts['social_colored']) == "yes")
					$output .= ' social-colored';

			$output .= '">';

			foreach($atts as $key => $att) {
				if ($key != 'image_url' and $key != 'image_alt' and $key != 'name' and $key != 'position' and $key != 'divider' and $key != 'social_colored' and $key != 'centered' and $key != 'boxed' and $key != 'circled_image' and $key != 'social_vertical') {
					$output .= !empty($att) ? '<li><a href="'.converio_arrangement_shortcode_value($att).'" class="'.$key.'">'.ucwords($key).'</a></li>' : "";
				}
			}

			$output .= '</ul>';
			if ($content != '')
				$output .= '<p>'.$content.'</p>';
		}
	
	if (isset($atts['boxed']) && !empty($atts['boxed'])) 
		if (converio_arrangement_shortcode_value($atts['boxed']) == "yes")
		{
			$output .= "</div>";
		}
	return $output;
}

function converio_team_member($atts, $content = null) {
	add_filter('converio_filters_team_member', 'converio_filter_team_member', 9, 2);
	return apply_filters('converio_filters_team_member', $content, $atts);
}
