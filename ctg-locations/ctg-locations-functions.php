<?php
/**
 * CTG Locations Functions
 *
 * These are all our helper functions related to the
 * locations component.
 */
defined('ABSPATH') || exit;


/**
 * CTG Locations Is Locations Category
 *
 * Uses WP's get_the_category and get_category functions
 * to determine whether or not the user is
 * viewing a CTG location post.
 */
function ctg_locations_is_locations_category()
{

	$success = false;
	$categories = get_the_category();

	foreach ($categories as $category) {

		$parent_category = get_category($category->category_parent);
		if ($parent_category->slug === 'locations') {
			$success = true;
		}

	}

	return $success;

}
add_action('ctg_actions', 'ctg_locations_is_locations_category');

/**
 * CTG Locations Is HQ
 *
 * Uses WP's get_the_category function
 * to determine whether or not the user is
 * viewing a CTG HQ location post.
 */
function ctg_locations_is_hq()
{

	$success = false;
	$categories = get_the_category();

	foreach ($categories as $category) {
		if ($category->slug === 'florida-locations') {
			$success = true;
		}
	}

	return $success;

}
add_action('ctg_actions', 'ctg_locations_is_hq');

function ctg_get_all_locations()
{

	global $wpdb;

	$select_sql = "SELECT *";
	$from_sql = "FROM {$wpdb->base_prefix}ctg_locations";

	$sql = "{$select_sql} {$from_sql}";

	return $wpdb->get_results($sql, 'ARRAY_A');

}

function ctg_locations_add_location($args = array())
{

	$ctg_location = new CTG_Locations_Location();

	$r = ctg_parse_args(
		$args,
		array(
			'street_primary' => '',
			'street_secondary' => '',
			'city' => '',
			'state' => '',
			'zip' => 0,
			'office_location' => '',
			'appointment_only' => false,
			'franchise_location' => false
		)
	);

	$success = false;

	if (empty($r['street_primary'])) {
		$success = false;
		$type = 'error';
		$feedback = __("The location was not added. Please add a primary address line.", 'ctg');
	}

	if (
		!ctype_alnum($r['street_primary']) ||
		!ctype_alnum($r['city']) ||
		!ctype_alnum($r['state']) ||
		!ctype_digit($r['zip'])
	) {
		$success = false;
		$type = 'error';
		$feedback = __('The location was not added. Please remove any special characters from the address form.', 'ctg');

	}

	if (
		!empty($r['street_secondary']) &&
		!ctype_alnum($r['street_secondary'])
	) {
		$success = false;
		$type = 'error';
		$feedback = __('The location was not added. Please remove any special characters from the address line 2 field.', 'ctg');
	}

	if (!preg_match('/[0-9]{5}/', $r['zip'])) {
		$success = false;
		$type = 'error';
		$feedback = __('The location was not added. Please make sure you are using a valid 5-digit postal code.', 'ctg');
	}

	$ctg_location->street_primary = $r['street_primary'];
	$ctg_location->street_secondary = $r['street_secondary'];
	$ctg_location->city = $r['city'];
	$ctg_location->state = $r['state'];
	$ctg_location->zip = $r['zip'];
	$ctg_location->office_location = $r['office_location'];
	$ctg_location->appointment_only = $r['appointment_only'];
	$ctg_location->franchise_location = $r['franchise_location'];

	$new_location = $ctg_location->add_new_location();

	if (!is_int($new_location)) {
		$success = false;
		$type = 'error';
		$feedback = __('The location was not added. We couldn\'t verify the address.', 'ctg');
	}

	ctg_core_add_message($feedback, $type);

}

function ctg_locations_get_location( $args = array() ) {

	$r = ctg_parse_args(
		$args,
		array(
			'select'		=> '',
			'where'			=> array(
				'street_primary' => '',
				'street_secondary' => '',
				'city' => '',
				'state' => '',
				'zip' => 0,
				'office_location' => '',
				'appointment_only' => false,
				'franchise_location' => false
			),
			'groupby'		=> '',
			'pagination'	=> array()
		)
	);

	$location = new CTG_Locations_Location();

	$location->get_location($r);

}

