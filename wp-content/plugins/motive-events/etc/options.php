<?php

function iworks_events_options()
{
    $iworks_events_options = array();

    /**
     * main settings
     */
    $iworks_events_options['index'] = array(
        'use_tabs' => true,
        'version' => '0.0',
        'options' => array(
            array(
                'name' => 'last_used_tab',
                'type' => 'hidden',
                'dynamic' => true,
                'autoload' => false,
                'default' => 0
            ),
            array(
                'type' => 'heading',
                'label' => __('Upcoming events', 'iworks_events'),
            ),
            array(
                'name' => 'upcoming_show',
                'type' => 'checkbox',
                'th' => __('Show Upcoming Events', 'iworks_events'),
                'sanitize' => 'intval',
                'default' => true,
            ),
            array(
                'name' => 'upcoming_number',
                'type' => 'number',
                'min' => 1,
                'th' => __('Numer of events', 'iworks_events' ),
                'sanitize' => 'intval',
                'default' => 3,
                'class' => 'small-text',
            ),
            array(
                'type' => 'heading',
                'label' => __('Other', 'iworks_events'),
            ),
            array(
                'name' => 'archive',
                'type' => 'serialize',
                'th' => __( 'Category for past events', 'events' ),
                'callback' => 'iworks_archive_event_category',
                'description' => __( 'There is no need to assign to this category.', 'iworks_events' ),
            ),
            array(
                'name' => 'show_alerts',
                'type' => 'checkbox',
                'th' => __('Show alerts', 'iworks_events'),
                'sanitize' => 'intval',
                'default' => true,
            ),
            array(
                'name' => 'list_type',
                'type' => 'radio',
                'th' => __('Taxonomy event list type', 'iworks_events' ),
                'default' => 'hash',
                'radio' => array(
                    'hash' => array(
                        'label' => __('anchor', 'iworks_events' ),
                        'description' => __('Create list of anchors with taxonomy slug.', 'iworks_events' ),
                    ),
                    'link' => array(
                        'label' => __('link', 'iworks_events' ),
                        'description' => __('Create list of links to taxonomy.', 'iworks_events' ),
                    ),
                ),
            ),
            array(
                'name' => 'all_name',
                'type' => 'text',
                'th' => __('Name for all events', 'iworks_events' ),
                'description' => __('Enter name of link to show all evens.', 'iworks_events' ),
                'sanitize' => 'esc_html',
                'default' => __('All', 'iworks_events'),
            ),
            array(
                'name' => 'events_page_limit',
                'type' => 'number',
                'min' => 1,
                'th' => __('Numer of events on events page', 'iworks_events' ),
                'sanitize' => 'intval',
                'default' => 3,
                'class' => 'small-text',
            ),
            array(
                'type' => 'heading',
                'label' => __('Widgets', 'iworks_events'),
            ),
            array(
                'name' => 'widget_calendar',
                'type' => 'checkbox',
                'th' => __('Calendar', 'iworks_events'),
                'sanitize' => 'intval',
                'default' => true,
            ),
        ),
    );

    return $iworks_events_options;
}

function iworks_archive_event_category($option_value, $option_name)
{
    global $iworks_events;
    return $iworks_events->select_archive_category( $option_name, __('None') );
}

