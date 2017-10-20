<?php
/**
 * @package FamilyPress
 * @version 1.0
 */
/*
Plugin Name: FamilyPress
Plugin URI: http://familypress.pro
Description: This creates a family tree interface and output.
Author: Kiera Howe
Version: 1.0
Author URI: http://familypress.pro
*/

require_once ( plugin_dir_path(__FILE__) . 'inc/enqueue.php' );
require_once ( plugin_dir_path(__FILE__) . 'inc/helpers.php' );
require_once ( plugin_dir_path(__FILE__) . 'inc/menus.php' );
require_once ( plugin_dir_path(__FILE__) . 'inc/cpt.php' );
require_once ( plugin_dir_path(__FILE__) . 'inc/meta.php' );
require_once ( plugin_dir_path(__FILE__) . 'inc/shortcodes.php' );
require_once ( plugin_dir_path(__FILE__) . 'inc/json-api.php' );

if( file_exists( plugin_dir_path(__FILE__) . 'pro/pro.php' ) ) { 
	require_once ( plugin_dir_path(__FILE__) . 'pro/pro.php' );
}

require_once ( ABSPATH . 'wp-admin/includes/screen.php' );