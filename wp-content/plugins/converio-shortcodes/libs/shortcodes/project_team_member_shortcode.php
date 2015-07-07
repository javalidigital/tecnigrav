<?php
// shortcode for Counters
add_shortcode('project_team_member', 'converio_project_team_member');

function converio_project_team_member($attr){
	
    extract(shortcode_atts(array(  
        'image_url' => '',
		'image_alt' => '',
		'name' => '',
		'job' => ''
    ), $attr));
	
	$output = '';

	//image_url and image_alt
	if (!empty($attr['image_url'])) { 
		$output .= '<img src="'.$image_url.'"';
		if (isset($attr['image_alt']) && !empty($attr['image_alt'])) {
			$output .= ' alt="'.$image_alt.'"';
		}
		$output .= '>';
	}
	
	//name and job
	if (!empty($attr['name']) || !empty($attr['job']) ) {
		$output .= '<p>';
		$output .= (!empty($attr["name"]) ? '<span class="name">'.$name.'</span>' : '');
		$output .= ((!empty($attr['name']) && !empty($attr['job'])) ? '<br />' : '');
		$output .= (!empty($attr["job"]) ? '<span>'.$job.'</span>' : '');
		$output .= '</p>';
	}
	
	return $output;
}

add_action('init', 'converio_project_team_member');