<?php 
defined( 'ABSPATH' ) || exit;

function ctg_setup_locations() {
	ctg()->locations = new CTG_Locations_Component();
}
add_action( 'ctg_loaded', 'ctg_setup_locations', 1 );
