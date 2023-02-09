<?php

defined( 'ABSPATH' ) || exit;	


class CTG_Component {

	public $name = '';

	public $id = '';

	public $slug = '';
	
	public $has_directory = false;

	public $path = '';

	public $query = false;

	public $current_id = '';

	public $admin_menu = '';

	public $search_string = '';

	public $root_slug = '';

	public $meta_tables = array();

	public $global_tables = array();

	public $search_query_arg = 's';

	public function start( $id = '', $name = '', $path = '', $params = array() ) {

		$this->id   = $id;

		$this->name = $name;

		$this->path = $path;

		if ( ! empty( $params ) ) {
			if ( ! empty( $params['adminbar_myaccount_order'] ) ) {
				$this->adminbar_myaccount_order = (int) $params['adminbar_myaccount_order'];
			}

			if ( ! empty( $params['features'] ) ) {
				$this->features = array_map( 'sanitize_title', (array) $params['features'] );
			}

			if ( ! empty( $params['search_query_arg'] ) ) {
				$this->search_query_arg = sanitize_title( $params['search_query_arg'] );
			}

		} else {
			$this->adminbar_myaccount_order = 9;
		}

		$this->setup_actions();
	}

	public function setup_globals( $args = array() ) {

		$r = wp_parse_args( $args, array(
			'slug'                  => $this->id,
			'has_directory'         => false,
			'directory_title'       => '',
			'search_string'         => '',
			'global_tables'         => '',
			'meta_tables'           => '',
		) );

		$this->slug = apply_filters( 'ctg_' . $this->id . '_slug', $r['slug'] );
		$this->has_directory = apply_filters( 'ctg_' . $this->id . '_has_directory', $r['has_directory'] );
		$this->directory_title = apply_filters( 'ctg_' . $this->id . '_directory_title', $r['directory_title'] );
		$this->search_string = apply_filters( 'ctg_' . $this->id . '_search_string', $r['search_string'] );

		if ( ! empty( $r['global_tables'] ) ) {
			$this->register_global_tables( $r['global_tables'] );
		}
		if ( ! empty( $r['meta_tables'] ) ) {
			$this->register_meta_tables( $r['meta_tables'] );
		}

		ctg()->loaded_components[$this->slug] = $this->id;

		do_action( 'ctg_' . $this->id . '_setup_globals' );
	}

	public function includes( $includes = array() ) {

		if ( ! empty( $includes ) ) {
			$slashed_path = trailingslashit( $this->path );

			foreach ( (array) $includes as $file ) {

				$paths = array(

					'ctg-' . $this->id . '/ctg-' . $this->id . '-' . $file  . '.php',
					'ctg-' . $this->id . '-' . $file . '.php',
					'ctg-' . $this->id . '/' . $file . '.php',

					$file,
					'ctg-' . $this->id . '-' . $file,
					'ctg-' . $this->id . '/' . $file,
				);

				foreach ( $paths as $path ) {
					if ( @is_file( $slashed_path . $path ) ) {
						require( $slashed_path . $path );
						break;
					}
				}
			}
		}

		do_action( 'ctg_' . $this->id . '_includes' );
	}

	public function late_includes() {}

	public function setup_actions() {

		add_action( 'ctg_setup_globals', array( $this, 'setup_globals' ), 10 );
		add_action( 'ctg_setup_canonical_stack', array( $this, 'setup_canonical_stack'  ), 10 );
		add_action( 'ctg_include', array( $this, 'includes' ), 8 );
		add_action( 'ctg_late_include', array( $this, 'late_includes'          ) );
		add_action( 'ctg_setup_nav',              array( $this, 'setup_nav'              ), 10 );
		add_action( 'ctg_setup_admin_bar',        array( $this, 'setup_admin_bar'        ), $this->adminbar_myaccount_order );
		add_action( 'ctg_setup_title',            array( $this, 'setup_title'            ), 10 );
		add_action( 'ctg_setup_cache_groups',     array( $this, 'setup_cache_groups'     ), 10 );
		add_action( 'ctg_register_post_types',    array( $this, 'register_post_types'    ), 10 );
		add_action( 'ctg_register_taxonomies',    array( $this, 'register_taxonomies'    ), 10 );
		add_action( 'ctg_add_rewrite_tags',       array( $this, 'add_rewrite_tags'       ), 10 );	 
		add_action( 'ctg_add_rewrite_rules',      array( $this, 'add_rewrite_rules'      ), 10 );
		add_action( 'ctg_add_permastructs',       array( $this, 'add_permastructs'       ), 10 );
		add_action( 'ctg_parse_query',            array( $this, 'parse_query'            ), 10 );
		add_action( 'ctg_generate_rewrite_rules', array( $this, 'generate_rewrite_rules' ), 10 );

		do_action( 'ctg_' . $this->id . '_setup_actions' );
	}

