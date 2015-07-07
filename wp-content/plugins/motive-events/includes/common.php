<?php

// test

$vendor = plugin_dir_path( dirname( __FILE__ ) ).'vendor';

/**
 * require: IworksEvents Class
 */
if ( !class_exists( 'IworksEvents' ) ) {
    require_once $vendor.'/iworks/events.php';
    require_once $vendor.'/iworks/events-shortcode-events.php';
    require_once $vendor.'/iworks/events-taxonomy-category.php';
}
/**
 * configuration
 */
require_once dirname( dirname( __FILE__ )).'/etc/options.php';

/**
 * require: IworksOptions Class
 */
if ( !class_exists( 'IworksOptions' ) ) {
    require_once $vendor.'/iworks/options.php';
}

/**
 * i18n
 */
load_plugin_textdomain( 'events', false, dirname( dirname( plugin_basename( __FILE__) ) ).'/languages' );

/**
 * load options
 */
$iworks_events_options = new IworksOptions();
$iworks_events_options->set_option_function_name( 'iworks_events_options' );
$iworks_events_options->set_option_prefix( IWORKS_EVENTS_PREFIX );

Function iworks_events_options_init()
{
    global $iworks_events_options;
    $iworks_events_options->options_init();
}

function iworks_events_activate()
{
    $iworks_events_options = new IworksOptions();
    $iworks_events_options->set_option_function_name( 'iworks_events_options' );
    $iworks_events_options->set_option_prefix( IWORKS_EVENTS_PREFIX );
    $iworks_events_options->activate();
    flush_rewrite_rules();
}

function iworks_events_deactivate()
{
    global $iworks_events_options;
    $iworks_events_options->deactivate();
    flush_rewrite_rules();
}
