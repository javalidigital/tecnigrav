<?php

/*

Copyright 2014 Marcin Pietrzak (marcin@iworks.pl)

this program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */

if ( !defined( 'WPINC' ) ) {
    die;
}

if ( class_exists( 'IworksEvents' ) ) {
    return;
}

class IworksEvents
{
    private $dev;
    private $options;
    private $admin_menu_slug;
    private $base;
    private $capability;
    private $dir;
    private $hours_name;
    private $noncename;
    private $post_type;
    private $version;
    private $taxonomy_category;
    private $permalink;
    private static $_post_type;

    public function __construct()
    {
        /**
         * static settings
         */
        $this->base = dirname( dirname( __FILE__ ) );
        $this->dir = basename( dirname( $this->base ) );
        $this->admin_menu_slug = $this->dir.'/admin';

        $this->capability = apply_filters( 'iworks_events_capability', 'edit_posts' );
        $this->dev = ( defined( 'IWORKS_DEV_MODE' ) && IWORKS_DEV_MODE )? '.dev':'';
        $this->noncename = __CLASS__;
        $this->permalink = 'events';
        $this->post_type = 'events';
        $this->version = '1.0';
        self::$_post_type = $this->post_type;

        /**
         * taxonmies
         */
        $this->taxonomy_category = new iWorks_Taxonomy_Category( $this );

        /**
         * generate
         */
        add_action( 'init', array( &$this, 'init' ) );
        add_action( 'init', array( &$this, 'register_post_type' ), 0 );
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_post' ) );
        add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
        add_filter( 'post_class', array( $this, 'post_class' ) );
        add_filter( 'iworks_events_show_metabox_help', '__return_false');
        add_action( 'admin_head-nav-menus.php', array( $this, 'add_filters' ) );
        add_filter( 'rewrite_rules_array', array( $this, 'add_rewrite_rules') );
        add_action( 'widgets_init', array($this, 'widgets_init' ) );

        /**
         * global option object
         */
        global $iworks_events_options;
        $this->options = $iworks_events_options;

        /**
         * shortcode
         */
        new IworksEventsShortcodeEvents($this);
    }

    /**
     * widgets
     */
    public function widgets_init()
    {
        if($this->get_option('widget_calendar')) {
            require_once dirname(__FILE__).'/events/widget/calendar.php';
            register_widget('iworks_events_widget_calendar');
        }
    }

    public function add_rewrite_rules($rewrite_rules)
    {
        $new_rules = array(
            $this->post_type.'/([0-9]{4})/?$' => 'index.php?post_type='.$this->post_type.'&year=$matches[1]',
            $this->post_type.'/([0-9]{4})/([0-9]{2})/?$' => 'index.php?post_type='.$this->post_type.'&year=$matches[1]&monthnum=$matches[2]',
            $this->post_type.'/([0-9]{4})/([0-9]{2})/([0-9]{2})/?$' => 'index.php?post_type='.$this->post_type.'&year=$matches[1]&monthnum=$matches[2]&day=$matches[3]',
        );
        return $new_rules+$rewrite_rules;
    }

    public function add_filters()
    {
        add_filter( 'nav_menu_items_' . $this->post_type, array( $this, 'add_archive_checkbox' ), null, 3 );
    }

    public function add_archive_checkbox($posts, $args, $post_type)
    {
        global $_nav_menu_placeholder, $wp_rewrite;
        $_nav_menu_placeholder = ( 0 > $_nav_menu_placeholder ) ? intval($_nav_menu_placeholder) - 1 : -1;

        //dump( $post_type, '$post_type', 'htmlcomment' );

        $archive_slug = $post_type['args']->has_archive === true ? $post_type['args']->rewrite['slug'] : $post_type['args']->has_archive;
        if ( $post_type['args']->rewrite['with_front'] )
            $archive_slug = substr( $wp_rewrite->front, 1 ) . $archive_slug;
        else
            $archive_slug = $wp_rewrite->root . $archive_slug;

        array_unshift( $posts, (object) array(
            'ID' => 0,
            'object_id' => $_nav_menu_placeholder,
            'post_content' => '',
            'post_excerpt' => '',
            'post_title' => $post_type['args']->labels->all_items,
            'post_type' => 'nav_menu_item',
            'type' => 'custom',
            'url' => site_url( $archive_slug ),
        ) );

        return $posts;
    }

