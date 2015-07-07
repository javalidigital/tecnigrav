<?php
/*
Plugin Name: Converio Shortcodes
Plugin URI: http://thememotive.com/
Description: Shortcodes for Converio WordPress theme.
Version: 1.0.6
Author: ThemeMotive
Author URI: http://thememotive.com/
License: commercial
License URI: commercial
*/
 
$plugin_dir_path = dirname(__FILE__);

function wp_gear_manager_admin_styles() {
    wp_enqueue_style('style', plugin_dir_url( __file__ ). '/libs/css/style.css',true);
}

add_action('admin_print_styles', 'wp_gear_manager_admin_styles');

 
require_once($plugin_dir_path.'/libs/index.php');


