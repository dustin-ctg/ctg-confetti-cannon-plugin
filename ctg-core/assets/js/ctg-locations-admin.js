jQuery(document).ready( function ($) {

	const feedbackContainer = $('#ctg_location_feedback_container');
	const feedback = $('#ctg_location_feedback');
	const feedbackClose = $('#ctg_location_feedback_close');
	const addNewLink	= $('#ctg_location_add_new_link');
	const formClose		= $('#ctg_locations_form_close');
	const locationFormContainer = $('#ctg_locations_form_container');
	const locationForm = $('#ctg_locations_form');
	const locationIDInput = $('#ctg_location_id');
	const lnInput = $('#ctg_location_name');
	const spInput = $('#ctg_street_primary');
	const ssInput = $('#ctg_street_secondary');
	const cityInput = $('#ctg_city');
	const stateInput = $('#ctg_state');
	const zipInput = $('#ctg_zip');
	const phoneInput = $('#ctg_phone');
	const emailInput = $('#ctg_email');
	const aoInput = $('#ctg_appointment_only');
	const flInput = $('#ctg_franchise_location');
	const formSubmit = $('#ctg_location_submit');
	const locationsNonce = $('#ctg_locations_nonce');
	const table = $('#the-list');

	const initValues = {
		id: '',
		location_name: '',
		street_primary: '',
		street_secondary: '',
		city: '',
		state: '',
		zip: '',
		phone: '',
		email: ''
	};

	let tableRows = $('#the-list tr');
	const editLink		= $('span.edit').find('a.ctg-location-edit-link');
	const deleteLink		= $('span.delete').find('a');

	function showForm () {
		locationFormContainer.show(500, function(){
			locationFormContainer.css('display','flex');
			locationForm.slideDown(500);
		});
	}

	function hideForm() {
		locationForm.slideUp(500, function() {
			locationFormContainer.hide(500);
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
			locationIDInput.blur();
			lnInput.blur();
			spInput.blur();
			ssInput.blur();
			cityInput.blur();
			stateInput.blur();
			zipInput.blur();
			phoneInput.blur();
			emailInput.blur();
		} else {
			values = JSON.parse(values);
			values = JSON.parse(values.text)[0];
		}
		let aoChecked = parseInt(values.appointment_only) === 1;
		let flChecked = parseInt(values.franchise_location) === 1;
		locationIDInput.val(values.id);
		lnInput.val(values.location_name);
		spInput.val(values.street_primary);
		ssInput.val(values.street_secondary);
		cityInput.val(values.city);
		stateInput.val(values.state);
		zipInput.val(values.zip);
		phoneInput.val(values.phone);
		emailInput.val(values.email);
		aoInput.prop("checked", aoChecked);
		flInput.prop("checked", flChecked);
		locationsNonce.val(ctg.locations_nonce);
		formSubmit.val( values.id !== '' ? 'Update Location' : 'Add Location');
	}

	function setRows( rows ) {
		tableRows.remove();
		table.append( rows );
		tableRows = $('#the-list tr');
	}

	async function refreshTable() {
		await $.ajax({
			method: 'GET',
			url: ctg.refresh_locations_table,
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

	async function addLocation( values = {} ) {
		if ( $.isEmptyObject( values ) ) {
			return;
		} else {

			await $.ajax({
				url: ctg.add_location,
				type: "POST",
				data: values,
				success: function(success) {	
					let data = JSON.parse(success);
					showMessage({
						text: data.text,
						type: data.type
					})
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

	async function updateLocation(values = {}) {
		if ($.isEmptyObject(values)) {
			return;
		} else {
			await $.ajax({
				url: ctg.update_location,
				type: "POST",
				data: values,
				success: function(success) {
					let data = JSON.parse(success);
					showMessage({
						text: data.text,
						type: data.type
					})
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

	locationForm.on( 'submit', function(e) {
		e.preventDefault();
		let values = $( this ).serializeArray();
		let data = {};
		$.each( values, function( i, field ) {
			data[ field.name ] = field.value;
		});

		if ( data.ctg_location_id ) {
			updateLocation( data );
		} else {
			addLocation( data );
		}

	});

	table.on('click', '.ctg-location-edit-link', async function(e) {

		e.preventDefault();

		let locationID = e.target.dataset['locationId'];
		let location = await $.get(
			ctg.get_location_by_id,
			{id:locationID},
			function (data) {
				return JSON.parse(data);
			}
		);

		showForm();
		setFormValues(location);

	});

	table.on('click', '.ctg-location-delete-link', async function(e) {

		e.preventDefault();

		let locationID = e.target.dataset['locationId'];
		let location = await $.ajax({
			url: ctg.delete_location,
			method: "POST",
			data: {
				id:locationID,
				ctg_delete_location_nonce: ctg.delete_location_nonce
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

	feedbackClose.on('click', function(){
		hideMessage();
	});

});