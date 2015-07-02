<?php
function converio_customize_register_blog($wp_customize) {
	$wp_customize->add_section('blog', array(
		'title' => esc_attr__('Blog', 'converio'),
		'priority' => 35
	));
	$wp_customize->add_setting('archive_layout', array(
		'default' => 0,
		'sanitize_callback' => ''
	));
	$wp_customize->add_control('archive_layout', array(
		'label' => esc_attr__('Archive page layout', 'converio'),
		'section' => 'blog',
		'settings' => 'archive_layout', 
		'type' => 'select',
		'choices' => array(
			0 => esc_attr__('Blog standard (default)', 'converio'),
			1 => esc_attr__('Blog - date exposed', 'converio'),
			2 => esc_attr__('Blog - masonry with sidebar', 'converio'),
			3 => esc_attr__('Blog - fullwidth masonry', 'converio')
		)
	));

	$wp_customize->add_setting('show_post_author', array(
		'default' => 0,
		'sanitize_callback' => ''
	));
	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'show_post_author',
        array(
            'label'     => esc_attr__('Disable post author on post page', 'converio'),
            'section'   => 'blog',
            'settings'  => 'show_post_author',
            'type'      => 'checkbox'
        )
	));
	$wp_customize->add_setting('show_related', array(
		'default' => 0,
		'sanitize_callback' => ''
	));
	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'show_related',
        array(
            'label'     => esc_attr__('Disable related posts on post page', 'converio'),
            'section'   => 'blog',
            'settings'  => 'show_related',
            'type'      => 'checkbox'
        )
	));
	//social sharing
	$wp_customize->add_setting('share_links', array(
		'default' => 2,
		'sanitize_callback' => ''
	));
	$wp_customize->add_control('share_links', array(
		'label' => esc_attr__('Disable default sharing links (works if ShareThis plugin is disabled)', 'converio'),
		'section' => 'blog',
		'settings' => 'share_links', 
		'type' => 'select',
		'choices' => array(
			1 => esc_attr__('Yes', 'converio'),
			2 => esc_attr__('No', 'converio')
		)
	));

	$wp_customize->add_setting('share_links_facebook', array(
		'default' => 0,
		'sanitize_callback' => ''
	));
	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'share_links_facebook',
        array(
            'label'     => esc_attr__('Disable Facebook', 'converio'),
            'section'   => 'blog',
            'settings'  => 'share_links_facebook',
            'type'      => 'checkbox'
        )
	));
	$wp_customize->add_setting('share_links_twitter', array(
		'default' => 0,
		'sanitize_callback' => ''
	));
	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'share_links_twitter',
        array(
            'label'     => esc_attr__('Disable Twitter', 'converio'),
            'section'   => 'blog',
            'settings'  => 'share_links_twitter',
            'type'      => 'checkbox'
        )
	));
	$wp_customize->add_setting('share_links_googleplus', array(
		'default' => 0,
		'sanitize_callback' => ''
	));
	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'share_links_googleplus',
        array(
            'label'     => esc_attr__('Disable Google +', 'converio'),
            'section'   => 'blog',
            'settings'  => 'share_links_googleplus',
            'type'      => 'checkbox'
        )
	));
	$wp_customize->add_setting('share_links_pinterest', array(
		'default' => 0,
		'sanitize_callback' => ''
	));
	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'share_links_pinterest',
        array(
            'label'     => esc_attr__('Disable Pinterest', 'converio'),
            'section'   => 'blog',
            'settings'  => 'share_links_pinterest',
            'type'      => 'checkbox'
        )
	));

}

add_action('customize_register', 'converio_customize_register_blog');