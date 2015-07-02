<?php
function converio_customize_register_sidebar($wp_customize) {
	// sidebars
	$wp_customize->add_section('sidebars', array(
		'title' => esc_attr__('Sidebars', 'converio'),
		'priority' => 37
	));
	$wp_customize->add_setting('sidebar_pos_archive', array(
		'default' => 0,
		'sanitize_callback' => ''
	));
	$wp_customize->add_control('sidebar_pos_archive', array(
		'label' => esc_attr__('Sidebar on Archive Pages', 'converio'),
		'section' => 'sidebars',
		'settings' => 'sidebar_pos_archive', 
		'type' => 'select',
		'choices' => array(
			0 => esc_attr__('Right (default)', 'converio'),
			1 => esc_attr__('Left', 'converio'),
			2 => esc_attr__('No sidebar', 'converio')
		)
	));
	$wp_customize->add_setting('sidebar_pos_blog', array(
		'default' => 0,
		'sanitize_callback' => ''
	));
	$wp_customize->add_control('sidebar_pos_blog', array(
		'label' => esc_attr__('Sidebar on Blog Page', 'converio'),
		'section' => 'sidebars',
		'settings' => 'sidebar_pos_blog', 
		'type' => 'select',
		'choices' => array(
			0 => esc_attr__('Right (default)', 'converio'),
			1 => esc_attr__('Left', 'converio'),
			2 => esc_attr__('No sidebar', 'converio')
		)
	));
	$wp_customize->add_setting('sidebar_pos_post', array(
		'default' => 0,
		'sanitize_callback' => ''
	));
	$wp_customize->add_control('sidebar_pos_post', array(
		'label' => esc_attr__('Sidebar on Single Post Pages', 'converio'),
		'section' => 'sidebars',
		'settings' => 'sidebar_pos_post', 
		'type' => 'select',
		'choices' => array(
			0 => esc_attr__('Right (default)', 'converio'),
			1 => esc_attr__('Left', 'converio'),
			2 => esc_attr__('No sidebar', 'converio')
		)
	));
	$wp_customize->add_setting('sidebar_pos_page', array(
		'default' => 0,
		'sanitize_callback' => ''
	));
	$wp_customize->add_control('sidebar_pos_page', array(
		'label' => esc_attr__('Sidebar on Pages', 'converio'),
		'section' => 'sidebars',
		'settings' => 'sidebar_pos_page', 
		'type' => 'select',
		'choices' => array(
			0 => esc_attr__('Right (default)', 'converio'),
			1 => esc_attr__('Left', 'converio'),
			2 => esc_attr__('No sidebar', 'converio')
		)
	));
	$wp_customize->add_setting('sidebar_pos_search', array(
		'default' => 0,
		'sanitize_callback' => ''
	));
	$wp_customize->add_control('sidebar_pos_search', array(
		'label' => esc_attr__('Sidebar on Search Results Page', 'converio'),
		'section' => 'sidebars',
		'settings' => 'sidebar_pos_search', 
		'type' => 'select',
		'choices' => array(
			0 => esc_attr__('Right (default)', 'converio'),
			1 => esc_attr__('Left', 'converio'),
			2 => esc_attr__('No sidebar', 'converio')
		)
	));
}

add_action('customize_register', 'converio_customize_register_sidebar');