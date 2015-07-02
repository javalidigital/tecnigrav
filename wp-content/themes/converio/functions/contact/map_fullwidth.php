<?php

class converio_map_fullwidth
{
    private static $meta_name = __CLASS__;

    public function __construct()
    {
        //add_filter( 'the_content', array($this, 'the_content'));
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_post' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
        $this->nonce = md5(self::$meta_name);
        $this->nonce_name = self::$meta_name.'_nounce';
    }

    public function the_content($content)
    {
        if ( !is_page() ) {
            return $content;
        }
        global $post;
        return self::get_meta($post->ID, $content);
    }

    public function admin_enqueue_scripts()
    {
        $screen = get_current_screen();
        if ( 'page' != $screen->id ) {
            return;
        }
        wp_register_script(
            __ClASS__,
            get_template_directory_uri().'/functions/contact/map_fullwidth.js',
            array('jquery'),
            '1.0',
            true
        );
        wp_enqueue_script(__ClASS__);
    }

    public static function get_meta($post_id, $content = '')
    {
        $value = get_post_meta($post_id, self::$meta_name, true);
        if ( empty( $value ) ) {
            return $content;
        }
        $data = preg_split( '/[\r\n]+/', $value );
        if (empty($data) ) {
            return $content;
        }
		
        $content .= '<div class=\'gmap fullwidth\' id="map" data-marker="'.get_template_directory_uri();
		$content .= '/images/map-markers/map-marker';
		$color_scheme = get_theme_mod('color_scheme');
		if($color_scheme) {
			$content .= '-'.$color_scheme;
		} 
		$content .= '.svg">';
            
        foreach( $data as $one ) {
            if ( empty( $one ) ) {
                continue;
            }
            $content .= '<div class="info-contact">';
            $content .= '<span class="address">'.esc_attr($one).'</span>';
            $content .= '</div>';
        }
        $content .= '</div>';
        return $content;
    }

    public function add_meta_boxes($post_type)
    {
        $post_types = array('page');
        if ( in_array( $post_type, $post_types )) {
            add_meta_box(
                __CLASS__.__FUNCTION__
                ,__( 'Addresses (please put each address in separate line)' )
                ,array( $this, 'render_meta_box_content' )
                ,$post_type
                ,'normal'
                ,'high'
            );
        }
    }

    public function render_meta_box_content($post)
    {
        wp_nonce_field( $this->nonce, $this->nonce_name );
        printf(
            '<textarea class="widefat" style="height:200px;" name="%s">%s</textarea>',
            self::$meta_name,
            get_post_meta($post->ID, self::$meta_name, true )
        );
    }

    public function save_post($post_id)
    {
        if ( ! isset( $_POST[$this->nonce_name] ) ) {
            return $post_id;
        }
        $nonce = $_POST[$this->nonce_name];
        if ( ! wp_verify_nonce( $nonce, $this->nonce ) ) {
            return $post_id;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return $post_id;

        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
        update_post_meta( $post_id, self::$meta_name, $_POST[self::$meta_name] );
    }

    public static function init()
    {
        new converio_map_fullwidth();
    }

    public static function get($post_id)
    {
        echo self::get_meta($post_id);
    }
}

converio_map_fullwidth::init();
