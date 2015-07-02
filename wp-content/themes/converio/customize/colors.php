<?php
function converio_customize_register_colors($wp_customize) {
   	// site color scheme
   	$wp_customize->add_setting('color_scheme', array(
   		'default' => 0,
		'sanitize_callback' => ''
   	));
	$wp_customize->add_control('color_scheme', array(
		'priority' => 1,
		'label' => esc_attr__('Choose color scheme', 'converio'),
		'section' => 'colors',
		'settings' => 'color_scheme', 
		'type' => 'radio',
		'choices' => array(
			0 => esc_attr__('Green (Default)', 'converio'),
			'turquoise' => esc_attr__('Turquoise', 'converio'),
			'light-blue' => esc_attr__('Light Blue', 'converio'),
			'blue' => esc_attr__('Blue', 'converio'),
			'purple' => esc_attr__('Purple', 'converio'),
			'pink' => esc_attr__('Pink', 'converio'),
			'red' => esc_attr__('Red', 'converio'),
			'orange' => esc_attr__('Orange', 'converio'),
			'yellow' => esc_attr__('Yellow', 'converio'),
			'brown' => esc_attr__('Brown', 'converio'),
			'custom' => esc_attr__('Custom Colors', 'converio')
		)
	));


	$wp_customize->add_setting('custom_color_3', array(
		'default' => '#70c14a',
		'sanitize_callback' => ''
	));
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'custom_color_3', 
		array(
			'priority' => 4,
			'label'      => esc_attr__('Color for buttons and elements', 'converio'),
			'section'    => 'colors',
			'settings'   => 'custom_color_3'
		)) 
	);
	
	$wp_customize->add_setting('custom_color_4', array(
		'default' => '#61b23b',
		'sanitize_callback' => ''
	));
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'custom_color_4', 
		array(
			'priority' => 5,
			'label'      => esc_attr__('Color for links', 'converio'),
			'section'    => 'colors',
			'settings'   => 'custom_color_4'
		)) 
	);
	
	$wp_customize->add_setting('custom_color_6', array(
		'default' => '#42931c',
		'sanitize_callback' => ''
	));
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'custom_color_6', 
		array(
			'priority' => 7,
			'label'      => esc_attr__('Color for links on hover', 'converio'),
			'section'    => 'colors',
			'settings'   => 'custom_color_6'
		)) 
	);

	/* Notes: hosting needs the permission to write to the static file (custom.css) using PHP function file_put_contents.*/	
	$wp_customize->add_setting('custom_external_stylesheet', array(
		'default' => 0,
		'sanitize_callback' => ''
	));
	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'custom_external_stylesheet',
        array(
			'priority' => 8,
            'label'     => esc_attr__('Move custom CSS from internal to external stylesheet', 'converio'),
            'section'   => 'colors',
            'settings'  => 'custom_external_stylesheet',
            'type'      => 'checkbox'
        )
	));
	
}

add_action('customize_register', 'converio_customize_register_colors');