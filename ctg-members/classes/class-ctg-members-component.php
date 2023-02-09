<?php

defined( 'ABSPATH' ) || exit;

class CTG_Members_Component extends CTG_Component {

	public $types = array();

	public function __construct() {
		parent::start(
			'members',
			__( 'Team Members', 'ctg' ),
			ctg()->plugin_dir,
			array(
				'adminbar_myaccount_order' => 10,
			)
		);
	}

	public function includes( $includes = array() ) {

		$includes = array(
			'functions',
		);

		parent::includes( $includes );
	}

	public function late_includes() {}

	public function setup_globals( $args = array() ) {
		global $wpdb;

		$args = array(
			'slug'				=> $this->id,
			'global_tables'   => array(
				'table_name_members' => $wpdb->base_prefix . 'ctg_members',
			),
		);

		parent::setup_globals( $args );

	}

	public function setup_canonical_stack() {
		$ctg = ctg();
		$ctg->canonical_stack['primary'] = $this->id;

	}

}
