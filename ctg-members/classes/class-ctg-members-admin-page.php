<?php


class CTG_Members_Admin_Page {

	// class instance
	static $instance;

	// customer WP_List_Table object
	public $members_obj;

	// class constructor
	public function __construct() {
		add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
		add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
	}

	public static function set_screen( $status, $option, $value ) {
		return $value;
	}

	public function plugin_menu() {

		$hook = add_menu_page(
			'CTG Confetti Cannon Members',
			'CTG Members Table',
			'manage_options',
			'ctg_members',
			[ $this, 'plugin_settings_page' ],
			'',
			20
		);

		add_action( "load-$hook", [ $this, 'screen_option' ] );

	}
	/**
* Screen options
*/
	public function screen_option() {

		$option = 'per_page';
		$args = [
			'label' => 'Members',
			'default' => 25,
			'option' => 'members_per_page'
		];

		add_screen_option( $option, $args );

		$this->members_obj = new CTG_Members_Admin_Table();
	}

	/**
* Plugin settings page
*/
	public function plugin_settings_page() {

		$action = isset( $_GET['action'] ) ? $_GET['action'] : '';
		$form_display = '';
		if ( $action === 'add_new' || $action === 'edit' ) {
			$form_display = 'display:flex;';
		} else {
			$form_display = 'display:none;';			
		}
		$locations_options = array();
		$locations = ctg_get_all_locations();
		foreach( $locations as $location ) {
			$locations_options[] = sprintf(
				"<option value='%s'>%s</option>",
				$location['id'],
				$location['location_name']
			);
		}

?>
<div class="wrap">
	<div id="ctg_member_feedback_container">
		<div id="ctg_member_feedback_close"></div>
		<p id="ctg_member_feedback"></p>
	</div>
	<?php 

	?>
	<h1 class="wp-heading-inline">CTG Team Members</h1>
	<a href="<?php esc_url( admin_url('page=ctg_members') )?>" class="page-title-action" id="ctg_member_add_new_link">Add New</a>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<form method="post">
						<?php
			$this->members_obj->prepare_items();
		$this->members_obj->display(); 
						?>
					</form>
				</div>
			</div>
		</div>
		<br class="clear">
	</div>

	<div id="ctg_members_form_container" stye="<?php echo $form_display ?>">
		<div style="position:absolute;right:2rem;top:2rem;">
			<button type="button" id="ctg_members_form_close"></button>
		</div>
		<div class="ctg-members-form-wrapper" >
			<form method="post" 
				  class="ctg-members-form"
				  style="display:none;flex-flow: row wrap;height:100%;gap:1rem;"
				  id="ctg_members_form"
				  enctype="multipart/form-data"
				  >
				<h3>
					CTG Team Member Form
				</h3>
				<div class="ctg-form-section" >
					<label 
						   for="ctg_first_name"
						   style="width:100%;"
						   >First Name</label>
					<input 
						   type="text" 
						   name="ctg_first_name" 
						   id="ctg_first_name" 
						   style="width:100%;" />
				</div>
				<div class="ctg-form-section" >
					<label 
						   for="ctg_last_name"
						   style="width:100%;"
						   >Last Name</label>
					<input type="text" name="ctg_last_name" id="ctg_last_name" style="width:100%;" />
				</div>
				<div class="ctg-form-section" >
					<label 
						   for="ctg_job_title"
						   style="width:100%;"
						   >Job Title</label>
					<input type="text" name="ctg_job_title" id="ctg_job_title" style="width:100%;" />
				</div>
				<div class="ctg-form-section" >
					<label 
						   for="ctg_email"
						   style="width:100%;"
						   >Email</label>
					<input type="text" name="ctg_email" id="ctg_email" style="width:100%;" />
				</div>
				<div class="ctg-form-section" >
					<label 
						   for="ctg_location_id"
						   style="width:100%;"
						   >Location</label>
					<select type="text" name="ctg_location_id" id="ctg_location_id" style="width:100%;" >
						<option value="">Please select a CTG location</option>
						<option value="0">Remote</option>
						<?php foreach ($locations_options as $locations_option ) { echo $locations_option; } ?>
					</select>
				</div>

				<?php wp_enqueue_media(); ?>
				<div style="display:flex;justify-content:space-evenly;align-items:center;">
					<div class='image-preview-wrapper'>
						<img id='image-preview' src='' width='100' height='100' style='max-height: 100px; width: 100px;'>
					</div>
					<input 
						   id="ctg_upload_image_button" 
						   type="button" 
						   class="button" 
						   style="height:100%;max-height:30px;"
						   value="<?php _e( 'Change Image' ); ?>" />
				</div>
				<input type='hidden' name='ctg_image_attachment_id' id='ctg_image_attachment_id' value=''>
				<input type='hidden' name='ctg_headshot_url' id='ctg_headshot_url' value=''>
				<input type="hidden" name="ctg_member_id" id="ctg_member_id" style="width:100%;" />
				<input type="hidden" name="ctg_members_nonce" id="ctg_members_nonce" style="width:100%;" value="<?php wp_create_nonce('ctg_members_nonce'); ?>"/>
				<?php submit_button('Add Member', 'primary', 'ctg_member_submit'); ?>
			</form>
		</div>
	</div>
</div>
<?php
	}
	/** Singleton instance */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}