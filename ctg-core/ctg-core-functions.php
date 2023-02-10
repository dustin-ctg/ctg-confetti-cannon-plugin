<?php
/**
 * CTG Core Functions
 * A collection of utility functions for CTG Core.
 *
 * @package CTG_Core
 * @subpackage CTG_Core\Functions
 * @since 0.1.0
 */
defined( 'ABSPATH' ) || exit;

/**
 * CTG Core Is Valid Email
 *
 * A utility function to validate an email address.
 *
 * @param string $text The email address to validate.
 * @return bool True if valid, false if not.
 */
function ctg_core_is_valid_email( $text = '' ) {
	return preg_match( '/\A[\w]{2,20}\.?[\w?]{2,20}@[\a-zA-Z0-9]{2,20}\.[a-z]{2,10}/', $text );
}

/**
 * CTG Core Is CTG Email
 *
 * A utility function to validate a CTG email address.
 *
 * @param string $text The email address to validate.
 * @return bool True if valid, false if not.
 */
function ctg_core_is_ctg_email( $text = '' ) {
	return preg_match( '/\A[\w]{2,20}\.?[\w?]{2,20}@celebrationtitlegroup\.com/', $text );
}

/**
 * CTG Core Validate Address
 * A utility function to validate an address.
 *
 * @param mixed $args Array of arguments to use. {
 * 		@var string $street_primary Primary street number.
 * 		@var string $street_secondary Secondary street number.
 * 		@var string $city City.
 * 		@var string $state State.
 * 		@var string $zip Zip.
 * 		@var string $phone Phone number.
 * 		@var string $location_name Internal name for the location.
 * }
 * @return array
 */
function ctg_core_validate_address($args = array())
{
	$r = wp_parse_args(
		$args,
		array(
			'street_primary' => '',
			'street_secondary' => '',
			'city' => '',
			'state' => '',
			'zip' => '',
			'phone' => '',
			'location_name' => '',
		)
	);

	$feedback = array(
		'street_primary' => false,
		'street_secondary' => false,
		'city' => false,
		'state' => false,
		'zip' => false,
		'phone' => false,
		'location_name' => false,
	);

	if (empty($r['street_primary']) || empty($r['city']) || empty($r['state']) || empty($r['zip'])) {
		return $feedback;
	}

	$feedback['street_primary'] = preg_match(
		'/\d{1,6}\s?[a-zA-Z]{1,20}(\s|\-)?[a-zA-Z0-9]{0,20}\s?[a-zA-Z0-9]{0,20}/',
		$r['street_primary']
	);

	if (!empty($r['street_secondary'])) {
		$feedback['street_secondary'] = preg_match(
			'/#?[a-zA-Z0-9]{1,10}\s?#?[a-zA-Z0-9]{0,10}\s?[a-zA-Z]?/',
			$r['street_secondary']
		);
	} else {
		$feedback['street_secondary'] = true;
	}

	$feedback['city'] = preg_match('/[a-zA-Z0-9 ]/', $r['city']);
	$feedback['state'] = preg_match('/[a-zA-Z]{2}/', $r['state']);
	$feedback['zip'] = preg_match('/\d{5}/', $r['zip']);

	if (!empty($r['phone'])) {
		$feedback['phone'] = preg_match(
			'/[\(]?\d{3}(\) |-)?\d{3}-\d{4}/',
			$r['phone']
		);
	} else {
		$feedback['phone'] = true;
	}

	if (!empty($r['location_name'])) {
		$feedback['location_name'] = preg_match('/[a-zA-Z0-9 ]/', $r['location_name']);
	} else {
		$feedback['location_name'] = true;
	}

	return $feedback;

}

/**
 * CTG Core Get Parent Category By Slug
 *
 * A utility function to get a parent category by slug.
 *
 * @param string $slug The slug of the category to get.
 * @return object|bool The category object or false if not found.
 */
function ctg_core_get_parent_category_by_slug( $slug = '' ) {
	if ( empty($slug)) {
		return;
	}
	return get_term_by( 'slug', $slug, 'category' );
}

/**
 * CTG Core Is Component Category
 *
 * A utility function to check if a post is in a category or a subcategory.
 *
 * @param string $slug The slug of the category to check.
 * @return bool True if in category, false if not.
 */
function ctg_core_is_component_category( $slug = '' ) {

	$is_category_component = false;

	if ( empty( $slug ) ) {
		return;
	}

	$current_category = get_the_category();
	$parent_category = ctg_core_get_parent_category_by_slug( $slug );

	if ( ! $parent_category ) {
		$is_category_component = false;
	}

	if ( isset($parent_category->term_id, $current_category[0]->term_id ) ) {
		if ( $parent_category->term_id === $current_category[0]->term_id ) {
			$is_category_component = true;
		}
		$subcats = get_term_children( $parent_category->term_id, 'category' );

		if ( in_array( $current_category[0]->term_id, $subcats ) ) {
			$is_category_component = true;
		}
	}

	return $is_category_component;

}

/**
 * CTG Core Get Parent Category By Slug
 * A utility function to get a parent category by slug.
 * @param string $slug The slug of the category to get.
 * @return object|bool The category object or false if not found.
 */
