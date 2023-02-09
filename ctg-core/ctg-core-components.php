<?php 
defined( 'ABSPATH' ) || exit;

function ctg_is_active( $component = '' ) {
	$retval = false;

	if ( empty( $component ) ) {
		$component = ctg_current_component();
	}

	if ( isset( ctg()->active_components[ $component ] ) || isset( ctg()->required_components[ $component ] ) ) {
		$retval = true;
	}

	return apply_filters( 'ctg_is_active', $retval, $component );
}


/*
 * Creates a list of URI parts to help determine whether the current page 
 * is part of the CTG repertoire.
 * */
function ctg_get_current_component() {

	$path = esc_url($_SERVER['REQUEST_URI']);

	// Strip out any GET parameters
	$path = strtok( $path, '?' );

	$ctg_uri_parts = explode( '/', $path );

	// Dump all the empty bits
	foreach ( (array) $ctg_uri_parts as $key => $uri_chunk ) {
		if ( empty( $ctg_uri_parts[ $key ] ) ) {
			unset( $ctg_uri_parts[ $key ] );
		}
	}

	return $ctg_uri_parts;

}

function ctg_set_component_globals() {

	$ctg = ctg();
	$ctg_uri_parts	= ctg_get_current_component();
	$category_component = ctg_core_is_component_category('locations');

	if ( ! empty( $ctg_uri_parts ) ) {
		if ( isset( $ctg_uri_parts[1] ) ) {
			if ( $ctg_uri_parts[1] === 'category' ) {
				$ctg->current_component = ! empty( $ctg_uri_parts[2] )
					? $ctg_uri_parts[2]
					: '';
			} else {
				if ( $category_component ) {
					$ctg->current_component = 'locations';
				} else {
					$ctg->current_component = $ctg_uri_parts[1];
				}
			}
		}
		if ( isset( $ctg_uri_parts[2] ) ) {
			$ctg->current_item = $ctg_uri_parts[2];
		}
		if ( isset( $ctg_uri_parts[3] ) ) {
			$ctg->current_action = $ctg_uri_parts[3];
		}
	}
}

function ctg_current_component() {
	$ctg = ctg();
	$current_component = !empty( $ctg->current_component )
		? $ctg->current_component
		: false;

	return $current_component;
}

function ctg_current_item() {
	$ctg = ctg();
	$current_item = !empty( $ctg->current_item )
		? $ctg->current_item
		: false;

	return apply_filters( 'ctg_current_item', $current_item );

}

function ctg_current_action() {
	$ctg = ctg();
	$current_action = !empty( $ctg->current_action )
		? $ctg->current_action
		: false;

	return apply_filters( 'ctg_current_action', $current_action );

}

function ctg_current_location() {
	$ctg = ctg();

	if ( ! empty($ctg->current_component ) ) {
		$current_component = $ctg->current_component;
	}

	if ( $current_component === 'locations' ) {
		$current_location = !empty( $ctg->current_item )
			? $ctg->current_item
			: false;
	} else {
		$current_location = '';
	}

	return $current_location;

}

/*
 * Checks whether the current page belongs to CTG
 * */
function ctg_is_component( $component = '' ) {

	// Default is the current component
	if ( '' === $component ) {
		$component = ctg_current_component();
	}

	$ctg_components = array(
		'locations',
		'celebratorcreator',
	);

	$is_component = in_array( $component, $ctg_components );

	return apply_filters('ctg_is_component', $is_component );

}

function ctg_core_is_locations_component() {

	$current_component = ctg_current_component();
	if ( $current_component ) {
		return $current_component == 'locations';	
	} else {
		$post = get_post();
		return !empty(get_post_meta($post->ID, '_ctg_location_id', true ));
	}

}

function ctg_core_is_franchise_location() {

	$post = get_post();
	return get_post_meta($post->ID, '_ctg_is_franchise', true );

}

function ctg_is_celebratorcreator_component() {

	$current_component = ctg_current_component();
	if ( $current_component === 'celebratorcreator' ) {
		return true;
	}

}