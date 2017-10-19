<?php 

namespace FP\helpers;


function create_event( $type, $date, $targets, $meta = [], $eventtargets = []) { 
	
	if ( !empty( $date ) ) { 
		if ( is_numeric( $date ) ) { 
			$meta['date'] = 't' . $date;
		} else { 
			$meta['date'] = strtotime( $date );
			if ( $meta['date'] === false ) { 
				$meta['date'] = 't' . $date;
			}
		}
	}
	$meta['type'] = $type;
	foreach( $targets as $n => $v ) { $targets[ $n ] = (string)$v; }
	foreach( $eventtargets as $n => $v ) { $eventtargets[ $n ] = (string)$v; }

	if( !empty( $meta['place'] ) ) { 
		$pid = find_or_add_place( $meta['place'] );
		$placetargets = [ (string)$pid ];
		unset( $meta['place'] );
	}

	$post_arr = [
		'post_type'    => 'fp_event', 
		'post_title'   => '', 
		'post_status'  => 'publish',
		'post_author'  => get_current_user_id(),
		'meta_input'   => $meta,
		'tax_input'    => [
	        'fp_tax_target'   => $targets,
	        'fp_tax_eventtarget' => $eventtargets,
	        'fp_tax_place' => $placetargets
	    ],
	];
	//print_r( $post_arr );
	$post_id = wp_insert_post( $post_arr, true );
	if ( is_wp_error($post_id) ) { 
		return $post_id;
	}
	
	$type = \FP\Helpers\translate_event_type( $post_id );
	$args = array(
		'ID'           => $post_id,
		'post_title'   => $type,
	);
	remove_action('save_post', '\FP\Meta\page_post');
	wp_update_post( $args );
	add_action('save_post', '\FP\Meta\page_post');

	return $post_id;
}

function find_or_add_place( $text ) { 
	$args = [
		'post_type' => 'fp_place',
		'title' => sanitize_text_field( $text )
	];
	$query = get_posts( $args );
	if ( count( $query ) >= 1 ) {
		return $query[0]->ID;
	} else { 
		$post_arr = [
			'post_type'    => 'fp_place', 
			'post_title'   => sanitize_text_field( $text ), 
			'post_status'  => 'publish',
		];
		$post_id = wp_insert_post( $post_arr, true );
		if ( is_wp_error($post_id) ) { 
			return false;
		}
		return $post_id;
	}
}

function get_parent_ids( $post_id, $text = false ) { 
	$terms = wp_get_post_terms( $post_id, 'fp_tax_parents' );
	$taxlimit = [];
	foreach( $terms as $n => $v ) { 
		$taxlimit[] = ( $text ) ? (string)$v->name : intval( $v->name ) ; 
	}

	return $taxlimit;
}

function get_parents( $post_id ) { 
	$taxlimit = get_parent_ids( $post_id );

	if ( !is_array( $taxlimit ) || count( $taxlimit ) === 0 ) { return false; }
	$people = [];
	$args = [
		'post_type' => 'fp_member',
		'post__in' => $taxlimit
	];

	$query = get_posts( $args );
	if ( count( $query ) >= 1 ) {
		foreach( $query as $postitem ) { 
			$x = (array)$postitem;
			$x['meta'] = get_post_meta( $postitem->ID, '', true );
			$people[] = $x;
		}
	} else {
		return false;
	}
	return $people;
}


function get_siblings( $post_id ) { 
	$taxlimit = get_parent_ids( $post_id, true );

	$people = [];
	$args = [
		'post_type' => 'fp_member',
		'tax_query' => array(
			array(
				'taxonomy' => 'fp_tax_parents',
				'field'    => 'slug',
				'terms'    => $taxlimit,
			),
		),
		'post__not_in' => [ $post_id ],
	];
	$query = get_posts( $args );
	if ( count( $query ) >= 1 ) {
		foreach( $query as $postitem ) { 
			$x = (array)$postitem;
			$x['meta'] = get_post_meta( $postitem->ID, '', true );
			$people[] = $x;
		}
	} else {
		return false;
	}
	return $people;
}


function get_children( $post_id ) { 

	$people = [];
	$args = [
		'post_type' => 'fp_member',
		'tax_query' => array(
			array(
				'taxonomy' => 'fp_tax_parents',
				'field'    => 'slug',
				'terms'    => [ (string)$post_id ],
			),
		),
	];
	$query = get_posts( $args );
	if ( count( $query ) >= 1 ) {
		foreach( $query as $postitem ) { 
			$x = (array)$postitem;
			$x['meta'] = get_post_meta( $postitem->ID, '', true );
			$people[] = $x;
		}
	} else {
		return false;
	}
	return $people;
}

function get_events( $post_id, $tax = 'fp_tax_target') { 
	$people = [];
	$args = [
		'post_type' => 'fp_event',
		'tax_query' => array(
			array(
				'taxonomy' => $tax,
				'field'    => 'slug',
				'terms'    => [ (string)$post_id ],
			),
		),
		'post__not_in' => [ $post_id ],
	];
	$query = get_posts( $args );
	if ( count( $query ) >= 1 ) {
		foreach( $query as $postitem ) { 
			$x = (array)$postitem;
			$x['meta'] = get_post_meta( $postitem->ID, '', true );
			$x['targets'] = wp_get_post_terms( $postitem->ID, 'fp_tax_target' );
			$x['eventtargets'] = wp_get_post_terms( $postitem->ID, 'fp_tax_eventtarget' );
			$people[] = $x;
		}
	} else {
		return false;
	}
	return $people;
}

