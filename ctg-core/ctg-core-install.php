<?php

function ctg_core_prepare_install() {

	global $wpdb;

	$raw_db_version = (int) $wpdb->db_version();
	$ctg_prefix      = $wpdb->base_prefix;
	
}


function ctg_core_install_team_members() {

	global $wpdb;
	$sql = array();

	$ctg_prefix      = $wpdb->base_prefix;
	$charset_collate = $wpdb->get_charset_collate();

	$sql[] = "CREATE TABLE {$ctg_prefix}ctg_members (
				id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				first_name varchar(75) NOT NULL,
				last_name varchar(75) NOT NULL,
				job_title varchar(75) NULL,
				location_id int(10) NULL,
				email varchar(75) NOT NULL,
				headshot_url varchar(155) NULL,
				KEY first_name (first_name),
				KEY last_name (last_name),
				KEY job_title (job_title),
				KEY location_id (location_id),
				KEY email (email),
				KEY headshot_url (headshot_url)
			) {$charset_collate};";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

}

function ctg_core_install_locations() {

	global $wpdb;
	$sql = array();

	$ctg_prefix      = $wpdb->base_prefix;
	$charset_collate = $GLOBALS['wpdb']->get_charset_collate();

	$sql[] = "CREATE TABLE {$ctg_prefix}ctg_locations (
				id int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				street_primary varchar(75) NOT NULL,
				street_secondary varchar(20) NULL,
				city varchar(75) NOT NULL,
				state varchar(25) NOT NULL,
				zip char(5) NOT NULL,
				phone varchar(30) NULL,
				email varchar(80) NULL,
				location_name varchar(75) NULL,
				appointment_only BOOLEAN,
				franchise_location BOOLEAN,
				KEY street_primary (street_primary),
				KEY street_secondary (street_secondary),
				KEY city (city),
				KEY state (state),
				KEY zip (zip),
				KEY phone (phone),
				KEY email (email),
				KEY location_name (location_name),
				KEY appointment_only (appointment_only),
				KEY franchise_location (franchise_location)
			) {$charset_collate};";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

}

function ctg_core_install( $active_components = false ) {

	ctg_core_prepare_install();

	if ( empty( $active_components ) ) {

		$active_components = apply_filters( 'ctg_active_components', ctg_get_option( 'ctg_active_components' ) );

	}

	ctg_core_install_team_members();
	ctg_core_install_locations();

	do_action('ctg_core_install');
	wp_cache_flush();
	flush_rewrite_rules();

}