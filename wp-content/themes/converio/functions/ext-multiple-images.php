<?php
/*
Class Name: converio_extMultipleImages
Description: Enables multiple featured images for posts and pages
Version: 0.1
*/

if( !class_exists( 'converio_extMultipleImages' ) ) {
    
    class converio_extMultipleImages {
        
        private $options            = array( // Options of a featured image for posts and pages
            'id'        => '',      // Set this to unique id for featured image
            'type'      => '',      // Set this to post or page, default is post
            'titles'    => array(
                'main'      => '',     // Set this to main title for featured image
                'sub'       => '',     // Set this to title of ajax popup for featured image
                'remove'    => '',     // Set this to title of remove link for featured image
                'use'       => '',     // Set this to title of link to use as featured image
            )
        );
        
        private $metabox_id         = ''; // Uniqued id to register a featured image to meta box
        private $post_meta_key      = ''; // Uniqued key be saved to database for featured image
        private $nonce              = ''; // Nonce key
        
        private static $images_in_type = array( // ID Array of multiple featured images for posts and pages
            'post'      => array(), 
            'page'      => array()
        );
        
        private $default_options    = array( // Default options of a featured image for posts and pages, deafult is post
            'id'        => 'featured-image-2',              // default unique id for featured image
            'type'      => 'post',                          // default page type, is post
            'titles'    => array(
                'main'      => 'Featured Image 2',          // default main title for featured image
                'sub'       => 'Set featured image 2',      // default title of ajax popup for featured image
                'remove'    => 'Remove featured image 2',   // default title of remove link for featured image
                'use'       => 'Use as featured image 2',   // default title of link to use as featured image
            )
        );
        
        private static $js_write    = false; // Flag to call only one time javascript for each initialize
        
        /**
         * Initialize the class
         * 
         * @param array $options
         * @return void
         */
        public function __construct( $options ) {
            $this->options['titles'] = wp_parse_args( $options['titles'], $this->default_options['titles'] );
            unset( $options['titles'] );
            unset( $this->default_options['titles'] );
            $options = wp_parse_args( $options, $this->default_options );
            $this->options['id'] = $options['id'];
            $this->options['type'] = empty($options['type']) ? 'post' : $options['type'];
            
            $this->metabox_id = $this->options['id'].'_'.$this->options['type'];
            $this->post_meta_key = 'ext_'.$this->options['id'].'_'.$this->options['type'].'_id';
            $this->nonce = 'ext-'.$this->options['id'].$this->options['type'];
            
            array_push(converio_extMultipleImages::$images_in_type[$this->options['type']], $this->options['id']);
            
            if( !current_theme_supports( 'post-thumbnails' ) ) {
                add_theme_support( 'post-thumbnails' );
            }

            if (!converio_extMultipleImages::$js_write) {
                converio_extMultipleImages::$js_write = true;
                add_filter( 'admin_head', array( &$this, 'ext_admin_init' ));
            }
            add_action( 'add_meta_boxes', array( &$this, 'ext_add_meta_box' ) );
            add_filter( 'attachment_fields_to_edit', array( &$this, 'ext_add_attachment_field' ), 11, 2 );
            add_action( 'wp_ajax_set-MuFeaImg-'.$this->options['id'].'-'.$this->options['type'], array( &$this, 'ext_ajax_set_image' ) );
            add_action( 'delete_attachment', array( &$this, 'ext_delete_attachment' ) );
        }        
        
        /**
         * Add admin-Javascript
         * 
         * @return void 
         */
        public function ext_admin_init() {
            ?>
            <script type="text/javascript">
            function extMuFeaImgSet( id, featuredImageID, post_type, nonce ) {
                var $link = jQuery( 'a#' + featuredImageID + '-featuredimage' );

                $link.text( setPostThumbnailL10n.saving );

                jQuery.post( ajaxurl, {
                    action: 'set-MuFeaImg-' + featuredImageID + '-' + post_type,
                    post_id: post_id,
                    thumbnail_id: id,
                    _ajax_nonce: nonce,
                    cookie: encodeURIComponent(document.cookie)
                }, function( str ) {
                    if( str == '0' ) {
                        alert( setPostThumbnailL10n.error );
                    }
                    else {
                        var win = window.dialogArguments || opener || parent || top;

                        $link.show().text( setPostThumbnailL10n.done );

                        $link.fadeOut( 'slow', function() {
                            jQuery('tr.MuFeaImg-' + featuredImageID + '-' + post_type ).hide();
                        });

                        win.extMuFeaImgSetBoxContent( str, featuredImageID, post_type );
                        win.extMuFeaImgSetMetaValue( id, featuredImageID, post_type );
                    }
                });
            }
            function extMuFeaImgRemove ( featuredImageID, post_type, nonce ) {
                jQuery.post( ajaxurl, {
                    action: 'set-MuFeaImg-' + featuredImageID + '-' + post_type,
                    post_id: jQuery('#post_ID').val(),
                    thumbnail_id: -1,
                    _ajax_nonce: nonce,
                    cookie: encodeURIComponent(document.cookie)
                }, function( str ) {
                    if( str == '0' ) {
                        alert( setPostThumbnailL10n.error );
                    }
                    else {
                        extMuFeaImgSetBoxContent( str, featuredImageID, post_type );
                    }   
                });
            }
            function extMuFeaImgSetBoxContent( content, featuredImageID, post_type ) {
                jQuery( '#' + featuredImageID + '_' + post_type + ' .inside' ).html( content );
            }
            function extMuFeaImgSetMetaValue( id, featuredImageID, post_type ) {
                var field = jQuery('input[value=ext_' + featuredImageID + '_' + post_type + '_id]', '#list-table');
                if ( field.size() > 0 ) {
                    jQuery('#meta\\[' + field.attr('id').match(/[0-9]+/) + '\\]\\[value\\]').text( id );
                }    
            }
            </script>
            <?php
        }
 
        /**
         * Add admin metabox for choosing additional featured images
         * 
         * @return void 
         */
        public function ext_add_meta_box() {
            /*
             * remove_meta_box('postimagediv', null, 'side');
             * It has default featured image on all wordpress themes.
             * If you don't want to use it, you can call remove_meta_box function like above thing.
             */
            add_meta_box(
                    $this->metabox_id,
                    $this->options['titles']['main'],
                    array( $this, 'ext_meta_box_content' ),
                    $this->options['type'],
                    'side',
                    'low'
            );
        }

        /**
         * Output the metabox content
         * 
         * @global object $post 
         * @return void
         */
        public function ext_meta_box_content() {
            global $post;
            
            $image_id = get_post_meta( 
                    $post->ID,
                    $this->post_meta_key,
                    true
            );
            
           echo $this->ext_meta_box_output( $image_id );
        }


        /**
         * Generate the metabox content
         * 
         * @global int $post_ID
         * @param int $image_id
         * @return string 
         */
        public function ext_meta_box_output( $image_id = NULL ) {
            global $post_ID;
            
            $output = '';
            
            $setImageLink = sprintf(
                    '<p class="hide-if-no-js"><a title="%2$s" href="%1$s" id="ext_%3$s" class="thickbox">%%s</a></p>',
                    get_upload_iframe_src( 'image' ),
                    $this->options['titles']['sub'],
                    $this->options['id']
            );
            
            if( $image_id && get_post( $image_id ) ) {
                $nonce_field = wp_create_nonce( $this->nonce.$post_ID );
                
                $thumbnail = wp_get_attachment_image( $image_id, array( 266, 266 ) );
                $output.= sprintf( $setImageLink, $thumbnail );
                $output.= '<p class="hide-if-no-js">';
                $output.= sprintf(
                        '<a href="#" id="remove-%1$s-image" onclick="extMuFeaImgRemove( \'%1$s\', \'%2$s\', \'%3$s\' ); return false;">',
                        $this->options['id'],
                        $this->options['type'],
                        $nonce_field
                );
                $output.= $this->options['titles']['remove'];
                $output.= '</a>';
                $output.= '</p>';
                
                return $output;
            }
            else {
                return sprintf( $setImageLink, $this->options['titles']['sub'] );
            }
                
        }
        
        /**
         * Create a new field in the image upload form
         * 
         * @param string $form_fields
         * @param object $post
         * @return string 
         */
        public function ext_add_attachment_field( $form_fields, $post ) {
            $calling_id = 0;
            if( isset( $_GET['post_id'] ) ) {
                $calling_id = absint( $_GET['post_id'] );
            }
            elseif( isset( $_POST ) && count( $_POST ) ) {
                $calling_id = $post->post_parent;
            }
            
            $calling_post = get_post( $calling_id );
            
            if( is_null( $calling_post ) || $calling_post->post_type != $this->options['type'] ) {
                return $form_fields;
            }
            
            $nonce_field = wp_create_nonce( $this->nonce.$calling_id );

            $output = sprintf(
                    '<a href="#" id="%1$s-featuredimage" onclick="extMuFeaImgSet( %3$s, \'%1$s\', \'%2$s\', \'%6$s\' ); return false;">%5$s</a>',
                    $this->options['id'],
                    $this->options['type'],
                    $post->ID,
                    $this->options['titles']['main'],
                    $this->options['titles']['use'],
                    $nonce_field
            );
            
            $form_fields['MuFeaImg-'.$this->options['id'].'-'.$this->options['type']] = array(
                'label' => $this->options['titles']['main'],
                'input' => 'html',
                'html'  => $output
            );
            
            return $form_fields;            
        }
        
        /**
         * Ajax function: set and delete featured image
         * 
         * @global int $post_ID 
         * @return void
         */
        public function ext_ajax_set_image() {
            global $post_ID;
            
            $post_ID = intval( $_POST['post_id'] );
            
            if( !current_user_can( 'edit_post', $post_ID ) ) {
                die( '-1' );
            }
            
            $thumb_id = intval( $_POST['thumbnail_id'] );
            
            check_ajax_referer( $this->nonce.$post_ID );
            
            if( $thumb_id == '-1' ) {
                delete_post_meta( $post_ID, $this->post_meta_key );
                
                die( $this->ext_meta_box_output( NULL ) );
            }
            
            if( $thumb_id && get_post( $thumb_id) ) {
                $thumb_html = wp_get_attachment_image( $thumb_id, 'thumbnail' );
                
                if( !empty( $thumb_html ) ) {
                    update_post_meta( $post_ID, $this->post_meta_key, $thumb_id );
                    
                    die( $this->ext_meta_box_output( $thumb_id ) );
                }
            }
            
            die( '0' );
            
        }
        
        /**
         * Delete the featured image if attachmet is deleted
         * 
         * @global object $wpdb
         * @param int $post_id 
         * @return void
         */
        public function ext_delete_attachment( $post_id ) {
            global $wpdb;

            $wpdb->query( 
                    $wpdb->prepare( 
                            "DELETE FROM $wpdb->postmeta WHERE meta_key = '%s' AND meta_value = %d", 
                            $this->post_meta_key, 
                            $post_id 
                    )
            );
        }
        
        /**
         * Retrieve the id array of all featured images of current posts or pages
         * 
         * @param string $post_type : post or page
         * @param int $post_id : id of page a user visited, default is null
         * @return array : if $post_id is null, return array of unique id of featured image, if not, return array of attachment id of featured image
         */
        public static function get_featured_image_ids( $post_type, $post_id = NULL) {
            $image_ids = self::$images_in_type[$post_type];
            if( is_null( $post_id ) ) {
                return $image_ids;
            }
            
            $ids = array();
            foreach ($image_ids as $image_id) {
                $ids[] = self::get_featured_image_id( $image_id, $post_type, $post_id);
            }
            
            return $ids;
        }
        
        /**
         * Retrieve the url array of all featured images of current posts or pages
         * 
         * @param string $post_type : post or page
         * @param string $size : full or thumbnail, default is full
         * @param int $post_id : id of page a user visited, default is null
         * @return array : url array of all featured image of current posts or pages
         */
        public static function get_featured_image_urls( $post_type, $size = 'full', $post_id = NULL ) {            
            $image_ids = self::$images_in_type[$post_type];
            
            $urls = array();
            foreach ($image_ids as $image_id) {
                $urls[] = self::get_featured_image_url( $image_id, $post_type, $size, $post_id);
            }
            
            return $urls;
        }
        
        /**
         * Retrieve the attachment id of current featured image
         * 
         * @param string $image_id : unique id of featured image
         * @param string $post_type : post or page
         * @param int $post_id : id of page a user visited, default is null
         * @return int : attachment id of featured image
         */
        public static function get_featured_image_id( $image_id, $post_type, $post_id = NULL) {
            if( is_null( $post_id ) ) {
                $post_id = get_the_ID();
            }
            
            return get_post_meta( $post_id, "ext_{$image_id}_{$post_type}_id", true);
        }
        
        /**
         * Retrieve the url of current featured image
         * 
         * @param string $image_id : unique id of featured image
         * @param string $post_type : post or page
         * @param int $post_id : id of page a user visited, default is null
         * @return int : url of featured image
         */
        public static function get_featured_image_url( $image_id, $post_type, $size = 'full', $post_id = NULL ) {
            $id = self::get_featured_image_id( $image_id, $post_type, $post_id);
            
            if( $size != 'full' ) {
            	$url = wp_get_attachment_image_src( $id, $size );
            	$url = $url[0];
            }
            else {
            	$url = wp_get_attachment_url( $id );
            }

            return $url;
        }
        
        /**
         * Retrieve the current featured image
         * 
         * @param string $image_id : unique id of featured image
         * @param string $post_type : post or page
         * @param string $size : full or thumbnail, default is full
         * @param int $post_id : id of page a user visited, default is null
         * @return string : image html
         */
        public static function get_the_featured_image( $image_id, $post_type, $size = 'full', $post_id = NULL ) {
            $id = self::get_featured_image_id( $image_id, $post_type, $post_id);

            $output = '';
            
            if( $id ) {
                $output = wp_get_attachment_image(
                        $id,
                        $size,
                        false
                );
            }
            
            return $output;
        }
        
        /**
         * Output the all featured images html of current  posts or pages
         * 
         * @param string $post_type : post or page
         * @param string $size : full or thumbnail, default is full
         * @param int $post_id : id of page a user visited, default is null
         * @return void
         */
        public static function the_featured_images( $post_type, $size = 'full', $post_id = NULL ) {
            $image_ids = self::$images_in_type[$post_type];
            
            if( !empty($image_ids) ) {
                foreach ($image_ids as $image_id) {
                    self::get_the_featured_image($image_id, $post_type, $size, $post_id);
                }
            }
            
        }
        
        /**
         * Output the featured image html output
         * 
         * @param string $image_id : unique id of featured image
         * @param string $post_type : post or page
         * @param string $size : full or thumbnail, default is full
         * @param int $post_id  : id of page a user visited, default is null
         * @return void
         */
        public static function the_featured_image( $image_id, $post_type, $size = 'full', $post_id = NULL ) {
            echo self::get_the_featured_image( $image_id, $post_type, $size, $post_id );
        }
    }
}

