<?php 
defined( 'ABSPATH' ) || exit;

add_action( 'plugins_loaded', 'ctg_loaded', 10 );
add_action( 'init', 'ctg_init', 10 );
add_action( 'customize_register', 'ctg_customize_register', 20 ); // After WP core.
add_action( 'wp', 'ctg_ready', 10 );
add_action( 'set_current_user', 'ctg_setup_current_user', 10 );
add_action( 'setup_theme', 'ctg_setup_theme', 10 );
add_action( 'after_setup_theme', 'ctg_after_setup_theme', 100 ); // After WP themes.
add_action( 'wp_enqueue_scripts', 'ctg_enqueue_scripts', 10 );
add_action( 'admin_bar_menu', 'ctg_setup_admin_bar', 20 ); // After WP core.
add_action( 'template_redirect', 'ctg_template_redirect', 10 );
add_action( 'widgets_init', 'ctg_widgets_init', 10 );
add_action( 'generate_rewrite_rules', 'ctg_generate_rewrite_rules', 10 );

add_action( 'ctg_loaded', 'ctg_setup_components', 2 );
add_action( 'ctg_loaded', 'ctg_include', 3 );
add_action( 'ctg_loaded', 'ctg_setup_cache_groups', 4 );
add_action( 'ctg_loaded', 'ctg_setup_widgets', 6 );
add_action( 'ctg_loaded', 'ctg_register_theme_packages', 12 );

add_action( 'ctg_init', 'ctg_register_post_types', 2 );
add_action( 'ctg_init', 'ctg_register_taxonomies', 2 );

add_action( 'ctg_init', 'ctg_set_component_globals', 2 );

add_action( 'ctg_init', 'ctg_setup_globals', 4 );
add_action( 'ctg_init', 'ctg_setup_canonical_stack', 5 );
add_action( 'ctg_init', 'ctg_setup_nav', 6 );
add_action( 'ctg_init', 'ctg_setup_title', 8 );
//add_action( 'ctg_init', 'ctg_core_load_admin_bar_css', 12 );
add_action( 'ctg_init', 'ctg_add_rewrite_tags', 20 );
add_action( 'ctg_init', 'ctg_add_rewrite_rules', 30 );
add_action( 'ctg_init', 'ctg_add_permastructs', 40 );

//add_action( 'ctg_init', 'ctg_init_background_updater', 50 );
//add_action( 'ctg_init', 'bb_init_email_background_updater', 51 );
//add_action( 'ctg_init', 'bb_init_notifications_background_updater', 52 );

add_action( 'ctg_register_taxonomies', 'ctg_register_member_types' );

add_action( 'ctg_setup_canonical_stack', 'ctg_late_include', 20 );

//add_action( 'ctg_template_redirect', 'ctg_redirect_canonical', 2 );

add_action( 'ctg_template_redirect', 'ctg_actions', 4 );
add_action( 'ctg_template_redirect', 'ctg_screens', 6 );
add_action( 'ctg_template_redirect', 'ctg_post_request', 10 );
add_action( 'ctg_template_redirect', 'ctg_get_request', 10 );

add_action( 'ctg_loaded', function() {
	CTG_Members_Admin_Page::get_instance();
}, 1 );

add_action( 'ctg_loaded', function() {
	CTG_Locations_Admin_Page::get_instance();
}, 1 );



//add_action( 'ctg_template_redirect', 'ctg_private_network_template_redirect', 10 );

/*/
add_action( 'ctg_after_setup_theme', 'ctg_load_theme_functions', 1 );
/*/

if ( is_admin() ) {
	//	add_action( 'ctg_loaded', 'ctg_admin' );
}

/*/
add_action( 'ctg_activation', 'ctg_add_activation_redirect' );
/*/

/*/
if ( is_admin() ) {
	add_action( 'ctg_init', 'ctg_platform_plugin_updater' );
}
/*/