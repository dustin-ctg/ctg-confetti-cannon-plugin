<?php 
defined( 'ABSPATH' ) || exit;

function ctg_setup_members() {
	ctg()->members = new CTG_Members_Component();
}
add_action( 'ctg_loaded', 'ctg_setup_members', 1 );
