<?php

function ctg_locations_add_meta_box() {
	$screens = [ 'post' ];
	foreach ( $screens as $screen ) {
		add_meta_box(
			'ctg_locations_meta',                 // Unique ID
			'CTG Confetti Cannon',      // Box title
			'ctg_locations_meta_box',  // Content callback, must be of type callable
			$screen                            // Post type
		);
	}
}
add_action( 'add_meta_boxes', 'ctg_locations_add_meta_box' );

function ctg_locations_meta_box( $post ) {
	$is_location = get_post_meta( $post->ID, '_ctg_is_location', true );
	$is_franchise = get_post_meta( $post->ID, '_ctg_is_franchise', true );
	$location_id = get_post_meta( $post->ID, '_ctg_location_id', true );
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
<div class="ctg-form-section">
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
<div class="ctg-form-section">
	<input 
		   type="checkbox" 
		   name="ctg_franchise_location" 
		   id="ctg_franchise_location" 
		   value="1"
		   <?php echo checked( $is_franchise ); ?>
		   />
	<label for="ctg_franchise_location">Is this a Franchise Location?</label>
</div>
<?php
}

function ctg_locations_save_post_meta( $post_id ) {
	if ( array_key_exists( 'ctg_locations_is_location', $_POST ) ) {
		update_post_meta(
			$post_id,
			'_ctg_is_location',
			true
		);
	} else {
		update_post_meta(
			$post_id,
			'_ctg_is_location',
			false
		);
	}

	if ( array_key_exists('ctg_location_id', $_POST ) ) {
		update_post_meta(
			$post_id,
			'_ctg_location_id',
			intval( $_POST['ctg_location_id'] )
		);
	} else {
		update_post_meta(
			$post_id,
			'_ctg_location_id',
			''
		);
	}
	if ( array_key_exists( 'ctg_franchise_location', $_POST ) ) {
		update_post_meta(
			$post_id,
			'_ctg_is_franchise',
			true
		);
	} else {
		update_post_meta(
			$post_id,
			'_ctg_is_franchise',
			false
		);
	}
}
add_action( 'save_post', 'ctg_locations_save_post_meta' );