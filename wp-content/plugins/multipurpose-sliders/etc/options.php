<?php

function iworks_sliders_options()
{
    $iworks_sliders_options = array();

    /**
     * main settings
     */
    $iworks_sliders_options['index'] = array(
        'use_tabs' => true,
        'version'  => '0.0',
        'options'  => array(
            array(
                'name'              => 'last_used_tab',
                'type'              => 'hidden',
                'dynamic'           => true,
                'autoload'          => false,
                'default'           => 0
            ),
            array(
                'type'              => 'heading',
                'label'             => __( 'Main page slider', 'iworks-sliders' ),
            ),
            array(
                'name'     => 'main_page_slider',
                'type'     => 'serialize',
                'th'       => __( 'Slider', 'iworks-sliders' ),
                'callback' => 'iworks_sliders_main_page_slider',
            ),
        ),
    );
    return $iworks_sliders_options;
}

function iworks_sliders_main_page_slider( $option_value, $option_name )
{
    global $iworks_sliders, $iworks_sliders_options;
    return $iworks_sliders_options->select_page_helper( $option_name, __( 'None', 'iworks-sliders' ), $iworks_sliders->get_post_type() );
}

