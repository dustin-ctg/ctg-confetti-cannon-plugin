<?php
/**
 * Plugin Name: CTG Confetti Cannon Plugin
 * Description: This is going to be a custom Elementor addon to get us some extra functionality out of Elementor/Elementor Pro, and make sure we can easily make updates live in minutes.
 * Plugin URI:  https://elementor.com/
 * Version:     1.0.0
 * Author:      Dustin Delgross
 * Author URI:  https://developers.elementor.com/
 * Text Domain: ctg
 * 
 * Elementor tested up to:     3.5.0
 * Elementor Pro tested up to: 3.5.0
 */

defined( 'ABSPATH' ) ||	exit; // Exit if accessed directly.

$ctg_plugin_file = 'ctg-confetti-cannon/ctg-confetti-cannon-loader.php';

if ( ! defined( 'CTG_VERSION' ) ) {
	define( 'CTG_VERSION', '1.0.0' );
}

require dirname( __FILE__ ) . '/class-confetti-cannon.php';

function ctg() {
	return Confetti_Cannon::instance();
}

$_GLOBALS['ctg'] = ctg();