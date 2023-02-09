<?php 

defined( 'ABSPATH' ) || exit;

class CTG_Members_Member {

	public $id;

	public $first_name;

	public $last_name;

	public $job_title;

	public $location_id;

	public $email;

	public $headshot_url;

	public function __construct( $id = 0 ) {
		
		$this->default_headshot_url = ctg()->plugin_url . 'ctg-core/assets/images/Sparkler.png';

		if ( ! empty ( $id ) ) {

			$this->id = (int) $id;

			$this->populate( $id );

		}

	}

	public function populate( $id = 0 ) {
		$member = self::get_member(
			array(
				'where' => array(
					'id'	=> $id,
				)
			)
		);

		$fetched_member = ( ! empty( $member ) ) ? current( $member ) : array();
		if ( ! empty( $fetched_member ) && ! is_wp_error( $fetched_member ) ) {
			$this->first_name = $fetched_member['first_name'];
			$this->last_name = $fetched_member['last_name'];
			$this->job_title = $fetched_member['job_title'];
			$this->location_id = $fetched_member['location_id'];
			$this->email = $fetched_member['email'];
			$this->headshot_url = $fetched_member['headshot_url'];
		}

	}

	protected static function _insert( $data = array(), $data_format = array() ) {
		global $wpdb;
		return $wpdb->insert( "{$wpdb->base_prefix}ctg_members", $data, $data_format );
	}

	public function add_member() {

		$retval = false;
		do_action( 'ctg_members_before_save', array( &$this ) );
		$data = array(
			'first_name' => $this->first_name,
			'last_name' => $this->last_name,
			'job_title' => $this->job_title,
			'location_id' => $this->location_id,
			'email' => $this->email,
			'headshot_url' => $this->headshot_url
		);

		$data_format = array( '%s', '%s', '%s', '%d', '%s', '%s' );
		$result = self::_insert( $data, $data_format );
		if ( ! empty( $result ) && ! is_wp_error( $result ) ) {
			global $wpdb;

			if ( empty( $this->id ) ) {
				$this->id = $wpdb->insert_id;
			}

			do_action( 'ctg_members_after_save' );

			$retval = $this->id;
		}
		return $retval;
	}

	public function get_member( $args = array() ) {

		global $wpdb;

		$r = ctg_parse_args(
			$args,
			array(
				'select'				=> '',
				'where'					=> array(
					'id'			=> '',
					'first_name'	=> '',
					'last_name'		=> '',
					'job_title'		=> '',
					'location_id'	=> '',
					'email'			=> '',
					'headshot_url'	=> '',
				),
				'groupby'		=> '',
				'pagination'	=> array(),
				'orderby' => array(),
			)
		);

		$select = empty( $r['select'] ) ? '*' : htmlspecialchars($r['select']);

		$select_sql	= esc_sql("SELECT {$select}");
		$from_sql	= "FROM {$wpdb->base_prefix}ctg_members";
		$where_sql	= self::get_where_sql( $r['where'], $select_sql, $from_sql);
		$orderby_sql = ! empty( $r['orderby'] ) && is_array( $r['orderby'] ) ? 
			"ORDER BY {$r['orderby'][0]} {$r['orderby'][1]}" : "";

		$gb_clean	= '';
		$group_sql	= '';
		$pagination		= array();
		$pagination_sql	= '';

		if ( ! empty($r['groupby']) ) {
			$gb_clean = esc_sql($r['groupby']);
			$group_sql = "GROUP BY {$gb_clean}";
		}

		if ( ! empty( $r['pagination']) ) {
			$pagination = array( 
				intval( $pagination[0] ), 
				intval( $pagination[1] )
			);
			$pagination_sql = esc_sql("LIMIT {$pagination[0]}, {$pagination[1]}");
		}

		$sql = "{$select_sql} {$from_sql} {$where_sql} {$group_sql} {$orderby_sql} {$pagination_sql}";

		return $wpdb->get_results( $sql, 'ARRAY_A' );
	}

	public function update( $update_args = array(), $where_args = array() ) {
		$update = self::get_query_clauses( $update_args );
		$where  = self::get_query_clauses( $where_args );

		return self::_update(
			$update['data'],
			$where['data'],
			$update['format'],
			$where['format']
		);
	}

	protected static function _update( $data = array(), $where = array(), $data_format = array(), $where_format = array() ) {
		global $wpdb;

		$table_name = "{$wpdb->base_prefix}ctg_members";

		$retval = $wpdb->update( 
			$table_name,
			$data, $where, 
			$data_format, $where_format 
		);

		do_action( 'ctg_members_after_update' );
		return $retval;
	}

	public function delete( $delete_args = array() ) {

		$where = self::get_query_clauses( $delete_args );

		return self::_delete(
			$where['data'],
			$where['format']
		);

	}

	/**
	 * Delete member.
	 *
	 *
	 * @see wpdb::update() for further description of paramater formats.
	 *
	 * @param array $where        Array of WHERE clauses to filter by, passed to
	 *                            {@link wpdb::delete()}. Accepts any property of a
	 *                            BP_Notification_Notification object.
	 * @param array $where_format See {@link wpdb::insert()}.
	 * @return int|false The number of rows updated, or false on error.
	 */
	protected static function _delete( $where = array(), $where_format = array() ) {

		global $wpdb;

		$table_name = "{$wpdb->base_prefix}ctg_members";

		$retval = $wpdb->delete( $table_name, $where, $where_format );

		do_action( 'ctg_members_after_delete' );

		return $retval;



	}

