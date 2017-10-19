<?php 
namespace FP\Menus;

function fp_add_menu_items() {
	add_menu_page(
		__( 'FamilyPress', 'fp' ),
		__( 'FamilyPress', 'fp' ),
		'manage_options',
		'fp_menu',
		'',
		plugins_url( 'familypress/assets/images/icon.png' ),
		21
	);

	add_submenu_page( 
		'fp_menu',
		__( 'FamilyPress Settings', 'fp' ), 
		__( 'FamilyPress Settings', 'fp' ), 
		'manage_options', 
		'fp_settings', 
		__NAMESPACE__ . '\fp_options_page'
		);
}
add_action( 'admin_menu', __NAMESPACE__ . '\fp_add_menu_items' );

function fp_add_admin_menu(  ) { 
	add_options_page( 'FamilyPress', 'FamilyPress', 'manage_options', 'familypress', __NAMESPACE__ . '\fp_options_page' );
}
add_action( 'admin_menu', __NAMESPACE__ . '\fp_add_admin_menu' );

function fp_settings_init(  ) { 

	register_setting( 'fp_pluginPage', 'fp_settings' );

	add_settings_section(
		'fp_pluginPage_section', 
		__( 'Settings', 'fp' ), 
		__NAMESPACE__ . '\fp_settings_section_callback', 
		'fp_pluginPage'
	);

	add_settings_field( 
		'fp_page_select1', 
		__( 'Person Display Page', 'fp' ), 
		__NAMESPACE__ . '\fp_page_select1_render', 
		'fp_pluginPage', 
		'fp_pluginPage_section' 
	);

	add_settings_field( 
		'fp_page_select2', 
		__( 'Map Display Page', 'fp' ), 
		__NAMESPACE__ . '\fp_page_select2_render', 
		'fp_pluginPage', 
		'fp_pluginPage_section' 
	);
}
add_action( 'admin_init', __NAMESPACE__ . '\fp_settings_init' );


function fp_page_select1_render( ) { 
	$options = get_option( 'fp_settings' );

	$itemnum = ''; $page = [];
	if ( !empty( $options['fp_page_select1'] ) ) {
		$itemnum = intval( $options['fp_page_select1'] );
		$page = get_posts( [ 'post_type' => 'page', 'p' => $itemnum ] );
	}
	?>
	<select class="fp_settings_page_select" id="fp_settings_page_select1" name="fp_settings[fp_page_select1]">
		<?php if( count( $page ) > 0 ) : ?>
	  		<option value="<?php echo intval( $itemnum ); ?>" selected><?php echo esc_html( $page[0]->post_title ); ?></option>
	  	<?php endif; ?>
	</select>
	<input type="button" id="select1_create" class="button" value="Create New">
	<?php if( count( $page ) > 0 ) : ?>
		<a id="fp_page_select1_href" href="<?php echo esc_url( get_edit_post_link( $itemnum ) ); ?>">Edit Page</a>
	<?php endif; ?>
<?php
}

function fp_page_select2_render( ) { 
	$options = get_option( 'fp_settings' );

	$itemnum = ''; $page = [];
	if ( !empty( $options['fp_page_select2'] ) ) {
		$itemnum = intval( $options['fp_page_select2'] );
		$page = get_posts( [ 'post_type' => 'page', 'p' => $itemnum ] );
	}
	?>
	<select class="fp_settings_page_select" id="fp_settings_page_select2" name="fp_settings[fp_page_select2]">
		<?php if( count( $page ) > 0 ) : ?>
			<option value="<?php echo intval( $itemnum ); ?>" selected><?php echo esc_html( $page[0]->post_title ); ?></option>
		<?php endif; ?>
	</select>
	<input type="button" id="select2_create" class="button" value="Create New">
	<?php if( count( $page ) > 0 ) : ?>
		<a id="fp_page_select2_href" href="<?php echo esc_url( get_edit_post_link( $itemnum ) ); ?>">Edit Page</a>
	<?php endif; ?>
	<?php

}

function fp_settings_section_callback(  ) { 

	echo __( 'This section description', 'fp' );

}

function fp_options_page(  ) { 
	require_once (plugin_dir_path(__FILE__) . "../partials/options.php");
}

?>
