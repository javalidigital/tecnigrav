<?php
/*
Plugin Name: Multipurpose Sliders
Plugin URI: #
Description: Create sliders and use on certain post.
Version: 1.6.5
Author: Marcin Pietrzak
Author URI: #
License: commercial
License URI: #
*/

/*

Copyright 2013 Marcin Pietrzak (marcin@iworks.pl)

 */

if ( !defined( 'WPINC' ) ) {
    die;
}

/**
 * static options
 */
define( 'IWORKS_SLIDERS_VERSION', '1.6.5' );
define( 'IWORKS_SLIDERS_PREFIX',  'iworks_is_' );

require_once dirname(__FILE__).'/includes/common.php';

$iworks_sliders = new IworksSliders();

/**
 * install & uninstall
 */
register_activation_hook  ( __FILE__, 'iworks_sliders_activate'   );
register_deactivation_hook( __FILE__, 'iworks_sliders_deactivate' );

