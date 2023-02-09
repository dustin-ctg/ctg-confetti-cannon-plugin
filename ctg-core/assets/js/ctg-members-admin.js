jQuery(document).ready( function ($) {

	const feedbackContainer = $('#ctg_member_feedback_container');
	const feedback = $('#ctg_member_feedback');
	const feedbackClose = $('#ctg_member_feedback_close');
	const addNewLink	= $('#ctg_member_add_new_link');
	const formClose		= $('#ctg_members_form_close');
	const memberFormContainer = $('#ctg_members_form_container');
	const memberForm = $('#ctg_members_form');
	const fnameInput = $('#ctg_first_name');
	const lnameInput = $('#ctg_last_name');
	const titleInput = $('#ctg_job_title');
	const emailInput = $('#ctg_email');
	const locationInput = $('#ctg_location_id');
	const headshotURLInput = $('#ctg_headshot_url');
	const memberIDInput = $('#ctg_member_id');
	const formSubmit = $('#ctg_member_submit');
	const membersNonce = $('#ctg_members_nonce');
	const table = $('#the-list');
	const tableRows = $('#the-list tr');
	const initValues = {
		id: '',
		first_name: '',
		last_name: '',
		job_title: '',
		email: '',
		location_id: '',
		headshot_url: ''
	};
	const wpMediaPostId = wp.media.model.settings.post.id; // Store the old id
	const setToPostId = ctg.attachment_id; // Set this
	let fileFrame;
	let editLink		= $('span.edit').find('a.ctg-member-edit-link');
	let deleteLink		= $('span.delete').find('a');

	function showForm() {
		memberFormContainer.show(500, function(){
			memberFormContainer.css('display','flex');
			memberForm.slideDown(500);
		});
	}

	function hideForm() {
		memberForm.slideUp(500, function() {
			memberFormContainer.hide(500);
		});
	}

	function formatMessage(type = '') {
		let retval;
		switch ( type ) {
			case 'error':
				retval = '#ffad87';
				break;
			case 'success':
				retval = '#62cc8f';
				break;
			case 'warning':
				retval = '#dbb778';
				break;
			default:
				retval = '#2a2a2a';
		}
		return retval;
	}

	function showMessage(m = {}) {
		feedbackContainer.slideDown(500, function(){
			feedbackContainer.css('display', 'flex');
			feedback.text(m.text).css('color', formatMessage(m.type));
		});
	}

	function hideMessage() {
		feedbackContainer.slideUp(500,function(){
			feedback.text('').css('color','inherit');
		});
	}

	function setFormValues( values = {} ) {

		if ($.isEmptyObject(values)) {
			values = initValues;
			memberIDInput.blur();
			fnameInput.blur();
			lnameInput.blur();
		} else {
			values = JSON.parse(values)[0];
		}

		memberIDInput.val(values.id);
		fnameInput.val(values.first_name);
		lnameInput.val(values.last_name);
		emailInput.val(values.email);
		titleInput.val(values.job_title);
		locationInput.val(values.location_id);
		headshotURLInput.val(values.headshot_url);
		$( '#image-preview' ).attr( 'src', values.headshot_url ).css( 'width', 'auto');
		membersNonce.val(ctg.members_nonce);
		formSubmit.val( values.id !== '' ? 'Update Member' : 'Add Member');
	}

	function setRows( rows ) {
		table.children('tr').remove();
		table.append( rows );
	}

	async function refreshTable() {
		await $.ajax({
			method: 'GET',
			url: ctg.refresh_members_table,
			data: {
				paged: $('#current-page-selector').val()
			},
			success: function (e) {
				setRows(e);
			},
			error:  function (e) {
				showMessage({
					text: e.toString(),
					type: 'error'
				});
			},
			complete:  function (e) {
			}
		});
	}

	async function addMember( values = {} ) {
		if ( $.isEmptyObject( values ) ) {
			return;
		} else {

			await $.ajax({
				url: ctg.add_member,
				type: "POST",
				data: values,
				success: function(success) {	
					let data = JSON.parse(success);
					showMessage({
						text: data.text,
						type: data.type
					});
					refreshTable();
				},
				error: function ( error ) {
					console.log(error)	
				},
				complete: function ( response ) {
					setFormValues();
					hideForm();
					refreshTable();
				}
			});
		}
	}

	async function updateMember(values = {}) {
		if ($.isEmptyObject(values)) {
			return;
		} else {
			await $.ajax({
				url: ctg.update_member,
				type: "POST",
				data: values,
				success: function(success) {
					let data = JSON.parse(success);
					showMessage({
						text: data.text,
						type: data.type
					});
					refreshTable();
				},
				error: function ( error ) {
					console.log(error);
				},
				complete: function ( response ) {
					setFormValues();
					hideForm();
					refreshTable();
				}
			});
		}
	}

	memberForm.on( 'submit', function(e) {
		e.preventDefault();
		let values = $( this ).serializeArray();
		let data = {};
		$.each( values, function( i, field ) {
			data[ field.name ] = field.value;
		});

		if ( data.ctg_member_id ) {
			updateMember( data );
		} else {
			addMember( data );
		}

	});

	table.on('click','.ctg-member-edit-link', async function(e) {

		e.preventDefault();

		let memberID = e.target.dataset['memberId'];
		let member = await $.get(
			ctg.get_member_by_id,
			{id:memberID},
			function (data) {
				return JSON.parse(data);
			}
		);

		showForm();
		setFormValues(member);

	});

	table.on('click', '.ctg-member-delete-link', async function(e) {

		e.preventDefault();

		let memberID = e.target.dataset['memberId'];
		let member = await $.ajax({
			url: ctg.delete_member,
			method: "POST",
			data: {
				id:memberID,
				ctg_delete_member_nonce: ctg.delete_member_nonce
			},
			success: function (data) {
				let response = JSON.parse(data);
				showMessage({
					text: response.text,
					type: response.type
				});
			},
			error: function (data) {
				let response = JSON.parse(data);
				showMessage({
					text: response.text,
					type: response.type
				});
			},
			complete: function (data) {
				refreshTable();
			},
		});

	});

	addNewLink.on('click', function(e){
		e.preventDefault();
		showForm();
	});

	formClose.on('click', function(e){
		setFormValues();
		hideForm();
	});

	$('#ctg_upload_image_button').on('click', function( e ){

		e.preventDefault();

		// If the media frame already exists, reopen it.
		if ( fileFrame ) {
			// Set the post ID to what we want
			fileFrame.uploader.uploader.param( 'post_id', setToPostId );
			// Open frame
			fileFrame.open();
			return;
		} else {
			// Set the wp.media post id so the uploader grabs the ID we want when initialised
			wp.media.model.settings.post.id = setToPostId;
		}

		// Create the media frame.
		fileFrame = wp.media.frames.file_frame = wp.media({
			title: 'Select an image to upload',
			button: {
				text: 'Use This Image',
			},
			multiple: false	// Set to true to allow multiple files to be selected
		});

		// When an image is selected, run a callback.
		fileFrame.on( 'select', function() {
			// We set multiple to false so only get one image from the uploader
			attachment = fileFrame.state().get('selection').first().toJSON();

			// Do something with attachment.id and/or attachment.url here
			$( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
			$( '#ctg_image_attachment_id' ).val( attachment.id );
			headshotURLInput.val( attachment.url );

			// Restore the main post ID
			wp.media.model.settings.post.id = wpMediaPostId;
		});

		// Finally, open the modal
		fileFrame.open();
	});

	// Restore the main ID when the add media button is pressed
	$( 'a.add_media' ).on( 'click', function() {
		wp.media.model.settings.post.id = wpMediaPostId;
	});

	feedbackClose.on('click', function(){
		hideMessage();
	});

	async function loadActions() {
		let action = getUrlParameter('action');
		let activeMemberIDParam = getUrlParameter('member_id');

		if ( action === 'add_new') {
			showForm();			
		}

		if ( action === 'update' ) {
			showForm();
			if ( activeMemberIDParam ) {
				let member = await $.get(
					ctg.get_member_by_id,
					{id:activeMemberIDParam},
					function (data) {
						return JSON.parse(data);
					}
				);
				setFormValues(member);
			}
		}
	}
	
	loadActions();

});

function getUrlParameter(name) {
	const url = window.location.search.substring(1);
	let parameters = url.split('&');

	for (var i = 0; i < parameters.length; i++) {
		let parameter = parameters[i].split('=');
		if (parameter[0] === name) {
			return parameter[1];
		}
	}
}
