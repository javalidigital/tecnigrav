<?php
function converio_customize_register_header($wp_customize) {
	
	// header settings

	$wp_customize->add_section('header', array(
		'title' => esc_attr__('Header settings', 'converio'),
		'priority' => 33
	));

	$wp_customize->add_setting('header_type', array(
		'default' => 1,
		'sanitize_callback' => ''
	) );
	$wp_customize->add_control('header_type', array(
		'label' => esc_attr__('Choose header type', 'converio'),
		'section' => 'header',
		'settings' => 'header_type', 
		'type' => 'radio',
		'priority' => 1,
		'choices' => array(
			1 => esc_attr__('Header 1', 'converio'),
			11 => __('Header 1 - boxed', 'converio'),
			12 => __('Header 1 - boxed rounded', 'converio'),			
			2 => esc_attr__('Header 2', 'converio'),
			21 => esc_attr__('Header 2 with search form', 'converio'),
			22 => esc_attr__('Header 2 with social icons', 'converio')
		)
	));

	$wp_customize->add_setting('header_opacity_enabled', array(
		'default' => 1
	) );
	$wp_customize->add_control('header_opacity_enabled', array(
		'label' => __('Header opacity', 'converio'),
		'section' => 'header',
		'settings' => 'header_opacity_enabled',
		'type' => 'radio',
		'priority' => 2,
		'choices' => array(
			0 => __('Header opacity enabled', 'converio'),
			1 => __('Header opacity disabled', 'converio')
		)
	));
	
	$wp_customize->add_setting('search_disabled', array(
		'default' => 0,
		'sanitize_callback' => ''
	) );
	$wp_customize->add_control('search_disabled', array(
		'label' => esc_attr__('Search in menu', 'converio'),
		'section' => 'header',
		'settings' => 'search_disabled', 
		'type' => 'radio',
		'priority' => 2,
		'choices' => array(
			0 => esc_attr__('Search in menu enabled', 'converio'),
			1 => esc_attr__('Search in menu disabed', 'converio')
		)
	));

	$wp_customize->add_setting('cart_disabled', array(
		'default' => 0,
		'sanitize_callback' => ''
	) );
	$wp_customize->add_control('cart_disabled', array(
		'label' => esc_attr__('Cart in menu', 'converio'),
		'section' => 'header',
		'settings' => 'cart_disabled', 
		'type' => 'radio',
		'priority' => 3,
		'choices' => array(
			0 => esc_attr__('Cart in menu enabled', 'converio'),
			1 => esc_attr__('Cart in menu disabed', 'converio')
		)
	));

	$wp_customize->add_setting('dropdown_on_hover_disabled', array(
		'default' => 0,
		'sanitize_callback' => ''
	) );
	$wp_customize->add_control('dropdown_on_hover_disabled', array(
		'label' => esc_attr__('Cart / Search - dropdown on hover', 'converio'),
		'section' => 'header',
		'settings' => 'dropdown_on_hover_disabled', 
		'type' => 'radio',
		'priority' => 4,
		'choices' => array(
			0 => esc_attr__('Dropdown on hover enabled', 'converio'),
			1 => esc_attr__('Dropdown on hover disabed', 'converio')
		)
	));
	
	$wp_customize->add_setting('dropdown_arrow_disabled', array(
		'default' => 1,
		'sanitize_callback' => ''
	) );
	$wp_customize->add_control('dropdown_arrow_disabled', array(
		'label' => esc_attr__('Dropdown arrow', 'converio'),
		'section' => 'header',
		'settings' => 'dropdown_arrow_disabled', 
		'type' => 'radio',
		'priority' => 5,
		'choices' => array(
			0 => esc_attr__('Dropdown arrow enabled', 'converio'),
			1 => esc_attr__('Dropdown arrow disabed', 'converio')
		)
	));
	
	$wp_customize->add_setting('social_colored_disabled', array(
		'default' => 0,
		'sanitize_callback' => ''
	) );
	$wp_customize->add_control('social_colored_disabled', array(
		'label' => esc_attr__('Colored social media icons', 'converio'),
		'section' => 'header',
		'settings' => 'social_colored_disabled', 
		'type' => 'radio',
		'priority' => 6,
		'choices' => array(
			0 => esc_attr__('Colored social media icons enabled', 'converio'),
			1 => esc_attr__('Colored social media icons disabed', 'converio')
		)
	));	
	

	//other
	$wp_customize->add_setting('hover_delay_enabled', array(
		'default' => 0,
		'sanitize_callback' => ''
	) );
	$wp_customize->add_control('hover_delay_enabled', array(
		'label' => esc_attr__('Delay on hover - improving the usability', 'converio'),
		'section' => 'header',
		'settings' => 'hover_delay_enabled', 
		'type' => 'radio',
		'priority' => 7,
		'choices' => array(
			0 => esc_attr__('Delay on hover - enabled', 'converio'),
			1 => esc_attr__('Delay on hover - disabled', 'converio')
		)
	));	

	$wp_customize->add_setting('menu_animation_enabled', array(
		'default' => 0,
		'sanitize_callback' => ''
	) );
	$wp_customize->add_control('menu_animation_enabled', array(
		'label' => esc_attr__('Dropdown menu animation', 'converio'),
		'section' => 'header',
		'settings' => 'menu_animation_enabled', 
		'type' => 'radio',
		'priority' => 10,
		'choices' => array(
			0 => esc_attr__('Menu animation - enabled', 'converio'),
			1 => esc_attr__('Menu animation - disabled', 'converio')
		)
	));

	//drop down menu customization
	$wp_customize->add_setting('data_menu_slideup_duration', array(
		'default' => 500,
		'sanitize_callback' => ''
	));
	$wp_customize->add_control('data_menu_slideup_duration', array(
		'label' => esc_attr__('Menu slide up duration (in milliseconds, for menu animation enabled only)', 'converio'),
	    'section' => 'header',
	    'settings' => 'data_menu_slideup_duration',
		'priority' => 11
	));
	
	$wp_customize->add_setting('data_menu_slidedown_duration', array(
		'default' => 500,
		'sanitize_callback' => ''
	));
	$wp_customize->add_control('data_menu_slidedown_duration', array(
		'label' => esc_attr__('Menu slide down duration (in milliseconds, for menu animation enabled only)', 'converio'),
	    'section' => 'header',
	    'settings' => 'data_menu_slidedown_duration',
		'priority' => 12
	));

	$wp_customize->add_setting('data_menu_fadein_duration', array(
		'default' => 300,
		'sanitize_callback' => ''
	));
	$wp_customize->add_control('data_menu_fadein_duration', array(
		'label' => esc_attr__('Menu fade in duration (in milliseconds, for menu animation enabled only)', 'converio'),
	    'section' => 'header',
	    'settings' => 'data_menu_fadein_duration',
		'priority' => 13
	));
	
	$wp_customize->add_setting('data_menu_fadeout_duration', array(
		'default' => 400,
		'sanitize_callback' => ''
	));
	$wp_customize->add_control('data_menu_fadeout_duration', array(
		'label' => esc_attr__('Menu fade out duration (in milliseconds, for menu animation enabled only)', 'converio'),
	    'section' => 'header',
	    'settings' => 'data_menu_fadeout_duration',
		'priority' => 14
	));
	


	$wp_customize->add_setting('hide_top', array(
		'default' => 2,
		'sanitize_callback' => ''
	) );
	$wp_customize->add_control('hide_top', array(
		'label' => esc_attr__('Top bar visibility', 'converio'),
		'section' => 'header',
		'settings' => 'hide_top', 
		'type' => 'radio',
		'priority' => 15,
		'choices' => array(
			1 => esc_attr__('Top bar visible', 'converio'),
			2 => esc_attr__('Top bar hidden', 'converio')
		)
	));	


	$wp_customize->add_setting('topbar_links', array(
		'default' => 'social',
		'sanitize_callback' => ''
	) );
	$wp_customize->add_control('topbar_links', array(
		'label' => esc_attr__('What to display in top bar?', 'converio'),
		'section' => 'header',
		'settings' => 'topbar_links', 
		'type' => 'radio',
		'priority' => 16,
		'choices' => array(
			'social' => esc_attr__('Social links', 'converio'),
			'menu' => esc_attr__('Secondary navigation', 'converio')
		)
	));

	$wp_customize->add_setting('top_header_msg', array(
		'sanitize_callback' => 'converio_sanitize_text'
	));
	$wp_customize->add_control('top_header_msg', array(
		'label' => esc_attr__('Top header message', 'converio'),
		'section' => 'header',
		'priority' => 17,
		'settings' => 'top_header_msg'
	));


	//sticky header
	$wp_customize->add_setting('sticky_disabled', array(
		'default' => 0,
		'sanitize_callback' => ''
	) );
	$wp_customize->add_control('sticky_disabled', array(
		'label' => esc_attr__('Sticky header feature', 'converio'),
		'section' => 'header',
		'settings' => 'sticky_disabled', 
		'type' => 'radio',
		'priority' => 20,
		'choices' => array(
			0 => esc_attr__('Sticky header enabled without top bar', 'converio'),
			1 => esc_attr__('Sticky header enabled with top bar', 'converio'),
			2 => esc_attr__('Sticky header disabled', 'converio')
		)
	));

	$wp_customize->add_setting('sticky_opacity_enabled', array(
		'default' => 0,
		'sanitize_callback' => ''
	) );
	$wp_customize->add_control('sticky_opacity_enabled', array(
		'label' => esc_attr__('Sticky header opacity', 'converio'),
		'section' => 'header',
		'settings' => 'sticky_opacity_enabled', 
		'type' => 'radio',
		'priority' => 21,
		'choices' => array(
			0 => esc_attr__('Sticky header opacity enabled ', 'converio'),
			1 => esc_attr__('Sticky header opacity disabled', 'converio')
		)
	));

	$wp_customize->add_setting('sticky_collapse', array(
		'default' => 0,
		'sanitize_callback' => ''
	) );
	$wp_customize->add_control('sticky_collapse', array(
		'label' => esc_attr__('Sticky header collapse', 'converio'),
		'section' => 'header',
		'settings' => 'sticky_collapse', 
		'type' => 'radio',
		'priority' => 22,
		'choices' => array(
			0 => esc_attr__('Sticky header collapse - enabled ', 'converio'),
			1 => esc_attr__('Sticky header collapse - disabled', 'converio')
		)
	));

	$wp_customize->add_setting('sticky_trigger_position', array(
		'default' => 400,
		'sanitize_callback' => ''
	));
	$wp_customize->add_control('sticky_trigger_position', array(
	    'label' => esc_attr__('Sticky header trigger position (in px): ', 'converio'),
	    'section' => 'header',
	    'settings' => 'sticky_trigger_position',
		'priority' => 23
	));

	$wp_customize->add_setting('widget_on_mobile_enabled', array(
		'default' => 0
	) );
	$wp_customize->add_control('widget_on_mobile_enabled', array(
		'label' => esc_attr__('Showing widgets in responsive/mobile mode', 'converio'),
		'section' => 'header',
		'settings' => 'widget_on_mobile_enabled', 
		'type' => 'radio',
		'priority' => 24,
		'choices' => array(
			0 => esc_attr__('Showing widgets - enabled ', 'converio'),
			1 => esc_attr__('Showing widgets - disabled', 'converio')
		)
	));
}
add_action('customize_register', 'converio_customize_register_header');