function ctg_locations_new_location_handler()
{

	if (ctg_is_post_request()) {
		if (isset($_POST['ctg_locations_submit'])) {
			$args = array(
				'street_primary' => $_POST['ctg_street_primary'],
				'street_secondary' => (
					!empty($_POST['ctg_street_secondary']) ?
					$_POST['ctg_street_secondary'] : ''
				),
				'city' => $_POST['ctg_city'],
				'state' => $_POST['ctg_state'],
				'zip' => $_POST['ctg_zip'],
				'office_location' => $_POST['ctg_office_location'],
				'appointment_only' => $_POST['ctg_appointment_only'],
			);

			ctg_locations_add_location($args);

			ctg_core_redirect('locations');

		}
	}
}
add_action('ctg_actions', 'ctg_locations_new_location_handler');

/**
 * CTG Locations Locations
 * 
 * Location, location, location...
 * Return bulk array of CTG locations and their data.
 * 
 * This array will be used by ctg_locations_populate_table()
 * on activation to populate placeholder data 
 * in the locations table.
 *
 * @return array $locations
 */
function ctg_locations_locations()
{
	$locations = array(
		array(
			'id' => 1,
			'street_primary' => '999 S Douglas Avenue',
			'street_secondary' => 'Suite 3311',
			'city' => 'Altamonte Springs',
			'state' => 'FL',
			'zip' => '32714',
			'phone' => '(407) 801-9776',
			'location_name' => 'Altamonte Springs',
			'appointment_only' => false,
			'franchise_location' => false,
		),
		array(
			'id' => 2,
			'street_primary' => '17410 Hwy 50',
			'street_secondary' => 'Suite 230',
			'city' => 'Clermont',
			'state' => 'FL',
			'zip' => '34711',
			'phone' => '(407) 801-9776',
			'location_name' => 'Clermont',
			'appointment_only' => false,
			'franchise_location' => false,
		),
		array(
			'id' => 3,
			'street_primary' => '950 Celebration Blvd',
			'street_secondary' => 'Suite D',
			'city' => 'Celebration',
			'state' => 'FL',
			'zip' => '34747',
			'phone' => '(407) 801-9776',
			'location_name' => 'Celebration',
			'appointment_only' => false,
			'franchise_location' => false,
		),
		array(
			'id' => 4,
			'street_primary' => '140 S Beach Street, Kress Building',
			'street_secondary' => 'Suite 105',
			'city' => 'Daytona Beach',
			'state' => 'FL',
			'zip' => '32114',
			'phone' => '(407) 801-9776',
			'location_name' => 'Daytona',
			'appointment_only' => false,
			'franchise_location' => false,
		),
		array(
			'id' => 5,
			'street_primary' => '211 East Colonial Dr',
			'street_secondary' => '',
			'city' => 'Orlando',
			'state' => 'FL',
			'zip' => '32801',
			'phone' => '(407) 801-9776',
			'location_name' => 'Downtown Orlando',
			'appointment_only' => false,
			'franchise_location' => false,
		),
		array(
			'id' => 6,
			'street_primary' => '6052 Turkey Lake Road',
			'street_secondary' => 'Suite 204',
			'city' => 'Orlando',
			'state' => 'FL',
			'zip' => '32819',
			'phone' => '(407) 801-9776',
			'location_name' => 'Dr. Phillips (Orlando)',
			'appointment_only' => false,
			'franchise_location' => false,
		),
		array(
			'id' => 7,
			'street_primary' => '2700 W Cypress Creek Rd',
			'street_secondary' => 'Suite D 101',
			'city' => 'Ft. Lauderdale',
			'state' => 'FL',
			'zip' => '33309',
			'phone' => '(407) 801-9776',
			'location_name' => 'Ft. Lauderdale',
			'appointment_only' => false,
			'franchise_location' => false,
		),
		array(
			'id' => 8,
			'street_primary' => '12653 New Brittandy Blvd',
			'street_secondary' => 'Unit 13E',
			'city' => 'Ft. Myers',
			'state' => 'FL',
			'zip' => '33907',
			'phone' => '(407) 801-9776',
			'location_name' => 'Ft. Myers',
			'appointment_only' => false,
			'franchise_location' => false,
		),
		array(
			'id' => 9,
			'street_primary' => '6550 N. Wickham Rd',
			'street_secondary' => 'Suite 8',
			'city' => 'Melbourne',
			'state' => 'FL',
			'zip' => '32840',
			'phone' => '(407) 801-9776',
			'location_name' => 'Melbourne',
			'appointment_only' => false,
			'franchise_location' => false,
		),
		array(
			'id' => 10,
			'street_primary' => '603 E Fort King St',
			'street_secondary' => '',
			'city' => 'Ocala',
			'state' => 'FL',
			'zip' => '34471',
			'phone' => '(407) 801-9776',
			'location_name' => 'Ocala',
			'appointment_only' => true,
			'franchise_location' => false,
		),
		array(
			'id' => 11,
			'street_primary' => '400 S. Atlantic Ave',
			'street_secondary' => 'Suite 105',
			'city' => 'Ormond Beach',
			'state' => 'FL',
			'zip' => '32176',
			'phone' => '(407) 801-9776',
			'location_name' => 'Ormond',
			'appointment_only' => true,
			'franchise_location' => false,
		),
		array(
			'id' => 12,
			'street_primary' => '469 S Central Ave',
			'street_secondary' => '',
			'city' => 'Oviedo',
			'state' => 'FL',
			'zip' => '32765',
			'phone' => '(407) 801-9776',
			'location_name' => 'Oviedo',
			'appointment_only' => false,
			'franchise_location' => false,
		),
		array(
			'id' => 13,
			'street_primary' => '500 Canal St',
			'street_secondary' => '',
			'city' => 'New Smyrna Beach',
			'state' => 'FL',
			'zip' => '32168',
			'phone' => '(407) 801-9776',
			'location_name' => 'New Smyrna Beach',
			'appointment_only' => true,
			'franchise_location' => false,
		),
		array(
			'id' => 14,
			'street_primary' => '701 S Howard Ave',
			'street_secondary' => 'Suite 204',
			'city' => 'Tampa',
			'state' => 'FL',
			'zip' => '33606',
			'phone' => '(407) 801-9776',
			'location_name' => 'Tampa',
			'appointment_only' => true,
			'franchise_location' => false,
		),
		array(
			'id' => 15,
			'street_primary' => '6330 Cypress Gardens Blvd',
			'street_secondary' => '',
			'city' => 'Winter Haven',
			'state' => 'FL',
			'zip' => '33884',
			'phone' => '(407) 801-9776',
			'location_name' => 'Winter Haven',
			'appointment_only' => false,
			'franchise_location' => false,
		),
		array(
			'id' => 16,
			'street_primary' => '946 Heights Blvd',
			'street_secondary' => '',
			'city' => 'Houston',
			'state' => 'TX',
			'zip' => '77008',
			'phone' => '(346) 509-4463',
			'location_name' => 'CTG Houston',
			'appointment_only' => false,
			'franchise_location' => true,
		),
		array(
			'id' => 17,
			'street_primary' => '103 E Maumee St',
			'street_secondary' => '',
			'city' => 'Adrian',
			'state' => 'MI',
			'zip' => '49221',
			'phone' => '(517) 280-5025',
			'location_name' => 'CTG Adrian',
			'appointment_only' => false,
			'franchise_location' => true,
		),
		array(
			'id' => 18,
			'street_primary' => '14401 Sommerville Ct',
			'street_secondary' => 'Suite A',
			'city' => 'Midlothian',
			'state' => 'VA',
			'zip' => '23113',
			'phone' => '(804) 500-2760',
			'location_name' => 'CTG Midlothian',
			'appointment_only' => false,
			'franchise_location' => true,
		),
		array(
			'id' => 19,
			'street_primary' => '1120 Hillcrest Rd',
			'street_secondary' => 'Unit 1A',
			'city' => 'Mobile',
			'state' => 'AL',
			'zip' => '36695',
			'phone' => '',
			'location_name' => 'CTG Mobile',
			'appointment_only' => false,
			'franchise_location' => true,
		),
	);

	return $locations;
}

