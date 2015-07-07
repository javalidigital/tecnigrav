<?php

/**
 * require: IworksSliders Class
 */
if ( !class_exists( 'IworksSliders' ) ) {
    require_once dirname( __FILE__ ).'/class-iworks-sliders.php';
}
/**
 * configuration
 */
require_once dirname( dirname( __FILE__ )).'/etc/options.php';

/**
 * require: IworksOptions Class
 */
if ( !class_exists( 'IworksOptions' ) ) {
    require_once dirname( __FILE__ ).'/class-iworks-options.php';
}

/**
 * i18n
 */
load_plugin_textdomain( 'iworks-sliders', false, dirname( dirname( plugin_basename( __FILE__) ) ).'/languages' );

/**
 * load options
 */
$iworks_sliders_options = new IworksOptions();
$iworks_sliders_options->set_option_function_name( 'iworks_sliders_options' );
$iworks_sliders_options->set_option_prefix( IWORKS_SLIDERS_PREFIX );

Function iworks_sliders_options_init()
{
    global $iworks_sliders_options;
    $iworks_sliders_options->options_init();
}

function iworks_sliders_activate()
{
    $iworks_sliders_options = new IworksOptions();
    $iworks_sliders_options->set_option_function_name( 'iworks_sliders_options' );
    $iworks_sliders_options->set_option_prefix( IWORKS_SLIDERS_PREFIX );
    $iworks_sliders_options->activate();
    flush_rewrite_rules();
}

function iworks_sliders_deactivate()
{
    global $iworks_sliders_options;
    $iworks_sliders_options->deactivate();
    flush_rewrite_rules();
}

