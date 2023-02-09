<?php 
defined( 'ABSPATH' ) || exit;

function ctg_setup_celebratorcreator() {
	ctg()->celebratorcreator = new CTG_CelebratorCreator_Component();
}
//add_action( 'ctg_loaded', 'ctg_setup_celebratorcreator', 1 );