function ctg_locations_state_options() {
	return array(
		"AL"=>"Alabama",
		"AK"=>"Alaska",
		"AZ"=>"Arizona",
		"AR"=>"Arkansas",
		"CA"=>"California",
		"CO"=>"Colorado",
		"CT"=>"Connecticut",
		"DE"=>"Delaware",
		"DC"=>"District Of Columbia",
		"FL"=>"Florida",
		"GA"=>"Georgia",
		"HI"=>"Hawaii",
		"ID"=>"Idaho",
		"IL"=>"Illinois",
		"IN"=>"Indiana",
		"IA"=>"Iowa",
		"KS"=>"Kansas",
		"KY"=>"Kentucky",
		"LA"=>"Louisiana",
		"ME"=>"Maine",
		"MD"=>"Maryland",
		"MA"=>"Massachusetts",
		"MI"=>"Michigan",
		"MN"=>"Minnesota",
		"MS"=>"Mississippi",
		"MO"=>"Missouri",
		"MT"=>"Montana",
		"NE"=>"Nebraska",
		"NV"=>"Nevada",
		"NH"=>"New Hampshire",
		"NJ"=>"New Jersey",
		"NM"=>"New Mexico",
		"NY"=>"New York",
		"NC"=>"North Carolina",
		"ND"=>"North Dakota",
		"OH"=>"Ohio",
		"OK"=>"Oklahoma",
		"OR"=>"Oregon",
		"PA"=>"Pennsylvania",
		"RI"=>"Rhode Island",
		"SC"=>"South Carolina",
		"SD"=>"South Dakota",
		"TN"=>"Tennessee",
		"TX"=>"Texas",
		"UT"=>"Utah",
		"VT"=>"Vermont",
		"VA"=>"Virginia",
		"WA"=>"Washington",
		"WV"=>"West Virginia",
		"WI"=>"Wisconsin",
		"WY"=>"Wyoming"
	);
}

