<?php 
/**
 * CTG Members Members
 * 
 * Member, member, member...
 * Return bulk array of CTG team members and their data.
 * 
 * This array will be used by ctg_members_populate_table()
 * on activation to populate placeholder data 
 * in the members table.
 *
 * @return array $members
 */
function ctg_members_members()
{
	$members = array(
		array(
			'first_name' => 'Dustin',
			'last_name' => 'Delgross',
			'job_title' => 'Web Developer',
			'location_id' => 0,
			'email' => 'dustin@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Amanda',
			'last_name' => 'Douglas',
			'job_title' => 'CEO',
			'location_id' => 3,
			'email' => 'amanda@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Ali',
			'last_name' => 'Braun',
			'job_title' => 'Implementation Specialist',
			'location_id' => 6,
			'email' => 'ali@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Robin',
			'last_name' => 'Winkler',
			'job_title' => 'Lead Success Partner',
			'location_id' => 4,
			'email' => 'robin@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Jacqueline',
			'last_name' => 'Arikape',
			'job_title' => 'Success Partner',
			'location_id' => 7,
			'email' => 'jacqueline@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Rob',
			'last_name' => 'Modeszto',
			'job_title' => 'Director of Franchising',
			'location_id' => 14,
			'email' => 'rob@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'David',
			'last_name' => 'Quinones',
			'job_title' => 'Success Partner',
			'location_id' => 1,
			'email' => 'david@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Jody',
			'last_name' => 'Seltzer',
			'job_title' => 'Vice President',
			'location_id' => 5,
			'email' => 'jody@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Brenda',
			'last_name' => 'Nunez',
			'job_title' => 'Processor',
			'location_id' => 3,
			'email' => 'brenda@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Nick',
			'last_name' => 'Minton',
			'job_title' => 'Digital Marketing Manager',
			'location_id' => 5,
			'email' => 'nick@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Katherine',
			'last_name' => 'Barron',
			'job_title' => 'Escrow Officer',
			'location_id' => 15,
			'email' => 'kathy@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Mariah',
			'last_name' => 'Miller',
			'job_title' => 'Processor',
			'location_id' => 15,
			'email' => 'mariah@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Priscian',
			'last_name' => 'Camacho',
			'job_title' => 'Jr Processor',
			'location_id' => 3,
			'email' => 'priscian@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Scott',
			'last_name' => 'Bevan',
			'job_title' => 'Celebration Expert | Office Lead',
			'location_id' => 3,
			'email' => 'scott@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Heather',
			'last_name' => 'Hopwood',
			'job_title' => 'Escrow Closer | Office Leader',
			'location_id' => 7,
			'email' => 'heather@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Jillian',
			'last_name' => 'Howard',
			'job_title' => 'Operations Training Captain & Franchise Trainer',
			'location_id' => 6,
			'email' => 'jillian@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Samantha',
			'last_name' => 'Albert',
			'job_title' => 'Processor',
			'location_id' => 3,
			'email' => 'samantha@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Lauren',
			'last_name' => 'Lasso',
			'job_title' => 'Finance Manager',
			'location_id' => 6,
			'email' => 'lauren@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Shannon',
			'last_name' => 'Soucie',
			'job_title' => 'Processor',
			'location_id' => 9,
			'email' => 'shannon@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Patricia',
			'last_name' => 'Holladay',
			'job_title' => 'Celebration Expert',
			'location_id' => 1,
			'email' => 'patricia@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Missy',
			'last_name' => 'Griffin',
			'job_title' => 'Celebration Expert',
			'location_id' => 4,
			'email' => 'missy@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Maria',
			'last_name' => 'Popadak',
			'job_title' => 'Administrative Assistant',
			'location_id' => 15,
			'email' => 'maria@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Bobby',
			'last_name' => 'Baldor',
			'job_title' => 'Post Closing Intern',
			'location_id' => 6,
			',email' => 'bobby@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Jessica',
			'last_name' => 'Hernandez',
			'job_title' => 'Processor',
			'location_id' => 3,
			'email' => 'jessica.h@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Katie',
			'last_name' => 'Atkinson',
			'job_title' => 'Professional Celebrator',
			'location_id' => 0,
			'email' => 'katie@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Justin',
			'last_name' => 'Lopez',
			'job_title' => 'Documents Wizard',
			'location_id' => 15,
			'email' => 'justin@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Sherry',
			'last_name' => 'Permanand',
			'job_title' => 'Recording Specialist',
			'location_id' => 6,
			'email' => 'sherry@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'TJ',
			'last_name' => 'Brown',
			'job_title' => 'Post Closing Extraordinaire',
			'location_id' => 6,
			'email' => 'tj@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Nishaa',
			'last_name' => 'Johnson',
			'job_title' => 'Processor',
			'location_id' => 6,
			'email' => 'nishaa@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Jensherise',
			'last_name' => 'Samayoa',
			'job_title' => 'Success Partner',
			'location_id' => 5,
			'email' => 'jensherise@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Luis',
			'last_name' => 'Sarcos',
			'job_title' => 'Success Partner',
			'location_id' => 5,
			'email' => 'luis@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Pat',
			'last_name' => 'Hopwood',
			'job_title' => 'Escrow Closer | Office Leader',
			'location_id' => 15,
			'email' => 'pat@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Jami',
			'last_name' => 'Miller',
			'job_title' => 'Lead Celebration Expert',
			'location_id' => 8,
			'email' => 'jami@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Gail',
			'last_name' => 'Douglas',
			'job_title' => 'Director of Finance',
			'location_id' => 6,
			'email' => 'gail@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Carlos',
			'last_name' => 'Cruz',
			'job_title' => 'Celebration Expert',
			'location_id' => 5,
			'email' => 'carlos@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Maria',
			'last_name' => 'Brawne',
			'job_title' => 'Celebration Expert Developer',
			'location_id' => 6,
			'email' => 'maria@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Kya',
			'last_name' => 'Wolfe',
			'job_title' => 'Senior Processor',
			'location_id' => 6,
			'email' => 'kya@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Melanie',
			'last_name' => 'Ramirez',
			'job_title' => 'Processor | Office Lead',
			'location_id' => 6,
			'email' => 'melanie@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Debra',
			'last_name' => 'Ali',
			'job_title' => 'Processor',
			'location_id' => 7,
			'email' => 'debra@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Joey',
			'last_name' => 'James',
			'job_title' => 'Chief of Staff',
			'location_id' => 6,
			'email' => 'joey@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Tristan',
			'last_name' => 'Ali',
			'job_title' => 'Celebration Expert',
			'location_id' => 5,
			'email' => 'tristan@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Lorena',
			'last_name' => 'Ramos',
			'job_title' => 'Legal Counsel',
			'location_id' => 6,
			'email' => 'lorena@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Bailey',
			'last_name' => 'Canales',
			'job_title' => 'Success Partner',
			'location_id' => 9,
			'email' => 'bailey@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Alisha',
			'last_name' => 'Railsback - Schroeder',
			'job_title' => 'Escrow Officer',
			'location_id' => 3,
			'email' => 'alisha@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Becky',
			'last_name' => 'Douglas',
			'job_title' => 'Director of Operations',
			'location_id' => 6,
			'email' => 'becky@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Shantalle',
			'last_name' => 'Bevan',
			'job_title' => 'Senior Escrow Officer',
			'location_id' => 6,
			'email' => 'shantalle@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Rachel',
			'last_name' => 'Suarez',
			'job_title' => 'In Loving Memory',
			'location_id' => NULL,
			'email' => NULL,
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Allyssa',
			'last_name' => 'Ortiz',
			'job_title' => 'Contract Specialist',
			'location_id' => 5,
			'email' => 'allyssa@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Cecilia',
			'last_name' => 'Samayoa',
			'job_title' => 'Jr Processor',
			'location_id' => 2,
			'email' => 'cecilia@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Christopher',
			'last_name' => 'Ungaro',
			'job_title' => 'Administrative Assistant',
			'location_id' => 1,
			'email' => 'chris@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Bryanna',
			'last_name' => 'Rosario',
			'job_title' => 'Administrative Assistant Captain',
			'loction_id' => 5,
			'email' => 'bryanna@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Giovanna',
			'last_name' => 'Ciccone',
			'job_title' => 'Marketing Specialist',
			'location_id' => 5,
			'email' => 'giovanna@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Davianet',
			'last_name' => 'Cruz',
			'job_title' => NULL,
			'location_id' => 6,
			'email' => 'davianet@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Bekka',
			'last_name' => 'Larson',
			'job_title' => 'Escrow Officer',
			'location_id' => 6,
			'email' => 'bekka@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Lauren',
			'last_name' => 'Rodriguez-Flores',
			'job_title' => 'Operations Training Specialist',
			'location_id' => 5,
			'email' => 'laurenrodriguez@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Megan',
			'last_name' => 'Szkarlat',
			'job_title' => 'Post Closing Specialist',
			'location_id' => 6,
			'email' => 'megan@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Tara',
			'last_name' => 'Hendrickson',
			'job_title' => 'Processor',
			'location_id' => 9,
			'email' => 'tara@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Vanessa',
			'last_name' => 'Maxey',
			'job_title' => 'Administrative Assistant',
			'location_id' => 8,
			'email' => 'vanessa@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Erica',
			'last_name' => 'Cochran',
			'job_title' => 'National Director of Marketing',
			'location_id' => 5,
			'email' => 'erica@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Nicole',
			'last_name' => 'Fonseca',
			'job_title' => 'Celebration Expert',
			'location_id' => 9,
			'email' => 'nicole@celebrationtitlegroup.com',
			'headshot_url' => ''
		),
		array(
			'first_name' => 'Katelyn',
			'last_name' => 'Taylor',
			'job_title' => 'Success Partner',
			'location_id' => 14,
			'email' => 'katelyn@celebrationtitlegroup.com',
			'headshot_url' => ''
		)
	);

	return $members;
}

