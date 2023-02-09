<?php
function ctg_core_register_styles() {
	$url = ctg()->plugin_url . 'ctg-core/assets/css/';

	$styles = array(
		'ctg-common' => array(
			'file' => "{$url}common.css",
			'dependencies' => array(),
		),
	);

	foreach ( $styles as $id => $style ) {
		wp_register_style( $id, $style['file'], $style['dependencies'], ctg_get_version() );
	}
}
add_action( 'ctg_enqueue_scripts', 'ctg_core_register_styles', 1 );

function ctg_core_register_scripts() {
	$url = ctg()->plugin_url . 'ctg-core/assets/js/';

	$scripts = array(
		'ctg-maps-api'	=> array(
			'file'	=> "https://maps.googleapis.com/maps/api/js?key=AIzaSyAU3YBJYXDMMn-Mqxl4Eo775iuYRTei7Qs&callback=initMap&libraries=places",
			'dependencies'	=> array(),
			'in_footer'		=> true,
		),
		'ctg-locations'	=> array(
			'file'			=> "{$url}ctg-locations.js",
			'dependencies' 	=> array(),
			'in_footer'		=> true,
		),
	);

	foreach ( $scripts as $id => $script ) {
		wp_register_script( $id, $script['file'], $script['dependencies'], ctg_get_version(), $script['in_footer'] );
	}
}
add_action( 'ctg_enqueue_scripts', 'ctg_core_register_scripts', 2 );

function ctg_core_enqueue_styles() {

	if ( ctg_is_component() ) {
		wp_enqueue_style( 'ctg-common' );
	}

}

function ctg_core_enqueue_scripts() {

	if ( ctg_core_is_locations_component() ) {
		wp_enqueue_script( 'ctg-locations' );
		wp_enqueue_script( 'ctg-maps-api' );
	}

}

function ctg_locations_scripts() {

	wp_enqueue_script( 
		'ctg-locations-js', 
		CTG_PLUGIN_URL . '/ctg-core/assets/js/ctg-locations.js', 
		'jquery' 
	);

	wp_enqueue_style( 
		'ctg-locations-css', 
		CTG_PLUGIN_URL . '/ctg-core/assets/css/ctg-locations.css'
	);

	$ctg_localized_variables = array(
		'get_locations'		=> admin_url( 
			'admin-ajax.php?action=ctg_get_locations' 
		),
		'get_members_by_location_id'		=> admin_url( 
			'admin-ajax.php?action=ctg_get_members_by_location_id' 
		),
		'get_member_by_id'		=> admin_url( 
			'admin-ajax.php?action=ctg_get_member_by_id'
		),
		'locations_nonce'	=> wp_create_nonce( 
			'ctg_locations_nonce' 
		),
		'members_nonce'		=> wp_create_nonce( 
			'ctg_members_nonce' 
		),
	);

	wp_localize_script( 
		'ctg-locations-js', 
		'ctg', 
		$ctg_localized_variables 
	);

}
add_action('ctg_enqueue_scripts', 'ctg_locations_scripts');

function ctg_locations_admin_scripts() {

	if ( !isset($_GET['page'] ) ) { 
		return; 
	} else {
		if ( is_admin() && $_GET['page'] === 'ctg_locations' ) {

			wp_enqueue_script( 
				'ctg-locations-admin-js', 
				CTG_PLUGIN_URL . '/ctg-core/assets/js/ctg-locations-admin.js', 
				'jquery' 
			);

			wp_enqueue_style( 
				'ctg-locations-admin-css', 
				CTG_PLUGIN_URL . '/ctg-core/assets/css/ctg-locations-admin.css'
			);

			$ctg_localized_variables = array(
				'get_locations'		=> admin_url( 
					'admin-ajax.php?action=ctg_get_locations' 
				),
				'get_location_by_id'		=> admin_url( 
					'admin-ajax.php?action=ctg_get_location_by_id'
				),
				'add_location'	=> admin_url( 
					'admin-ajax.php?action=ctg_add_location'
				),
				'update_location'	=> admin_url( 
					'admin-ajax.php?action=ctg_update_location'
				),
				'delete_location' => admin_url(
					'admin-ajax.php?action=ctg_delete_location'
				),
				'refresh_locations_table'		=> admin_url( 
					'admin-ajax.php?action=ctg_refresh_locations_table'
				),
				'locations_nonce'	=> wp_create_nonce( 
					'ctg_locations_nonce' 
				),
			);

			wp_localize_script( 
				'ctg-locations-admin-js', 
				'ctg', 
				$ctg_localized_variables 
			);
		}
	}

}
add_action('admin_enqueue_scripts', 'ctg_locations_admin_scripts');

function ctg_members_admin_scripts() {

	if ( ! isset($_GET['page'])) {
		return;
	} else {
		if ( is_admin() && $_GET['page'] === 'ctg_members') {
			wp_enqueue_script( 
				'ctg-members-admin-js', 
				CTG_PLUGIN_URL . '/ctg-core/assets/js/ctg-members-admin.js', 
				'jquery' 
			);

			wp_enqueue_style( 
				'ctg-members-admin-css', 
				CTG_PLUGIN_URL . '/ctg-core/assets/css/ctg-members-admin.css'
			);

			$ctg_localized_variables = array(
				'get_members_by_location_id'		=> admin_url( 
					'admin-ajax.php?action=ctg_get_members_by_location_id' 
				),
				'get_member_by_id'		=> admin_url( 
					'admin-ajax.php?action=ctg_get_member_by_id' 
				),
				'add_member'	=> admin_url( 
					'admin-ajax.php?action=ctg_add_member'
				),
				'update_member'	=> admin_url( 
					'admin-ajax.php?action=ctg_update_member'
				),
				'delete_member' => admin_url(
					'admin-ajax.php?action=ctg_delete_member'
				),
				'refresh_members_table'		=> admin_url( 
					'admin-ajax.php?action=ctg_refresh_members_table'
				),
				'members_nonce'		=> wp_create_nonce( 
					'ctg_members_nonce' 
				),
				'delete_member_nonce' => wp_create_nonce(
					'ctg_delete_member'
				),
				'attachment_id'	=> get_option( 'media_selector_attachment_id', 0 )
			);

			wp_localize_script( 
				'ctg-members-admin-js', 
				'ctg', 
				$ctg_localized_variables 
			);

		}
	}

}
add_action('admin_enqueue_scripts', 'ctg_members_admin_scripts');

add_action( 'ctg_enqueue_scripts', 'ctg_core_enqueue_styles', 3 );
add_action( 'ctg_enqueue_scripts', 'ctg_core_enqueue_scripts', 4 );
add_action( 'ctg_admin_enqueue_scripts', 'ctg_core_register_styles', 1 );