function get_events_aa( $post_id ) { 
	$ev = get_events( $post_id );

	$out = [];
	foreach( $ev as $event ) { 
		if( $event['meta']['type'][0] === 'MARR' ) { 
			$x = get_events( $event['ID'], 'fp_tax_eventtarget' );
			if( is_array( $x ) ) { 
				$ev = array_merge( $ev, $x );
			}
		}
	}


	foreach ( $ev as $event ) { 
		$tmp = [];
		$tmp['id'] = $event['ID'];
		$tmp['title'] = $event['post_title'];
		$tmp['date'] = $event['meta']['date'][0];
		$tmp['targets'] = [];
		// Get all the people targets
		foreach( $event['targets'] as $term ) { 
			if ( intval( $term->name ) !== $post_id ) { 
				$tmp['targets'][] = [ 
					'id' => intval( $term->name ), 
					'name' => get_the_title( intval( $term->name ) ),
					'url' => get_the_guid(intval( $term->name ) ) 
				]; 
			}
		}

		// Get all the events connected to this event
		$tmp['eventtargets'] = [];
		foreach( $event['eventtargets'] as $term ) { 
			if ( intval( $term->name ) !== $post_id ) { 
				$tmp['eventtargets'][] = [ 
					'id' => intval( $term->name ), 
					'name' => get_the_title( intval( $term->name ) ),
					'url' => get_the_guid( intval( $term->name ) ) 
				]; 
			}
		}

		if ( $event['meta']['type'][0] === 'BIRT' || $event['meta']['type'][0] === 'DEAT' ) { 
			$out[ $event['meta']['type'][0] ] = $tmp;
		} else { 
			$out[ $event['meta']['type'][0] ][] = $tmp;
		}
	}

	return $out;
}

function event_types() { 
	return [ 
		'BIRT' => [ 'Birth', 'fp_tax_target' ], 
		'MARR' => [ 'Marrage', 'fp_tax_target' ], 
		'DEAT' => [ 'Death', 'fp_tax_target' ], 
		'DIVO' => [ 'Divorce', 'fp_tax_eventtarget' ], 
	];
}

function translate_event_type( $post_id ) { 
	$t = event_types();

	$type = get_post_meta( $post_id, 'type', true );
	$targets = wp_get_post_terms( $post_id, $t[ $type ][1] );
	if ( $t[ $type ][1] === 'fp_tax_eventtarget') { 
		$targets = wp_get_post_terms( intval( $targets[0]->name), 'fp_tax_target' );
	}

	$out = '';
	foreach( $targets as $target ) {
		if ( !empty( $out ) ) { $out .= ' and '; }
		$out .= get_the_title( intval( $target->name ) );
	}
	$out = $t[ $type ][0] . ' of ' . $out;
	return $out;
}


function get_person_details( $post_id ) { 
	$person = [];
	$args = [
		'post_type' => 'fp_member',
	];

	if ( empty( $post_id ) ) { return false; }

	if ( is_numeric( $post_id ) ) { 
		$args['p'] = $post_id;
	} else { 
		$args['post_name__in'] = [ $post_id ];
	}

	$query = get_posts( $args );
	if ( count( $query ) >= 1 ) {
		foreach( $query as $postitem ) { 
			$person = (array)$postitem;
			$person['meta'] = get_post_meta( $postitem->ID, '', true );
			$person['img'] = get_the_post_thumbnail( $postitem->ID );
			if ( empty( $person['img'] ) ) { 
				$person['img'] = '<img src="' . plugin_dir_url( __FILE__ ) . '../assets/images/person.png' . '">';
			}
		}
	} else {
		return false;
	}
	return $person;
}

function frontend_post_select( $name, $post_type, $cur_setting, $param = [] ) { 
	if( empty( $post_type ) ) { $post_type = 'page'; }

	$pt_detail = get_post_type_object( 	$post_type );

	$itemnum = ''; $page = [];
	if ( !empty( $cur_setting ) ) {
		$itemnum = intval( $cur_setting );
		$page = get_posts( [ 'post_type' => $post_type, 'p' => $cur_setting ] );
	}
	$js_settings = [ 'post_type' => $post_type, 'edit_text' => $pt_detail->labels->edit_item ];
	?>
	<div id="<?php echo esc_attr( $name ); ?>_overall">
		<select js-settings="<?php echo esc_attr( json_encode( $js_settings ) ); ?>" 
			class="fp_post_select" 
			id="<?php echo esc_attr( $name ); ?>" 
			name="<?php echo esc_attr( $name ); ?>"
		>
			<?php if( count( $page ) > 0 ) : ?>
				<option value="<?php echo intval( $itemnum ); ?>" selected><?php echo esc_html( $page[0]->post_title ); ?></option>
			<?php endif; ?>
		</select>
		<?php if( count( $page ) > 0 ) : ?>
			<a id="<?php echo esc_attr( $name ); ?>_href" href="<?php echo esc_url( get_edit_post_link( $itemnum ) ); ?>">
				<?php echo esc_html( $pt_detail->labels->edit_item ); ?>
			</a>
		<?php 
		endif; 
		if ( !empty( $param['addnew'] ) ) : ?>
			<input type="button" id="<?php echo esc_attr( $name ); ?>_addnew_init" class="button" value="Add New">
		<?php endif; ?>
	</div>
	<?php
	if ( !empty( $param['addnew'] ) ) : ?>
	<div id="<?php echo esc_attr( $name ); ?>_addnew_text" class="fp_addnew_text">
		<input type="text" id="<?php echo esc_attr( $name ); ?>_addnew">
		<input type="button" id="<?php echo esc_attr( $name ); ?>_addnew_button" value="Add">
		<input type="button" id="<?php echo esc_attr( $name ); ?>_addnew_cancel_button" value="Cancel">
	</div>
		
	<?php 
	endif;
	?>
	<?php

}