/**
 * CTG Locations Populate Table
 * 
 * Populates the locations table with placeholder data.
 * 
 */
function ctg_members_populate_table() {

	$populated = get_option('ctg_members_populated');
	$ctg = ctg();
	if ( ! $populated ) {

		$ctg		= ctg();
		$table_name	= $ctg->members->table_name;
		$members	= ctg_members_members();

		foreach ( $members as $add ) {

			$member = new CTG_Members_Member();
			$member->first_name		= $add['first_name'];
			$member->last_name		= $add['last_name'];
			$member->job_title		= $add['job_title'];
			$member->location_id	= $add['location_id'];
			$member->email			= $add['email'];

			if ( empty( $add['headshot_url'] ) ) {
				$member->headshot_url = $member->default_headshot_url;
			} else {
				$member->headshot_url	= $add['headshot_url'];	
			}

			$member->add_member();

		}

		update_option('ctg_members_populated', true );
	}

}
add_action( 'ctg_loaded', 'ctg_members_populate_table' );

/**
 * CTG AJAX Get Members
 * 
 * @param	array 	$args	Array of member options to use. {
 * 
 * 		@var	int		$id		Member ID. Default empty.
 * 		@var	string	$first_name	Member first name. Default empty.
 * 		@var	string	$last_name	Member last name. Default empty.
 * 		@var	string	$job_title	Member job title. Default empty.
 * 		@var	int		$location_id	Member location ID. Default 0.
 * 		@var	string	$email		Member email. Default empty.
 * 		@var	string	$headshot_url	Member headshot URL. Default empty.
 * 		@var	int		$per_page	Number of members to return per page. Default -1.
 * 		@var	int		$page		Page number. Default 0.
 * 		
 * }
 * 
 * @return	array	Array of members.
 * 
 */
