<?php
function converio_customize_register_general($wp_customize) {
	//general section
	$wp_customize->add_section('general', array(
		'title' => esc_attr__('General', 'converio'),
		'priority' => 30
	));	

	$wp_customize->add_setting( 'logo_upload' , array(
		'sanitize_callback' => ''
	));
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'logo_upload', array(
		'priority' => 1,
	    'label'    => esc_attr__( 'Logo Upload', 'converio' ),
	    'section'  => 'general',
	    'settings' => 'logo_upload'
	) ) );

	$wp_customize->add_setting('logo_width', array(
		'sanitize_callback' => ''
	));
	$wp_customize->add_control('logo_width', array(
		'priority' => 2,
	    'label'    => esc_attr__('Logo width: ', 'converio'),
	    'section'  => 'general',
	    'settings' => 'logo_width'
	));

	$wp_customize->add_setting('logo_height', array(
		'sanitize_callback' => ''
	));
	$wp_customize->add_control('logo_height', array(
		'priority' => 3,
	    'label'    => esc_attr__('Logo height: ', 'converio'),
	    'section'  => 'general',
	    'settings' => 'logo_height'
	));
	
	//favicon
	$wp_customize->add_setting( 'favicon', array(
		'sanitize_callback' => ''
	));
	$wp_customize->add_control( new Converio_Customize_Image_Media_Library_Control( $wp_customize, 'favicon', array(
		'priority' => 4,
	    'label'    => esc_attr__( 'Favicon upload', 'converio' ),
	    'section'  => 'general',
	    'settings' => 'favicon'
	) ) );
	
	//avatar shape
	$wp_customize->add_setting('avatar_shape', array(
		'default' => 0,
		'sanitize_callback' => ''
	) );
	$wp_customize->add_control('avatar_shape', array(
		'label' => esc_attr__('Avatar shape', 'converio'),
		'section' => 'general',
		'settings' => 'avatar_shape', 
		'type' => 'radio',
		'priority' => 5,
		'choices' => array(
			0 => esc_attr__('Circle', 'converio'),
			1 => esc_attr__('Rounded', 'converio'),
			2 => esc_attr__('Square', 'converio')
		)
	));	

	//Web analytics code
	$wp_customize->add_setting('analytics', array(
		'sanitize_callback' => 'converio_sanitize_text'
	));
	$wp_customize->add_control( new WP_Customize_Textarea_Control( $wp_customize, 'analytics', array(
		'priority' => 6,
		'label' => esc_attr__('Web Analytics tracking code', 'converio'),
		'section' => 'general',
		'settings' => 'analytics'
	) ) );
			
	//Custom CSS
	$wp_customize->add_setting('custom_css', array(
		'sanitize_callback' => 'converio_sanitize_text'
	));
	$wp_customize->add_control( new WP_Customize_Textarea_Control( $wp_customize, 'custom_css', array(
		'priority' => 7,
		'label' => esc_attr__('Custom CSS code (please make sure you know what you\'re doing)', 'converio'),
		'section' => 'general',
		'settings' => 'custom_css'
	) ) );	
}

add_action('customize_register', 'converio_customize_register_general');