function ctg_core_check_post_subcategories( $categories, $_post = null ) {

	foreach ( (array) $categories as $category ) {
		$subcats = get_term_children( (int) $category, 'category' );
		if ( $subcats && in_category( $subcats, $_post ) )
			return true;
	}
	return false;
}

/**
 * CTG Core Get Parent Category By Slug
 * A utility function to get a parent category by slug.
 * @param string $slug The slug of the category to get.
 * @return object|bool The category object or false if not found.
 */
function ctg_get_version() {
	return ctg()->version;
}

/**
 * CTG Get Option
 * A utility function to get a CTG option.
 *
 * @param string $option_name The name of the option to get.
 * @param string $default The default value to return if the option is not set.
 * @return string The option value.
 */
function ctg_get_option( $option_name, $default = '' ) {
	$value = get_option( $option_name, $default );
	return apply_filters( 'ctg_get_option', $value );
}

/**
 * CTG Update Option
 * A utility function to update a CTG option.
 *
 * @param string $option_name The name of the option to update.
 * @param string $value The value to set the option to.
 * @return bool True if the option was updated, false if not.
 */
function ctg_update_option( $option_name, $value ) {
	return update_blog_option( get_current_blog_id(), $option_name, $value );
}

/**
 * CTG Delete Option
 * A utility function to delete a CTG option.
 *
 * @param string $option_name The name of the option to delete.
 * @return bool True if the option was deleted, false if not.
 */
function ctg_delete_option( $option_name ) {
	return delete_blog_option( get_current_blog_id(), $option_name );
}

/**
 * CTG Get Admin URL
 * A utility function to get the admin URL.
 *
 * @param string $path The path to append to the admin URL.
 * @param string $scheme The scheme to use. Default is 'admin', which obeys force_ssl_admin() and is_ssl().
 * @return string The admin URL link with optional path appended.
 */
function ctg_get_admin_url( $path = '', $scheme = 'admin' ) {
	$url = admin_url( $path, $scheme );
	return $url;
}

/**
 * CTG Is Post Request
 * A utility function to check if the current request is a POST request.
 *
 * @return bool True if the current request is a POST request, false if not.
 */
function ctg_is_post_request() {
	return (bool) ( 'POST' === strtoupper( $_SERVER['REQUEST_METHOD'] ) );
}

/**
 * CTG Is Get Request
 * A utility function to check if the current request is a GET request.
 *
 * @return bool True if the current request is a GET request, false if not.
 */
function ctg_is_get_request() {
	return (bool) ( 'GET' === strtoupper( $_SERVER['REQUEST_METHOD'] ) );
}

/**
 * CTG Is Ajax Request
 * A utility function to check if the current request is an AJAX request.
 *
 * @return bool True if the current request is an AJAX request, false if not.
 */
function ctg_is_ajax_request() {
	return (bool) ( defined( 'DOING_AJAX' ) && DOING_AJAX );
}


function ctg_core_get_components( $type = 'all' ) {

	$required_components = array(
		'locations' => array(
			'title'       => __( 'Locations', 'ctg' ),
			'settings'    => ctg_get_admin_url( add_query_arg( array(
				'page' => 'ctg-settings',
				'tab' => 'ctg-locations' ) , 'admin.php' ) ),
			'description' => __( 'Allow site admins to create, modify, and delete CTG locations.', 'ctg' ),
		),
	);

	$optional_components = array();

	$default_components = array();

	foreach( array_merge( $required_components, $optional_components ) as $key => $component ) {
		if ( isset( $component['default'] ) && true === $component['default'] ) {
			$default_components[ $key ] = $component;
		}
	}

	switch ( $type ) {
		case 'required' :
			$components = $required_components;
			break;
		case 'optional' :
			$components = $optional_components;
			break;
		case 'default' :
			$components = $default_components;
			break;
		case 'all' :
		default :
			$components = array_merge( $required_components, $optional_components );
			break;
	}

	return apply_filters( 'ctg_core_get_components', $components, $type );

}


function ctg_displayed_user_id() {
	$ctg = ctg();
	$id = !empty( $ctg->displayed_user->id )
		? $ctg->displayed_user->id
		: 0;

	return (int) apply_filters( 'ctg_displayed_user_id', $id );
}


/**
 * CTG Core Get Table Prefix
 * A utility function to get the table prefix.
 *
 * @return string The table prefix.
 */
function ctg_core_get_table_prefix() {
	global $wpdb;
	return apply_filters( 'ctg_core_get_table_prefix', $wpdb->base_prefix );
}

/**
 * CTG Core Get Site Path
 * A utility function to get the site path.
 *
 * @return string The site path.
 */
function ctg_core_get_site_path() {

	$site_path = (array) explode( '/', home_url() );

	if ( count( $site_path ) < 0 ) {
		$site_path = '/';
	} else {
		// Unset the first three segments (http(s)://example.com part).
		unset( $site_path[0] );
		unset( $site_path[1] );
		unset( $site_path[2] );

		if ( !count( $site_path ) ) {
			$site_path = '/';
		} else {
			$site_path = '/' . implode( '/', $site_path ) . '/';
		}
	}

	return apply_filters( 'ctg_core_get_site_path', $site_path );
}

