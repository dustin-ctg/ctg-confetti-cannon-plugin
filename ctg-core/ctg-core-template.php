<?php

/**
 * CTG Header
 *
 *
 * @return void
 */
function ctg_header()
{
	if ( ! current_user_can('administrator') ) {
		return;
	}
	if ( ctg_is_locations_component() ) {
	?>
	<div class="ctg-header-container">
		<?php
		wp_nav_menu();
		?>
	</div>
<?php }
}

function ctg_core_load_template( $templates ) {

	global $wp_query;

	$filtered_templates = array();
	foreach ( (array) $templates as $template ) {
		$filtered_templates[] = $template . '.php';
	}

	$template = locate_template( (array) $filtered_templates, true );

	$located_template = apply_filters( 'ctg_located_template', $template, $filtered_templates );

	if ( function_exists( 'is_embed' ) && is_embed() ) {
		$located_template = '';
	}

	if ( !empty( $located_template ) ) {

		status_header( 200 );
		$wp_query->is_page     = true;
		$wp_query->is_singular = true;
		$wp_query->is_404      = false;

		do_action( 'ctg_core_pre_load_template', $located_template );

		load_template( apply_filters( 'ctg_load_template', $located_template ) );

		do_action( 'ctg_core_post_load_template', $located_template );

		exit();

	}
}