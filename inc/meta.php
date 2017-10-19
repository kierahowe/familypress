<?php 

namespace FP\Meta;

function adding_custom_meta_boxes( $post_type, $post ) {

	// Meta boxes for the member admin area
	add_meta_box( 
		'fp_member_detail',
		__( 'Member Detail' ),
		__NAMESPACE__ . '\meta_box',
		'fp_member',
		'normal',
		'default'
	);

	add_meta_box( 
		'fp_member_parents',
		__( 'Parents' ),
		__NAMESPACE__ . '\parents_meta_box',
		'fp_member',
		'normal',
		'default'
	);

	add_meta_box( 
		'fp_member_events',
		__( 'Events' ),
		__NAMESPACE__ . '\events_meta_box',
		'fp_member',
		'normal',
		'default'
	);

	add_meta_box( 
		'fp_member_siblings',
		__( 'Siblings' ),
		__NAMESPACE__ . '\siblings_meta_box',
		'fp_member',
		'side',
		'low'
	);

	add_meta_box( 
		'fp_member_children',
		__( 'Children' ),
		__NAMESPACE__ . '\children_meta_box',
		'fp_member',
		'side',
		'low'
	);

	// Meta Boxes for the Events area
	add_meta_box( 
		'fp_event_detail',
		__( 'Event Detail' ),
		__NAMESPACE__ . '\event_meta_box',
		'fp_event',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', __NAMESPACE__ . '\\adding_custom_meta_boxes', 10, 2 );

function meta_box () { 
	$post = get_post(); 
	$details = get_post_meta( $post->ID, '', true );

	?>
	<div class="inside">
	<table>
		<tbody>
			<tr>
				<td>First Name:</td>
				<td><input type="text" name="firstname" value="<?php echo esc_attr( $details['firstname'][0] ); ?>" ></td>
			</tr>
			<tr>
				<td>Middle Name(s):</td>
				<td><input type="text" name="middlename" value="<?php echo esc_attr( $details['middlename'][0] ); ?>" ></td>
			</tr>
			<tr>
				<td>Last Names:</td>
				<td><input type="text" name="lastname" value="<?php echo esc_attr( $details['lastname'][0] ); ?>" ></td>
			</tr>
			<tr>
				<td>Nick Names:</td>
				<td><input type="text" name="nickname" value="<?php echo esc_attr( $details['nickname'][0] ); ?>" ></td>
			</tr>
			<tr>
				<td>Gender:</td>
				<td> 
					<select name="gender" id="gender">
					<option value="" selected="selected"></option>
					<option value="M" <?php selected( $details['gender'][0], 'M' ); ?>>Male</option>
					<option value="F" <?php selected( $details['gender'][0], 'F' ); ?>>Female</option>
					<option value="X" <?php selected( $details['gender'][0], 'X' ); ?>>Other</option>
					</select>
				</td>
			</tr>

		</tbody>
	</table>
    </div>

<?php 
}

function parents_meta_box () { 
	$post = get_post();
	$details = get_post_meta( $post->ID, '', true );

 	$parents = \FP\helpers\get_parents( $post->ID );

	$plist = [ 0, 1 ];
 	foreach( $plist as $v ) :
 		$vals = $parents[ $v ];
 	?>
 		<div class="fp_parent_block">
 	<?php 
 			\FP\Helpers\frontend_post_select( 'fp_set_parent_' . $v, 'fp_member', $vals['ID'] );
	?>
		</div>
<?php 
	endforeach;
}

function siblings_meta_box () { 
	$post = get_post();
	$details = get_post_meta( $post->ID, '', true );

 	$siblings = \FP\helpers\get_siblings( $post->ID );
 	if ( !is_array( $siblings ) ) { return; }

	foreach( $siblings as $vals ) :
	?>
		<div>
			<a href="/wp-admin/post.php?post=<?php echo intval( $vals['ID'] ); ?>&action=edit"><?php echo esc_html( $vals['post_title'] ); ?></a><br>
			<?php echo esc_html( $vals['meta']['gender'][0] ); ?>
		</div>
<?php 
	endforeach;
 
}

function events_meta_box () { 
	$post = get_post();
	$details = get_post_meta( $post->ID, '', true );

 	$events = \FP\helpers\get_events( $post->ID );
 	if ( !is_array( $events ) ) { return; }

	foreach( $events as $vals ) :
	?>
		<div>
			<a href="/wp-admin/post.php?post=<?php echo intval( $vals['ID'] ); ?>&action=edit"><?php echo esc_html( $vals['post_title'] ); ?></a><br>
			<?php echo esc_html( $vals['meta']['gender'][0] ); ?>
		</div>
<?php 
	endforeach;
 
}

function children_meta_box () { 
	$post = get_post();
	$details = get_post_meta( $post->ID, '', true );

 	$children = \FP\helpers\get_children( $post->ID );
	if ( !is_array( $children ) ) { return; }
 	
	foreach( $children as $vals ) :
	?>
		<div>
			<a href="/wp-admin/post.php?post=<?php echo intval( $vals['ID'] ); ?>&action=edit"><?php echo esc_html( $vals['post_title'] ); ?></a><br>
			<?php echo esc_html( $vals['meta']['gender'][0] ); ?>
		</div>
<?php 
	endforeach;
 
}
	
function get_fullname( $meta ) { 
	return sanitize_text_field( $meta['firstname'] ) . ' ' . 
			(  !empty( $meta['nickname'] ) ? '(' . sanitize_text_field( $meta['nickname'] ) . ') ' : '' ) . 
			sanitize_text_field( $meta['middlename'] ) . ' ' . 
			sanitize_text_field( $meta['lastname'] );
}

function page_post( $post_id ) { 
	$screen = \get_current_screen();
		
	if( $screen->id === 'fp_member') { 
		update_post_meta( $post_id, 'firstname', sanitize_text_field( $_POST['firstname'] ) ); 
		update_post_meta( $post_id, 'middlename', sanitize_text_field( $_POST['middlename'] ) ); 
		update_post_meta( $post_id, 'lastname', sanitize_text_field( $_POST['lastname'] ) ); 
		update_post_meta( $post_id, 'nickname', sanitize_text_field( $_POST['nickname'] ) ); 
		update_post_meta( $post_id, 'gender', sanitize_text_field( $_POST['gender'] ) ); 

		$fullname = get_fullname( $_POST );
		$args = array(
			'ID'           => $post_id,
			'post_title'   => $fullname,
		);

		remove_action('save_post', __NAMESPACE__ . '\\page_post');
		wp_update_post( $args );
		add_action('save_post', __NAMESPACE__ . '\\page_post');

		// Parents
		wp_set_post_terms( $post_id, [ (string)intval( $_POST['fp_set_parent_0'] ), (string)intval( $_POST['fp_set_parent_1'] ) ], 'fp_tax_parents' );
	}
	if( $screen->id === 'fp_event') { 
		$evtype =  sanitize_text_field( $_POST['type'] );
		update_post_meta( $post_id, 'type', $evtype ); 
		
		$t = strtotime( $_POST['date'] );
		if ( $t === false ) { 
			$t = time();
		}
		update_post_meta( $post_id, 'date', intval( $t ) ); 

		wp_set_post_terms( $post_id, [ (string)intval( $_POST['place'] ) ], 'fp_tax_place' );

		if ( $evtype === 'DIVO') { 
			wp_set_post_terms( $post_id, [ (string)intval( $_POST['divorceevent'] ) ], 'fp_tax_eventtarget' );
		}
		if ( $evtype === 'MARR') { 
			wp_set_post_terms( $post_id, [ (string)intval( $_POST['marrperson0'] ), (string)intval( $_POST['marrperson1'] ), ], 'fp_tax_target' );
		}
		if ( $evtype === 'BIRT') { 
			wp_set_post_terms( $post_id, [ (string)intval( $_POST['birthperson'] ) ], 'fp_tax_target' );
		}
		if ( $evtype === 'DEAT') { 
			wp_set_post_terms( $post_id, [ (string)intval( $_POST['deathperson'] ) ], 'fp_tax_target' );
		}

		$type = \FP\Helpers\translate_event_type( $post_id );
		$args = array(
			'ID'           => $post_id,
			'post_title'   => $type,
		);
		remove_action('save_post', __NAMESPACE__ . '\\page_post');
		wp_update_post( $args );
		add_action('save_post', __NAMESPACE__ . '\\page_post');

	}
}

add_action( 'save_post', __NAMESPACE__ . '\\page_post' );



function event_meta_box () { 
	$post = get_post();
	$details = get_post_meta( $post->ID, '', true );

	$placeterms = wp_get_post_terms( $post->ID, 'fp_tax_place' );
	$personterms = wp_get_post_terms( $post->ID, 'fp_tax_target' );
	$eventterms = wp_get_post_terms( $post->ID, 'fp_tax_eventtarget' );

	$divorce = \FP\Helpers\get_events( $post->ID, 'fp_tax_eventtarget')
?>
<div id="fp_event_data">
	<div class="fp_table">
		<div class="fp_table_row">
			<div class="fp_table_cell">Type:</div>
			<div class="fp_table_cell">
				<select name="type" id="fp_event_type">
					<?php 
						foreach( \FP\Helpers\event_types() as $n => $v ) : ?>
							<option value="<?php echo esc_attr( $n ); ?>" <?php selected( $details['type'][0], $n ); ?> ><?php echo esc_attr( $v[0] ); ?>
					<?php 
						endforeach; 
					?>
				</select>
			</div>
		</div>
		<div class="fp_table_row">
			<div class="fp_table_cell">Date:</div>
			<div class="fp_table_cell">
				<input type="date" name="date" 
					value="<?php echo is_numeric( $details['date'][0] ) ? date( "Y-m-d", $details['date'][0] ) : $details['date'][0] ; ?>" ></div>
		</div>
		<div class="fp_table_row">
			<div class="fp_table_cell">Location:</div>
			<div class="fp_table_cell">
				<?php
				\FP\Helpers\frontend_post_select( 'place', 'fp_place', intval( $placeterms[0]->name ), [ 'addnew' => 1 ]  );
				?>
			</div>
		</div>
	</div>
	<div class="fp_table fp_event_type_inputs" id="fp_event_BIRT">
		<div class="fp_table_row">
			<div class="fp_table_cell">Person:</div>
			<div class="fp_table_cell">
				<?php
				\FP\Helpers\frontend_post_select( 'birthperson', 'fp_member', intval( $personterms[0]->name ));
				?>
			</div>
		</div>
	</div>
	<div class="fp_table fp_event_type_inputs" id="fp_event_MARR">
		<div class="fp_table_row">
			<div class="fp_table_cell">People:</div>
			<div class="fp_table_cell">
				<?php
				\FP\Helpers\frontend_post_select( 'marrperson0', 'fp_member', intval( $personterms[0]->name ) );
				?>
				<?php
				\FP\Helpers\frontend_post_select( 'marrperson1', 'fp_member', intval( $personterms[1]->name ) );
				?>
			</div>
		</div>
		<div class="fp_table_row">
			<?php 
				if ( is_array( $divorce ) && count( $divorce ) > 0 ) : 
			?>
			<div class="fp_table_cell">Divorced on</div>
			<div class="fp_table_cell" id="fp_have_divorce">
				<a href="<?php echo esc_url( get_edit_post_link( $divorce[0]['ID'] ) ); ?>">
					<?php print date( 'm-d-Y', $divorce[0]['meta']['date'][0]); ?>
				</a>
			</div>
			<?php 
				else: 
			?>
			<div class="fp_table_cell"><a id="fp_divorce_button">Add Divorce</a></div>
			<div class="fp_table_cell" id="fp_add_divorce">
				On Date: <input type="date" id="fp_divorce_addnew" js-marr="<?php echo intval( $post->ID ); ?>">
				<input type="button" id="fp_divorce_addnew_button" value="Add">
				<input type="button" id="fp_divorce_addnew_cancel_button" value="Cancel">
			</div>
			<?php 
			endif; 
			?>
		</div>
	</div>
	<div class="fp_table fp_event_type_inputs" id="fp_event_DEAT">
		<div class="fp_table_row">
			<div class="fp_table_cell">Person:</div>
			<div class="fp_table_cell">
				<?php
				\FP\Helpers\frontend_post_select( 'deathperson', 'fp_member', intval( $personterms[0]->name ) );
				?>
			</div>
		</div>
	</div>
	<div class="fp_table fp_event_type_inputs" id="fp_event_DIVO">
		<div class="fp_table_row">
			<div class="fp_table_cell">Marrage Event:</div>
			<div class="fp_table_cell">
				<?php
				\FP\Helpers\frontend_post_select( 'divorceevent', 'fp_event', intval( $eventterms[0]->name ) );
				?>
			</div>
		</div>
	</div>
</div>
<?php 

}