/**
 * CTG Locations Populate Table
 * 
 * Populates the locations table with placeholder data.
 * 
 */
function ctg_locations_populate_table() {

	$populated = get_option('ctg_locations_populated');

	if ( ! $populated ) {

		$ctg		= ctg();
		$table_name	= $ctg->locations->table_name;
		$locations	= ctg_locations_locations();

		foreach ( $locations as $add ) {
			$location = new CTG_Locations_Location();
			$location->street_primary		= $add['street_primary'];
			$location->street_secondary		= $add['street_secondary'];
			$location->city					= $add['city'];
			$location->state				= $add['state'];
			$location->zip					= $add['zip'];
			$location->phone				= $add['phone'];
			$location->location_name		= $add['location_name'];
			$location->appointment_only		= $add['appointment_only'];
			$location->franchise_location	= $add['franchise_location'];

			$saved = $location->add_location();

		}

		update_option('ctg_locations_populated', true );
	}

}
add_action( 'ctg_loaded', 'ctg_locations_populate_table' );


/**
 * CTG AJAX Get Location By ID
 *
 * @param	array 	$args	Array of location options to use. {
 *
 * 		@var	int		$id		Location ID. Default empty.
 * 		@var	string	$street_primary	Location primary street number. Default empty.
 * 		@var	string	$street_secondary	Location secondary street number. Default empty.
 * 		@var	string	$city	Location city. Default empty.
 * 		@var	string	$state	Location state. Default empty.
 * 		@var	string	$zip		Location zip. Default empty.
 * 		@var	string	$phone	Location phone number. Default empty.
 * 		@var	string	$location_name	Internal name for the location. Default empty.
 * 		@var	bool	$franchise_location		Whether or not the location is a franchise location. Default false.
 * 		@var	bool	$appointment_only		Wether or not the location is appointment only. Default false.
 * 		@var	int		$page		Page number. Default 0.
 *
 * }
 *
 * @return	array	Array of locations.
 */
