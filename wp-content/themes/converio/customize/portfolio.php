<?php
function converio_customize_register_portfolio($wp_customize) {	
	
	//portfolio
	$wp_customize->add_section('portfolio', array(
		'title' => esc_attr__('Portfolio', 'converio'),
		'priority' => 36
	));	

	//portfolio layout settings
	$wp_customize->add_setting('portfolio_layout', array(
		'default' => 3,
		'sanitize_callback' => ''
	) );
	$wp_customize->add_control('portfolio_layout', array(
		'label' => esc_attr__('Portfolio layout', 'converio'),
		'section' => 'portfolio',
		'settings' => 'portfolio_layout', 
		'type' => 'radio',
		'choices' => array(
			3 => esc_attr__('Three columns', 'converio'),
			4 => esc_attr__('Four columns', 'converio'),
			5 => esc_attr__('Three columns masonry', 'converio'),
			6 => esc_attr__('Four columns masonry', 'converio')
		)
	));

	$wp_customize->add_setting('projects_per_page4', array(
		'sanitize_callback' => ''
	));
	$wp_customize->add_control(
       new WP_Customize_Control($wp_customize, 'projects_per_page4', array(
               'label'          => esc_attr__( 'Projects per page (4 columns)', 'converio' ),
               'section'        => 'portfolio',
               'settings'       => 'projects_per_page4'
           )
       )
   	);
   	$wp_customize->add_setting('projects_per_page3', array(
   		'sanitize_callback' => ''
   	));
	$wp_customize->add_control(
       new WP_Customize_Control($wp_customize, 'projects_per_page3', array(
               'label'          => esc_attr__( 'Projects per page (3 columns)', 'converio' ),
               'section'        => 'portfolio',
               'settings'       => 'projects_per_page3'
           )
       )
   	);

   	$wp_customize->add_setting('project_slug', array(
   		'sanitize_callback' => ''
   	));
	$wp_customize->add_control(
       new WP_Customize_Control($wp_customize, 'project_slug', array(
               'label'          => esc_attr__( 'Project page slug / path', 'converio' ),
               'section'        => 'portfolio',
               'settings'       => 'project_slug'
           )
       )
   	);
	
}

add_action('customize_register', 'converio_customize_register_portfolio');