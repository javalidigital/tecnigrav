<?php

/*

Copyright 2013-2014 Marcin Pietrzak (marcin@iworks.pl)

 */

if ( !defined( 'WPINC' ) ) {
    die;
}

if ( class_exists( 'IworksSliders' ) ) {
    return;
}

class IworksSliders
{
    private $dev;
    private $options;
    private $admin_menu_slug;
    private $base;
    private $capability;
    private $capability_settings;
    private $slider_options_name;
    private $noncename;
    private $post_type;
    private $version;
    private $sliders;
    private $image_name;
    private $thumbnail_name;
    private $slide_keys;

    public function __construct()
    {
        /**
         * static settings
         */
        $this->version = '1.5.1';
        $this->base = dirname( __FILE__ );
        $this->dir = basename( dirname( $this->base ) );
        $this->capability = apply_filters( 'iworks_sliders_capability', 'edit_posts' );
        $this->capability_settings = apply_filters( 'iworks_sliders_capability', 'manage_options' );
        $this->dev = ( defined( 'IWORKS_DEV_MODE' ) && IWORKS_DEV_MODE )? '.dev':'';
        $this->admin_menu_slug = $this->dir.'/admin';
        $this->post_type = hash( 'adler32', __CLASS__ . 'slider' );
        $this->post_type_slide = hash( 'adler32', __CLASS__ . 'slide' );
        $this->slider_options_name = hash( 'adler32', __CLASS__ . 'slider_options' );
        $this->noncename = __CLASS__;
        $this->thumbnail_name = $this->post_type_slide.'-thumbnail';

        /**
         * sliders
         */
        $this->slide_keys = array( 'link_more_text', 'link_more_url', 'link_text', 'link_url', 'video', 'link_classes', );

        /**
         * generate
         */
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'edit_form_after_title', array( &$this, 'edit_form_after_title' ) );
        add_action( 'init', array( &$this, 'init' ) );
        add_action( 'init', array( &$this, 'register_post_type' ), 0 );
        add_action( 'iworks_sliders_produce', array( &$this, 'produce' ) );
        add_action( 'multipurpose_sliders', array( &$this, 'produce' ) );
        add_action( 'save_post', array( $this, 'save_post' ) );
        add_action( 'wp_ajax_get_slider_kind', array( &$this, 'get_slider_kind_by_post_slider_id' ) );
        add_action( 'wp_enqueue_scripts', array( &$this, 'wp_enqueue_scripts' ) );
        add_action( 'wp_footer', array( &$this, 'wp_footer' ) );
        add_filter( 'admin_body_class', array( &$this, 'admin_body_class' ), 10, 3 );
        add_filter( 'manage_edit-'.$this->post_type.'_columns', array( $this, 'slider_columns' ) );
        add_filter( 'manage_edit-'.$this->post_type_slide.'_columns', array( $this, 'slide_columns' ) );
        add_filter( 'manage_'.$this->post_type.'_posts_custom_column', array( $this, 'add_columns_content' ), 10, 2 );
        add_filter( 'manage_'.$this->post_type_slide.'_posts_custom_column', array( $this, 'add_columns_content' ), 10, 2 );
        add_filter( 'sanitize_html_class', array( &$this, 'sanitize_html_class' ), 10, 3 );
        add_filter( 'iworks_sliders_template_content', array( $this, 'placeholders' ) );
        /**
         * global option object
         */
        global $iworks_sliders_options;
        $this->options = $iworks_sliders_options;
    }

    public function placeholders($content)
    {
        $content = preg_replace( '/%TEMPLATE_DIRECORY_URI%/', get_template_directory_uri(), $content );
        $content = preg_replace( '/%STYLESHEET_DIRECTORY_URI%/', get_stylesheet_directory_uri(), $content );
        foreach( array( 'name', 'wpurl', 'url', 'text_direction', 'language' ) as $key ) {
            $re = strtoupper('/%option_'.$key.'%/');
            $content = preg_replace( $re, get_bloginfo( $key ), $content );
        }
        return $content;
    }

    public function edit_form_after_title()
    {
        if ( get_post_type() != $this->post_type ) {
            return;
        }
        $slider_options = $this->get_slider_options( get_the_ID() );
        if ( empty( $slider_options ) ) {
            return;
        }
        $value = $this->get_slider_option_value( $slider_options['kind'], 'text_before_message' );
        if ( $value ) {
            printf( '<p class="update-nag text_before_message">%s</p><br/>', $value);
        }
    }

    public function get_slider_kind_by_post_slider_id()
    {
        $kind = 0;
        if ( isset( $_POST['slider_id'] ) && preg_match( '/^\d+$/', $_POST['slider_id'] ) ) {
            $slider = $this->check_sense( intval( $_POST['slider_id'] ) );
            if ( !empty( $slider ) ) {
                $slider_options = $this->get_slider_options( $slider->ID );
                $kind = $slider_options['kind'];
            }
        }
        echo $kind;
        die();
    }

    public function wp_enqueue_scripts()
    {
        $slider = $this->check_sense();
        if ( empty( $slider ) ) {
            return;
        }
        wp_enqueue_script( 'jquery' );
    }

    public function wp_footer()
    {
        $slider = $this->check_sense();
        if ( empty( $slider ) ) {
            return;
        }
        $slider_options = $this->get_slider_options( $slider->ID );
        $exclude = array(
            'email',
            'footer_button_text',
            'footer_button_url',
            'footer_link_more_text',
            'footer_link_more_url',
            'kind',
            'pattern',
            'service',
        );
        foreach( $exclude as $key ) {
            if ( array_key_exists( $key, $slider_options ) ) {
                unset( $slider_options[$key] );
            }
        }
        /**
         * normalize to int
         */
        foreach( array( 'autoslides', 'change_slide_on_click', 'pause_on_hover', 'pause_on_action', 'delay', ) as $key ) {
            $slider_options[$key] = (int)(( array_key_exists( $key, $slider_options ) && $slider_options[$key] )? $slider_options[$key]:0);
        }
        echo '<script type="text/javascript">';
        printf( 'var OptionsSlider = %s;', json_encode( $slider_options ) );
        echo '</script>';
    }

    /**
     * slider columns
     */
    public function slider_columns($columns)
    {
        $columns['number-of-slides'] = __( 'Number of slides', 'iworks-sliders' );
        return $columns;
    }

    /**
     * slide columns
     */
    public function slide_columns($columns)
    {
        $columns['slider'] = __( 'Slider', 'iworks-sliders' );
        $columns['order'] = __( 'Order', 'iworks-sliders' );
        return $columns;
    }

    /**
     * slider & slides columns
     */
    public function add_columns_content($column_name, $post_id)
    {
        switch( $column_name ) {
        case 'slider':
            $slider_id = get_post_meta( $post_id, $this->options->get_option_name( $this->slider_options_name ), true );
            if ( empty( $slider_id ) ) {
                return;
            }
            echo get_the_title( $slider_id );
            break;
        case 'order':
            $post = get_post( $post_id );
            echo $post->menu_order;
            break;
        case 'number-of-slides':
            echo $this->count_slides($post_id);
            return;
        }
    }

    private function check_sense($slider_id = null)
    {
        if ( is_null( $slider_id ) ) {
            if ( is_home() ) {
                $slider_id = $this->options->get_option( 'main_page_slider' );
            } elseif ( is_page() ) {
                global $post;
                $slider_id = get_post_meta( $post->ID, $this->options->get_option_name( $this->slider_options_name ), true );
            }
        }
        if ( empty( $slider_id ) ) {
            return null;
        }
        $slider = get_post( $slider_id );
        if ( empty( $slider ) or $this->post_type != $slider->post_type ) {
            return null;
        }
        return $slider;
    }

    private function get_slider_options($slider_id)
    {
        return get_post_meta( $slider_id, $this->slider_options_name, true );
    }

    private function get_slider_option_value($kind, $key)
    {
        if ( array_key_exists( $kind, $this->sliders ) && array_key_exists( $key, $this->sliders[$kind] ) ) {
            return $this->sliders[$kind][$key];
        }
        return false;
    }

    private function get_template($kind, $name)
    {
        /**
         * theme templates
         */
        $file = sprintf( '%s/templates/%s/%s.php', get_template_directory(), $kind, $name );
        $file = apply_filters( 'iworks_sliders_get_template', $file, $kind, $name );
        if( is_file($file) && is_readable( $file ) ) {
            return apply_filters( 'iworks_sliders_template_content', file_get_contents( $file ) );
        }
        /**
         * plugin templates
         */
        $file = sprintf( '%s/templates/%s/%s.php', dirname( dirname( __FILE__ ) ), $kind, $name );
        if( is_file($file) && is_readable( $file ) ) {
            return apply_filters( 'iworks_sliders_template_content', file_get_contents( $file ) );
        }
        return apply_filters( 'iworks_sliders_template_content', false );
    }

    public function produce()
    {
        $slider = $this->check_sense();
        if ( empty( $slider ) ) {
            return;
        }
        $slider_options = $this->get_slider_options( $slider->ID );

        /**
         * special characters broke export/import process
         */
        if ( is_array($slider_options) && array_key_exists('service', $slider_options) ) {
            $slider_options['service'] = urldecode($slider_options['service']);
        }

        if ( array_key_exists( 'kind', $slider_options ) && array_key_exists( $slider_options['kind'], $this->sliders ) ) {
            $kind = $slider_options['kind'];
            $content = $this->get_template($kind, 'header');

            $thumbnails_list = '';

            if ( array_key_exists( 'pattern', $slider_options) ) {
                $value = sprintf( '%02d', $slider_options['pattern'] );
                $content = preg_replace( '/%PATTERN%/', $value, $content );
            }
            $content = preg_replace( '/%PATTERN%/', '00', $content );

            $one_template = $this->get_template($kind, 'one' );
            $one_template_video = $this->get_template($kind, 'one_video');

            $the_query = new WP_Query( $this->get_wp_query_args_by_slider_id( $slider->ID ) );
            if ( $the_query->have_posts() ) {
                while ( $the_query->have_posts() ) {
                    $the_query->the_post();
                    $slide_options = get_post_meta( get_the_ID(), $this->slider_options_name, true );

                    $one = $one_template;

                    if ( $this->sliders[$kind]['video'] && $slide_options['video'] && $one_template_video ) {
                        $one = $one_template_video;
                    }

                    $img = '';
                    if ( has_post_thumbnail() ) {
                        $img = $this->strip_attributes( get_the_post_thumbnail( get_the_ID(), 'full' ) );
                    }
                    if ( $this->sliders[$kind]['thumbnails_list_element'] ) {
                        $one_list_element = $this->get_slider_option_value( $kind, 'thumbnails_list_element' );
                        if ( $one_list_element ) {
                            $thumbnail = '';
                            if ( $img ) {
                                $thumbnail = $this->strip_attributes( get_the_post_thumbnail( get_the_ID(), $this->thumbnail_name ) );
                            }
                            $one_list_element = preg_replace( '/%IMG%/', $thumbnail, $one_list_element );
                            $one_list_element = preg_replace( '/%TITLE%/', get_the_title(), $one_list_element );

                            /**
                             * link class
                             */
                            $value = '';
                            $key = 'link_classes';
                            if ( array_key_exists( $key, $slide_options ) && !empty( $slide_options[$key] ) ) {
                                $value = $slide_options[$key];
                            }
                            $one_list_element = preg_replace( '/%LINK_CLASSES%/', $value, $one_list_element );

                            $thumbnails_list .= $one_list_element;
                        }
                    }
                    $one = preg_replace( '/%TITLE%/', apply_filters( 'the_title', get_the_title()), $one );
                    $one = preg_replace( '/%CONTENT%/', apply_filters( 'the_content', get_the_content() ), $one );

                    $one = preg_replace( '/%IMG%/', $img, $one );

                    /**
                     * link_template & link_more_template
                     */
                    foreach( array( 'link_template' => 'link_url', 'link_more_template' => 'link_more_url' ) as $test => $key ) {
                        $value = '';
                        if ( array_key_exists( $key, $slide_options ) && !empty( $slide_options[$key] ) ) {
                            $value = $this->get_slider_option_value( $kind, $test );
                        }
                        $re = sprintf( '/%%%s%%/', strtoupper( $test ) );
                        $one = preg_replace( $re, $value, $one );
                    }

                    foreach( $this->slide_keys as $key ) {
                        $re = sprintf( '/%%%s%%/', strtoupper( $key ) );
                        $value = '';
                        if ( array_key_exists( $key, $slide_options ) && !empty( $slide_options[$key] ) ) {
                            $value = $slide_options[$key];
                        }
                        $one = preg_replace( $re, $value, $one );
                    }

                    $content .= $one;
                }
            }
            wp_reset_postdata();

            $content .= $this->get_template( $kind, 'footer' );

            /**
             * send path
             */
            $content = preg_replace( '/%PATH%/', plugin_dir_url( $this->base ), $content );

            if ( $this->sliders[$kind]['thumbnails_list_element'] ) {
                $content = preg_replace( '/%THUMBNAILS_LIST%/', $thumbnails_list, $content );
            }

            $content = preg_replace( '/%PREVIOUS%/', __( 'Previous', 'iworks-sliders' ), $content );
            $content = preg_replace( '/%NEXT%/', __( 'Next', 'iworks-sliders' ), $content );
            $content = preg_replace( '/%CONTENT%/', wpautop($slider->post_content), $content );

            /**
             * footer_button & footer_link_more
             */
            foreach( array( 'footer_button', 'footer_link_more' ) as $k ) {
                $value = $this->get_slider_option_value( $kind, $k );
                foreach( array( 'text', 'url' ) as $sufix ) {
                    $key = sprintf( '%s_%s', $k, $sufix );
                    $re = sprintf( '/%%%s%%/', strtoupper($key) );
                    if ( array_key_exists( $key, $slider_options ) && !empty( $slider_options[$key] ) ) {
                        $value = preg_replace( $re, $slider_options[$key], $value );
                    } else {
                        $value = preg_replace( $re, '', $value );
                    }
                }
                $re = sprintf( '/%%%s%%/', strtoupper( $k) );
                $content = preg_replace( $re, $value, $content );
            }

            /**
             * simple fields
             */
            foreach( array( 'email', 'form_title' ) as $key ) {
                $value = '';
                $re = sprintf( '/%%%s%%/', strtoupper($key) );
                if ( array_key_exists( $key, $slider_options ) && !empty( $slider_options[$key] ) ) {
                    $value = $slider_options[$key];
                }
                if ( 'email' == $key ) {
                    $value = antispambot( $value );
                }
                $content = preg_replace( $re, $value, $content );
            }

            /**
             * select and options
             */
            foreach( array( 'service' ) as $key ) {
                $value = '';
                $re = sprintf( '/%%%s_OPTIONS%%/', strtoupper($key) );
                if ( array_key_exists( $key, $slider_options ) && !empty( $slider_options[$key] ) ) {
                    foreach( preg_split( '/[\n\r\;]+/', $slider_options[$key] ) as $one ) {
                        $option = preg_split( '/:/', $one );
                        $value .= sprintf(
                            '<option value="%s">%s</option>',
                            $option[0],
                            isset($option[1])? $option[1]:$option[0]
                        );
                    }
                }
                $content = preg_replace( $re, $value, $content );
            }

            /**
             * i18n
             */

            $content = preg_replace( '/%Email%/', __( 'Email', 'iworks_sliders' ), $content );
            $content = preg_replace( '/%Last Name%/', __( 'Last Name', 'iworks_sliders' ), $content );
            $content = preg_replace( '/%Message%/', __( 'Message', 'iworks_sliders' ), $content );
            $content = preg_replace( '/%Name%/', __( 'Name', 'iworks_sliders' ), $content );
            $content = preg_replace( '/%Phone%/', __( 'Phone', 'iworks_sliders' ), $content );
            $content = preg_replace( '/%Service%/', __( 'Service', 'iworks_sliders' ), $content );
            $content = preg_replace( '/%Submit%/', __( 'Submit', 'iworks_sliders' ), $content );

            echo $content;
        }
    }

    public function sanitize_html_class($sanitized, $class, $fallback)
    {
        $re = '/posts-('.implode( '|', array( $this->post_type, $this->post_type_slide ) ).')$/';
        return preg_replace( $re, 'slider', $sanitized );
    }

    public function get_version($file = null)
    {
        if ( defined( 'IWORKS_DEV_MODE' ) && IWORKS_DEV_MODE ) {
            if ( null != $file ) {
                $file = dirname( dirname ( __FILE__ ) ) . $file;
                return md5_file( $file );
            }
            return rand( 0, 99999 );
        }
        return $this->version;
    }

    public function init()
    {
        $config = apply_filters(
            'iworks_sliders_configuration_file',
            dirname( $this->base ).'/etc/sliders.php'
        );
        $this->sliders = include_once $config;
        /**
         * actions
         */
        add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ) );
        add_action( 'admin_init', array( &$this, 'admin_init' ) );
        add_action( 'admin_init', 'iworks_sliders_options_init' );
        add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
        add_action( 'right_now_content_table_end', array( &$this, 'right_now_content_table_end' )        );
        /**
         * other inits
         */
        if ( function_exists( 'add_theme_support' ) ) {
            add_theme_support( 'post-thumbnails' );

            add_image_size( $this->thumbnail_name, 160, 96, true );
        }
    }

    public function admin_init()
    {
        add_filter( 'plugin_row_meta', array( &$this, 'plugin_row_meta' ), 10, 2 );
    }

    public function admin_enqueue_scripts()
    {
        $screen = get_current_screen();
        $re = '/^(' . join( '|', array( $this->dir, '(edit\-)?'.$this->post_type, '(edit\-)?'.$this->post_type_slide ) ) . ')/';
        if ( isset( $screen->id ) && preg_match( $re, $screen->id ) ) {
            /**
             * enqueue resources
             */
            $re = sprintf( '/(%s|%s)/', $this->post_type, $this->post_type_slide );
            if ( preg_match( $re, $screen->post_type ) && 'post' == $screen->base ) {
                wp_enqueue_script( 'iworks-sliders-admin-js', plugins_url( '/scripts/iworks-sliders-admin.js', $this->base ), null, $this->get_version() );
                wp_localize_script( 'iworks-sliders-admin-js', 'iworks_sliders', $this->sliders );
                wp_localize_script(
                    'iworks-sliders-admin-js',
                    'iworks_slider_vars',
                    array(
                        'slider' => $this->post_type,
                        'slide' => $this->post_type_slide,
                        'option_name' => $this->slider_options_name,
                        'option_global_name' => $this->options->get_option_name( $this->slider_options_name ),
                    )
                );
            }
            $this->enqueue_style( 'iworks-sliders-admin' );
        }
    }

    public function admin_body_class($class)
    {
        $screen = get_current_screen();
        $re = '/^(' . join( '|', array( $this->dir, '(edit\-)?'.$this->post_type, '(edit\-)?'.$this->post_type_slide ) ) . ')/';
        if ( isset( $screen->id ) && preg_match( $re, $screen->id ) ) {
            $class .= ' iworks-sliders';
        }
        return $class;
    }

    public function plugin_row_meta($links, $file)
    {
        if ( $this->dir.'/iworks-sliders.php' == $file ) {
            if ( !is_multisite() && current_user_can( $this->capability_settings ) ) {
                $links[] = '<a href="admin.php?page='.$this->dir.'/admin/index.php">' . __( 'Settings' ) . '</a>';
            }
        }
        return $links;
    }

    public function update()
    {
        $version = $this->options->get_option( 'version', 0 );
        if ( 0 < version_compare( $this->version, $version ) ) {
            $this->options->update_option( 'version', $this->version );
        }
    }

    /**
     * Add admin menu
     */
    public function admin_menu()
    {
        $admin_page = add_menu_page(
            __( 'Sliders', 'iworks-sliders' ),
            __( 'Sliders', 'iworks-sliders' ),
            $this->capability,
            $this->admin_menu_slug,
            null, // function
            plugin_dir_url( dirname( __FILE__ ) ).'images/oxygen/16x16/actions/view-presentation.png',
            '20.7430217403387853'
        );
        add_submenu_page( $this->admin_menu_slug, __( 'Slides', 'iworks-sliders' ), __( 'Slides', 'iworks-sliders' ), $this->capability,  add_query_arg( 'post_type', $this->post_type_slide, 'edit.php' ) );
        //add_submenu_page( $this->admin_menu_slug, __( 'Settings', 'iworks-sliders' ), __( 'Settings', 'iworks-sliders' ), $this->capability_settings, $this->dir.'/admin/index.php' );
    }

    public function get_admin_menu_slug($sufix = null)
    {
        if ( empty( $sufix ) ) {
            return $this->admin_menu_slug;
        }
        if ( is_file( $sufix ) ) {
            return sprintf( '%s/%s', $this->admin_menu_slug, basename( $sufix ));
        }
        return sprintf( '%s%s', $this->admin_menu_slug, $sufix);
    }

    /**
     * custom post type
     */

    public function register_post_type()
    {
        $labels = array(
            'name' => __( 'Sliders', 'iworks-sliders' ),
            'singular_name' => __( 'Slider', 'iworks-sliders' ),
            'add_new' => __( 'Add New', 'iworks-sliders' ),
            'add_new_item' => __( 'Add New Slider', 'iworks-sliders' ),
            'edit_item' => __( 'Edit Slider', 'iworks-sliders' ),
            'new_item' => __( 'New Slider', 'iworks-sliders' ),
            'all_items' => __( 'Sliders', 'iworks-sliders' ),
            'view_item' => __( 'View Slider', 'iworks-sliders' ),
            'search_items' => __( 'Search Slider', 'iworks-sliders' ),
            'not_found' =>  __( 'No sliders found', 'iworks-sliders' ),
            'not_found_in_trash' => __( 'No sliders found in Trash', 'iworks-sliders' ),
            'parent_item_colon' => '',
            'menu_name' => __( 'Sliders', 'iworks-sliders' ),
        );
        $args = array(
            'description' => __( 'This post type allow to create sliders.', 'iworks-sliders' ),
            'hierarchical' => true,
            'labels' => $labels,
            'label' => __( 'Slider', 'iworks-sliders' ),
            'query_var' => false,
            'rewrite' => false,
            'show_in_admin_bar' => true,
            'show_in_menu' => $this->admin_menu_slug,
            'show_ui' => true,
            'supports' => array( 'title', 'revisions', 'editor' ),
            'publicly_queryable' => false,
        );
        register_post_type( $this->post_type, $args );

        $labels = array(
            'name' => __( 'Slides', 'iworks-sliders' ),
            'singular_name' => __( 'Slide', 'iworks-sliders' ),
            'add_new' => __( 'Add New', 'iworks-sliders' ),
            'add_new_item' => __( 'Add New Slide', 'iworks-sliders' ),
            'edit_item' => __( 'Edit Slide', 'iworks-sliders' ),
            'new_item' => __( 'New Slide', 'iworks-sliders' ),
            'all_items' => __( 'All Slides', 'iworks-sliders' ),
            'view_item' => __( 'View Slide', 'iworks-sliders' ),
            'search_items' => __( 'Search Slide', 'iworks-sliders' ),
            'not_found' =>  __( 'No slides found', 'iworks-sliders' ),
            'not_found_in_trash' => __( 'No slides found in Trash', 'iworks-sliders' ),
            'parent_item_colon' => '',
            'menu_name' => __( 'Slides', 'iworks-sliders' ),
        );
        $args = array(
            'description' => __( 'This post type allow to add slide to slider.', 'iworks-sliders' ),
            'labels' => $labels,
            'label' => __( 'Slide', 'iworks-sliders' ),
            'query_var' => false,
            'rewrite' => false,
            'show_in_admin_bar' => true,
            'show_in_menu' => false,
            'show_ui' => true,
            'supports' => array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes' ),
            'public' => false,
            'publicly_queryable' => false,

        );
        register_post_type( $this->post_type_slide, $args );
    }

    /**
     * enqueue helper
     */
    private function enqueue_style($name, $deps = null)
    {
        $file = '/styles/'.$name.$this->dev.'.css';
        wp_enqueue_style ( $name, plugins_url( $file, $this->base ), $deps, $this->get_version( $file ) );
    }

    /**
     * Adds the meta box container
     */
    public function add_meta_boxes()
    {
        add_meta_box(
            $this->options->get_option_name( 'slider' ),
            __( 'Options', 'iworks-sliders' ),
            array( &$this, 'render_meta_box_content_slider' ),
            $this->post_type,
            'advanced',
            'high'
        );
        add_meta_box(
            $this->options->get_option_name( 'slide' ),
            __( 'Configuration', 'iworks-sliders' ),
            array( &$this, 'render_meta_box_content_slide' ),
            $this->post_type_slide,
            'advanced',
            'high'
        );
        add_meta_box(
            $this->options->get_option_name( 'page' ),
            __( 'Select slider', 'iworks-sliders' ),
            array( &$this, 'render_meta_box_content_page' ),
            'page',
            'side',
            'core'
        );
    }

    public function render_meta_box_content_page($post)
    {
        wp_nonce_field( plugin_basename( __FILE__ ), $this->noncename );
        echo $this->select_slider_helper( $post->ID );
    }

    public function render_meta_box_content_slide($post)
    {
        wp_nonce_field( plugin_basename( __FILE__ ), $this->noncename );
        $options = array();
        foreach( $this->slide_keys as $key ) {
            $options[$key] = null;
        }
        $post_options = get_post_meta( $post->ID, $this->slider_options_name, true );
        if ( is_array( $post_options ) ) {
            $options = array_merge( $options, $post_options );
        }

        echo '<table class="widefat">';
        echo '<thead>';
        echo '<tbody>';

        $value = $this->select_slider_helper( $post->ID );
        $this->one_table_row( __( 'Slider', 'iworks-sliders' ), $value );

        $options_frontend = array(
            'video' => array( 'type' => 'textarea', 'label' => __( 'Video code', 'iworks-sliders' ), 'add_id' => true, 'class' => 'large-text', ),
            'link_url' => array( 'type' => 'text', 'label' => __( 'Link URL', 'iworks-sliders' ), 'add_id' => true, 'class' => 'large-text', ),
            'link_text' => array( 'type' => 'text', 'label' => __( 'Link text', 'iworks-sliders' ), 'add_id' => true, 'class' => 'large-text', ),
            'link_classes' => array( 'type' => 'select', 'label' => __( 'Link class', 'iworks-sliders' ), 'add_id' => true, ),
            'link_more_url' => array( 'type' => 'text', 'label' => __( 'Button URL', 'iworks-sliders' ), 'add_id' => true, 'class' => 'large-text', ),
            'link_more_text' => array( 'type' => 'text', 'label' => __( 'Button text', 'iworks-sliders' ), 'add_id' => true, 'class' => 'large-text', ),
            'info' => array( 'type' => 'info', 'label' => __( 'Select slider first!', 'iworks-sliders' ), 'add_id' => true,'class' => 'side-info', ),
        );
        $this->render_options( $options, $options_frontend );

        echo '</tbody>';
        echo '</table>';
    }

    public function render_meta_box_content_slider($post)
    {
        wp_nonce_field( plugin_basename( __FILE__ ), $this->noncename );
        $options = get_post_meta( $post->ID, $this->slider_options_name, true );

        /**
         * special characters broke export/import process
         */
        if ( is_array($options) && array_key_exists('service', $options) ) {
            $options['service'] = urldecode($options['service']);
        }

        if ( !is_array( $options ) ) {
            $options = array(
                'kind' => 'slider01',
                'pattern' => false,
                'autoslides' => true,
                'delay' => 9000,
                'change_slide_on_click' => true,
                'pause_on_hover' => false,
                'pause_on_action' => true,
                'footer_button_text' => null,
                'footer_link_more_text' => null,
                'footer_button_url' => null,
                'footer_link_more_url' => null,
                'email' => false,
                'form_title' => false,
            );
        }

        echo '<table class="widefat">';
        echo '<tbody>';

        $name = sprintf('%s[%s]', $this->slider_options_name, 'kind' );
        $value = sprintf( '<select name="%s" id="input_%s_%s">', $name, $this->slider_options_name, 'kind' );
        foreach ( $this->sliders as $key => $data ) {
            $value .= sprintf(
                '<option value="%s"%s>%s</option>',
                $key,
                $key == $options['kind']? ' selected="selected"':'',
                $data['label']
            );
        }
        $value .= '</select>';
        $this->one_table_row( __( 'Slider', 'iworks-sliders' ), $value );

        $value = sprintf(
            '<input type="number" min="%d" max="%d" name="%s" class="short-text" %s/>',
            apply_filters('iworks_sliders_pattern_min', 1),
            apply_filters('iworks_sliders_pattern_max', 100),
            sprintf('%s[%s]', $this->slider_options_name, 'pattern' ),
            empty( $options['pattern'] )? '':sprintf('value="%d" ', $options['pattern'])
        );
        $this->one_table_row( __( 'Pattern', 'iworks-sliders' ), $value, ' id="iworks_sliders_pattern" style="display: none;"' );

        $options_frontend = array(
            'autoslides' => array( 'type' => 'checkbox', 'label' => __( 'Auto Slides', 'iworks-sliders' ) ),
            'delay' => array( 'type' => 'number', 'label' => __( 'Delay', 'iworks-sliders' ) ),
            'change_slide_on_click' => array( 'type' => 'checkbox', 'label' => __( 'Change slide on click', 'iworks-sliders' ) ),
            'pause_on_hover' => array( 'type' => 'checkbox', 'label' => __( 'Pause on hover', 'iworks-sliders' ) ),
            'pause_on_action' => array( 'type' => 'checkbox', 'label' => __( 'Pause on action', 'iworks-sliders' ) ),
            'footer_button_text' => array( 'type' => 'text', 'label' => __( 'Button text', 'iworks-sliders' ), 'add_id' => true, 'class' => 'large-text', 'hide' => true, ),
            'footer_button_url' => array( 'type' => 'text', 'label' => __( 'Button url', 'iworks-sliders' ), 'add_id' => true, 'class' => 'large-text', 'hide' => true, ),
            'footer_link_more_text' => array( 'type' => 'text', 'label' => __( 'Link text', 'iworks-sliders' ), 'add_id' => true, 'class' => 'large-text', 'hide' => true, ),
            'footer_link_more_url' => array( 'type' => 'url', 'label' => __( 'Link url', 'iworks-sliders' ), 'add_id' => true, 'class' => 'large-text', 'hide' => true, ),
            'email' => array( 'type' => 'email', 'label' => __( 'Email address', 'iworks-sliders' ), 'add_id' => true, 'class' => 'large-text', 'hide' => true, ),
            'service' => array( 'type' => 'textarea', 'label' => __( 'Services', 'iworks-sliders' ), 'add_id' => true, 'hide' => true, 'help' => __( 'Enter pair of key:value in one line, use ":" to separate both values or just write a line for both key and value.', 'iworks_sliders' ) ),
            'form_title' => array( 'type' => 'text', 'label' => __( 'Form title', 'iworks-sliders' ), 'add_id' => true, 'class' => 'large-text', 'hide' => true, ),
        );

        $this->render_options( $options, $options_frontend );

        echo '</tbody>';
        echo '</table>';
    }

    private function render_options($options, $options_frontend)
    {
        if ( empty( $options_frontend ) || !is_array( $options_frontend ) ) {
            return;
        }
        foreach( $options_frontend as $key => $data ) {
            $value = '';
            /**
             * add help section
             */
            if ( isset( $data['help'] ) && $data['help'] ) {
                $value .= sprintf( '<p class="description">%s</p>', $data['help'] );
            }
            $html_element_name = sprintf('%s[%s]', $this->slider_options_name, $key );
            switch( $data['type'] ) {
            case 'info':
                $value .= '&nbsp;';
                break;
            case 'textarea':
                $value .= sprintf(
                    '<textarea name="%s"%s rows="10" cols="50">%s</textarea>',
                    $html_element_name,
                    array_key_exists( 'class', $data ) && !empty( $data['class'] ) ? sprintf( ' class="%s"', $data['class'] ):'',
                    array_key_exists( $key, $options )? $options[$key]:''
                );
                break;
            case 'select':
                $value .= sprintf( '<select name="%s"></select>', $html_element_name );
                $value .= sprintf(
                    '<input type="hidden" name="%s" value="%s" id="iworks_sliders_%s_hidden" />',
                    $key,
                    $options[$key],
                    $key
                );
                break;
            case 'checkbox':
                $value .= sprintf(
                    '<input type="checkbox" name="%s" value="1"%s%s/>',
                    $html_element_name,
                    array_key_exists( $key, $options ) && $options[$key]? ' checked="checked" ':'',
                    array_key_exists( 'class', $data ) && !empty( $data['class'] ) ? sprintf( ' class="%s"', $data['class'] ):''
                );
                break;
            default:
                $value .= sprintf(
                    '<input type="%s" name="%s" value="%s"%s/>',
                    $data['type'],
                    $html_element_name,
                    array_key_exists( $key, $options )? $options[$key]:'',
                    array_key_exists( 'class', $data ) && !empty( $data['class'] ) ? sprintf( ' class="%s"', $data['class'] ):''
                );
            }
            $tr_extra = '';
            /**
             * add html element ID
             */
            if ( isset( $data['add_id'] ) && $data['add_id'] ) {
                $tr_extra .= sprintf(
                    ' id="iworks_sliders_%s" ',
                    $key
                );
            }
            /**
             * add display none
             */
            if ( isset( $data['hide'] ) && $data['hide'] ) {
                $tr_extra .= ' style="display: none;" ';
            }
            /**
             * produce
             */
            $this->one_table_row( $data['label'], $value, $tr_extra );
        }
    }

    private function one_table_row($label, $value, $tr_extra = '', $echo = true)
    {
        $content = sprintf( '<tr valign="top"%s><th scope="row" style="width: 150px;">%s</th><td>%s</td></tr>', $tr_extra, $label, $value );
        if ( !$echo ) {
            return $content;
        }
        echo $content;
    }

    public function save_post($post_id)
    {

        if ( !isset( $_REQUEST['post_type'] ) ) {
            return;
        }

        if ( !in_array( $_REQUEST['post_type'], array( 'page', $this->post_type, $this->post_type_slide ) ) ) {
            return;
        }
        if ( ! isset( $_POST[$this->noncename] ) || ! wp_verify_nonce( $_POST[$this->noncename], plugin_basename( __FILE__ ) ) ) {
            return;
        }

        foreach ( array( $this->slider_options_name, $this->options->get_option_name($this->slider_options_name) ) as $name ) {
            $mydata = array_key_exists($name, $_POST )? $_POST[$name]:array();

            /**
             * fix "no-pattern" error on first save
             */
            if ( $this->post_type == $_POST['post_type'] ) {
                if ( is_array( $mydata ) ) {
                    if ( !array_key_exists( 'pattern', $mydata ) || !$mydata['pattern'] ) {
                        $mydata['pattern'] = '';
                    }
                } else {
                    $mydata['pattern'] = '';
                }
            }
            /**
             * special characters broke export/import process
             */
            if ( is_array($mydata) && array_key_exists('service', $mydata) ) {
                $mydata['service'] = urlencode($mydata['service']);
            }
            if ( !add_post_meta( $post_id, $name, $mydata, true ) ) {
                update_post_meta( $post_id, $name, $mydata );
            }
        }
    }

    public function right_now_content_table_end()
    {
        if ( !current_user_can( $this->capability ) ) {
            return;
        }
        $count_posts = wp_count_posts( $this->post_type );
        $link = get_admin_url(null, 'edit.php?post_type=' . $this->post_type );
        printf( '<tr><td class="first b b-%s">', $this->post_type );
        printf( '<a href="%s">%d</>', $link, $count_posts->publish );
        printf( '</td><td class="t %s">', $this->post_type );
        printf( '<a href="%s">%s</>', $link, __( 'Sliders', 'iworks-sliders' ) );
        echo '</td></tr>';

        $count_posts = wp_count_posts( $this->post_type_slide );
        $link = get_admin_url(null, 'edit.php?post_type=' . $this->post_type_slide );
        printf( '<tr><td class="first b b-%s">', $this->post_type_slide );
        printf( '<a href="%s">%d</>', $link, $count_posts->publish );
        printf( '</td><td class="t %s">', $this->post_type_slide );
        printf( '<a href="%s">%s</>', $link, __( 'Slides', 'iworks-sliders' ) );
        echo '</td></tr>';
    }

    public function get_this_post_type()
    {
        return $this->post_type;
    }

    public function get_this_capability()
    {
        return $this->capability;
    }

    public function select_slider_helper($post_id)
    {
        $name = $this->options->get_option_name( $this->slider_options_name );
        $args = array(
            'echo' => false,
            'name' => $name,
            'selected' => get_post_meta( $post_id, $name, true ),
            'show_option_none' => __( 'None', 'iworks-sliders' ),
            'post_type' => $this->post_type,
        );
        return wp_dropdown_pages( $args );
    }

    /**
     * count slides by slider id
     */
    private function count_slides($slider_id)
    {
        $the_query = new WP_Query( $this->get_wp_query_args_by_slider_id( $slider_id ) );
        return $the_query->found_posts;
    }

    private function get_wp_query_args_by_slider_id($slider_id)
    {
        return array(
            'post_type' => $this->post_type_slide,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'meta_key' => $this->options->get_option_name( $this->slider_options_name ),
            'meta_value' => $slider_id,
        );
    }

    public function get_post_type()
    {
        return $this->post_type;
    }

    private function strip_attributes($text)
    {
        $text = preg_replace( '/ class="[^"]+"/', '', $text );
        return preg_replace( '/ (width|height)="\d+"/', '', $text );
    }
}
/*
function wptutsplus_text_after_title($post_type) { ?>
    <div class="after-title-help postbox">
        <h3>Using this screen</h3>
        <div class="inside">
            <p>Use this screen to add new articles or edit existing ones. Make sure you click 'Publish' to publish a new article once you've added it, or 'Update' to save any changes.</p>
        </div><!-- .inside -->
    </div><!-- .postbox -->
<?php }
add_action( 'edit_form_after_title', 'wptutsplus_text_after_title' );
*/
