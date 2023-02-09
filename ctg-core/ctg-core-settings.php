<?php
defined( 'ABSPATH' ) || exit;

function ctg_settings_init() {


	/*/ 
	 * $option_group, $option_name, $sanitize_callback
	/*/
	register_setting( 'ctg_confetti_cannon_settings', 'ctg_active_components' );

	/*/ 
	 * $id, $title, $callback, $page
	/*/
	add_settings_section(
		'ctg_components_section',
		__( 'Confetti Cannon Components Settings', 'ctg' ), 
		'ctg_components_section_callback',
		'ctg-settings'
	);

	$components = ctg_core_get_components('all');

	foreach ( $components as $name => $data ) {

		/*/ 
	 	 * $id, $title, $callback, $page, $section, $args = []
		/*/
		add_settings_field(
			"ctg_components[{$name}]",
			__( 'Component', 'ctg' ),
			'ctg_components_field_cb',
			'ctg-settings',
			'ctg_components_section',
			array(
				'label_for'         => 'ctg_components_field',
				'class'             => 'ctg_row',
				'component_name'	=> 'custom',
			)
		);

	}

}

//add_action( 'admin_init', 'ctg_settings_init' );

function ctg_components_section_callback( $args ) {
?>
<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Yeehaw', 'ctg' ); ?></p>
<?php
												  }

function ctg_components_field_cb( $args ) {

	$options = get_option( 'ctg_active_components' );
?>
<select
		id="<?php echo esc_attr( $args['label_for'] ); ?>"
		data-custom="<?php echo esc_attr( $args['ctg_custom_data'] ); ?>"
		name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
	<option value="red" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'red', false ) ) : ( '' ); ?>>
		<?php esc_html_e( 'red pill', 'ctg' ); ?>
	</option>
	<option value="blue" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'blue', false ) ) : ( '' ); ?>>
		<?php esc_html_e( 'blue pill', 'ctg' ); ?>
	</option>
</select>
<p class="description">
	<?php esc_html_e( 'Here\'s a description.', 'ctg' ); ?>
</p>
<p class="description">
	<?php esc_html_e( 'Here\'s a description too.', 'ctg' ); ?>
</p>
<?php
}

function ctg_options_page() {
	add_menu_page(
		'CTG Confetti Cannon',
		'Confetti Cannon Options',
		'manage_options',
		'ctg-settings',
		'ctg_options_page_html'
	);
}

//add_action( 'admin_menu', 'ctg_options_page' );

function ctg_options_page_html() {

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( isset( $_GET['settings-updated'] ) ) {
		// add settings saved message with the class of "updated"
		add_settings_error( 'ctg_messages', 'ctg_message', __( 'Settings Saved', 'ctg' ), 'updated' );
	}

	settings_errors( 'ctg_messages' );
?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<form action="options.php" method="post">
		<?php
	settings_fields( 'ctg-settings' );
	do_settings_sections( 'ctg-settings' );
	submit_button( 'Save Settings' );
		?>
	</form>
</div>
<?php
}

function ctg_get_admin_url( $path = '', $scheme = 'admin' ) {

	$url = admin_url( $path, $scheme );

	return $url;
}

function ctg_core_get_components( $type = 'all' ) {

	$required_components = array();

	$optional_components = array(		
		'celebratorcreator' => array(
			'title'			=> __( 'Celebrator Creator', 'ctg' ),
			'settings'		=> ctg_get_admin_url( add_query_arg( array( 'page' => 'ctg-settings' ) , 'admin.php' ) ),
			'description'	=> __( 'Enables the Celebrator Creator.', 'ctg' ),
			'default'		=> true,
		),
	);

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