<?php 

defined( 'ABSPATH' ) || exit;

class CTG_Locations_Location {

	public $id;

	public $street_primary;

	public $street_secondary;

	public $city;

	public $state;

	public $zip;

	public $phone;

	public $email;

	public $location_name;

	public $appointment_only;

	public $franchise_location;

	public function __construct( $id = 0 ) {

		if ( ! empty ( $id ) ) {

			$this->id = (int) $id;

			$this->populate( $id );

		}

	}

	public function populate( $id ) {
		$location = self::get_location(
			array(
				'where' => array(
					'id'	=> $id,
				)
			)
		);

		$fetched_location = ( ! empty( $location ) ) ? current( $location ) : array();
		if ( ! empty( $fetched_location ) && ! is_wp_error( $fetched_location ) ) {

			$this->street_primary = $fetched_location['street_primary'];
			$this->street_secondary = $fetched_location['street_secondary'];
			$this->city = $fetched_location['city'];
			$this->state = $fetched_location['state'];
			$this->zip = $fetched_location['zip'];
			$this->phone = $fetched_location['phone'];
			$this->email = $fetched_location['email'];
			$this->location_name = $fetched_location['location_name'];
			$this->appointment_only = $fetched_location['appointment_only'];
			$this->franchise_location = $fetched_location['franchise_location'];
		}

	}

	protected static function _insert( $data = array(), $data_format = array() ) {
		global $wpdb;
		return $wpdb->insert( "{$wpdb->base_prefix}ctg_locations", $data, $data_format );
	}

	public function add_location() {

		$retval = false;
		do_action( 'ctg_locations_before_save', array( &$this ) );
		$data = array(
			'street_primary' => $this->street_primary,
			'street_secondary' => $this->street_secondary,
			'city' => $this->city,
			'state' => $this->state,
			'zip' => $this->zip,
			'phone' => $this->phone,
			'email' => $this->email,
			'location_name' => $this->location_name,
			'appointment_only' => $this->appointment_only,
			'franchise_location' => $this->franchise_location
		);

		$data_format = array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d' );
		$result = self::_insert( $data, $data_format );
		if ( ! empty( $result ) && ! is_wp_error( $result ) ) {
			global $wpdb;

			if ( empty( $this->id ) ) {
				$this->id = $wpdb->insert_id;
			}

			do_action( 'ctg_locations_after_save', $data );

			$retval = $this->id;
		}
		return $retval;
	}

	public function get_location( $args = array() ) {

		global $wpdb;

		$r = ctg_parse_args(
			$args,
			array(
				'select'				=> '',
				'where'					=> array(
					'id'					=> '',
					'street_primary'		=> '',
					'street_secondary'		=> '',
					'city'					=> '',
					'state'					=> '',
					'zip'					=> '',
					'phone'					=> '',
					'email'					=> '',
					'location_name'			=> '',
					'appointment_only'		=> '',
					'franchise_location'	=> ''
				),
				'groupby'		=> '',
				'pagination'	=> array()
			)
		);

		$select = empty( $r['select'] ) ? '*' : htmlspecialchars($r['select']);

		$select_sql	= "SELECT {$select}";
		$from_sql	= "FROM {$wpdb->base_prefix}ctg_locations";
		$where_sql	= self::get_where_sql( $r['where'], $select_sql, $from_sql);
		$gb_clean	= !empty( $r['groupby'] )
			? esc_sql($r['groupby'])
			: '';
		$group_sql	= !empty( $gb_clean )
			? "GROUP BY {$gb_clean}"
			: '';

		$pagination = !empty( $r['pagination'])
			? array( intval( $pagination[0] ), intval( $pagination[1] ) )
			: '';

		$pagination_sql = !empty( $pagination )
			? esc_sql("LIMIT {$pagination[0]}, {$pagination[1]}")
			: '';

		$sql = "{$select_sql} {$from_sql} {$where_sql} {$group_sql} {$pagination_sql}";

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

		$table_name = "{$wpdb->base_prefix}ctg_locations";

		$retval = $wpdb->update( 
			$table_name,
			$data, $where, 
			$data_format, $where_format 
		);

		do_action( 'ctg_location_after_update', $data );
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
	 * Delete location.
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

		$table_name = "{$wpdb->base_prefix}ctg_locations";

		return $wpdb->delete( $table_name, $where, $where_format );

	}

