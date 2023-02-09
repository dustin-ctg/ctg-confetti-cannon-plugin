<?php
//add_action( 'admin_menu', 'ctg_options_page' );
function ctg_options_page() {
	add_menu_page(
		'CTG Confetti Cannon Options',
		'CTG Confetti Cannon',
		'manage_options',
		'confetti-cannon',
		'ctg_options_page_html',
		'',
		20
	);
}

function ctg_options_page_html() {
?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<form action="options.php" method="post">
		<?php
								  settings_fields( 'ctg_options' );
								  // output setting sections and their fields
								  // (sections are registered for "wporg", each field is registered to a specific section)
								  do_settings_sections( 'ctg' );
								  // output save settings button
								  submit_button( __( 'Save Settings', 'ctg' ) );
		?>
	</form>
</div>
<?php
								 }
?>