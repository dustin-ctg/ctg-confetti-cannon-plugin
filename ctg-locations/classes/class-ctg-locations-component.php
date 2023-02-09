<?php

defined( 'ABSPATH' ) || exit;

class CTG_Locations_Component extends CTG_Component {

	public $types = array();

	public function __construct() {
		parent::start(
			'locations',
			__( 'Locations', 'ctg' ),
			ctg()->plugin_dir,
			array(
				'adminbar_myaccount_order' => 10,
			)
		);
	}

	public function includes( $includes = array() ) {

		$includes = array(
			'functions',
			'template',
			'admin'
		);

		parent::includes( $includes );
	}

	public function late_includes() {}

	public function setup_globals( $args = array() ) {
		global $wpdb;

		$args = array(
			'slug'				=> $this->id,
			'global_tables'   => array(
				'table_name_locations' => $wpdb->base_prefix . 'ctg_locations',
			),
		);

		parent::setup_globals( $args );

	}

	public function setup_canonical_stack() {
		$ctg = ctg();
		$ctg->canonical_stack['primary'] = $this->id;

		if ( ctg_current_component() ) {
			$ctg->canonical_stack['secondary'] = ctg_current_location();
		}

		if ( ctg_current_action() ) {
			$ctg->canonical_stack['action'] = ctg_current_action();
		}

		if ( ! ctg_current_action() ) {
			unset( $ctg->canonical_stack['secondary'] );
		}
	}

}