function ctg_ajax_get_location_by_id()
{

	if (!isset($_GET['id'])) {
		return;
	}

	$feedback = array(
		'text' => "",
		'type' => ""
	);

	$id = intval($_GET['id']);
	$location = new CTG_Locations_Location();
	$found = $location->get_location(
		array(
			'where' => array(
				'id' => $id
			)
		)
	);


	$feedback = (!empty($found)) ? array(
		"text" => json_encode($found),
		"type" => "success"
	) :
	array(
		"text" => "'Empty results set.'",
		"type" => "error"
	);

	echo json_encode($feedback);
	die();

}
add_action('wp_ajax_ctg_get_location_by_id', 'ctg_ajax_get_location_by_id');

/**
 * CTG AJAX Update Location
 *
 * @param	array 	$args	Array of location options to use. {
 *
 * 		@var	int		$id		Location ID. Default empty.
 * 		@var	string	$street_primary	Location primary street number. Default empty.
 * 		@var	string	$street_secondary	Location secondary street number. Default empty.
 * 		@var	string	$city	Location city. Default empty.
 * 		@var	string	$state	Location state. Default empty.
 * 		@var	string	$zip		Location zip. Default empty.
 * 		@var	string	$phone	Location phone number. Default empty.
 * 		@var	string	$location_name	Internal name for the location. Default empty.
 * 		@var	bool	$franchise_location		Whether or not the location is a franchise location. Default false.
 * 		@var	bool	$appointment_only		Wether or not the location is appointment only. Default false.
 * 		@var	int		$page		Page number. Default 0.
 *
 * }
 *
 * @return	array	Array of locations.
 *
 */
function ctg_ajax_update_location()
{

	if (!isset($_POST['ctg_location_id'])) {
		return;
	}

	$id = intval($_POST['ctg_location_id']);

	$location = new CTG_Locations_Location($id);

	$feedback = array(
		'text' => "",
		'type' => ""
	);

	$update = array();
	$where = array('id' => $id);

	$address_validate = ctg_core_validate_address(
		array(
			'street_primary' => $_POST['ctg_street_primary'],
			'street_secondary' => $_POST['ctg_street_secondary'],
			'city' => $_POST['ctg_city'],
			'state' => $_POST['ctg_state'],
			'zip' => $_POST['ctg_zip'],
			'phone' => $_POST['ctg_phone'],
			'location_name' => $_POST['ctg_location_name'],
		)
	);

	if ($address_validate['street_primary']) {
		$update['street_primary'] = $_POST['ctg_street_primary'];
	} else {
		$feedback = array(
			'text' => 'Please remove any special characters and numbers from address line 1.',
			'type' => "error"
		);
	}

	if ($address_validate['street_secondary']) {
		$update['street_secondary'] = $_POST['ctg_street_secondary'];
	} else {
		$feedback = array(
			'text' => 'Please remove any special characters and numbers from address line 2.',
			'type' => "error"
		);
	}

	if ($address_validate['city']) {
		$update['city'] = $_POST['ctg_city'];
	} else {
		$feedback = array(
			'text' => 'Please remove any special characters from city.',
			'type' => "error"
		);
	}

	if ($address_validate['state']) {
		$update['state'] = $_POST['ctg_state'];
	} else {
		$feedback = array(
			'text' => 'Please remove any special characters from state.',
			'type' => "error"
		);
	}

	if ($address_validate['zip']) {
		$update['zip'] = $_POST['ctg_zip'];
	} else {
		$feedback = array(
			'text' => 'Please remove any special characters from zip.',
			'type' => "error"
		);
	}

	if ($address_validate['phone']) {
		$update['phone'] = $_POST['ctg_phone'];
	} else {
		$feedback = array(
			'text' => 'Please remove any special characters from phone.',
			'type' => "error"
		);
	}

	if ($address_validate['location_name']) {
		$update['location_name'] = $_POST['ctg_location_name'];
	} else {
		$feedback = array(
			'text' => 'Please remove any special characters from location name.',
			'type' => "error"
		);
	}

	if ( isset($_POST['ctg_franchise_location']) ) {
		$update['franchise_location'] = intval($_POST['ctg_franchise_location']);
	} else {
		$update['franchise_location'] = 0;
	}

	if ( isset($_POST['ctg_appointment_only'] ) ) {
		$update['appointment_only'] = intval($_POST['ctg_appointment_only']);
	} else {
		$update['appointment_only'] = 0;	
	}

	if (in_array(false, $address_validate)) {
		echo json_encode($feedback);
	} else {
		$updated = $location->update($update, $where);
		$feedback = $updated === false ? array(
			"text" => "Error processing location update.",
			"type" => "error"
		) : array(
			"text" => "Successfully updated {$location->location_name}'s info.",
			"type" => "success"
		);
	}

	do_action('ctg_ajax_update_location');

	echo json_encode($feedback);
	die();

}
add_action('wp_ajax_ctg_update_location', 'ctg_ajax_update_location');