	protected static function get_where_sql( $args = array(), $select_sql = '', $from_sql = '' ) {
		global $wpdb;
		$where_conditions = array();
		$where            = '';

		if ( ! empty( $args['id'] ) ) {
			$id_in                  = implode( ',', wp_parse_id_list( $args['id'] ) );
			$where_conditions['id'] = "id IN ({$id_in})";
		}

		if ( ! empty( $args['first_name'] ) ) {
			$first_names	= explode( ',', $args['first_name'] );

			$fn_clean = array();
			foreach ( $first_names as $fn ) {
				$fn_clean[] = $wpdb->prepare( '%s', $fn );
			}

			$fn_in = implode( ',', $fn_clean );

			$where_conditions['first_name'] = "first_name LIKE ({$fn_in})";
		}

		if ( ! empty( $args['last_name'] ) ) {
			$last_names	= explode( ',', $args['last_name'] );

			$ln_clean = array();
			foreach ( $last_names as $ln ) {
				$ln_clean[] = $wpdb->prepare( '%s', $ln );
			}

			$ln_in = implode( ',', $ln_clean );

			$where_conditions['last_name'] = "last_name LIKE ({$ln_in})";
		}

		if ( ! empty( $args['job_title'] ) ) {
			if ( ! is_array( $args['job_title'] ) ) {
				$job_titles = explode( ',', $args['job_title'] );
			} else {
				$job_titles = $args['job_title'];
			}
			$jt_clean = array();
			foreach ( $job_titles as $jt ) {
				$jt_clean[] = $wpdb->prepare( '%s', $jt );
			}
			$jt_in = implode( ',', $jt_clean );
			$where_conditions['job_title'] = "job_title IN ({$jt_in})";
		}

		if ( ! empty( $args['location_id'] ) ) {

			if ( ! is_array( $args['location_id'] ) ) {
				$location_ids = explode( ',', $args['location_id'] );
			} else {
				$location_ids = $args['location_id'];
			}

			$lid_clean = array();
			foreach ( $location_ids as $lid ) {
				$lid_clean[] = $wpdb->prepare( '%d', $lid );
			}

			$lid_in = implode( ',', $lid_clean );
			$where_conditions['location_id'] = "location_id IN ({$lid_in})";
		}

		if ( ! empty( $args['email'] ) ) {
			if ( ! is_array( $args['email'] ) ) {
				$emails = explode( ',', $args['email'] );
			} else {
				$emails = $args['email'];
			}
			$e_clean = array();
			foreach ( $emails as $e ) {
				$e_clean[] = $wpdb->prepare( '%s', $e );
			}
			$e_in = implode( ',', $e_clean );
			$where_conditions['email'] = "email IN ({$e_in})";
		}

		if ( ! empty( $args['headshot_url'] ) ) {

			$headshot_url = esc_url($args['headshot_url']);

			$where_conditions['headshot_url'] = "headshot_url IN ({$headshot_url})";
		}

		$where_conditions = apply_filters( 'ctg_members_get_where_conditions', $where_conditions, $args, $select_sql, $from_sql );

		if ( ! empty( $where_conditions ) ) {
			$where = 'WHERE ' . implode( ' AND ', $where_conditions );
		}

		return $where;

	}

	/**
	 * Assemble query clauses, based on arguments, to pass to $wpdb methods.
	 *
	 * The insert(), update(), and delete() methods of {@link wpdb} expect
	 * arguments of the following forms:
	 *
	 * - associative arrays whose key/value pairs are column => value, to
	 *   be used in WHERE, SET, or VALUES clauses.
	 * - arrays of "formats", which tell $wpdb->prepare() which type of
	 *   value to expect when sanitizing (eg, array( '%s', '%d' ))
	 *
	 * This utility method can be used to assemble both kinds of params,
	 * out of a single set of associative array arguments, such as:
	 *
	 *     $args = array(
	 *         'user_id' => 4,
	 *         'component_name' => 'groups',
	 *     );
	 *
	 * This will be converted to:
	 *
	 *     array(
	 *         'data' => array(
	 *             'user_id' => 4,
	 *             'component_name' => 'groups',
	 *         ),
	 *         'format' => array(
	 *             '%d',
	 *             '%s',
	 *         ),
	 *     )
	 *
	 * which can easily be passed as arguments to the $wpdb methods.
	 *
	 *
	 * @param array $args Associative array of filter arguments.
	 *                    
	 * @return array Associative array of 'data' and 'format' args.
	 */
	protected static function get_query_clauses( $args = array() ) {
		$where_clauses = array(
			'data'   => array(),
			'format' => array(),
		);

		if ( ! empty( $args['id'] ) ) {
			$where_clauses['data']['id'] = absint( $args['id'] );
			$where_clauses['format'][]   = '%d';
		}

		if ( ! empty( $args['first_name'] ) ) {
			$where_clauses['data']['first_name'] = $args['first_name'];
			$where_clauses['format'][] = '%s';
		}

		if ( ! empty( $args['last_name'] ) ) {
			$where_clauses['data']['last_name'] = $args['last_name'];
			$where_clauses['format'][] = '%s';
		}

		if ( ! empty( $args['job_title'] ) ) {
			$where_clauses['data']['job_title'] = $args['job_title'];
			$where_clauses['format'][] = '%s';
		}

		if ( ! empty( $args['location_id'] ) ) {
			$where_clauses['data']['location_id'] = $args['location_id'];
			$where_clauses['format'][] = '%d';
		}

		if ( ! empty( $args['email'] ) ) {
			$where_clauses['data']['email'] = $args['email'];
			$where_clauses['format'][] = '%s';
		}

		if ( ! empty( $args['headshot_url'] ) ) {
			$where_clauses['data']['headshot_url'] = $args['headshot_url'];
			$where_clauses['format'][] = '%s';
		}

		return $where_clauses;
	}

}