function ctg_ajax_get_members_by_location_id() {

	$member = new CTG_Members_Member();
	$feedback = '';

	$fetch_args = array(
		'select' => '*'
	);

	if ( isset( $_GET['location_id'] ) ) {
		$fetch_args['where']['location_id'] = intval($_GET['location_id']);
	}

	if ( isset( $_GET['page'], $_GET['per_page'] ) ) {
		$fetch_args['pagination'] = array(intval($_GET['page']), intval($_GET['per_page']));
	}

	if ( isset( $_GET['orderby'] ) ) {
		$fetch_args['orderby'] = esc_sql($_GET['orderby'] );
	}

	$paged_members = $member->get_member( $fetch_args );

	$feedback .= ( ! empty( $paged_members ) ) ? json_encode( $paged_members ) : json_encode('Empty results set.');

	echo $feedback;
	die();

}
add_action( 'wp_ajax_ctg_get_members_by_location_id', 'ctg_ajax_get_members_by_location_id' );

/**
 * CTG AJAX Get Member By ID
 * 
 * @param	array 	$args	Array of member options to use. {
 * 
 * 		@var	int		$id		Member ID. Default empty.
 * 		@var	string	$first_name	Member first name. Default empty.
 * 		@var	string	$last_name	Member last name. Default empty.
 * 		@var	string	$job_title	Member job title. Default empty.
 * 		@var	int		$location_id	Member location ID. Default 0.
 * 		@var	string	$email		Member email. Default empty.
 * 		@var	string	$headshot_url	Member headshot URL. Default empty.
 * 		@var	int		$per_page	Number of members to return per page. Default -1.
 * 		@var	int		$page		Page number. Default 0.
 * 		
 * }
 * 
 * @return	array	Array of members.
 * 
 */