/**
 * CTG AJAX Add Location
 *
 * @param	array 	$args	Array of location options to use. {
 *
 * 		@var	int		$id		Location ID. Default empty.
 * 		@var	string	$street_primary	Location first name. Default empty.
 * 		@var	string	$last_name	Location last name. Default empty.
 * 		@var	string	$zip	Location job title. Default empty.
 * 		@var	int		$phone	Location location ID. Default 0.
 * 		@var	string	$city		Location city. Default empty.
 * 		@var	string	$headshot_url	Location headshot URL. Default empty.
 * 		@var	int		$per_page	Number of locations to return per page. Default -1.
 * 		@var	int		$page		Page number. Default 0.
 *
 * }
 *
 * @return	array	Array of locations.
 *
 */
function ctg_ajax_add_location()
{

	$location = new CTG_Locations_Location();
	$feedback = array(
		"text" => "",
		"type" => ""
	);
	$street_primary = '';
	$street_secondary = '';
	$city = '';
	$state = '';
	$zip = '';
	$location_name = '';
	$franchise_location = false;
	$appointment_only = false;

	if (
		!isset(
			$_POST['ctg_street_primary'],
			$_POST['ctg_city'],
			$_POST['ctg_state'],
			$_POST['ctg_zip']
		)
	) {
		return;
	}

	$address_validate = ctg_core_validate_address(
		array(
			'street_primary' => $_POST['ctg_street_primary'],
			'street_secondary' => $_POST['ctg_street_secondary'],
			'city' => $_POST['ctg_city'],
			'state' => $_POST['ctg_state'],
			'zip' => $_POST['ctg_zip'],
			'phone' => $_POST['ctg_phone'],
			'location_name' => $_POST['ctg_location_name'],
		)
	);

	if ($address_validate['street_primary']) {
		$street_primary = $_POST['ctg_street_primary'];
	} else {
		$feedback = array(
			'text' => 'Please remove any special characters and numbers from address line 1.',
			'type' => "error"
		);
	}

	if ($address_validate['street_secondary']) {
		$street_secondary = $_POST['ctg_street_secondary'];
	} else {
		$feedback = array(
			'text' => 'Please remove any special characters and numbers from address line 2.',
			'type' => "error"
		);
	}

	if ($address_validate['city']) {
		$city = $_POST['ctg_city'];
	} else {
		$feedback = array(
			'text' => 'Please remove any special characters from city.',
			'type' => "error"
		);
	}

	if ($address_validate['state']) {
		$state = $_POST['ctg_state'];
	} else {
		$feedback = array(
			'text' => 'Please remove any special characters from state.',
			'type' => "error"
		);
	}

	if ($address_validate['zip']) {
		$zip = $_POST['ctg_zip'];
	} else {
		$feedback = array(
			'text' => 'Please remove any special characters from zip.',
			'type' => "error"
		);
	}

	if ($address_validate['phone']) {
		$phone = $_POST['ctg_phone'];
	} else {
		$feedback = array(
			'text' => 'Please remove any special characters from phone.',
			'type' => "error"
		);
	}

	if ($address_validate['location_name']) {
		$location_name = $_POST['ctg_location_name'];
	} else {
		$feedback = array(
			'text' => 'Please remove any special characters from location name.',
			'type' => "error"
		);
	}

	if (isset($_POST['ctg_franchise_location'])) {
		$franchise_location = intval($_POST['ctg_franchise_location']) === 1;
	}

	if (isset($_POST['ctg_appointment_only'])) {
		$appointment_only = intval($_POST['ctg_appointment_only']) === 1;
	}

	if (in_array(false, $address_validate)) {
		echo json_encode($feedback);
		die();
	}
	if (
		!empty($street_primary) &&
		!empty($street_secondary) &&
		!empty($city) &&
		!empty($state) &&
		!empty($zip)
	) {
		$location->street_primary = $street_primary;
		$location->city = $city;
		$location->state = $state;
		$location->zip = $zip;
		if (!empty($street_secondary)) {
			$location->street_secondary = $street_secondary;
		}
		if (!empty($phone)) {
			$location->phone = $phone;
		}
		if (!empty($location_name)) {
			$location->location_name = $location_name;
		}
		if (!empty($franchise_location)) {
			$location->franchise_location = $franchise_location;
		}
		if (!empty($appointment_only)) {
			$location->appointment_only = $appointment_only;
		}

		$added = $location->add_location();
		$feedback = $added ? array(
			"text" => "Successfully added {$location->street_primary}. Welcome to CTG!",
			"type" => "success"
		) :
		array(
			"text" => "Failed to process location addition.",
			"type" => "error"
		);
	}

	do_action('ctg_ajax_add_location');

	echo json_encode($feedback);
	die();

}
add_action('wp_ajax_ctg_add_location', 'ctg_ajax_add_location');

