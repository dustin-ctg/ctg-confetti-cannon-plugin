<?php
if (!class_exists('WP_List_Table')) {
	require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class CTG_Locations_Admin_Table extends WP_List_Table
{
	/** Class constructor */
	public function __construct()
	{

		parent::__construct([
			'singular' => __('Location', 'ctg'),
			//singular name of the listed records
			'plural' => __('Locations', 'ctg'),
			//plural name of the listed records
			'ajax' => true //should this table support ajax?
		]);
	}

	public static function get_locations($per_page = 25, $page_number = 0)
	{
		global $wpdb;
		$sql = "SELECT * FROM {$wpdb->prefix}ctg_locations";
		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ($page_number - 1) * $per_page;

		$result = $wpdb->get_results($sql, 'ARRAY_A');
		return $result;

	}

	public static function delete_location($id)
	{
		global $wpdb;

		$wpdb->delete(
			"{$wpdb->prefix}ctg_locations",
			['id' => $id],
			['%d']
		);
	}

	public static function record_count()
	{
		global $wpdb;

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}ctg_locations";

		return $wpdb->get_var($sql);
	}

	/** Text displayed when no location data is available */
	public function no_items()
	{
		_e('No locations to be seen.', 'ctg');
	}

	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_name($item)
	{

		$title = "<strong>{$item['location_name']}</strong>";
		$page_args = array(
			'page' => isset($_REQUEST['page']) ? $_REQUEST['page'] : ''
		);

		$actions = [
			'delete' => sprintf(
				'<a class="ctg-location-delete-link" data-location-id="%d" href="#">Delete</a>',
				absint($item['id'])
			),
			'edit' => sprintf(
				'<a class="ctg-location-edit-link" data-location-id="%d" href="#">Edit</a>',
				absint($item['id']),
			),
		];

		return $title . $this->row_actions($actions);
	}

	/**
	 * Render a column when no column specific method exists.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default($item, $column_name)
	{
		switch ($column_name) {
			case 'street_primary':
			case 'street_secondary':
			case 'city':
			case 'state':
			case 'zip':
			case 'phone':
			case 'email':
			case 'appointment_only':
			case 'franchise_location':
				return $item[$column_name];
			case 'location_name':
				return $this->column_name($item);
			default:
				return print_r($item, true); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb($item)
	{
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
		);
	}

	/**
	 * Associative array of columns
	 *
	 * @return array
	 */
	function get_columns()
	{
		$columns = [
			'cb' => '<input type="checkbox" />',
			'location_name' => __('Location Name', 'ctg'),
			'street_primary' => __('Street Address', 'ctg'),
			'street_secondary' => __('Street Address 2', 'ctg'),
			'city' => __('City', 'ctg'),
			'state' => __('State', 'ctg'),
			'zip' => __('Zip', 'ctg'),
			'phone' => __('Phone Number', 'ctg'),
			'email' => __('Email Address', 'ctg'),
			'appointment_only' => __('Appointment Only', 'ctg'),
			'franchise_location' => __('Franchise Location', 'ctg'),
		];

		return $columns;
	}

	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns()
	{
		$sortable_columns = array(
			'name' => array('location_name', true),
			'street_primary' => array('street_primary', true),
			'appointment_only' => array('appointment_only', true),
			'franchise_location' => array('franchise_location', true),
		);

		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions()
	{
		$actions = [
			'bulk-delete' => 'Delete'
		];

		return $actions;
	}

	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items()
	{

		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page = $this->get_items_per_page('locations_per_page', 5);
		$current_page = $this->get_pagenum();
		$total_items = self::record_count();

		$this->set_pagination_args([
			'total_items' => $total_items,
			//WE have to calculate the total number of items
			'per_page' => $per_page //WE have to determine how many items to show on a page
		]);

		$this->items = self::get_locations($per_page, $current_page);
	}

	public function process_bulk_action()
	{

		//Detect when a bulk action is being triggered...
		if ('delete' === $this->current_action()) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr($_REQUEST['_wpnonce']);

			if (!wp_verify_nonce($nonce, 'ctg_delete_location')) {
				die('Go get a life script kiddies');
			} else {
				self::delete_location(absint($_GET['location_id']));

				wp_redirect(esc_url(add_query_arg()));
				exit;
			}

		}

		// If the delete bulk action is triggered
		if (
			(isset($_POST['action']) && $_POST['action'] == 'bulk-delete')
			|| (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete')
		) {

			$delete_ids = esc_sql($_POST['bulk-delete']);

			// loop over the array of record IDs and delete them
			foreach ($delete_ids as $id) {
				self::delete_location($id);

			}

			wp_redirect(esc_url(add_query_arg()));
			exit;
		}
	}
}