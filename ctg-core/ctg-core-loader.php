<?php 
defined( 'ABSPATH' ) || exit;

function ctg_setup_core() {
	ctg()->core = new CTG_Core();
}
add_action( 'ctg_loaded', 'ctg_setup_core', 0 );