/**
 * CTG AJAX Delete Location
 *
 * @param	array 	$args	Array of location options to use. {
 *
 * 		@var	int		$id		Location ID. Default empty.
 * 		@var	string	$street_primary	Location first name. Default empty.
 * 		@var	string	$last_name	Location last name. Default empty.
 * 		@var	string	$zip	Location job title. Default empty.
 * 		@var	int		$phone	Location location ID. Default 0.
 * 		@var	string	$city		Location city. Default empty.
 * 		@var	string	$headshot_url	Location headshot URL. Default empty.
 * 		@var	int		$per_page	Number of locations to return per page. Default -1.
 * 		@var	int		$page		Page number. Default 0.
 *
 * }
 *
 * @return	array	Array of locations.
 *
 */
function ctg_ajax_delete_location()
{

	if (!isset($_POST['id'])) {
		return;
	}

	$id = intval($_POST['id']);

	$location = new CTG_Locations_Location();
	$feedback = array(
		"text" => "",
		"type" => ""
	);

	if (!wp_verify_nonce($_POST['ctg_delete_location_nonce'], 'ctg_delete_location')) {
		$feedback = array(
			"text" => "Invalid nonce parameter.",
			"type" => "error"
		);
	} else {
		$deleted = $location->delete(
			array(
				'id' => $id
			)
		);
		$feedback = $deleted ? array(
			"text" => "Successfully removed {$location->street_primary} from the database.",
			"type" => "success"
		) :
		array(
			"text" => "Failed to process location deletion.",
			"type" => "error"
		);
	}

	do_action('ctg_ajax_delete_location');
	echo json_encode($feedback);
	die();

}
add_action('wp_ajax_ctg_delete_location', 'ctg_ajax_delete_location');

function ctg_locations_refresh_table()
{

	$table = new CTG_Locations_Admin_Table();
	$table->prepare_items();
	return $table->display_rows();

}
add_action('wp_ajax_ctg_refresh_locations_table', 'ctg_locations_refresh_table');