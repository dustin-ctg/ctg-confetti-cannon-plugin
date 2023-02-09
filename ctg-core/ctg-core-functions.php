<?php 
defined( 'ABSPATH' ) || exit;

function ctg_core_is_valid_email( $text = '' ) {
	return preg_match( '/\A[\w]{2,20}\.?[\w?]{2,20}@[\a-zA-Z0-9]{2,20}\.[a-z]{2,10}/', $text );
}

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
function ctg_core_get_parent_category_by_slug( $slug = '' ) {
	if ( empty($slug)) {
		return;
	}
	return get_term_by( 'slug', $slug, 'category' );
}

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

function ctg_core_check_post_subcategories( $categories, $_post = null ) {

	foreach ( (array) $categories as $category ) {
		$subcats = get_term_children( (int) $category, 'category' );
		if ( $subcats && in_category( $subcats, $_post ) )
			return true;
	}
	return false;
}

function ctg_get_version() {
	return ctg()->version;
}

function ctg_get_option( $option_name, $default = '' ) {
	$value = get_option( $option_name, $default );
	return apply_filters( 'ctg_get_option', $value );
}

function ctg_update_option( $option_name, $value ) {
	return update_blog_option( get_current_blog_id(), $option_name, $value );
}

function ctg_is_post_request() {
	return (bool) ( 'POST' === strtoupper( $_SERVER['REQUEST_METHOD'] ) );
}

function ctg_is_get_request() {
	return (bool) ( 'GET' === strtoupper( $_SERVER['REQUEST_METHOD'] ) );
}

function ctg_get_admin_url( $path = '', $scheme = 'admin' ) {

	$url = admin_url( $path, $scheme );

	return $url;
}

function ctg_core_get_components( $type = 'all' ) {

	$required_components = array(
		'celebratorcreator' => array(
			'title'       => __( 'Celebrator Creator', 'ctg' ),
			'settings'    => ctg_get_admin_url( add_query_arg( array( 
				'page' => 'ctg-settings', 
				'tab' => 'ctg-celebratorcreator' ) , 'admin.php' ) ),
			'description' => __( 'Allow site admins to create, modify, and delete CTG celebrators, confetti cannons, and more.', 'ctg' ),
		),
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

function ctg_core_get_directory_page_default_titles() {
	$page_default_titles = array(
		'celebratorcreator'	=> __( 'Celebrators', 'ctg' ),
	);

	return apply_filters( 'ctg_core_get_directory_page_default_titles', $page_default_titles );
}

function ctg_core_get_table_prefix() {
	global $wpdb;
	return apply_filters( 'ctg_core_get_table_prefix', $wpdb->base_prefix );
}

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



function ctg_core_redirect( $slug = '', $status = 302 ) {

	wp_safe_redirect( home_url() . "/{$slug}", $status );

}

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

function ctg_sender_email( $original_email_address ) {
	return 'dustin@aws-ses-smtp.celebrationtitlegroup.com';
}
function ctg_sender_name( $original_email_from ) {
	return 'Celebration Title Group';
}

add_action( 'phpmailer_init', 'ctg_smtp_init' );
add_filter( 'wp_mail_from', 'ctg_sender_email' );
add_filter( 'wp_mail_from_name', 'ctg_sender_name' );