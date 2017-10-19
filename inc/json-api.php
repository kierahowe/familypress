<?php 
namespace FP\JSONAPI;

function fp_json_endpoint_graph() {
	$data = [ ];

	$data['nodes'] = [];
	$data['links'] = [];

	$names = [];
	$ctr = 0;
	$args = [
		'post_type' => 'fp_member',
		'posts_per_page' => -1
	];
	$query = get_posts( $args );
	if ( count( $query ) >= 1 ) {
		foreach( $query as $postitem ) { 
			$name = get_post_meta( $postitem->ID, 'lastname', true );
			if ( empty( $names[ $name ] ) ) { $names[ $name ] = $ctr;  $ctr ++; }
			$data['nodes'][] = [ 
				'id' => $postitem->ID, 
				'group' => $names[ $name ],
				'title' => $postitem->post_title, 
				'slug' => $postitem->post_name,
			];
			$t = wp_get_post_terms( $postitem->ID, 'fp_tax_parents' );
			foreach ( $t as $n => $v ) {
				$data['links'][] = [ 
					'source' => $postitem->ID, 
					'target' => intval( $v->name ), 
					'value' => 1,
					'dash' => '0' 
				];
			}
		}
	} 

	$names = [];
	$ctr = 0;
	$args = [
		'post_type' => 'fp_event',
		'posts_per_page' => -1
	];
	$query = get_posts( $args );
	if ( count( $query ) >= 1 ) {
		foreach( $query as $postitem ) { 
			$type = get_post_meta( $postitem->ID, 'type', true );
			if ( $type === 'MARR' ) {  
				$t = wp_get_post_terms( $postitem->ID, 'fp_tax_target' );
				if ( count( $t ) === 2 ) { 
					$data['links'][] = [ 'source' => intval( $t[0]->name ), 'target' => intval( $t[1]->name ), 'value' => 1, 'dash' => '3'  ];
				}
			}
		}
	} 

	// for ( $i = 0; $i < 100; $i ++ ) {
	// 	$data['links'][] = [ 'source' => rand( 0, 9 ), 'target' => rand( 0, 9 ), 'value' => 'awesomeness' ];
	// }
    
	wp_send_json( $data );
}
add_action( 'wp_ajax_nopriv_graphjson', __NAMESPACE__ . '\fp_json_endpoint_graph' );
add_action( 'wp_ajax_graphjson', __NAMESPACE__ . '\fp_json_endpoint_graph' );

function fp_json_select_page() {
	$data = [ ];

	$post_type = sanitize_text_field( $_GET['post_type'] );
	if( empty( $post_type ) ) { $post_type = 'page'; };

	$args = [ 's' => sanitize_text_field( $_GET['search'] ), 'post_type' => $post_type ];
	$posts = get_posts( $args );
	foreach( $posts as $post ) { 
		$data[] = [ 'text' => $post->post_title, 'id' => $post->ID ];
	}
	wp_send_json( $data );
}
add_action( 'wp_ajax_fp_select_page', __NAMESPACE__ . '\fp_json_select_page' );

function fp_json_create_page() {
	$data = [ ];

	$content = '';
	$title = '';
	$opt_name = '';
	if ( $_GET['type'] === 'person') { 
		$title = 'A Family Member';
		$content = '[fp_display_person]';
		$opt_name = 'fp_page_select1';
	}
	if ( $_GET['type'] === 'map') { 
		$title = 'Tree map';
		$content = '[fp_map_people]';
		$opt_name = 'fp_page_select2';
	}

	$data['postnum'] = wp_insert_post( [ 'post_type' => 'page', 'post_status' => 'publish', 'post_content' => $content, 'post_title' => $title ] );
	$data['post_title'] = $title;
	if ( is_wp_error( $data['postnum'] ) ) { 
		$data['error'] = $data['postnum']->get_error_message();
		$data['postnum'] = 0;
	} else { 
		$opt = get_option( 'fp_settings' );
		$opt[ $opt_name ] = $data['postnum'];
		update_option( 'fp_settings', $opt );

		$data['url'] = get_edit_post_link( $data['postnum'], 'go' );
	}
	
	wp_send_json( $data );
}
add_action( 'wp_ajax_fp_create_page', __NAMESPACE__ . '\fp_json_create_page' );


function fp_json_create_item() {
	$data = [ ];

	$content = '';
	$title = sanitize_text_field( $_GET['value'] );
	$post_type = sanitize_text_field( $_GET['post_type'] );

	$data['postnum'] = wp_insert_post( [ 
		'post_type' => $post_type, 
		'post_status' => 'publish', 
		'post_content' => $content, 
		'post_title' => $title 
	] );
	$data['post_title'] = $title;
	if ( is_wp_error( $data['postnum'] ) ) { 
		$data['error'] = $data['postnum']->get_error_message();
		$data['postnum'] = 0;
	} else { 
		$data['url'] = get_edit_post_link( $data['postnum'], 'go' );
	}
	
	wp_send_json( $data );
}
add_action( 'wp_ajax_fp_create_item', __NAMESPACE__ . '\fp_json_create_item' );

function fp_json_create_event() {
	$type = sanitize_text_field( $_GET['type'] );
	$date = sanitize_text_field( $_GET['date'] );
	$marr = intval( $_GET['marrage'] );

	$data = [];
	$data['postnum'] = \FP\Helpers\create_event( $type, $date, [], [], [ $marr ] );
	$data['date'] = $date;
	$data['url'] = get_edit_post_link( $data['postnum'], 'go' );
	wp_send_json( $data );
}
add_action( 'wp_ajax_fp_create_event', __NAMESPACE__ . '\fp_json_create_event' );