    public function post_class($classes)
    {
        if ( $this->post_type != get_post_type() ) {
            return $classes;
        }
        $classes[] = 'post';
        foreach( explode( ' ', $this->taxonomy_category->get_terms(0, 'slugs') ) as $slug ) {
            $classes[] = $slug;
        }
        /**
         * add class is past or upcoming event
         */

        if ( intval( $this->get_post_meta(get_the_ID(), 'date') ) > time() ) {
            $classes[] = 'event-upcoming';
        } else {
            $classes[] = 'event-past';
        }
        /**
         * return $classes
         */
        return $classes;
    }

    public function get_version($file = null)
    {
        if ( defined( 'IWORKS_DEV_MODE' ) && IWORKS_DEV_MODE ) {
            if ( null != $file ) {
                $file = dirname( $this->base ). $file;
                if ( is_file( $file ) ) {
                    return md5_file( $file );
                }
            }
            return rand( 0, PHP_INT_MAX );
        }
        return $this->version;
    }

    public function init()
    {
        /**
         * actions & filters
         */
        add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ) );
        add_action( 'admin_init', 'iworks_events_options_init' );
        add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
        add_action( 'dashboard_glance_items', array( &$this, 'dashboard_glance_items' ) );
        add_action( 'right_now_content_table_end', array( &$this, 'right_now_content_table_end' ) );
        add_filter( 'pre_get_posts', array( &$this, 'pre_get_posts') );
        add_filter( 'template_include', array( $this, 'template_include' ), PHP_INT_MAX );
        add_filter( 'manage_'.$this->post_type.'_posts_columns', array($this, 'manage_posts_columns'));
        add_action( 'manage_'.$this->post_type.'_posts_custom_column', array($this, 'manage_posts_custom_column'), 10, 2 );
        add_filter( 'manage_edit-'.$this->post_type.'_sortable_columns', array($this, 'sortable_columns' ) );
        add_filter( 'request', array($this, 'request' ) );
        /**
         * register
         */
        $file = 'scripts/iworks-events-admin.js';
        wp_register_script(
            $this->slug_name('edit'),
            plugin_dir_url($this->base).$file,
            array('jquery', 'jquery-ui-datepicker' ),
            $this->get_version(dirname($this->base).'/'.$file)
        );
    }

    public function request($vars)
    {
        if ( array_key_exists('orderby', $vars) && 'EventDate' == $vars['orderby'] ) {
            $vars = array_merge( $vars, array(
                'meta_key' => $this->get_post_meta_name('date'),
                'orderby' => 'meta_value'
            ) );
        }
        return $vars;
    }

    public function sortable_columns($columns)
    {
        $columns[$this->add_prefix('date')] = array(
            __('Event Date', 'iworks_events'),
            array_key_exists('order', $_GET) && 'asc' == $_GET['order']
        );
        return $columns;
    }

    public function manage_posts_custom_column($column_name, $post_id)
    {
        if ( $this->add_prefix('date' ) == $column_name ) {
            $date = intval( $this->get_post_meta($post_id, 'date') );
            if ( empty( $date ) ) {
                echo '-';
            } else {
                echo date_i18n( get_option( 'date_format' ), $date );
            }
        }
    }

    public function manage_posts_columns($columns)
    {
        $columns[$this->add_prefix('date')] = __('Event Date', 'iworks_events');
        return $columns;
    }

    private function add_prefix($string)
    {
        return sprintf( '%s%s', IWORKS_EVENTS_PREFIX, $string);
    }

    public function template_include($template)
    {
        /**
         * single event
         */
        if ( is_singular($this->post_type) ) {
            $in_theme_template = locate_template(
                array(
                    sprintf(
                        'single-%s.php',
                        $this->post_type
                    )
                )
            );
            if ( empty( $in_theme_template ) ) {
                $in_plugin_template = sprintf(
                    '%s/templates/twentyfourteen/single-%s.php',
                    dirname( $this->base ),
                    $this->post_type
                );
                if ( is_file( $in_plugin_template ) && is_readable( $in_plugin_template ) ) {
                    add_action('iworks_events_alert_template', array( $this, 'show_alert_template' ));
                    return $in_plugin_template;
                }
            }
        }

        /**
         * custom post type archive for events
         */
        else if( is_post_type_archive( $this->post_type ) ) {
            $in_theme_template = locate_template(
                array(
                    sprintf(
                        'archive-%s.php',
                        $this->post_type
                    )
                )
            );
            if ( empty( $in_theme_template ) ) {
                $in_plugin_template = sprintf(
                    '%s/templates/twentyfourteen/archive-%s.php',
                    dirname( $this->base ),
                    $this->post_type
                );
                if ( is_file( $in_plugin_template ) && is_readable( $in_plugin_template ) ) {
                    add_action('iworks_events_alert_template', array( $this, 'show_alert_template' ));
                    return $in_plugin_template;
                }
            }
        }

        /**
         * custom taxonomy for event category
         */
        else if ( is_tax( $this->taxonomy_category->get_taxonomy_name() ) ) {
            global $term;
            if ( empty( $term ) ) {
                $term = get_term( $this->get_option('archive'), $this->taxonomy_category->get_taxonomy_name() );
                $term = $term->slug;
            }
            /**
             * theme template for certain taxonumy
             */
            $in_theme_template = locate_template(
                array(
                    sprintf(
                        'taxonomy-%s-%s.php',
                        $this->taxonomy_category->get_taxonomy_name(),
                        $term
                    )
                )
            );
            if ( !empty( $in_theme_template ) ) {
                return $in_theme_template;
            }
            /**
             * theme template for ALL taxonomies
             */
            $in_theme_template = locate_template(
                array(
                    sprintf(
                        'taxonomy-%s.php',
                        $this->taxonomy_category->get_taxonomy_name()
                    )
                )
            );
            if ( !empty( $in_theme_template ) ) {
                return $in_theme_template;
            }
            /**
             * plugin template for all taxonomies
             */
            $in_plugin_template = sprintf(
                '%s/templates/twentyfourteen/taxonomy-%s.php',
                dirname( $this->base ),
                $this->taxonomy_category->get_taxonomy_name()
            );
            if ( is_file( $in_plugin_template ) && is_readable( $in_plugin_template ) ) {
                add_action('iworks_events_alert_template', array( $this, 'show_alert_template' ));
                return $in_plugin_template;
            }
        }

        /**
         * return default value
         */
        return $template;
    }

    public function dashboard_glance_items($elements)
    {
        $num_posts = wp_count_posts( $this->post_type );
        $text = _n( '%s Event', '%s Events', $num_posts->publish, 'iworks_events' );
        $text = sprintf( $text, number_format_i18n( $num_posts->publish ) );
        $elements[] = sprintf(
            '<a class="iworks-events" href="%s">%s</a>',
            add_query_arg( array( 'post_type'=>$this->post_type ), 'edit.php' ),
            $text
        );
        return $elements;
    }

    public function admin_init()
    {
        add_filter( 'plugin_row_meta', array( &$this, 'plugin_row_meta' ), 10, 2 );
        if ( $this->get_option( 'flush_rules' ) ) {
            flush_rewrite_rules();
            $this->options->update_option( 'flush_rules', false );
        }
    }

    public function admin_enqueue_scripts()
    {
        $screen = get_current_screen();
        $re = '/^(' . join( '|', array( $this->dir, 'edit-events', 'events', 'dashboard' ) ) . ')/';
        if ( isset( $screen->id ) && preg_match( $re, $screen->id ) ) {
            /**
             * enqueue resources
             */
            $this->enqueue_style( 'iworks-events-admin' );
        }
        if ( 'post' == $screen->base && $screen->post_type == $this->post_type ) {
            wp_enqueue_script( $this->slug_name('edit') );
            $this->enqueue_style( 'iworks-events-admin' );
            wp_enqueue_style( 'jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/overcast/jquery-ui.min.css' );
        }
    }

    public function plugin_row_meta($links, $file)
    {
        if ( $this->dir.'/iworks-events.php' == $file ) {
            if ( !is_multisite() && current_user_can( $this->capability ) ) {
                $links[] = '<a href="admin.php?page='.$this->dir.'/admin/index.php">' . __( 'Settings' ) . '</a>';
            }
        }
        return $links;
    }

    public function update()
    {
        $version = $this->get_option( 'version', 0 );
        if ( 0 < version_compare( $this->version, $version ) ) {
            $this->options->update_option( 'version', $this->version );
        }
    }

    /**
     * Add admin menu
     */
    public function admin_menu()
    {
        $parent = sprintf('edit.php?post_type=%s', $this->post_type);
        add_submenu_page( $parent, __( 'Settings', 'iworks_events' ), __( 'Settings', 'iworks_events' ), $this->capability, $this->dir.'/admin/index.php' );
    }

    public function get_admin_menu_slug($sufix = null)
    {
        if ( empty( $sufix ) ) {
            return $this->admin_menu_slug;
        }
        if ( is_file( $sufix ) ) {
            return sprintf(
                '%s/%s',
                $this->admin_menu_slug,
                basename( $sufix )
            );
        }
        return sprintf(
            '%s%s',
            $this->admin_menu_slug,
            $sufix
        );
    }

    /**
     * custom post type
     */

    public function register_post_type()
    {
        /**
         * posts types
         */
        $labels = array(
            'name' => __( 'Events', 'iworks_events' ),
            'singular_name' => __( 'Event', 'iworks_events' ),
            'add_new' => __( 'Add New', 'iworks_events' ),
            'add_new_item' => __( 'Add New Event', 'iworks_events' ),
            'edit_item' => __( 'Edit Event', 'iworks_events' ),
            'new_item' => __( 'New Event', 'iworks_events' ),
            'all_items' => __( 'All Events', 'iworks_events' ),
            'view_item' => __( 'View Event', 'iworks_events' ),
            'search_items' => __( 'Search Events', 'iworks_events' ),
            'not_found' =>  __( 'No events found', 'iworks_events' ),
            'not_found_in_trash' => __( 'No events found in Trash', 'iworks_events' ),
            'parent_item_colon' => '',
            'menu_name' => __( 'Events', 'iworks_events' ),
        );
        $args = array(
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'labels' => $labels,
            'label' => __( 'Event', 'iworks_events' ),
            'menu_position' => null,
            'publicly_queryable' => true,
            'public' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => $this->permalink ),
            'show_in_menu' => true, //$this->admin_menu_slug,
            'show_ui' => true,
            'supports' => array( 'title', 'thumbnail', 'excerpt', 'editor', 'revisions' ),
            'show_in_admin_bar' => true,
        );
        register_post_type( $this->post_type, $args );

        /**
         * taxonomies
         */
        $this->taxonomy_category->register();

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
     * avoid change content if singe-${post_type}.php file exists
     */
    private function check_single_event_template()
    {
        $file = sprintf( '%s/single-%s.php', get_template_directory(), $this->post_type );
        return is_file($file) && is_readable( $file );
    }

    /**
     * Adds the meta box container
     */
    public function add_meta_boxes()
    {
        add_meta_box(
            'people_tagline',
            __( 'Event misc', 'iworks_events' ),
            array( &$this, 'render_meta_box_misc' ),
            $this->post_type,
            'advanced',
            'high'
        );
    }

    public function render_meta_box_misc()
    {
        wp_nonce_field( plugin_basename( __FILE__ ), $this->noncename );
        echo '<table class="form-table">';

        $date = intval( $this->get_post_meta(get_the_ID(), 'date') );
        if ( empty( $date ) ) {
            $date = strtotime( 'now' );
        }
        echo '<tr>';
        printf( '<th>%s</th>', __( 'Date', 'iworks_events' ) );
        printf( '<td><input type="text" class="datepicker" name="%s" value="%s"/></td>', $this->get_post_meta_name('date'), date_i18n( get_option( 'date_format' ), $date ) );
        echo '</tr>';

        echo '<tr>';
        printf( '<th>%s</th>', __( 'Hour', 'iworks_events' ) );
        printf( '<td><input type="time" name="%s" value="%s"/></td>', $this->get_post_meta_name('hour'), $this->get_post_meta(get_the_ID(), 'hour') );
        echo '</tr>';

        echo '<tr>';
        printf( '<th>%s</th>', __( 'Place', 'iworks_events' ) );
        $this->td_text('place');
        echo '</tr>';

        echo '<tr>';
        printf( '<th>%s</th>', __( 'City', 'iworks_events' ) );
        $this->td_text('city');
        echo '</tr>';

        echo '<tr>';
        printf( '<th>%s</th>', __( 'Address', 'iworks_events' ) );
        $this->td_text('address');
        echo '</tr>';

        echo '</table>';
    }

    private function td_text($name)
    {
        printf( '<td><input type="text" name="%s" value="%s" class="widefat" /></td>', $this->get_post_meta_name($name), $this->get_post_meta(get_the_ID(), $name ) );
    }

    public function get_post_meta_name($name)
    {
        return preg_replace( '/[^\w]+/', '_', strtolower( 'ie_' . $name ) );
    }

    public function save_post($post_id)
    {
        if ( !isset( $_REQUEST['post_type'] ) ) {
            return;
        }

        if ( $this->post_type != $_REQUEST['post_type'] ) {
            return;
        }

        if ( ! isset( $_POST[$this->noncename] ) || ! wp_verify_nonce( $_POST[$this->noncename], plugin_basename( __FILE__ ) ) ) {
            return;
        }

        $values = array();
        foreach( array( 'date', 'hour', 'place', 'address', 'city' ) as $type ) {
            $name = $this->get_post_meta_name($type);
            if ( array_key_exists( $name, $_POST ) ) {
                $mydata = $_POST[$name];
                $values[$type] = $mydata;
                if ( 'date' == $type ) {
                    $mydata = strtotime( $mydata );
                    $values['date_timestamp'] = $mydata;
                }
                if ( !add_post_meta( $post_id, $name, $mydata, true ) ) {
                    update_post_meta( $post_id, $name, $mydata );
                }
            } else {
                delete_post_meta( $post_id, $name );
            }
        }
        $name = $this->get_post_meta_name( 'timestamp' );
        $mydata = strtotime( sprintf( '%s %s', $values['date'], $values['hour'] ) );
        if ( !add_post_meta( $post_id, $name, $mydata, true ) ) {
            update_post_meta( $post_id, $name, $mydata );
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
        printf( '<a href="%s">%s</>', $link, __( 'peoples', 'iworks_events' ) );
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

    private function slug_name($name)
    {
        return preg_replace( '/[_ ]+/', '-', strtolower( __CLASS__ . '_' . $name ) );
    }

    public function get_post_meta($post_id, $meta_key)
    {
        return get_post_meta( $post_id, $this->get_post_meta_name($meta_key), true );
    }

    private function get_default_args_for_wp_query($posts_per_page = 0, $compare = '>', $date = null)
    {
        remove_filter( 'pre_get_posts', array( &$this, 'pre_get_posts') );
        $args = array(
            'post_type' => $this->post_type,
            'meta_key' => $this->get_post_meta_name('timestamp'),
            'order' => '>' == $compare? 'ASC':'DESC',
            'orderby' => 'meta_value_num',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => $this->get_post_meta_name('timestamp'),
                    'value' => empty($date)? time():$date,
                    'type' => 'numeric',
                    'compare' => $compare,
                ),
            ),
        );
        if ( $posts_per_page ) {
            $args['posts_per_page'] = $posts_per_page;
        }
        return $args;
    }

    /**
     * shortcodes
     */
    public function get_events($type = 'present')
    {
        $content = '<div class="event">';
        /**
         * add categories
         */
        /**
         * past events
         */
        add_filter('posts_orderby', array(&$this, 'posts_orderby' ) );
        $args = $this->get_default_args_for_wp_query();
        if ( 'past' == $type ) {
            $args['meta_query'][0]['compare'] = '<';
        }
        $the_query = new WP_Query( $args );
        // The Loop
        if ( $the_query->have_posts() ) {
            $last_date = '';
            $content .= '<section class="content event main postlist event-page postlist-blog">';
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                $date = intval($this->get_post_meta(get_the_ID(),'date'));
                if ( $date != $last_date ) {
                    $last_date = $date;
                    $content .= '<article class="post groups-title">';
                    $content .= sprintf('<p class="event-day-number">%d</p>', date('d', $last_date ) );
                    $content .= sprintf('<p class="event-day-month">%s <span>%s</span></p>', __(date('F',$last_date)), __(date('l',$last_date)));
                    $content .='</article>';
                }
                $content .= sprintf( '<article class="post %s">', $this->taxonomy_category->get_terms(0, 'slugs') );
                $content .= sprintf('<p class="event-hour">%s</p>', $this->get_post_meta(get_the_ID(), 'hour') );
                $content .= '<div class="post-right">';
                if ( has_post_thumbnail() ) {
                    $content .= sprintf( '<a href="%s">', get_permalink() );
                    $content .= get_the_post_thumbnail( get_the_ID(), 'event-thumb' );
                    $content .= '</a>';
                }
                $content .= sprintf( '<span class="event-category">%s</span>', $this->event_category() );
                $content .= sprintf( '<h2 class="post-healine"><a href="%s">%s</a></h2>', get_permalink(), get_the_title() );
                $content .= sprintf( '<p class="event-place">%s, %s</p>', $this->get_post_meta(get_the_ID(),'place'), $this->get_post_meta(get_the_ID(),'city'));
                $content .= '<!-- czesc dodana przez plugin sharethis -->';
                $content .= '</div>';
                $content .= '</article>';
            }
            $content .= '</section>';
        }
        $content .= '</div>';
        wp_reset_postdata();
        remove_filter('posts_orderby', array(&$this, 'posts_orderby' ) );
        return $content;
    }

    public function get_previous_post_link($post_id)
    {
        return $this->get_post_link($post_id, 'previous');
    }

    public function get_next_post_link($post_id)
    {
        return $this->get_post_link($post_id, 'next');
    }

    private function get_post_link($post_id, $order)
    {
        if ( !preg_match('/^(previous|next)$/', $order ) ) {
            $order = 'next';
        }
        $compare = 'next' == $order ? '>':'<';
        $label = 'next' == $order ? __('Next', 'iworks_events'): __('Previous', 'iworks_events');
        $url = '';
        $date = $this->get_post_meta($post_id, 'date');
        $args = $this->get_default_args_for_wp_query(1, $compare, $date);
        $args['post__not_in'] = array( $post_id );
        add_filter('posts_orderby', array(&$this, 'posts_orderby' ) );
        $the_query = new WP_Query( $args );
        // The Loop
        if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                $url = sprintf(
                    '<li><a class="%s" href="%s">%s</a></li>',
                    $order,
                    get_permalink(),
                    $label
                );
            }
        }
        wp_reset_postdata();
        remove_filter('posts_orderby', array(&$this, 'posts_orderby' ) );
        return $url;
    }

    public function posts_orderby($orderby)
    {
        $orderby = preg_replace( '/,/', ' ASC,', $orderby );
        return $orderby;
    }

    public function get_option($option_name)
    {
        return $this->options->get_option($option_name);
    }

    /**
     * add Upcoming Events
     */

    public function upcoming_events()
    {
        $content = '';
        $args = $this->get_default_args_for_wp_query($this->get_option('upcoming_number'));
        if ( is_singular() ) {
            global $post;
            $args['post__not_in'] = array( $post->ID );
        }
        $the_query = new WP_Query( $args );
        /**
         * query loop
         */
        if ( $the_query->have_posts() ) {
            $content .= sprintf(
                '<section class="%s">',
                apply_filters('events_related_class_section', 'related' )
            );
            $content .= sprintf( '<h2>%s</h2>', __( 'Upcoming events', 'iworks_events' ) );
            $content .= sprintf(
                '<div class="%s">',
                apply_filters('events_related_class_box', 'columns related-box clearfix')
            );
            $content = apply_filters('events_related_content_begin', $content );
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                $article = '';
                $article .= sprintf(
                    '<article class="%s">',
                    apply_filters('events_related_class_post', 'col col3', get_post())
                );
                if ( has_post_thumbnail() ) {
                    $article .= sprintf(
                        '<div class="img"><a href="%s">%s</a></div>',
                        get_permalink(),
                        get_the_post_thumbnail(get_the_ID(), 'event-thumb')
                    );
                }
                $article .= sprintf( '<p class="event-category">%s</p>', $this->event_category() );
                $article .= sprintf( '<h3><a href="%s">%s</a></h3>', get_permalink(), get_the_title() );
                $article .= sprintf(
                    '<p class="event-time">%s<span> %s</span></p>',
                    date_i18n(
                        get_option( 'date_format' ),
                        $this->get_post_meta(get_the_ID(), 'date')
                    ),
                    $this->get_post_meta(get_the_ID(), 'hour' )
                );
                $article .= sprintf( '<p class="event-address">%s</p>', $this->get_post_meta(get_the_ID(), 'address') );
                $article .= '</article>';
                $content .= apply_filters( 'events_related_content_article', $article, get_post() );
            }
            $content .= apply_filters('events_related_content_end', '</div></section>' );
        }
        return $content;
    }

    public function event_category()
    {
        return $this->taxonomy_category->get_terms(1, 'plain');
    }

    public function get_event_category()
    {
        echo $this->event_category();
    }

    public function event_categories()
    {
        return $this->taxonomy_category->get_terms();
    }

    public function get_event_categories()
    {
        echo $this->event_categories();
    }

    public function pre_get_posts($query)
    {
        if ( is_admin() ) {
            return;
        }
        if ( !$query->is_main_query() ) {
            return;
        }
        if (
            $query->is_tax($this->taxonomy_category->get_taxonomy_name())
            or $query->is_post_type_archive(self::$_post_type)
        ) {
            $compare = '>';
            if ( is_tax( $this->taxonomy_category->get_taxonomy_name(), $this->get_option('archive') )) {
                $compare = '<';
                $query->set( $this->taxonomy_category->get_taxonomy_name(), null );
            }
            $query->set(
                'meta_query',
                array(
                    'relation' => 'AND',
                    array(
                        'key' => $this->get_post_meta_name('timestamp'),
                        'value' => time(),
                        'type' => 'numeric',
                        'compare' => $compare,
                    )
                )
            );
            /**
             * add post limit on archive page
             */
            if ( $query->is_post_type_archive(self::$_post_type) ) {
                $query->set('posts_per_page', intval(($this->get_option('events_page_limit'))));
            }
            $query->set( 'meta_key', $this->get_post_meta_name('timestamp') );
            $query->set( 'order', 'ASC' );
            $query->set( 'orderby', 'meta_value_num' );
            /**
             * parse year
             */
            if ( $year = $query->get('year') ) {
                $query->set('year', '');
                $query->set($this->get_post_meta_name('year'), $year);
                $date = array(
                    'start' => array(
                        'year' => $year,
                        'month' => 1,
                        'day' => 1,
                    ),
                    'end' => array(
                        'year' => $year,
                        'month' => 12,
                        'day' => 31,
                    )
                );
                /**
                 * parse month
                 */
                if ( $month = $query->get('monthnum') ) {
                    $query->set('monthnum', '');
                    $query->set($this->get_post_meta_name('monthnum'), $month);
                    $date['start']['month'] = $month;
                    $date['end']['month'] = $month;
                    $date['end']['day'] = zeroise( date('t', strtotime(implode('-',$date['start']))), 2);
                    /**
                     * parse day
                     */
                    if ( $day = $query->get('day') ) {
                        $query->set('day', '');
                        $query->set($this->get_post_meta_name('day'), $day);
                        $date['start']['day'] = $day;
                        $date['end']['day'] = $day;
                    }
                }
                $query->set(
                    'meta_query',
                    array(
                        'relation' => 'AND',
                        array(
                            'key' => $this->get_post_meta_name('timestamp'),
                            'value' => strtotime(implode('-', $date['start']).' 00:00:00'),
                            'type' => 'numeric',
                            'compare' => '>=',
                        ),
                        array(
                            'key' => $this->get_post_meta_name('timestamp'),
                            'value' => strtotime(implode('-', $date['end']).' 23:59:59'),
                            'type' => 'numeric',
                            'compare' => '<=',
                        )
                    )
                );
            }
        }
        /**
         * custom category
         */
        if ( is_tax( $this->taxonomy_category->get_taxonomy_name() ) ) {
            $query->set('posts_per_page', intval(($this->get_option('events_page_limit'))));
        }
    }

    public function after_setup_theme()
    {
        if ( function_exists( 'add_theme_support' ) ) {
            add_theme_support( 'post-thumbnails' );
            $images = array(
                'event-thumb' => array( 'width' =>  560, 'height' => 370, 'crop' =>  true),
                'event-category' => array( 'width' =>  155, 'height' => 155, 'crop' =>  true),
                'event-full' => array( 'width' =>  810, 'height' => 9999, 'crop' =>  false),
            );
            $images = apply_filters( 'iworks_events_thumbnails', $images );
            foreach( $images as $key => $data ) {
                add_image_size( $key, $data['width'], $data['height'], $data['crop'] );
            }
        }
    }

    private function check_show_alert()
    {
        $show = $this->get_option('show_alerts');
        if ( empty( $show ) ) {
            return false;
        }
        if ( current_user_can('manage_options') ) {
            return true;
        }
        return false;
    }

    private function show_alert()
    {
        if ( !$this->check_show_alert() ) {
            return '';
        }
        $content = '<div class="alert" style="border: 1px solid orange; background-color: #ff0; padding: 20px;">';
        $content .= sprintf(
            __( 'Please copy templates from <b>%stemplates/</b> to <b>%s/</b>', 'iworks_events'),
            plugin_dir_path(dirname(dirname(__FILE__))),
            get_template_directory()
        );
        $content .= '</div>';
        return $content;
    }

    private function show_alert_page()
    {
        if ( !$this->check_show_alert() ) {
            return '';
        }
        $content = '<div class="alert" style="border: 1px solid orange; background-color: #ff0; padding: 20px;">';
        $content .= __( 'If you need to use own template, please use <b>event category</b> in menu, not a event page.', 'iworks_events');
        $content .= '</div>';
        return $content;
    }

    public function show_alert_template()
    {
        if ( !$this->check_show_alert() ) {
            return;
        }
        $content = '<div class="alert" style="border: 1px solid orange; background-color: #ff0; padding: 20px;">';
        $content .= sprintf(
            __( 'This page is using template file from plugin directory. If you want to use yuor own templates please copy it from <pre>%s</pre> to <pre>%s/</pre>', 'iworks_events'),
            sprintf( '%s/templates/twentyfourteen/', dirname($this->base) ),
            get_template_directory()
        );
        $content .= '</div>';
        echo $content;
    }

    public function get_all_event_categories_linked()
    {
        return $this->get_all_event_categories('link');
    }

    public function get_all_event_categories($type = 'anchor')
    {
        global $term;
        $content = '';
        $terms = false;
        if ( 'anchor' == $type ) {
            $terms = $this->taxonomy_category->get_used();
        } else {
            $terms = $this->taxonomy_category->get_all('objects');
        }
        if ( !empty( $terms ) ) {
            $content .= '<div class="head">';
            $content .= '<div class="filters">';
            $content .= '<ul>';
            $classes = array('all');
            if (
                is_archive($this->post_type)
                && !is_tax( $this->taxonomy_category->get_taxonomy_name() )
            ) {
                $classes[] = 'selected';
            }
            $content .= sprintf(
                '<li><a class="%s" href="%s">%s</a></li>',
                implode(' ', $classes),
                $this->get_all_link(),
                $this->get_option('all_name')
            );

            foreach ( $terms as $current_term ) {
                $class = '';
                $href = sprintf( '#%s', $current_term->slug );
                if ( 'link' == $type ) {
                    $href = get_term_link( $current_term );
                    if ( $current_term->slug == $term ) {
                        $class = ' class="selected"';
                    }
                }
                $content .= sprintf(
                    '<li><a href="%s"%s>%s</a></li>',
                    $href,
                    $class,
                    $current_term->name
                );
            }
            $content .= '</ul>';
            $content .= '</div>';
            $content .= '</div>';
        }
        return $content;
    }

    public function get_term_by_obj_id($post_id)
    {
        return $this->taxonomy_category->get_term_by_obj_id( $post_id );
    }

    public function select_archive_category($option_name, $show_option_none)
    {
        return $this->options->select_category_helper(
            $option_name,
            null,
            $show_option_none,
            $this->taxonomy_category->get_taxonomy_name()
        );
    }

    public function all_link()
    {
        echo $this->get_all_link();
    }

    public function get_all_link()
    {
        if ('link' == $this->get_option('list_type')) {
            return get_post_type_archive_link($this->post_type);
        }
        return '#all';
    }

    public function get_post_type()
    {
        return $this->post_type;
    }

    public function get_permalink()
    {
        return $this->permalink;
    }

}