function ctg_ajax_get_member_by_id() {

	$member = new CTG_Members_Member();
	$feedback = '';

	$fetch_args = array(
		'select' => '*'
	);

	if ( isset( $_GET['id'] ) ) {
		$fetch_args['where']['id'] = intval($_GET['id']);
	}

	$member = $member->get_member( $fetch_args );

	$feedback .= ( ! empty( $member ) ) ? json_encode( $member ) : json_encode('Empty results set.');

	echo $feedback;
	die();

}
add_action( 'wp_ajax_ctg_get_member_by_id', 'ctg_ajax_get_member_by_id' );

/**
 * CTG AJAX Update Member
 * 
 * @param	array 	$args	Array of member options to use. {
 * 
 * 		@var	int		$id		Member ID. Default empty.
 * 		@var	string	$first_name	Member first name. Default empty.
 * 		@var	string	$last_name	Member last name. Default empty.
 * 		@var	string	$job_title	Member job title. Default empty.
 * 		@var	int		$location_id	Member location ID. Default 0.
 * 		@var	string	$email		Member email. Default empty.
 * 		@var	string	$headshot_url	Member headshot URL. Default empty.
 * 		@var	int		$per_page	Number of members to return per page. Default -1.
 * 		@var	int		$page		Page number. Default 0.
 * 		
 * }
 * 
 * @return	array	Array of members.
 * 
 */
function ctg_ajax_update_member() {

	if ( ! isset($_POST['ctg_member_id'] ) ) {
		return;
	}

	$id = intval($_POST['ctg_member_id']);

	$member = new CTG_Members_Member($id);

	$feedback = array(
		'text' => '',
		'type' => 'error'
	);

	$update = array();
	$where = array( 'id' => $id );

	if ( !empty( $_POST['ctg_first_name'] ) && ctype_alpha( $_POST['ctg_first_name'] ) ) {
		$update['first_name'] = $_POST['ctg_first_name'];
	} else {
		$feedback['text'] = 'Please remove any special characters and numbers from first name.';
	}

	if ( !empty( $_POST['ctg_last_name'] ) && ctype_alpha( $_POST['ctg_last_name'] ) ) {
		$update['last_name'] = $_POST['ctg_last_name'];
	} else {
		$feedback['text'] = 'Please remove any special characters and numbers from last name.';
	}

	if ( !empty( $_POST['ctg_email'] ) && ctg_core_is_ctg_email( $_POST['ctg_email'] ) ) {
		$update['email'] = $_POST['ctg_email'];
	} else {
		$feedback['text'] = 'Please enter a valid CTG email address.';
	}

	if ( !empty( $_POST['ctg_job_title'] ) && preg_match( '/[a-zA-Z ]{3,75}/', $_POST['ctg_job_title'] ) ) {
		$update['job_title'] = $_POST['ctg_job_title'];
	} else {
		$feedback['text'] = 'Please remove any special characters and numbers from job title.';
	}

	if ( !empty( $_POST['ctg_location_id'] ) && ! ctype_digit( $_POST['ctg_location_id'] ) ) {
		$feedback['text'] = 'Please select a valid CTG location.';
	} else {
		if ( $_POST['ctg_location_id'] === 0 ) {
			$update['location_id'] = 0;
		} else {
			$update['location_id'] = intval($_POST['ctg_location_id']);
		}
	}

	if ( !empty( $_POST['ctg_headshot_url'] ) ) {
		$update['headshot_url'] = esc_url( $_POST['ctg_headshot_url'] );
	} else {
		$update['headshot_url'] = $member->default_headshot_url;
	}

	$updated = $member->update( $update, $where );

	if ( $updated ) {
		$feedback = array(
			"text" => "Successfully updated {$member->first_name}'s info.",
			"type" => "success"
		);	
	} else {
		if ( empty($feedback['text'] ) ) {
			$feedback['text'] = "Error processing member update.";
			$feedback['type'] = "error";
		}
	}

	do_action('ctg_ajax_update_member');
	echo json_encode($feedback);

	die();

}
add_action( 'wp_ajax_ctg_update_member', 'ctg_ajax_update_member' );

