<?php

function ctg_admin_enqueue_scripts() {
	do_action( 'ctg_admin_enqueue_scripts' );
}

function ctg_loaded() {
	do_action( 'ctg_loaded' );
}

function ctg_init() {
	do_action( 'ctg_init' );
}

function ctg_customize_register( WP_Customize_Manager $customizer ) {
	do_action( 'ctg_customize_register', $customizer );
}

function ctg_ready() {
	do_action( 'ctg_ready' );
}

function ctg_setup_current_user() {
	do_action( 'ctg_setup_current_user' );
}

function ctg_setup_theme() {
	do_action( 'ctg_setup_theme' );
}

function ctg_after_setup_theme() {
	do_action( 'ctg_after_setup_theme' );
}

function ctg_enqueue_scripts() {
	do_action( 'ctg_enqueue_scripts' );
}

/*/
function ctg_enqueue_embed_scripts() {
	if ( ! is_ctg() ) {
		return;
	}
	do_action( 'ctg_enqueue_embed_scripts' );
}
/*/


function ctg_setup_admin_bar() {
	//	if ( ctg_use_wp_admin_bar() ) {
	do_action( 'ctg_setup_admin_bar', array() );
	//	}
}

function ctg_template_redirect() {
	do_action( 'ctg_template_redirect' );
}

function ctg_widgets_init() {
	do_action( 'ctg_widgets_init' );
}

function ctg_generate_rewrite_rules( $wp_rewrite ) {
	do_action_ref_array( 'ctg_generate_rewrite_rules', array( &$wp_rewrite ) );
}

function ctg_setup_components() {
	do_action( 'ctg_setup_components' );
}

function ctg_include() {
	do_action( 'ctg_include' );
}

function ctg_setup_cache_groups() {
	do_action( 'ctg_setup_cache_groups' );
}

function ctg_setup_widgets() {
	do_action( 'ctg_register_widgets' );
}

function ctg_register_theme_packages() {
	do_action( 'ctg_register_theme_packages' );
}




function ctg_register_post_types() {
	do_action( 'ctg_register_post_types' );
}

function ctg_register_taxonomies() {
	do_action( 'ctg_register_taxonomies' );
}

function ctg_setup_globals() {
	do_action( 'ctg_setup_globals' );
}

function ctg_setup_canonical_stack() {
	do_action( 'ctg_setup_canonical_stack' );
}

function ctg_setup_nav() {
	do_action( 'ctg_setup_nav' );
}

function ctg_setup_title() {
	do_action( 'ctg_setup_title' );
}

function ctg_add_rewrite_tags() {
	do_action( 'ctg_add_rewrite_tags' );
}

function ctg_add_rewrite_rules() {
	do_action( 'ctg_add_rewrite_rules' );
}

function ctg_add_permastructs() {
	do_action( 'ctg_add_permastructs' );
}





function ctg_register_member_types() {
	do_action( 'ctg_register_member_types' );
}

function ctg_late_include() {
	do_action( 'ctg_late_include' );
}



function ctg_actions() {
	do_action( 'ctg_actions' );
}

function ctg_screens() {
	do_action( 'ctg_screens' );
}

function ctg_post_request() {

	if ( ! ctg_is_post_request() ) {
		return;
	}

	if ( empty( $_POST['action'] ) ) {
		return;
	}

	$action = sanitize_key( $_POST['action'] );

	do_action( 'ctg_post_request_' . $action );

	do_action( 'ctg_post_request', $action );
}

function ctg_get_request() {

	if ( ! ctg_is_get_request() ) {
		return;
	}

	if ( empty( $_GET['action'] ) ) {
		return;
	}

	$action = sanitize_key( $_GET['action'] );

	do_action( 'ctg_get_request_' . $action );

	do_action( 'ctg_get_request', $action );
}

function ctg_rest_api_init() {
	do_action( 'ctg_rest_api_init' );
}


function ctg_head() {
	do_action( 'ctg_head' );
}


function ctg_request( $query_vars = array() ) {
	return apply_filters( 'ctg_request', $query_vars );
}

function ctg_login_redirect( $redirect_to = '', $redirect_to_raw = '', $user = false ) {

	/**
	 * @param string           $redirect_to     The redirect destination URL.
	 * @param string           $redirect_to_raw The requested redirect destination URL passed as a parameter.
	 * @param WP_User|WP_Error $user            WP_User object if login was successful, WP_Error object otherwise.
	 */
	return apply_filters( 'ctg_login_redirect', $redirect_to, $redirect_to_raw, $user );
}

/**
 * @param string $template See 'template_include'.
 * @return string Template file to use.
 */
function ctg_template_include( $template = '' ) {
	return apply_filters( 'ctg_template_include', $template );
}


function ctg_allowed_themes( $themes ) {
	return apply_filters( 'ctg_allowed_themes', $themes );
}