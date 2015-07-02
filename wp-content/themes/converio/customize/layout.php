<?php
function converio_customize_register_layout($wp_customize) {
	// global layout options
	$wp_customize->add_section('layout', array(
		'title' => esc_attr__('Layout', 'converio'),
		'priority' => 31
	));

	$wp_customize->add_setting('layout_type', array(
		'default' => 0,
		'sanitize_callback' => ''
	) );
	$wp_customize->add_control('layout_type', array(
		'label' => esc_attr__('Layout type', 'converio'),
		'section' => 'layout',
		'settings' => 'layout_type', 
		'type' => 'radio',
		'priority' => 1,
		'choices' => array(
			0 => esc_attr__('Wide (default)', 'converio'),
			1 => esc_attr__('Boxed', 'converio')
		)
	));
	
	//border for boxed version
	$wp_customize->add_setting('layout_border_width', array(
		'sanitize_callback' => ''
	));
	$wp_customize->add_control('layout_border_width', array(
		'label' => esc_attr__('Page border width (in px, boxed layout only)', 'converio'),
	    'section' => 'layout',
	    'settings' => 'layout_border_width',
		'priority' => 4
	));
	
	$wp_customize->add_setting('layout_border_color', array(
		'sanitize_callback' => ''
	));
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'layout_border_color', 
		array(
			'label' => esc_attr__('Page border color (boxed layout only)', 'converio'),
		    'section' => 'layout',
		    'settings' => 'layout_border_color',
			'priority' => 5
		)) 
	);

	$wp_customize->add_setting('layout_border_opacity', array(
		'sanitize_callback' => ''
	));
	$wp_customize->add_control('layout_border_opacity', array(
		'label' => esc_attr__('Page border opacity (from 0 to 1, boxed layout only)', 'converio'),
	    'section' => 'layout',
	    'settings' => 'layout_border_opacity',
		'priority' => 6
	));
	
	//shadow for boxed version
	$wp_customize->add_setting('layout_shadow_size', array(
		'sanitize_callback' => ''
	));
	$wp_customize->add_control('layout_shadow_size', array(
		'label' => esc_attr__('Page shadow size (in px, boxed layout only)', 'converio'),
	    'section' => 'layout',
	    'settings' => 'layout_shadow_size',
		'priority' => 7
	));
	
	$wp_customize->add_setting('layout_shadow_color', array(
		'sanitize_callback' => ''
	));
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'layout_shadow_color', 
		array(
			'label' => esc_attr__('Page shadow color (boxed layout only)', 'converio'),
		    'section' => 'layout',
		    'settings' => 'layout_shadow_color',
			'priority' => 8
		)) 
	);	

	$wp_customize->add_setting('layout_shadow_opacity', array(
		'sanitize_callback' => ''
	));
	$wp_customize->add_control('layout_shadow_opacity', array(
		'label' => esc_attr__('Page shadow opacity (from 0 to 1, boxed layout only)', 'converio'),
	    'section' => 'layout',
	    'settings' => 'layout_shadow_opacity',
		'priority' => 9
	));

}

add_action('customize_register', 'converio_customize_register_layout');