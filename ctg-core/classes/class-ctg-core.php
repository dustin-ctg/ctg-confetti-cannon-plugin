<?php

defined( 'ABSPATH' ) || exit;

class CTG_Core extends CTG_Component {

	public $types = array();

	public function __construct() {
		parent::start(
			'core',
			__( 'CTG Core', 'ctg' ),
			CTG_PLUGIN_PATH,
			array(
				'adminbar_myaccount_order' => 10,
			)
		);
		$this->bootstrap();
	}

	private function bootstrap() {

		do_action( 'ctg_core_loaded' );

		$this->load_components();
		
	}

	public function includes( $includes = array() ) {
		$includes = array(
			'admin',
		);
		
		parent::includes($includes);
	}
	
	public function late_includes() {}

	public function setup_globals( $args = array() ) {


		$ctg = ctg();

		if ( empty( $ctg->table_prefix ) ) {
			$ctg->table_prefix = ctg_core_get_table_prefix();
		}
/*/
		// The domain for the root of the site where the main blog resides.
		if ( empty( $bp->root_domain ) ) {
			$bp->root_domain = bp_core_get_root_domain();
		}

		// Fetches all of the core BuddyPress settings in one fell swoop.
		if ( empty( $bp->site_options ) ) {
			$bp->site_options = bp_core_get_root_options();
		}

		// The names of the core WordPress pages used to display BuddyPress content.
		if ( empty( $bp->pages ) ) {
			$bp->pages = bp_core_get_directory_pages();
		}

	
		// Logged in user is the 'current_user'.
		$current_user = wp_get_current_user();

		// The user ID of the user who is currently logged in.
		$bp->loggedin_user     = new stdClass();
		$bp->loggedin_user->id = isset( $current_user->ID ) ? $current_user->ID : 0;


	$bp->core->table_name_notifications = $bp->table_prefix . 'bp_notifications';

		// Backward compatibility for plugins modifying the legacy bp_nav and bp_options_nav global properties.
		$bp->bp_nav         = new BP_Core_BP_Nav_BackCompat();
		$bp->bp_options_nav = new BP_Core_BP_Options_Nav_BackCompat();

		/**
		 * Used to determine if user has admin rights on current content. If the
		 * logged in user is viewing their own profile and wants to delete
		 * something, is_item_admin is used. This is a generic variable so it
		 * can be used by other components. It can also be modified, so when
		 * viewing a group 'is_item_admin' would be 'true' if they are a group
		 * admin, and 'false' if they are not.
		 */
/*/		bp_update_is_item_admin( bp_user_has_access(), 'core' );

		// Is the logged in user is a mod for the current item?
		bp_update_is_item_mod( false, 'core' );
	do_action( 'bp_core_setup_globals' );
/*/	}

	public function setup_canonical_stack() {
		$ctg = ctg();

/*/		if ( ctg_displayed_user_id() ) {
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
		}/*/
	}

	private function load_components() {

		$ctg = ctg();

		$ctg->optional_components = apply_filters( 
			'ctg_optional_components', 
			array_keys( ctg_core_get_components( 'optional' ) ) 
		);

		$ctg->required_components = apply_filters( 
			'ctg_required_components', 
			array('celebratorcreator', 'locations', 'members') 
		);

		if ( $active_components = ctg_get_option( 'ctg-active-components' ) ) {

			$ctg->active_components = apply_filters( 'ctg_active_components', $active_components );

			$ctg->deactivated_components = apply_filters( 
				'ctg_deactivated_components', 
				array_values( 
					array_diff( 
						array_values( 
							array_merge( 
								$ctg->optional_components, 
								$ctg->required_components 
							) 
						), 
						array_keys( 
							$ctg->active_components
						) 
					) 
				) 
			);

		} else {

			$ctg->deactivated_components = array();

			$active_components = array_fill_keys( 
				array_values( 
					array_merge( 
						$ctg->optional_components, 
						$ctg->required_components 
					) 
				), '1'
			);

			$ctg->active_components = apply_filters( 
				'ctg_active_components', 
				$ctg->active_components 
			);
		}

		foreach ( $ctg->optional_components as $component ) {
			if ( 
				ctg_is_active( $component ) && 
				file_exists( 
					$ctg->plugin_dir . 
					'ctg-' . $component . 
					'/ctg-' . $component . 
					'-loader.php' ) 
			) {
				include $ctg->plugin_dir . 'ctg-' . $component . '/ctg-' . $component . '-loader.php';
			}
		}
		
		foreach ( $ctg->required_components as $component ) {
			if ( file_exists( 
				$ctg->plugin_dir . 
				'ctg-' . $component . 
				'/ctg-' . $component . '-loader.php' ) 
			   ) {
				include $ctg->plugin_dir . 
					'ctg-' . $component . 
					'/ctg-' . $component . 
					'-loader.php';
			}
		}

		$ctg->required_components[] = 'core';

		do_action( 'ctg_core_components_included' );
	}
}
