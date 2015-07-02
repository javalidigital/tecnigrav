<?php

//layout configuration, customizations of standard elements etc.
include('functions/comments.php');
include('functions/breadcrumb.php');
include('functions/menus.php');
include('functions/metaboxes.php');
include('functions/related-posts.php');

if (class_exists('Woocommerce')) {
    include('functions/woocommerce-support.php');
}

// sidebars
include('functions/sidebars.php');
include('functions/metaboxes-sidebars.php');

//widgets
include('widgets/map.php');
include('widgets/social.php');
include('widgets/recent-blog-posts.php');
include('widgets/recent-comments.php');
include('widgets/recent-projects.php');
include('widgets/twitter.php');
include('lib/twitteroauth/twitteroauth.php'); //required by the twitter widget

// post types
include('post-types/project.php');

//theme customizations

include('functions/customize.php');
include('customize/general.php');
include('customize/layout.php');
include('customize/header.php');
include('customize/social.php');
include('customize/breadcrumb.php');
include('customize/call_to_action.php');
include('customize/sidebar.php');
include('customize/footer.php');
include('customize/blog.php');
include('customize/portfolio.php');
include('customize/typography.php');
include('customize/colors.php');

//admin panel settings pages
include('functions/settings-contact.php');

//extend featured images
include('functions/ext-multiple-images.php');

//etend user profile
include('functions/user.php');

//contact
include('functions/contact.php');
//add textarea to put addresses on contact fullwidth template
include('functions/contact/map_fullwidth.php');

//other functions
include('functions/social.php');
include('functions/footer.php');

//TGM Plugin Activation
include('functions/tgm-plugin-activation/plugins.php');



add_filter('widget_text', 'do_shortcode');

if ( ! isset( $content_width ) )
$content_width = 810;

add_action('after_setup_theme', 'converio_setup');

function converio_setup() {
    add_editor_style();
    add_theme_support('automatic-feed-links');
    add_theme_support('post-thumbnails');
    add_theme_support('html5');
    add_theme_support('custom-background');
    add_theme_support('woocommerce');

	set_post_thumbnail_size( 810, 9999, false ); // Default size, featured image
	add_image_size('thumbnail-medium', 560, 395, true); //blog 4 columns, portfolio 4 cols
	add_image_size('thumbnail-large', 1100, 439, true); //fullwidth without sidebar
	add_image_size('thumbnail-related', 560, 370, true); //related posts
	add_image_size('thumbnail-masonry', 560, 9999, false); //masonry for blog and portfolio
	add_image_size('thumbnail-megamenu', 240, 158, true); //magazine megamenu
	add_image_size('admin-thumbnail', 120, 90, false); //wp admin -> Projects thumbnail
	add_image_size('thumbnail-widget', 50, 50, true); //recent blog posts widget

    // Make theme available for translation
    // Translations can be filed in the /languages/ directory
    load_theme_textdomain('converio', get_template_directory() . '/languages');

    register_nav_menus(
        array(
          'primary' => esc_attr__('Main menu', 'converio'),
          'secondary' => esc_attr__('Top bar menu', 'converio')
        )
    );
}