/**
 * CTG Parse Args
 * A utility function to parse arguments.
 *
 * @param string|array|object $args The value being parsed.
 * @param array $defaults The default values.
 * @return array The parsed arguments.
 */
function ctg_parse_args( $args, $defaults = array() ) {

	if ( is_object( $args ) ) {
		$r = get_object_vars( $args );
	} elseif ( is_array( $args ) ) {
		$r =& $args;
	} else {
		wp_parse_str( $args, $r );
	}

	if ( is_array( $defaults ) && !empty( $defaults ) ) {
		$r = array_merge( $defaults, $r );
	}

	return $r;
}

/**
 * CTG Core Redirect
 * A utility function to redirect to a specific page.
 *
 * @param string $slug The slug of the page to redirect to.
 * @param int $status The HTTP status code to use.
 * @return void
 */
function ctg_core_redirect( $slug = '', $status = 302 ) {

	wp_safe_redirect( home_url() . "/{$slug}", $status );

}

/**
 * CTG Core Add Message
 * A utility function to add a message to the cookie.
 *
 * @param string $message The message to add.
 * @param string $type The type of message to add.
 * @return void
 */
function ctg_core_add_message( $message, $type = '' ) {

	if ( empty( $type ) ) {
		$type = 'success';
	}

	@setcookie(
		'ctg-message',
		$message,
		time() + 60 * 60 * 24,
		COOKIEPATH,
		COOKIE_DOMAIN,
		is_ssl()
	);
	@setcookie( 'ctg-message-type',
			   $type,
			   time() + 60 * 60 * 24,
			   COOKIEPATH,
			   COOKIE_DOMAIN,
			   is_ssl()
			  );

	$ctg = ctg();
	$ctg->message = $message;
	$ctg->message_type = $type;

}

/**
 * CTG Core Setup Message
 * A utility function to setup the message.
 *
 * @global object $ctg The CTG object.
 *
 * @return void
 */
function ctg_core_setup_message() {

	$ctg = ctg();

	if ( empty( $ctg->message ) && isset( $_COOKIE['ctg-message'] ) ) {
		$ctg->message = stripslashes( rawurldecode( $_COOKIE['ctg-message'] ) );
	}

	if ( empty( $ctg->message_type ) && isset( $_COOKIE['ctg-message-type'] ) ) {
		$ctg->message_type = stripslashes( rawurldecode( $_COOKIE['ctg-message-type'] ) );
	}

	add_action( 'template_notices', 'ctg_core_render_message' );

	if ( isset( $_COOKIE['ctg-message'] ) ) {
		@setcookie( 'ctg-message', false, time() - 1000, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
	}

	if ( isset( $_COOKIE['ctg-message-type'] ) ) {
		@setcookie( 'ctg-message-type', false, time() - 1000, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
	}
}
add_action( 'ctg_actions', 'ctg_core_setup_message', 3 );

/**
 * CTG Core Render Message
 * A utility function to render the message.
 *
 * @return void
 */
function ctg_core_render_message() {

	$ctg = ctg();

	if ( !empty( $ctg->message ) ) {
		$type    = ( 'success' === $ctg->message_type ) ? 'updated' : 'error';
		$content = apply_filters( 'ctg_core_render_message_content', $ctg->message, $type ); ?>

<div id="ctg_message" class="ctg-message-<?php echo esc_attr( $type ); ?>">
	<?php echo $content; ?>
</div>

<?php
		do_action( 'ctg_core_render_message' );

	}
}

/**
 * CTG SMTP Init
 * A utility function to initialize SMTP.
 *
 * @param object $phpmailer The PHPMailer object.
 * @return void
 */
function ctg_smtp_init( $phpmailer ) {
	$phpmailer->Host		= SMTP_HOST;
	$phpmailer->Port		= SMTP_PORT;
	$phpmailer->Username	= SMTP_USER;
	$phpmailer->Password	= SMTP_PASS;
	$phpmailer->From		= SMTP_FROM;
	$phpmailer->FromName	= SMTP_NAME;
	$phpmailer->SMTPAuth	= true;
	$phpmailer->SMTPSecure = SMTP_SECURE;

	$phpmailer->IsSMTP();

}
add_action( 'phpmailer_init', 'ctg_smtp_init' );

/**
 * CTG Sender Email
 * A utility function to set the sender email.
 *
 * @param string $original_email_address The original email address.
 * @return string The new email address.
 */
function ctg_sender_email( $original_email_address ) {
	return 'dustin@aws-ses-smtp.celebrationtitlegroup.com';
}
add_filter( 'wp_mail_from', 'ctg_sender_email' );

/**
 * CTG Sender Name
 * A utility function to set the sender name.
 *
 * @param string $original_email_from The original email from.
 * @return string The new email from.
 */
function ctg_sender_name( $original_email_from ) {
	return 'Celebration Title Group';
}
add_filter( 'wp_mail_from_name', 'ctg_sender_name' );