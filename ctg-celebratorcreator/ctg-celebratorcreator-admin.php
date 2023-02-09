<?php 
/*/ 
 * 
 * Here we have true facts about the Celebrator Creator Admin Page Stuff
 * 
/*/

defined('ABSPATH') || exit;
/*/
if ( ! function_exists( 'ctg_check_admin_page' ) ) {
	function ctg_check_admin_page( $pagenow ) {
		if ( 'plugin-editor.php' === $pagenow ) {
			return;
		} else {
			echo "We're in the clear, captain.";	
		}
	}
}
add_action('init', 'ctg_check_admin_page');
/*/