function converio_widgets() {
    register_sidebar(array(
        'name' => esc_attr__( 'Sidebar Widget Area', 'converio'),
        'id' => 'sidebar-widget-area',
        'description' => esc_attr__( 'The sidebar widget area', 'converio'),
        'before_widget' => '<section class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    $column_count = get_theme_mod('column_count') ? get_theme_mod('column_count') : 4;
    $col_class = 'col' . $column_count;
    register_sidebar(array(
        'name' => esc_attr__( 'Footer Widget Area', 'converio'),
        'id' => 'footer-widget-area',
        'description' => esc_attr__( 'Widgetized area in the footer', 'converio'),
        'before_widget' => '<article class="widget col '.$col_class.' %2$s">',
        'after_widget' => '</article>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    
    register_sidebar(array(
        'name' => esc_attr__( 'Contact page sidebar', 'converio'),
        'id' => 'contact-sidebar',
        'description' => 'Sidebar on the contact page',
        'before_widget' => '<section class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
	
    register_sidebar(array(
        'name' => esc_attr__( 'Coming Soon Widget Area', 'converio'),
        'id' => 'coming-soon-widget-area',
        'description' => esc_attr__( 'Widget Area on the Coming Soon', 'converio'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    register_sidebar(array(
        'name' => esc_attr__( 'Lead Page Widget Area', 'converio'),
        'id' => 'lead-page-widget-area',
        'description' => esc_attr__( 'Sidebar on the Lead Page', 'converio'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
	
    $custom_sidebar = get_option('custom_sidebar') ? get_option('custom_sidebar') : array();
    if(count($custom_sidebar) > 0) {
        foreach($custom_sidebar as $sidebar) {  
            register_sidebar( array(  
                'name' => esc_attr__( $sidebar, 'converio' ),  
                'id' => converio_generateSlug($sidebar, 45),  
                'before_widget' => '<section id="%1$s" class="widget %2$s">',  
                'after_widget' => "</section>",  
                'before_title' => '<h3>',  
                'after_title' => '</h3>',  
            ) );  
        }  
    }	
	
}

add_action ('widgets_init', 'converio_widgets' );

add_filter('the_title', 'converio_title');

function converio_title($title) {
    if ($title == '') {
        return 'Untitled';
    } else {
        return $title;
    }
}

function converio_generateSlug($phrase, $maxLength) {  
    $result = strtolower($phrase);  
    $result = preg_replace("/[^a-z0-9\s-]/", "", $result);  
    $result = trim(preg_replace("/[\s-]+/", " ", $result));  
    $result = trim(substr($result, 0, $maxLength));  
    $result = preg_replace("/\s/", "-", $result);  
  
    return $result;  
} 

function converio_scripts() {
    wp_register_script('googlemaps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false', array(), 1, true);
    wp_enqueue_script('googlemaps');
    wp_register_script('basic', get_template_directory_uri().'/js/scripts.js', array('jquery'), 1, true);
	wp_enqueue_script('basic');

	wp_register_script('modernizr', get_template_directory_uri().'/js/modernizr.js', array(), '2.8.1', false);
    wp_enqueue_script('modernizr');	
	
	wp_register_script('header1', get_template_directory_uri().'/js/respond.min.js', array(), 1, true);
	wp_enqueue_script('header1');
	wp_register_script('header2', get_template_directory_uri().'/js/jquery.hoverIntent.js', array(), 1, true);
	wp_enqueue_script('header2');
	wp_register_script('header3', get_template_directory_uri().'/js/header.js', array(), 1, true);
    wp_enqueue_script('header3');
	
	if ( is_single() | is_page() ) {
    	wp_register_script('slides', get_template_directory_uri().'/js/jquery.slides.min.js', array('jquery'), 1, true);
    	wp_enqueue_script('slides');
	}
}
add_action( 'wp_enqueue_scripts', 'converio_scripts');

function converio_generate_custom_css() {
	$path = get_stylesheet_directory() . '/styles/colors/';
	
	//generate and save data
	ob_start();
	include('styles/colors/custom.php');
	$custom = ob_get_clean();
	file_put_contents($path.'custom.css', $custom, LOCK_EX);
}

function converio_styles() {
	wp_register_style( 'main_style', get_stylesheet_uri(), false, 1.0 );
    wp_enqueue_style( 'main_style');
	
    wp_register_style('headers', get_template_directory_uri().'/styles/headers.css');
    wp_enqueue_style('headers');
	
	if (class_exists('Woocommerce')) {
	    wp_register_style('woocommerce', get_template_directory_uri().'/styles/woocommerce.css');
	    wp_enqueue_style('woocommerce');
	}
	
    $color_scheme = get_theme_mod('color_scheme');
	if($color_scheme != 'custom' && $color_scheme != '' && $color_scheme != '0') {
		wp_register_style('color-schemes', get_template_directory_uri().'/styles/colors/'.$color_scheme.'.css');  
    	wp_enqueue_style('color-schemes');
	} else if ($color_scheme == 'custom') {
		$custom_external_stylesheet = get_theme_mod('custom_external_stylesheet');
		if($custom_external_stylesheet) {
			converio_generate_custom_css();
			wp_register_style('color-version', get_template_directory_uri().'/styles/colors/custom.css');
			wp_enqueue_style('color-version');
		}
	}
	
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( is_plugin_active( 'revslider/revslider.php' ) ) {
	    wp_register_style('revolution-slider-custom-styles', get_template_directory_uri().'/styles/revolution-slider.css');
	    wp_enqueue_style('revolution-slider-custom-styles');
	}	
	
}
add_action( 'wp_enqueue_scripts', 'converio_styles', 99);

/* Enqueue the font. */
function converio_fonts() {
	$protocol = is_ssl() ? 'https' : 'http';

	//primary font
	$primary_font = get_theme_mod('primary_font') ? get_theme_mod('primary_font') : false;
	
	if ($primary_font == 'google') {
		$primary_google_font = get_theme_mod('primary_google_font');
		if (!$primary_google_font) $primary_font = false;
	}
	
	if ($primary_font == 'google') {
		wp_enqueue_style( 'converio-custom-font', "$protocol://fonts.googleapis.com/css?family=$primary_google_font" );
	} else { 
		wp_enqueue_style( 'converio-opensans', "$protocol://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic&amp;subset=latin,cyrillic,greek" );
	}
	
	//secondary font
	$secondary_font = get_theme_mod('secondary_font') ? get_theme_mod('secondary_font') : false;

	if ($secondary_font == 'google') {
		$secondary_google_font = get_theme_mod('secondary_google_font');
		if (!$secondary_google_font) $secondary_font = false;
	}
	if ($secondary_font == 'google') {
		wp_enqueue_style( 'converio-custom-heading-font', "$protocol://fonts.googleapis.com/css?family=$secondary_google_font" );
	}
}

add_action( 'wp_enqueue_scripts', 'converio_fonts' );


function converio_filter_wp_title( $title, $sep ) {
	global $page, $paged;

	if( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary.
	if( $paged >= 2 || $page >= 2 ) {
		$title .= " $sep " . sprintf( esc_attr__( 'Page %s', 'converio' ), max( $paged, $page ) );
	}

	return $title;
}

add_filter( 'wp_title', 'converio_filter_wp_title', 10, 2); 

function converio_current_page_url() {
    $pageURL = 'http';
    if( isset($_SERVER["HTTPS"]) ) {
        if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

add_filter('gallery_style', create_function('$a', 'return "<div class=\'gallery\'>";'));

function converio_custom_excerpt($limit, $post) {
    $more = '<!--more-->';
    $found = strpos($post->post_content, $more);
    if(!$found) {
        $excerpt = explode(' ', get_the_excerpt(), $limit);
        if (count($excerpt)>=$limit) {
            array_pop($excerpt);
            $excerpt = implode(" ", $excerpt).'...';
        } else {
            $excerpt = implode(" ", $excerpt);
        }   
        $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
    } else {
        $excerpt = substr($post->post_content, 0, $found);
    }
    return $excerpt;
}

function converio_default_comments_off( $data ) {
    if( $data['post_type'] == 'page') {
        $data['comment_status'] = 0;
    }

    return $data;
}
add_filter( 'wp_insert_post_data', 'converio_default_comments_off' );

//add class to next and previous post links
add_filter('next_posts_link_attributes', 'converio_add_class_next_posts_link_attributes');
add_filter('previous_posts_link_attributes', 'converio_add_class_next_posts_link_attributes');

function converio_add_class_next_posts_link_attributes() {
    return 'class="next"';
}
function converio_add_class_previous_posts_link_attributes() {
    return 'class="previous"';
}

/* fix empty searches */
function converio_search_filter($query) {
    // If 's' request variable is set but empty
    if (isset($_GET['s']) && empty($_GET['s']) && $query->is_main_query()){
        $query->is_search = true;
        $query->is_home = false;
    }
    return $query;
}
add_filter('pre_get_posts','converio_search_filter');

/* sanitization */
function converio_stripslashes( $string ) {
    if(get_magic_quotes_gpc()) {
        return stripslashes($string);
    } else {
        return $string;
    }
}

function converio_sanitize_text( $string ) {
	return converio_stripslashes(htmlspecialchars($string));
}

function converio_sanitize_text_decode( $string ) {
	return converio_stripslashes(htmlspecialchars_decode($string));
}

/* fix for admin bar */
function converio_admin_bar_fix() {
    if(!is_admin() && is_admin_bar_showing()) {
        remove_action('wp_head', '_admin_bar_bump_cb');
        $output  = '<style type="text/css">'."\n\t";
        $output .= 'body.admin-bar { padding-top: 28px; }'."\n";
        $output .= '</style>'."\n";
    echo $output;
    }
}
add_action('wp_head', 'converio_admin_bar_fix', 5);

/* sliders config and templates directory */
if ( class_exists( 'IworksSliders' ) ) {

	add_filter( 'iworks_sliders_configuration_file', 'converio_iworks_sliders_configuration_file', 10, 1 );

	function converio_iworks_sliders_configuration_file($file)
	{
		return dirname(__FILE__).'/sliders.php';
	}

	add_filter('iworks_sliders_get_template', 'converio_iworks_sliders_get_template', 10, 3 );
	function converio_iworks_sliders_get_template( $file, $kind, $name )
	{
		return sprintf(
			'%s/multipurpose-sliders-templates/%s/%s.php',
			dirname(__FILE__),
			$kind,
			$name
		);
	}
}