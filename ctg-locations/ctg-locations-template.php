<?php 

function ctg_get_locations_slug() {
	return apply_filters( 'ctg_get_locations_slug', 'locations' );
}

function ctg_location_locate_template_part( $template = '' ) {

	if ( ! $template ) {
		return '';
	}

	$ctg_template_parts = array(
		'locations/single/ctg-%s.php',
	);

	$templates = array();

	foreach ( $ctg_template_parts as $ctg_template_part ) {
		$templates[] = sprintf( $ctg_template_part, $template );
	}

	return locate_template( $templates, true, true );

}

function ctg_location_get_template_part( $template = '' ) {

	$located = ctg_location_locate_template_part( $template );

	if ( false !== $located ) {
		$slug = str_replace( '.php', '', $located );
		$name = null;

		do_action( 'get_template_part_' . $slug, $slug, $name );

		load_template( $located, true );

	}

	return $located;
}

function ctg_location_template_part() {

	$templates = array();

	if ( current_user_can('administrator') ) {

		if ( ctg_core_is_locations_component()  ) {
			$templates[] = 'locations-single';
		}
	}

	foreach ( $templates as $template ) {
		ctg_location_get_template_part( $template );
	}

}

add_action( 'astra_entry_content_before', 'ctg_location_template_part');