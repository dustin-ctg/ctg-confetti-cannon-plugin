<?php 

defined( 'ABSPATH' ) || exit;

class CTG_CelebratorCreator_Celebrator {

	public $id;

	public $first_name;

	public $last_name;

	public $job_title;

	public $email_address;

	public $phone_number;

	public $extension;

	public $headshot;

	public $office_location;

	public $office_state;

	public function __construct( $id = 0 ) {

		if ( ! empty ( $id ) ) {

			$this->id = (int) $id;

			$this->populate( $id );

		}

	}

	public function populate( $id ) {
		$team_member = self::get_team_member(
			array(
				'id'	=> $id,
			)
		);

		$fetched_team_member = ( ! empty( $team_member ) ) ? current( $team_member ) : array();
		if ( ! empty( $fetched_team_member ) && ! is_wp_error( $fetched_team_member ) ) {

			$this->first_name = $fetched_team_member->first_name;
			$this->last_name = $fetched_team_member->last_name;
			$this->job_title = $fetched_team_member->job_title;
			$this->email_address = $fetched_team_member->email_address;
			$this->phone_number = $fetched_team_member->phone_number;
			$this->extension = $fetched_team_member->extension;
			$this->headshot = $fetched_team_member->headshot;
			$this->office_location = $fetched_team_member->office_location;
			$this->office_state = $fetched_team_member->office_state;

		}

	}

	public static function get_team_member() {

		global $wpdb;


	}

	protected static function get_where_sql( $args = array(), $select_sql = '', $from_sql = '', $join_sql = '', $meta_query_sql = '' ) {
		global $wpdb;
		$where_conditions = array();
		$where            = '';

		if ( ! empty( $args['id'] ) ) {
			$id_in                  = implode( ',', wp_parse_id_list( $args['id'] ) );
			$where_conditions['id'] = "id IN ({$id_in})";
		}

		if ( ! empty( $args['user_id'] ) ) {
			$user_id_in                  = implode( ',', wp_parse_id_list( $args['user_id'] ) );
			$where_conditions['user_id'] = "user_id IN ({$user_id_in})";
		}

		if ( ! empty( $args['sender_id'] ) ) {
			$sender_id_in                  = implode( ',', wp_parse_id_list( $args['sender_id'] ) );
			$where_conditions['sender_id'] = "sender_id IN ({$sender_id_in})";
		}

		if ( ! empty( $args['item_id'] ) ) {
			$item_id_in                  = implode( ',', wp_parse_id_list( $args['item_id'] ) );
			$where_conditions['item_id'] = "item_id IN ({$item_id_in})";
		}

		if ( ! empty( $args['secondary_item_id'] ) ) {
			$secondary_item_id_in                  = implode( 
				',', 
				wp_parse_id_list( 
					$args['secondary_item_id'] 
				) 
			);
			$where_conditions['secondary_item_id'] = "secondary_item_id IN ({$secondary_item_id_in})";
		}

		if ( ! empty( $args['recipient_id'] ) ) {
			$recipient_id_in                  = implode( ',', wp_parse_id_list( $args['recipient_id'] ) );
			$where_conditions['recipient_id'] = "recipient_id IN ({$recipient_id_in})";
		}

		if ( ! empty( $args['identifier'] ) ) {
			$identifier_in                  = implode( ',', wp_parse_id_list( $args['identifier'] ) );
			$where_conditions['identifier'] = "identifier IN ({$identifier_in})";
		}

		if ( ! empty( $args['log_entry'] ) ) {
			$log_entries	= explode( ',', $args['log_entry'] );

			$log_entry_clean = array();
			foreach ( $log_entries as $log_entry ) {
				$log_entry_clean[] = $wpdb->prepare( '%s', $log_entry );
			}

			$log_entry_in = implode( ',', $log_entry_clean );

			$where_conditions['log_entry'] = "log_entry LIKE ({$log_entry_in})";
		}

		if ( ! empty( $args['component_name'] ) ) {
			if ( ! is_array( $args['component_name'] ) ) {
				$component_names = explode( ',', $args['component_name'] );
			} else {
				$component_names = $args['component_name'];
			}
			$cn_clean = array();
			foreach ( $component_names as $cn ) {
				$cn_clean[] = $wpdb->prepare( '%s', $cn );
			}
			$cn_in                              = implode( ',', $cn_clean );
			$where_conditions['component_name'] = "component_name IN ({$cn_in})";
		}

		if ( ! empty( $args['component_action'] ) ) {
			if ( 'leadership' === $args['component_action'] || 'all' === $args['component_action'] ) {

				$where_conditions['component_action'] = array(
					'cb_send_bits',
					'cb_import_bits',
					'cb_activity_bits',
					'cb_bits_request',
				);

			}

			if ( 'transfers' === $args['component_action'] ) {

				$where_conditions['component_action'] = array('cb_transfer_bits',);

			}

			if ( ! is_array( $args['component_action'] ) ) {
				$component_actions = explode( ',', $args['component_action'] );
			} else {
				$component_actions = $args['component_action'];
			}

			$ca_clean = array();
			foreach ( $component_actions as $ca ) {
				$ca_clean[] = $wpdb->prepare( '%s', $ca );
			}

			$ca_in                                = implode( ',', $ca_clean );
			$where_conditions['component_action'] = "component_action IN ({$ca_in})";
		}

		if ( ! empty( $args['excluded_action'] ) ) {
			if ( ! is_array( $args['excluded_action'] ) ) {
				$excluded_action = explode( ',', $args['excluded_action'] );
			} else {
				$excluded_action = $args['excluded_action'];
			}
			$ca_clean = array();
			foreach ( $excluded_action as $ca ) {
				$ca_clean[] = $wpdb->prepare( '%s', $ca );
			}
			$ca_not_in                           = implode( ',', $ca_clean );
			$where_conditions['excluded_action'] = "component_action NOT IN ({$ca_not_in})";
		}

		if ( ! empty( $args['search_terms'] ) ) {
			$search_terms_like                = '%' . bp_esc_like( $args['search_terms'] ) . '%';
			$where_conditions['search_terms'] = $wpdb->prepare( '( component_name LIKE %s OR component_action LIKE %s OR log_entry LIKE %s )', $search_terms_like, $search_terms_like, $search_terms_like );
		}

		if ( ! empty( $args['exclude_terms'] ) ) {
			$search_terms_not_like                = '%' . bp_esc_like( $args['exclude_terms'] ) . '%';
			$where_conditions['exclude_terms'] = $wpdb->prepare( '( log_entry NOT LIKE %s )', $search_terms_not_like );
		}

		if ( ! empty( $args['date_query'] ) ) {
			$where_conditions['date_query'] = self::get_date_query_sql( $args['date_query'] );
		}
		if ( ! empty( $meta_query_sql['where'] ) ) {
			$where_conditions['meta_query'] = $meta_query_sql['where'];
		}

		if ( ! empty( $args['amount'] ) && ! empty( $args['amount_comparison'] ) ) {
			$where_conditions['amount'] = "amount " . $args['amount_comparison'] . " " . $args['amount'];
		}

		$where_conditions = apply_filters( 'ctg_team_member_get_where_conditions', $where_conditions, $args, $select_sql, $from_sql, $join_sql, $meta_query_sql );

		if ( ! empty( $where_conditions ) ) {
			$where = 'WHERE ' . implode( ' AND ', $where_conditions );
		}

		return $where;

	}

}