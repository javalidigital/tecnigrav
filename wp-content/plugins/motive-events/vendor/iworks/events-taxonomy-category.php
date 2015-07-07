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

if ( class_exists( 'iWorks_Taxonomy_Category' ) ) {
    return;
}

class iWorks_Taxonomy_Category
{
    private $taxonomy;
    private $post_type;
    private $iworks_event;

    public function __construct($iworks_event)
    {
        $this->iworks_event = $iworks_event;
        $this->post_type = $iworks_event->get_this_post_type();
        $this->taxonomy = 'event_category';
        add_filter( 'wp_title', array( $this, 'wp_title' ), 10, 3 );
    }

    public function get_taxonomy_name()
    {
        return $this->taxonomy;
    }

    public function register()
    {
        $labels = array(
            'name'                       => _x( 'Event Categories', 'taxonomy general name', 'iworks_event' ),
            'singular_name'              => _x( 'Event Category', 'taxonomy singular name', 'iworks_event' ),
            'search_items'               => __( 'Search Event Categories', 'iworks_event' ),
            'popular_items'              => __( 'Popular Event Categories', 'iworks_event' ),
            'all_items'                  => __( 'All Event Categories', 'iworks_event' ),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => __( 'Edit Event Category', 'iworks_event' ),
            'update_item'                => __( 'Update Event Category', 'iworks_event' ),
            'add_new_item'               => __( 'Add New Event Category', 'iworks_event' ),
            'new_item_name'              => __( 'New Event Category Name', 'iworks_event' ),
            'separate_items_with_commas' => __( 'Separate places with commas', 'iworks_event' ),
            'add_or_remove_items'        => __( 'Add or remove places', 'iworks_event' ),
            'choose_from_most_used'      => __( 'Choose from the most used event categories', 'iworks_event' ),
            'not_found'                  => __( 'No event categories found.', 'iworks_event' ),
            'menu_name'                  => __( 'Event Categories', 'iworks_event' ),
        );
        $args = array(
            'hierarchical'          => true,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
            'rewrite'               => array( 'slug' => 'ecat' ),
        );
        register_taxonomy( $this->taxonomy, null, $args );
        register_taxonomy_for_object_type( $this->taxonomy, $this->post_type );
    }

    private function get_name($term_id, $key)
    {
        return sprintf( '%s_%d_%s', $this->taxonomy, $term_id, $key );
    }

    public function get_all($mode = 'array')
    {
        $data = array();
        $args =  array(
            'get' => 'all',
            'exclude' => array( $this->iworks_event->get_option( 'archive' ) ),
        );
        $terms = get_terms( $this->taxonomy, $args );
        if ( 'objects' == $mode ) {
            return $terms;
        }
        foreach( $terms as $term ) {
            if ( 'hash' == $mode ) {
                $data[$term->term_id] = apply_filters( 'the_title', $term->name );
            } else {
                $one = array(
                    'term_id' => $term->term_id,
                    'name' => apply_filters( 'the_title', $term->name ),
                    'slug' => $term->slug,
                );
                $data[] = $one;
            }
        }
        return $data;
    }

    public function get_used()
    {
        global $wp_query;
        $terms = array();
        if ( !isset($wp_query->posts ) ) {
            return $terms;
        }
        $args = array(
            'order' => 'DESC',
            'orderby' => 'count',
            'fields' => 'all',
        );
        $added_terms = array();
        foreach( $wp_query->posts as $post ) {
            $object_terms = wp_get_object_terms( $post->ID, $this->taxonomy, $args );
            foreach($object_terms as $term) {
                if (in_array($term->term_id, $added_terms)) {
                    continue;
                }
                $added_terms[] = $term->term_id;
                $terms[] = $term;
            }
        }
        return $terms;
    }

    public function get_terms( $number = 0, $type = 'list', $args = array() )
    {
        if ( 'slugs' != $type ) {
            $args = array(
                'orderby' => 'count',
                'exclude' => array( $this->iworks_event->get_option( 'archive' ) ),
            );
            if ( $number ) {
                $args['number'] = $number;
            }
        }
        /**
         * get terms
         */
        $terms = wp_get_object_terms( get_the_ID(), $this->taxonomy, $args );
        if ( !count( $terms ) ) {
            return '';
        }
        $terms_list = array();
        foreach( $terms as $term ) {
            $terms_list[$term->slug] = $term->name;
        }
        switch( $type ) {
        case 'plain':
            return implode( ', ', array_values( $terms_list ) );
        case 'slugs':
            return implode( ' ', array_keys( $terms_list ) );
        }
        $content = '<ul>';
        foreach( $terms_list as $slug => $name ) {
            $content .= sprintf( '<li><a href="#%s">%s</a></li>', $slug, $name );
        }
        $content .= '</ul>';
        return $content;
    }

    public function wp_title($title, $sep, $seplocation)
    {
        if ( !is_tax($this->taxonomy ) ) {
            return $title;
        }
        $term_title = single_term_title( '', false );
        if ( $sep ) {
            if ( 'right' == $seplocation ) {
                $title = $term_title . " $sep ";
            } else {
                $title = " $sep " . $term_title;
            }
        } else {
            $title .= $term_title;
        }
        return $title;
    }

    public function get_term_by_obj_id($post_id)
    {
        $args = array(
            'order' => 'DESC',
            'orderby' => 'count',
            'fields' => 'all',
        );
        $terms = wp_get_object_terms( $post_id, $this->taxonomy, $args );
        if ( is_array( $terms) && count( $terms ) ) {
            return sprintf(
                '<a href="%s">%s</a>',
                get_term_link( $terms[0] ),
                apply_filters( 'the_title', $terms[0]->name, $this->taxonomy )
            );
        }
        return '';
    }

}
