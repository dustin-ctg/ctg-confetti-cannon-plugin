<?php

defined( 'ABSPATH' ) || exit;

class CTG_CelebratorCreator_Component extends CTG_Component {

	public $types = array();

	public function __construct() {
		parent::start(
			'cc',
			__( 'Celebrator Creator', 'ctg' ),
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

		if ( is_admin() ) {
			$includes[] = 'admin';
		}

		parent::includes( $includes );
	}

	public function late_includes() {}

	public function setup_globals( $args = array() ) {
		global $wpdb;

		$ctg = ctg();

//		$default_directory_titles = ctg_core_get_directory_page_default_titles();
//		$default_directory_title  = $default_directory_titles[ $this->id ];

		$args = array(
			'slug'				=> $this->id,
			'has_directory'		=> true,
			'directory_title' => isset( $ctg->pages->cc->title ) ? $ctg->pages->cc->title : 'celebrators',
			'search_string'   => __( 'Search Members&hellip;', 'ctg' ),
			'global_tables'   => array(
				'table_name_celebrator_creator' => $wpdb->base_prefix . 'celebratorcreator',
			),
		);

		parent::setup_globals( $args );

	}

	public function setup_canonical_stack() {
		$ctg = ctg();

		if ( ctg_displayed_user_id() ) {
			$ctg->canonical_stack['base_url'] = ctg_displayed_user_domain();

			if ( ctg_current_component() ) {
				$ctg->canonical_stack['component'] = ctg_current_component();
			}

			if ( ctg_current_action() ) {
				$ctg->canonical_stack['action'] = ctg_current_action();
			}

			if ( ! empty( $ctg->action_variables ) ) {
				$ctg->canonical_stack['action_variables'] = ctg_action_variables();
			}

			if ( ! ctg_current_component() ) {
				$ctg->current_component = $ctg->default_component;

			} elseif ( ctg_is_current_component( $ctg->default_component ) && ! ctg_current_action() ) {
				unset( $ctg->canonical_stack['component'] );
			}
		}
	}
	
}
	