/**
 * CTG AJAX Add Member
 * 
 * @param	array 	$args	Array of member options to use. {
 * 
 * 		@var	int		$id		Member ID. Default empty.
 * 		@var	string	$first_name	Member first name. Default empty.
 * 		@var	string	$last_name	Member last name. Default empty.
 * 		@var	string	$job_title	Member job title. Default empty.
 * 		@var	int		$location_id	Member location ID. Default 0.
 * 		@var	string	$email		Member email. Default empty.
 * 		@var	string	$headshot_url	Member headshot URL. Default empty.
 * 		@var	int		$per_page	Number of members to return per page. Default -1.
 * 		@var	int		$page		Page number. Default 0.
 * 		
 * }
 * 
 * @return	array	Array of members.
 * 
 */
function ctg_ajax_add_member() {

	$member = new CTG_Members_Member();
	$feedback		= array(
		"text" => "",
		"type" => ""
	);
	$fname			= '';
	$lname			= '';
	$job_title		= '';
	$email			= '';
	$location		= 0;
	$headshot_url	= '';

	if ( !isset(
		$_POST['ctg_first_name'],
		$_POST['ctg_last_name'],
		$_POST['ctg_email'],
		$_POST['ctg_job_title'],
		$_POST['ctg_location_id']
	)) {
		return;
	}

	if ( ! ctype_alpha( $_POST['ctg_first_name'] ) ) {
		$feedback = array(
			"text" => 'Please remove any special characters and numbers from first name.',
			"type" => 'error'
		);
	} else {
		$fname = trim($_POST['ctg_first_name']);
	}

	if ( ! ctype_alpha( $_POST['ctg_last_name'] ) ) {
		$feedback = array(
			"text" => 'Please remove any special characters and numbers from last name.',
			"type" => 'error'
		);
	} else {
		$lname = trim($_POST['ctg_last_name']);
	}

	if ( ! preg_match( '/[a-zA-Z ]{3,75}/', $_POST['ctg_job_title'] ) ) {
		$feedback = array(
			"text" => 'Please remove any special characters and numbers from job title.',
			"type" => 'error'
		);

	} else {
		$job_title = trim(ucwords($_POST['ctg_job_title']));
	}

	if ( ! ctg_core_is_ctg_email( $_POST['ctg_email'] ) ) {
		$feedback = array(
			"text" => 'Please enter a valid CTG email address.',
			"type" => 'error'
		);

	} else {
		$email = $_POST['ctg_email'];
	}

	if ( ! ctype_digit( $_POST['ctg_location_id'] ) ) {
		$feedback = array(
			"text" => 'Please select a valid CTG location.',
			"type" => 'error'
		);
	} else {
		$location = $_POST['ctg_location_id'];
	}

	if ( !empty($_POST['ctg_headshot_url'] ) ) {
		$headshot_url = esc_url($_POST['ctg_headshot_url']);
	}

	if ( 
		!empty( $fname ) &&
		!empty( $lname ) &&
		!empty( $job_title ) &&
		!empty( $email ) &&
		!empty( $location )
	) {
		$member->first_name		= $fname;
		$member->last_name		= $lname;
		$member->job_title		= $job_title;
		$member->email			= $email;
		$member->location_id	= $location;

		if ( !empty( $headshot_url ) ) {
			$member->headshot_url = $headshot_url;
		} else {
			$member->headshot_url = $member->default_headshot_url;
		}

		$added = $member->add_member();
		$feedback = $added ? array(
			"text" => "Successfully added {$member->first_name}. Welcome to the team!",
			"type" => "success"
		) :
		array(
			"text" => "Failed to process member addition.",
			"type" => "error"
		);
	}

	do_action('ctg_ajax_add_member');

	echo json_encode( $feedback );
	die();

}
add_action( 'wp_ajax_ctg_add_member', 'ctg_ajax_add_member' );

