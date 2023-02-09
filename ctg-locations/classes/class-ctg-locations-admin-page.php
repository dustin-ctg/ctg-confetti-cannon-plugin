<?php


class CTG_Locations_Admin_Page
{

	// class instance
	static $instance;

	// customer WP_List_Table object
	public $locations_obj;

	// class constructor
	public function __construct()
	{
		add_filter('set-screen-option', [__CLASS__, 'set_screen'], 10, 3);
		add_action('admin_menu', [$this, 'plugin_menu']);
	}

	public static function set_screen($status, $option, $value)
	{
		return $value;
	}

	public function plugin_menu()
	{

		$hook = add_menu_page(
			'CTG Confetti Cannon Locations',
			'CTG Locations Table',
			'manage_options',
			'ctg_locations',
			[$this, 'plugin_settings_page'],
			'',
			21
		);

		add_action("load-$hook", [$this, 'screen_option']);

	}
	/**
	 * Screen options
	 */
	public function screen_option()
	{

		$option = 'per_page';
		$args = [
			'label' => 'Locations',
			'default' => 25,
			'option' => 'locations_per_page'
		];

		add_screen_option($option, $args);

		$this->locations_obj = new CTG_Locations_Admin_Table();
	}

	/**
	 * Plugin settings page
	 */
	public function plugin_settings_page()
	{

		$states_options = array();
		$states = ctg_locations_state_options();
		foreach ($states as $abbr => $name) {
			$states_options[] = sprintf(
				"<option value='%s'>%s</option>",
				$abbr,
				$name
			);
		}

?>
<div class="wrap">
	<div id="ctg_location_feedback_container">
		<div id="ctg_location_feedback_close"></div>
		<p id="ctg_location_feedback"></p>
	</div>
	<h1 class="wp-heading-inline">CTG Team Locations</h1>
	<a href="<?php esc_url(admin_url('page=ctg_locations')) ?>" class="page-title-action"
	   id="ctg_location_add_new_link">Add New</a>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<form method="post">
						<?php
			$this->locations_obj->prepare_items();
		$this->locations_obj->display();
						?>
					</form>
				</div>
			</div>
		</div>
		<br class="clear">
	</div>

	<div id="ctg_locations_form_container">
		<div style="position:absolute;right:2rem;top:2rem;">
			<button type="button" id="ctg_locations_form_close"></button>
		</div>
		<div class="ctg-locations-form-wrapper">
			<form method="post" class="ctg-locations-form"
				  style="display:none;flex-flow: row wrap;height:100%;gap:1rem;" id="ctg_locations_form"
				  enctype="multipart/form-data">
				<h3>
					CTG Team Location Form
				</h3>
				<div class="ctg-form-section">
					<label for="ctg_location_name" style="width:100%;">Location Name</label>
					<input type="text" required name="ctg_location_name" id="ctg_location_name" style="width:100%;" />
				</div>
				<div class="ctg-form-section">
					<label for="ctg_street_primary" style="width:100%;">Line 1</label>
					<input type="text" required name="ctg_street_primary" id="ctg_street_primary" style="width:100%;" />
				</div>
				<div class="ctg-form-section">
					<label for="ctg_street_secondary" style="width:100%;">Line 2</label>
					<input type="text" name="ctg_street_secondary" id="ctg_street_secondary" style="width:100%;" />
				</div>
				<div class="ctg-form-section">
					<label for="ctg_city" style="width:100%;">City</label>
					<input type="text" required name="ctg_city" id="ctg_city" style="width:100%;" />
				</div>

				<div class="ctg-form-section">
					<label for="ctg_state" style="width:100%;">State</label>
					<select type="text" required name="ctg_state" id="ctg_state" style="width:100%;">
						<option value="">Please select a state</option>
						<?php foreach ($states_options as $states_option) {
							echo $states_option;
						} ?>
					</select>
				</div>

				<div class="ctg-form-section">
					<label for="ctg_zip" style="width:100%;">Zip</label>
					<input type="text" required name="ctg_zip" id="ctg_zip" style="width:100%;" />
				</div>

				<div class="ctg-form-section">
					<label for="ctg_phone" style="width:100%;">Phone</label>
					<input type="text" required name="ctg_phone" id="ctg_phone" style="width:100%;" />
				</div>

				<div class="ctg-form-section">
					<label for="ctg_email" style="width:100%;">Email Address</label>
					<input type="text" required name="ctg_email" id="ctg_email" style="width:100%;" />
				</div>

				<div class="ctg-form-section">

					<input 
						   type="checkbox" 
						   name="ctg_appointment_only" 
						   id="ctg_appointment_only" 
						   value="1"
						   />
					<label for="ctg_appointment_only" style="width:100%;">Appointment Only</label>
				</div>

				<div class="ctg-form-section">

					<input 
						   type="checkbox" 
						   name="ctg_franchise_location" 
						   id="ctg_franchise_location" 
						   value="1"
						   />
					<label for="ctg_franchise_location" style="width:100%;">Franchise Location</label>
				</div>

				<input type="hidden" name="ctg_location_id" id="ctg_location_id" style="width:100%;" />
				<input type="hidden" name="ctg_locations_nonce" id="ctg_locations_nonce" style="width:100%;"
					   value="<?php wp_create_nonce('ctg_locations_nonce'); ?>" />
				<?php submit_button('Add Location', 'primary', 'ctg_location_submit'); ?>
			</form>
		</div>
	</div>
</div>
<?php
	}
	/** Singleton instance */
	public static function get_instance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}