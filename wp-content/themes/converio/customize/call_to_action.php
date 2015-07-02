<?php
function converio_customize_register_call_to_action($wp_customize) {
	// call to action
	$wp_customize->add_section('call_to_action', array(
		'title' => esc_attr__('Call to action', 'converio'),
		'priority' => 35
	));

	$wp_customize->add_setting('call_to_action', array(
		'default' => -1,
		'sanitize_callback' => ''
	) );
	$wp_customize->add_control('call_to_action', array(
		'label' => esc_attr__('Call to action on blog and portfolio pages (for single page please use shortcode)', 'converio'),
		'section' => 'call_to_action',
		'settings' => 'call_to_action', 
		'type' => 'radio',
		'priority' => 1,
		'choices' => array(
			1 => esc_attr__('Call to action enabled', 'converio'),
			-1 => esc_attr__('Call to action disabled', 'converio')
		)
	));
	

	$wp_customize->add_setting('call_to_action_text', array(
		'sanitize_callback' => 'converio_sanitize_text'
	));
	$wp_customize->add_control('call_to_action_text', array(
	    'label' => esc_attr__('Call to action text: ', 'converio'),
	    'section' => 'call_to_action',
	    'settings' => 'call_to_action_text',
		'priority' => 2
	));
	
	
	$wp_customize->add_setting('call_to_action_button_text', array(
		'sanitize_callback' => ''
	));
	$wp_customize->add_control('call_to_action_button_text', array(
	    'label' => esc_attr__('Call to action button text: ', 'converio'),
	    'section' => 'call_to_action',
	    'settings' => 'call_to_action_button_text',
		'priority' => 3
	));
	

	$wp_customize->add_setting('call_to_action_button_link', array(
		'sanitize_callback' => ''
	));
	$wp_customize->add_control('call_to_action_button_link', array(
	    'label' => esc_attr__('Call to action button link: ', 'converio'),
	    'section' => 'call_to_action',
	    'settings' => 'call_to_action_button_link',
		'priority' => 4
	));
	
	$wp_customize->add_setting('call_to_action_pattern', array(
		'default' => 1,
		'sanitize_callback' => ''
	) );
	$wp_customize->add_control('call_to_action_pattern', array(
		'label' => esc_attr__('Call to action background pattern', 'converio'),
		'section' => 'call_to_action',
		'settings' => 'call_to_action_pattern',
		'priority' => 5,
		'type' => 'radio',
		'choices' => array(
			0 => esc_attr__('No pattern', 'converio'),
			1 => esc_attr__('Pattern 1 - circle dark', 'converio'),
			2 => esc_attr__('Pattern 2 - circle light', 'converio'),
			3 => esc_attr__('Pattern 3 - circle 2 dark', 'converio'),
			4 => esc_attr__('Pattern 4 - circle 2 light', 'converio'),
			5 => esc_attr__('Pattern 5 - cube dark', 'converio'),
			6 => esc_attr__('Pattern 6 - cube light', 'converio'),
			7 => esc_attr__('Pattern 7 - cube 2 dark', 'converio'),
			8 => esc_attr__('Pattern 8 - cube 2 light', 'converio'),
			9 => esc_attr__('Pattern 9 - hex dark', 'converio'),
			10 => esc_attr__('Pattern 10 - hex light', 'converio'),
			11 => esc_attr__('Pattern 11 - hex 2 dark', 'converio'),
			12 => esc_attr__('Pattern 12 - hex 2 light', 'converio'),
			13 => esc_attr__('Pattern 13 - line dark', 'converio'),
			14 => esc_attr__('Pattern 14 - line light', 'converio'),
			15 => esc_attr__('Pattern 15 - rhombus dark', 'converio'),
			16 => esc_attr__('Pattern 16 - rhombus light', 'converio'),
			17 => esc_attr__('Pattern 17 - rhombus 2 dark', 'converio'),
			18 => esc_attr__('Pattern 18 - rhombus 2 light', 'converio'),
			19 => esc_attr__('Pattern 19 - waves dark', 'converio'),
			20 => esc_attr__('Pattern 20 - waves light', 'converio')
		)
	));
	$wp_customize->add_setting('call_to_action_color', array(
		'sanitize_callback' => ''
	));
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'call_to_action_color', 
		array(
			'label'      => esc_attr__('Call to action background color', 'converio'),
			'section'    => 'call_to_action',
			'settings'   => 'call_to_action_color',
			'priority' => 6
		)) 
	);
	
}

add_action('customize_register', 'converio_customize_register_call_to_action');	