	protected static function get_where_sql( $args = array(), $select_sql = '', $from_sql = '' ) {
		global $wpdb;
		$where_conditions = array();
		$where            = '';

		if ( ! empty( $args['id'] ) ) {
			$id = intval($args['id']);
			$where_conditions['id'] = $wpdb->prepare("id = %d", $id );
		}

		if ( ! empty( $args['ids'] ) ) {
			$ids_in                  = implode( ',', wp_parse_id_list( $args['ids'] ) );
			$where_conditions['ids'] = "id IN ({$ids_in})";
		}

		if ( ! empty( $args['street_primary'] ) ) {
			$street_primaries	= explode( ',', $args['street_primary'] );

			$sp_clean = array();
			foreach ( $street_primaries as $street_primary ) {
				$sp_clean[] = $wpdb->prepare( '%s', $street_primary );
			}

			$street_primary_in = implode( ',', $sp_clean );

			$where_conditions['street_primary'] = "street_primary LIKE ({$street_primary_in})";
		}

		if ( ! empty( $args['street_secondary'] ) ) {
			$street_secondaries	= explode( ',', $args['street_secondary'] );

			$ss_clean = array();
			foreach ( $street_secondaries as $street_secondary ) {
				$ss_clean[] = $wpdb->prepare( '%s', $street_secondary );
			}

			$street_secondary_in = implode( ',', $ss_clean );

			$where_conditions['street_secondary'] = "street_secondary LIKE ({$street_secondary_in})";
		}

		if ( ! empty( $args['city'] ) ) {
			if ( ! is_array( $args['city'] ) ) {
				$cities = explode( ',', $args['city'] );
			} else {
				$cities = $args['city'];
			}
			$c_clean = array();
			foreach ( $cities as $c ) {
				$c_clean[] = $wpdb->prepare( '%s', $c );
			}
			$c_in = implode( ',', $cn_clean );
			$where_conditions['city'] = "city IN ({$c_in})";
		}

		if ( ! empty( $args['state'] ) ) {

			if ( ! is_array( $args['state'] ) ) {
				$states = explode( ',', $args['state'] );
			} else {
				$states = $args['state'];
			}

			$s_clean = array();
			foreach ( $states as $s ) {
				$s_clean[] = $wpdb->prepare( '%s', $s );
			}

			$s_in = implode( ',', $s_clean );
			$where_conditions['state'] = "state IN ({$s_in})";
		}

		if ( ! empty( $args['zip'] ) ) {
			if ( ! is_array( $args['zip'] ) ) {
				$zips = explode( ',', $args['zip'] );
			} else {
				$zips = $args['zip'];
			}
			$z_clean = array();
			foreach ( $zips as $z ) {
				$z_clean[] = $wpdb->prepare( '%s', $z );
			}
			$z_in = implode( ',', $z_clean );
			$where_conditions['zip'] = "zip IN ({$z_in})";
		}

		if ( ! empty( $args['phone'] ) ) {
			$p_clean = array();
			if ( ! is_array( $args['phone'] ) ) {
				$phones = explode( ',', $args['phone'] );
			} else {
				$phones = $args['phone'];
			}
			foreach ( $phones as $p ) {
				$p_clean[] = $wpdb->prepare( '%s', $p );
			}
			$p_in = implode( ',', $p_clean );
			$where_conditions['phone'] = "phone IN ({$p_in})";
		}

		if ( ! empty( $args['email'] ) ) {
			$e_clean = array();
			if ( ! is_array( $args['email'] ) ) {
				$emails = explode( ',', $args['email'] );
			} else {
				$emails = $args['email'];
			}
			foreach ( $emails as $e ) {
				$e_clean[] = $wpdb->prepare( '%s', $e );
			}
			$e_in = implode( ',', $e_clean );
			$where_conditions['email'] = "email IN ({$e_in})";
		}

		if ( isset( $args['appointment_only'] ) ) {

			$appointment_only = intval($args['appointment_only']);

			$where_conditions['appointment_only'] = $wpdb->prepare("appointment_only = %d", $appointment_only );

		}


		if ( isset( $args['franchise_location'] ) ) {

			$franchise_location = intval($args['franchise_location']);

			$where_conditions['franchise_location'] = $wpdb->prepare("franchise_location = %d", $franchise_location );

		}

		$where_conditions = apply_filters( 'ctg_locations_get_where_conditions', $where_conditions, $args, $select_sql, $from_sql );

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

		if ( ! empty( $args['street_primary'] ) ) {
			$where_clauses['data']['street_primary'] = $args['street_primary'];
			$where_clauses['format'][] = '%s';
		}

		if ( ! empty( $args['street_secondary'] ) ) {
			$where_clauses['data']['street_secondary'] = $args['street_secondary'];
			$where_clauses['format'][] = '%s';
		}

		if ( ! empty( $args['city'] ) ) {
			$where_clauses['data']['city'] = $args['city'];
			$where_clauses['format'][] = '%s';
		}

		if ( ! empty( $args['state'] ) ) {
			$where_clauses['data']['state'] = $args['state'];
			$where_clauses['format'][] = '%s';
		}

		if ( ! empty( $args['zip'] ) ) {
			$where_clauses['data']['zip'] = $args['zip'];
			$where_clauses['format'][] = '%s';
		}

		if ( ! empty( $args['phone'] ) ) {
			$where_clauses['data']['phone'] = $args['phone'];
			$where_clauses['format'][] = '%s';
		}

		if ( ! empty( $args['email'] ) ) {
			$where_clauses['data']['email'] = $args['email'];
			$where_clauses['format'][] = '%s';
		}

		if ( ! empty( $args['location_name'] ) ) {
			$where_clauses['data']['location_name'] = $args['location_name'];
			$where_clauses['format'][] = '%s';
		}

		if ( isset( $args['appointment_only'] ) ) {
			$where_clauses['data']['appointment_only'] = $args['appointment_only'];
			$where_clauses['format'][] = '%d';
		}

		if ( isset( $args['franchise_location'] ) ) {
			$where_clauses['data']['franchise_location'] = $args['franchise_location'];
			$where_clauses['format'][] = '%d';
		}

		return $where_clauses;
	}

}