if( class_exists( 'converio_extMultipleImages' ) ) { // Check whether class converio_extMultipleImages exist or not.
    
if ( ! function_exists( 'converio_ext_get_featured_image_ids' ) ) { // Check whether function converio_ext_get_featured_image_ids exist or not.

    function converio_ext_get_featured_image_ids( $post_type, $post_id = NULL ) {
        return converio_extMultipleImages::get_featured_image_ids( $post_type, $post_id );
    }

}

if ( ! function_exists( 'ext_get_featured_image_urls' ) ) { // Check whether function ext_get_featured_image_urls exist or not.
    
    function ext_get_featured_image_urls( $post_type, $size = 'full', $post_id = NULL ) {
        
        return converio_extMultipleImages::get_featured_image_urls( $post_type, $size, $post_id );
        
    }
    
}
    
if ( ! function_exists( 'converio_ext_get_featured_image_id' ) ) { // Check whether function converio_ext_get_featured_image_id exist or not.

    function converio_ext_get_featured_image_id( $image_id, $post_type, $post_id = NULL ) {
        return converio_extMultipleImages::get_featured_image_id( $image_id, $post_type, $post_id );
    }

}

if ( ! function_exists( 'ext_get_featured_image_url' ) ) { // Check whether function ext_get_featured_image_url exist or not.
    
    function ext_get_featured_image_url( $image_id, $post_type, $size = 'full', $post_id = NULL ) {
        
        return converio_extMultipleImages::get_featured_image_url( $image_id, $post_type, $size, $post_id );
        
    }
    
}

if ( ! function_exists( 'ext_get_the_featured_image' ) ) { // Check whether function ext_get_featured_image_url exist or not.
    
    function ext_get_the_featured_image( $image_id, $post_type, $size = 'full', $post_id = NULL ) {
        
        return converio_extMultipleImages::get_the_featured_image( $image_id, $post_type, $size, $post_id );
        
    }
    
}

if ( ! function_exists( 'converio_ext_the_featured_images' ) ) { // Check whether function converio_ext_the_featured_images exist or not.
    
    function converio_ext_the_featured_images( $post_type, $size = 'full', $post_id = NULL ) {
        
        return converio_extMultipleImages::the_featured_images( $post_type, $size, $post_id );
        
    }
    
}

if ( ! function_exists( 'converio_ext_the_featured_image' ) ) { // Check whether function converio_ext_the_featured_image exist or not.
    
    function converio_ext_the_featured_image( $image_id, $post_type, $size = 'full', $post_id = NULL ) {
        
        return converio_extMultipleImages::the_featured_image( $image_id, $post_type, $size, $post_id );
        
    }
    
}
    
}

