<?php
/*
Plugin Name: Motive Events
Plugin URI: #
Description:
Version: 1.4
Author: Marcin Pietrzak
Author URI: #
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/*

Copyright 2013-2014 Marcin Pietrzak (marcin@iworks.pl)

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

/**
 * static options
 */
define( 'IWORKS_EVENTS_VERSION', '1.4' );
define( 'IWORKS_EVENTS_PREFIX',  'iworks_events_' );

require_once dirname(__FILE__).'/includes/common.php';

$iworks_events = new IworksEvents();

/**
 * install & uninstall
 */
register_activation_hook( __FILE__, 'iworks_events_activate' );
register_deactivation_hook( __FILE__, 'iworks_events_deactivate' );
