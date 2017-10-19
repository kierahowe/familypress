<?php

namespace FP\CPTs;

function fp_init() {

	$labels = array(
		'name'                  => _x( 'Family Members', 'Post Type General Name', 'fp' ),
		'singular_name'         => _x( 'Family Member', 'Post Type Singular Name', 'fp' ),
		'menu_name'             => __( 'Family Members', 'fp' ),
		'name_admin_bar'        => __( 'Family Members', 'fp' ),
		'archives'              => __( 'Item Archives', 'fp' ),
		'parent_item_colon'     => __( 'Parent Item:', 'fp' ),
		'all_items'             => __( 'Family Members', 'fp' ),
		'add_new_item'          => __( 'Add New Item', 'fp' ),
		'add_new'               => __( 'Add New', 'fp' ),
		'new_item'              => __( 'New Item', 'fp' ),
		'edit_item'             => __( 'Edit Item', 'fp' ),
		'update_item'           => __( 'Update Item', 'fp' ),
		'view_item'             => __( 'View Item', 'fp' ),
		'search_items'          => __( 'Search Item', 'fp' ),
		'not_found'             => __( 'Not found', 'fp' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'fp' ),
		'featured_image'        => __( 'Featured Image', 'fp' ),
		'set_featured_image'    => __( 'Set featured image', 'fp' ),
		'remove_featured_image' => __( 'Remove featured image', 'fp' ),
		'use_featured_image'    => __( 'Use as featured image', 'fp' ),
		'insert_into_item'      => __( 'Insert into item', 'fp' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'fp' ),
		'items_list'            => __( 'Items list', 'fp' ),
		'items_list_navigation' => __( 'Items list navigation', 'fp' ),
		'filter_items_list'     => __( 'Filter items list', 'fp' ),
	);
	$args = array(
		'label'                 => __( 'Family Member', 'fp' ),
		'description'           => __( 'Monitored Family Member', 'fp' ),
		'labels'                => $labels,
		'supports'              => array( 'thumbnail' ),
		'taxonomies'            => array(),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => 'fp_menu',
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-format-gallery',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => false,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => false,
		'capability_type'       => 'page',
	);
	register_post_type( 'fp_member', $args );

	$labels = array(
		'name'                  => _x( 'Events', 'Post Type General Name', 'fp' ),
		'singular_name'         => _x( 'Event', 'Post Type Singular Name', 'fp' ),
		'menu_name'             => __( 'Events', 'fp' ),
		'name_admin_bar'        => __( 'Events', 'fp' ),
		'archives'              => __( 'Item Archives', 'fp' ),
		'parent_item_colon'     => __( 'Parent Item:', 'fp' ),
		'all_items'             => __( 'Events', 'fp' ),
		'add_new_item'          => __( 'Add New Item', 'fp' ),
		'add_new'               => __( 'Add New', 'fp' ),
		'new_item'              => __( 'New Item', 'fp' ),
		'edit_item'             => __( 'Edit Item', 'fp' ),
		'update_item'           => __( 'Update Item', 'fp' ),
		'view_item'             => __( 'View Item', 'fp' ),
		'search_items'          => __( 'Search Item', 'fp' ),
		'not_found'             => __( 'Not found', 'fp' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'fp' ),
		'featured_image'        => __( 'Featured Image', 'fp' ),
		'set_featured_image'    => __( 'Set featured image', 'fp' ),
		'remove_featured_image' => __( 'Remove featured image', 'fp' ),
		'use_featured_image'    => __( 'Use as featured image', 'fp' ),
		'insert_into_item'      => __( 'Insert into item', 'fp' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'fp' ),
		'items_list'            => __( 'Items list', 'fp' ),
		'items_list_navigation' => __( 'Items list navigation', 'fp' ),
		'filter_items_list'     => __( 'Filter items list', 'fp' ),
	);
	$args = array(
		'label'                 => __( 'Event', 'fp' ),
		'description'           => __( 'Monitored Event', 'fp' ),
		'labels'                => $labels,
		'supports'              => array( '' ),
		'taxonomies'            => array(),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => 'fp_menu',
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-format-gallery',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => false,
		'can_export'            => false,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'capability_type'       => 'page',
	);
	register_post_type( 'fp_event', $args );

	$labels = array(
		'name'                  => _x( 'Places', 'Post Type General Name', 'fp' ),
		'singular_name'         => _x( 'Place', 'Post Type Singular Name', 'fp' ),
		'menu_name'             => __( 'Places', 'fp' ),
		'name_admin_bar'        => __( 'Places', 'fp' ),
		'archives'              => __( 'Item Archives', 'fp' ),
		'parent_item_colon'     => __( 'Parent Item:', 'fp' ),
		'all_items'             => __( 'Places', 'fp' ),
		'add_new_item'          => __( 'Add New Item', 'fp' ),
		'add_new'               => __( 'Add New', 'fp' ),
		'new_item'              => __( 'New Item', 'fp' ),
		'edit_item'             => __( 'Edit Item', 'fp' ),
		'update_item'           => __( 'Update Item', 'fp' ),
		'view_item'             => __( 'View Item', 'fp' ),
		'search_items'          => __( 'Search Item', 'fp' ),
		'not_found'             => __( 'Not found', 'fp' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'fp' ),
		'featured_image'        => __( 'Featured Image', 'fp' ),
		'set_featured_image'    => __( 'Set featured image', 'fp' ),
		'remove_featured_image' => __( 'Remove featured image', 'fp' ),
		'use_featured_image'    => __( 'Use as featured image', 'fp' ),
		'insert_into_item'      => __( 'Insert into item', 'fp' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'fp' ),
		'items_list'            => __( 'Items list', 'fp' ),
		'items_list_navigation' => __( 'Items list navigation', 'fp' ),
		'filter_items_list'     => __( 'Filter items list', 'fp' ),
	);
	$args = array(
		'label'                 => __( 'Place', 'fp' ),
		'description'           => __( 'Monitored Place', 'fp' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ),
		'taxonomies'            => array(),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => 'fp_menu',
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-format-gallery',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => false,
		'can_export'            => false,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'capability_type'       => 'page',
	);
	register_post_type( 'fp_place', $args );

	$labels = array(
		'name'              => _x( 'People Targets', 'taxonomy general name', 'fp' ),
		'singular_name'     => _x( 'People Target', 'taxonomy singular name', 'fp' ),
		'search_items'      => __( 'Search People Targets', 'fp' ),
		'all_items'         => __( 'All People Targets', 'fp' ),
		'parent_item'       => __( 'Parent People Target', 'fp' ),
		'parent_item_colon' => __( 'Parent People Target:', 'fp' ),
		'edit_item'         => __( 'Edit People Target', 'fp' ),
		'update_item'       => __( 'Update People Target', 'fp' ),
		'add_new_item'      => __( 'Add New People Target', 'fp' ),
		'new_item_name'     => __( 'New People Target Name', 'fp' ),
		'menu_name'         => __( 'People Target', 'fp' ),
	);
	$args = array(
		'hierarchical'      => false,
		'labels'            => $labels,
		'show_ui'           => false,
		'show_admin_column' => true,
	);
	register_taxonomy( 'fp_tax_target', [ 'fp_event' ], $args );

	$labels = array(
		'name'              => _x( 'Event Targets', 'taxonomy general name', 'fp' ),
		'singular_name'     => _x( 'Event Target', 'taxonomy singular name', 'fp' ),
		'search_items'      => __( 'Search Event Targets', 'fp' ),
		'all_items'         => __( 'All Event Targets', 'fp' ),
		'parent_item'       => __( 'Parent Event Target', 'fp' ),
		'parent_item_colon' => __( 'Parent Event Target:', 'fp' ),
		'edit_item'         => __( 'Edit Event Target', 'fp' ),
		'update_item'       => __( 'Update Event Target', 'fp' ),
		'add_new_item'      => __( 'Add New Event Target', 'fp' ),
		'new_item_name'     => __( 'New Event Target Name', 'fp' ),
		'menu_name'         => __( 'Event Target', 'fp' ),
	);
	$args = array(
		'hierarchical'      => false,
		'labels'            => $labels,
		'show_ui'           => false,
		'show_admin_column' => true,
	);
	register_taxonomy( 'fp_tax_eventtarget', [ 'fp_event' ], $args );

	$labels = array(
		'name'              => _x( 'Places', 'taxonomy general name', 'fp' ),
		'singular_name'     => _x( 'Place', 'taxonomy singular name', 'fp' ),
		'search_items'      => __( 'Search Places', 'fp' ),
		'all_items'         => __( 'All Places', 'fp' ),
		'parent_item'       => __( 'Parent Place', 'fp' ),
		'parent_item_colon' => __( 'Parent Place:', 'fp' ),
		'edit_item'         => __( 'Edit Place', 'fp' ),
		'update_item'       => __( 'Update Place', 'fp' ),
		'add_new_item'      => __( 'Add New Place', 'fp' ),
		'new_item_name'     => __( 'New Place Name', 'fp' ),
		'menu_name'         => __( 'Place', 'fp' ),
	);
	$args = array(
		'hierarchical'      => false,
		'labels'            => $labels,
		'show_ui'           => false,
		'show_admin_column' => true,
	);
	register_taxonomy( 'fp_tax_place', [ 'fp_event' ], $args );

	$labels = array(
		'name'              => _x( 'Parents', 'taxonomy general name', 'fp' ),
		'singular_name'     => _x( 'Parent', 'taxonomy singular name', 'fp' ),
		'search_items'      => __( 'Search Parents', 'fp' ),
		'all_items'         => __( 'All Parents', 'fp' ),
		'parent_item'       => __( 'Parent Parent', 'fp' ),
		'parent_item_colon' => __( 'Parent Parent:', 'fp' ),
		'edit_item'         => __( 'Edit Parent', 'fp' ),
		'update_item'       => __( 'Update Parent', 'fp' ),
		'add_new_item'      => __( 'Add New Parent', 'fp' ),
		'new_item_name'     => __( 'New Parent Name', 'fp' ),
		'menu_name'         => __( 'Parent', 'fp' ),
	);
	$args = array(
		'hierarchical'      => false,
		'labels'            => $labels,
		'show_ui'           => false,
		'show_admin_column' => true,
	);
	register_taxonomy( 'fp_tax_parents', [ 'fp_member' ], $args );

}
add_action( 'init', __NAMESPACE__ . '\fp_init', 0 );
