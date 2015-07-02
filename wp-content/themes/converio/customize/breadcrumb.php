<?php
function converio_customize_register_breadcrumb($wp_customize) {
	// breadcrumb settings
	
	$wp_customize->add_section('breadcrumb', array(
		'title' => esc_attr__('Breadcrumb', 'converio'),
		'priority' => 34
	));

	$wp_customize->add_setting('breadcrumb_disabled', array(
		'default' => 0,
		'sanitize_callback' => ''
	) );
	$wp_customize->add_control('breadcrumb_disabled', array(
		'label' => esc_attr__('Disable breadcrumb (if option is set to enabled then may be overwritten by post/page settings)', 'converio'),
		'section' => 'breadcrumb',
		'settings' => 'breadcrumb_disabled', 
		'type' => 'select',
		'priority' => 1,
		'choices' => array(
			0 => esc_attr__('Breadcrumb enabled', 'converio'),
			1 => esc_attr__('Breadcrumb disabled', 'converio'),
			2 => esc_attr__('Breadcrumb enabled without headline', 'converio')
		)
	));	
	
	$wp_customize->add_setting('breadcrumb_pattern', array(
		'default' => 2,
		'sanitize_callback' => ''
	) );
	$wp_customize->add_control('breadcrumb_pattern', array(
		'label' => esc_attr__('Breadcrumb background pattern', 'converio'),
		'section' => 'breadcrumb',
		'settings' => 'breadcrumb_pattern', 
		'type' => 'radio',
		'priority' => 2,
		'choices' => array(
			-1 => esc_attr__('No pattern', 'converio'),
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
	
	$wp_customize->add_setting('breadcrumb_pattern_opacity', array(
		'sanitize_callback' => ''
	));
	$wp_customize->add_control('breadcrumb_pattern_opacity', array(
	    'label' => esc_attr__('Breadcrumb pattern opacity (value from 0 to 1, for example 0.15, default: 0.06): ', 'converio'),
	    'section' => 'breadcrumb',
	    'settings' => 'breadcrumb_pattern_opacity',
		'priority' => 3
	));	
	
	$wp_customize->add_setting('breadcrumb_color', array(
		'sanitize_callback' => ''
	));
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'breadcrumb_color',
		array(
			'label'      => esc_attr__('Breadcrumb background color', 'converio'),
			'section'    => 'breadcrumb',
			'settings'   => 'breadcrumb_color',
			'priority' => 4
		)) 
	);
	
}

add_action('customize_register', 'converio_customize_register_breadcrumb');