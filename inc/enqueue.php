<?php 

namespace FP\Enqueue;

/**
 * Enqueues the public css and js files
 * @return void
 */
function enqueue_scripts() {
	wp_enqueue_script( 'fp_jscript', plugin_dir_url( __FILE__ ) . '../assets/js/familypress.js', array( 'jquery' ) );
	wp_enqueue_style( 'fp_styles', plugin_dir_url( __FILE__ ) . '../assets/css/familypress.css' );
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_scripts' );

/**
 * Enqueues the admin css and js files
 * @return void
 */
function admin_enqueue_scripts( $hook ) {
	wp_enqueue_script( 'custom-script', plugin_dir_url( __FILE__ ) . '../assets/js/fp_admin.js', array( 'jquery' ) );
	wp_enqueue_style( 'fp_styles', plugin_dir_url( __FILE__ ) . '../assets/css/fp_admin.css' );

	wp_enqueue_style( 'fp_select2', plugins_url('../vendors/select2/css/select2.min.css', __FILE__) );
	wp_enqueue_script( 'fp_select2_js', plugins_url('../vendors/select2/js/select2.min.js', __FILE__) );
}

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\admin_enqueue_scripts' );

/**
 * Adds rewrite rules for the map and member pages
 * @return void
 */
function custom_rewrite_basic() {
	$options = get_option( 'fp_settings' );
	$person = $options['fp_page_select1'];
	$map = $options['fp_page_select2'];
	
	if ( !empty( $person ) ) { 
		add_rewrite_rule('^fp_member/map/([^\/]*)/?', 'index.php?page_id=' . $map . '&fp_member=$matches[1]', 'top');
		add_rewrite_rule('^archives/fp_member/map/([^\/]*)/?', 'index.php?page_id=' . $map . '&fp_member=$matches[1]', 'top');
	}
	if ( !empty( $map ) ) { 
		add_rewrite_rule('^fp_member/([^\/]*)/?', 'index.php?page_id=' . $person . '&fp_member=$matches[1]', 'top');
		add_rewrite_rule('^archives/fp_member/([^\/]*)/?', 'index.php?page_id=' . $person . '&fp_member=$matches[1]', 'top');
	}
	add_rewrite_tag('%fp_member%', '([^&]+)');
}
add_action('init', __NAMESPACE__ . '\custom_rewrite_basic');