$args = array(
        'id' => 'featured-image-2', // Set this to unique id for featured image
        'type' => 'post',           // Set this to post or page, default is post
        'titles' => array(
            'main'      => 'Featured image 2',          // Set this to main title for featured image
            'sub'       => 'Set featured image 2',      // Set this to title of ajax popup for featured image
            'remove'    => 'Remove featured image 2',   // Set this to title of remove link for featured image
            'use'       => 'Use as featured image 2',   // Set this to title of link to use as featured image
        )
);
new converio_extMultipleImages( $args );
//uncomment if needed on Page
//$args['type'] = 'page';
//new converio_extMultipleImages( $args );

$args = array(
        'id' => 'featured-image-3', // Set this to unique id for featured image
        'type' => 'post',           // Set this to post or page, default is post
        'titles' => array(
            'main'      => 'Featured image 3',          // Set this to main title for featured image
            'sub'       => 'Set featured image 3',      // Set this to title of ajax popup for featured image
            'remove'    => 'Remove featured image 3',   // Set this to title of remove link for featured image
            'use'       => 'Use as featured image 3',   // Set this to title of link to use as featured image
        )
);
new converio_extMultipleImages( $args );
//uncomment if needed on Page
//$args['type'] = 'page';
//new converio_extMultipleImages( $args );

?>
