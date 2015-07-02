<?php
function converio_customize_register_footer($wp_customize) {
	// footer appearance
	$wp_customize->add_section('footer', array(
		'title' => esc_attr__('Footer', 'converio'),
		'priority' => 35
	));

	$wp_customize->add_setting('column_count', array(
		'default' => 4,
		'sanitize_callback' => ''
	));
	$wp_customize->add_control('column_count', array(
		'priority' => 1,
		'label' => esc_attr__('Number of columns', 'converio'),
		'section' => 'footer',
		'settings' => 'column_count', 
		'type' => 'radio',
		'choices' => array(
			1 => esc_attr__('One column', 'converio'),
			2 => esc_attr__('Two columns', 'converio'),
			3 => esc_attr__('Three columns', 'converio'),
			4 => esc_attr__('Four columns', 'converio')
		)
	));

	$wp_customize->add_setting('footer_bg_color', array(
		'sanitize_callback' => ''
	));
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'footer_bg_color', 
		array(
			'priority' => 2,
			'label'      => esc_attr__('Footer background color', 'converio'),
			'section'    => 'footer',
			'settings'   => 'footer_bg_color'
		)) 
	);

	/*
	 * "go to top" link
	 */
	$wp_customize->add_setting('disable_top_link', array(
		'default' => 0,
		'sanitize_callback' => ''
	));
	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'disable_top_link',
        array(
			'priority' => 3,
            'label'     => esc_attr__('Disable "Go to top" link', 'converio'),
            'section'   => 'footer',
            'settings'  => 'disable_top_link',
            'type'      => 'checkbox'
        )
	));

	$wp_customize->add_setting('show_footer_content', array(
		'default' => 0,
		'sanitize_callback' => ''
	));
	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'show_footer_content',
        array(
			'priority' => 4,
            'label'     => esc_attr__('Show custom footer content', 'converio'),
            'section'   => 'footer',
            'settings'  => 'show_footer_content',
            'type'      => 'checkbox'
        )
	));
	
	//Footer Content
	$wp_customize->add_setting('footer_content', array(
		'default' => '<p>&copy; 2013-2014 <a href="http://thememotive.com/converio/">Converio</a> WordPress Theme by <a href="http://thememotive.com/">ThemeMotive</a>. | All rights reserved.</p>',
		'sanitize_callback' => 'converio_sanitize_text'
	));
	$wp_customize->add_control( new WP_Customize_Textarea_Control( $wp_customize, 'footer_content', array(
		'priority' => 5,
		'label' => esc_attr__('Footer Content', 'converio'),
		'section' => 'footer',
		'settings' => 'footer_content'
	) ) );	

	
}

add_action('customize_register', 'converio_customize_register_footer');