	public function setup_canonical_stack() {}

	public function setup_nav( $main_nav = array(), $sub_nav = array() ) {

		if ( !empty( $main_nav ) ) {
			//				ctg_core_new_nav_item( $main_nav, 'members' );

			if ( !empty( $sub_nav ) ) {
				foreach( (array) $sub_nav as $nav ) {
					//						ctg_core_new_subnav_item( $nav, 'members' );
				}
			}
		}
		do_action( 'ctg_' . $this->id . '_setup_nav' );
	}

	public function setup_admin_bar( $wp_admin_nav = array() ) {

		if ( defined( 'DOING_AJAX' ) ) {
			return;
		}

//		if ( ! ctg_use_wp_admin_bar() ) {
//			return;
//		}

		$wp_admin_nav = apply_filters( 'ctg_' . $this->id . '_admin_nav', $wp_admin_nav );

		if ( !empty( $wp_admin_nav ) ) {
			$pos = 0;
			$not_set_pos = 1;
			foreach( $wp_admin_nav as $key => $nav ) {
				if ( ! isset( $nav['position'] ) ) {
					$wp_admin_nav[$key]['position'] = $pos + $not_set_pos;

					if ( 9 !== $not_set_pos ) {
						++$not_set_pos;
					}
				} else {
					$pos = $nav['position'];

					if ( $pos % 10 === 0 ) {
						$not_set_pos = 1;
					}
				}
			}

			$wp_admin_nav = ctg_sort_by_key( $wp_admin_nav, 'position', 'num' );

			$this->admin_menu = $wp_admin_nav;

			global $wp_admin_bar;

			foreach( $this->admin_menu as $admin_menu ) {
				$wp_admin_bar->add_menu( $admin_menu );
			}
		}

		do_action( 'ctg_' . $this->id . '_setup_admin_bar' );
	}

	public function setup_title() {

		do_action(  'ctg_' . $this->id . '_setup_title' );
	}

	public function setup_cache_groups() {

		do_action( 'ctg_' . $this->id . '_setup_cache_groups' );
	}

	public function register_global_tables( $tables = array() ) {

		$tables = apply_filters( 'ctg_' . $this->id . '_global_tables', $tables );

		if ( !empty( $tables ) && is_array( $tables ) ) {
			foreach ( $tables as $global_name => $table_name ) {
				$this->$global_name = $table_name;
			}

			$this->global_tables = $tables;
		}

		do_action( 'ctg_' . $this->id . '_register_global_tables' );
	}

	public function register_meta_tables( $tables = array() ) {
		global $wpdb;

		$tables = apply_filters( 'ctg_' . $this->id . '_meta_tables', $tables );

		if ( !empty( $tables ) && is_array( $tables ) ) {
			foreach( $tables as $meta_prefix => $table_name ) {
				$wpdb->{$meta_prefix . 'meta'} = $table_name;
			}

			$this->meta_tables = $tables;
		}

		do_action( 'ctg_' . $this->id . '_register_meta_tables' );
	}

	public function register_post_types() {

		do_action( 'ctg_' . $this->id . '_register_post_types' );
	}

	public function register_taxonomies() {

		do_action( 'ctg_' . $this->id . '_register_taxonomies' );
	}

	public function add_rewrite_tags() {

		do_action( 'ctg_' . $this->id . '_add_rewrite_tags' );
	}

	public function add_rewrite_rules() {

		do_action( 'ctg_' . $this->id . '_add_rewrite_rules' );
	}

	public function add_permastructs() {

		do_action( 'ctg_' . $this->id . '_add_permastructs' );
	}

	public function parse_query( $query ) {

		do_action_ref_array( 'ctg_' . $this->id . '_parse_query', array( &$query ) );
	}

	public function generate_rewrite_rules() {

		do_action( 'ctg_' . $this->id . '_generate_rewrite_rules' );
	}
}