/**
 * CTG AJAX Add Member
 * 
 * @param	array 	$args	Array of member options to use. {
 * 
 * 		@var	int		$id		Member ID. Default empty.
 * 		@var	string	$first_name	Member first name. Default empty.
 * 		@var	string	$last_name	Member last name. Default empty.
 * 		@var	string	$job_title	Member job title. Default empty.
 * 		@var	int		$location_id	Member location ID. Default 0.
 * 		@var	string	$email		Member email. Default empty.
 * 		@var	string	$headshot_url	Member headshot URL. Default empty.
 * 		@var	int		$per_page	Number of members to return per page. Default -1.
 * 		@var	int		$page		Page number. Default 0.
 * 		
 * }
 * 
 * @return	array	Array of members.
 * 
 */
function ctg_ajax_delete_member() {

	if ( !isset( $_POST['id'] ) ) {
		return;
	}

	$id = intval($_POST['id'] );

	$member = new CTG_Members_Member();
	$feedback = array(
		"text" => "",
		"type" => ""
	);

	if ( !wp_verify_nonce($_POST['ctg_delete_member_nonce'], 'ctg_delete_member') ) {
		$feedback = array(
			"text" => "Invalid nonce parameter.",
			"type" => "error"
		);
	} else {
		$deleted = $member->delete(
			array(
				'id' => $id
			)
		);
		$feedback = $deleted ? array(
			"text" => "Successfully removed {$member->first_name} from the database.",
			"type" => "success"
		) :
		array(
			"text" => "Failed to process member deletion.",
			"type" => "error"
		);
	}

	do_action('ctg_ajax_delete_member');
	echo json_encode($feedback);
	die();

}
add_action( 'wp_ajax_ctg_delete_member', 'ctg_ajax_delete_member' );

function ctg_members_refresh_table() {

	$table = new CTG_Members_Admin_Table();
	$table->prepare_items();
	return $table->display_rows();

}
add_action( 'wp_ajax_ctg_refresh_members_table', 'ctg_members_refresh_table' );

function ctg_members_get_members_by_location_id( $id = 0 ) {
	if ( empty( $id ) ) {
		return;
	}
	
	$member = new CTG_Members_Member();
	return $member->get_member(
		array(
			'where' => array(
				'location_id' => intval($id)
			),
			'orderby' => array('first_name', 'ASC')
		)
	);
	
}

/**
 * Confetti Bits Get Total Members
 * 
 * @return Total of all participation entries.
 */
function ctg_members_get_total_members() {

	$status = ( !empty( $_GET['status'] ) && is_string( $_GET['status'] ) ) ? $_GET['status'] : 'new';

	$valid_statuses = array( 'new', 'all', 'approved', 'denied' );

	$status = ( in_array( $status, $valid_statuses ) ) ? $status : 'new';
	$participation = new Confetti_Bits_Participation_Participation();
	$where = array(
		'status'		=> $status,
		'date_query'	=> array(
			'column'	=> 'event_date',
			'month'		=> $participation->month,
			'year'		=> $participation->year,
			'inclusive'	=> true
		)
	);

	if ( !empty( $_GET['event_type'] ) ) {
		$where['event_type'] = trim($_GET['event_type']);
	}


	$all_participation = $participation->get_participation(
		array(
			'select' => 'count(id) as total_count',
			'where' => $where
		)
	);

	echo json_encode( $all_participation );

	die();

}
//add_action( 'wp_ajax_cb_participation_get_participation_total', 'cb_participation_get_total_participation' );