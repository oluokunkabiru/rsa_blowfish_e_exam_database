$(document).ready(function(){
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 4000
	});

	$("#add_scheme_form").validate({
		ignore: "",
		focusInvalid: false,
		invalidHandler: function (form, validator) {
			if (!validator.numberOfInvalids())
				return;
			$('#modal-scheme').animate({
				scrollTop: $(validator.errorList[0].element).offset().top
			}, 2000);

		},
		errorClass: "err_msg",
		rules: {
			scheme: "required",
			scheme_class: "required",
			sharing: "required",
		},
		messages: {

		},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.add-scheme').attr('disabled', true);
			$('.add-scheme').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
			
			let data = {
				'class': $('#scheme_class').val().join(','),
				'scheme': $('#scheme').val(),
				'sharing': $('#sharing').val(),
			};

			let response = await fetch(base_url + 'add_schemes', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
				},
				body: JSON.stringify(data)
			}).then(function (response) {
				return response.json();
			}).then(function (data) {
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: 'Scheme Added Successfully.'
					})
					$('#add_scheme_form').trigger("reset");
					$('#modal-scheme').modal('hide');
					$('#schemes_table').DataTable().ajax.reload();
				} else if (data.status == 'info'){
					Toast.fire({
						icon: 'info',
						title: 'Scheme info: '+data.message
					})
				} else {
					Toast.fire({
						icon: 'error',
						title: 'Error Adding Scheme. ' + data.message
					})
				}
			});
			$('.add-scheme').html('<i class="fa fa-save"></i> Save Scheme');
			$('.add-scheme').attr('disabled', false);
		}
	});

	$("#edit_scheme_form").validate({
		ignore: "",
		focusInvalid: false,
		invalidHandler: function (form, validator) {
			if (!validator.numberOfInvalids())
				return;
			$('#modal-scheme-edit').animate({
				scrollTop: $(validator.errorList[0].element).offset().top
			}, 2000);

		},
		errorClass: "err_msg",
		rules: {
			edit_scheme: "required",
			edit_scheme_class: "required",
			edit_shariing: "required",
		},
		messages: {
			
		},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.edit-scheme').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
			$('.edit-scheme').attr('disabled', true);
			let data = {
				'scheme': $('#edit_scheme').val(),
				'sharing': $('#edit_sharing').val(),
				'class': $('#edit_scheme_class').val().join(','),
			};

			let response = await fetch(base_url + 'edit_scheme/' + $('#edit_scheme_form').attr('action'), {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
				},
				body: JSON.stringify(data)
			}).then(function (response) {
				return response.json();
			}).then(function (data) {
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: 'Scheme Updated Successfully.'
					})
					$('#edit_scheme_form').trigger("reset");
					$('#modal-scheme-edit').modal('hide');
					$('#schemes_table').DataTable().ajax.reload();
				} else if(data.status == 'info'){
					Toast.fire({
						icon: 'info',
						title: 'Info: ' + data.message
					})
				} else {
					Toast.fire({
						icon: 'error',
						title: 'Error Updating Scheme. ' + data.message
					})
				}
			});
			$('.edit-scheme').html('<i class="fa fa-save"></i> Update Scheme');
			$('.edit-scheme').attr('disabled', false);
		}
	});


	function urldecode(url) {
		return decodeURIComponent(url.replace(/\+/g, ' '));
	}
	$(document).on("click", ".sc-edit", function (e) {
		var data = JSON.parse(urldecode($(this).attr('data')));
		$('#edit_scheme').val(data.scheme);
		$('#edit_scheme_class').val(data.class);
		$('#edit_sharing').val(data.sharing);
		$('#edit_scheme_form').attr('action', $(this).attr('href'));
	});

	$(document).on("click", ".sc-del", function (e) {
		e.preventDefault();
		var table = $('#schemes_table').DataTable();
		var row = $(this).parents('tr');

		if (confirm('Are you sure you want to delete?')) {
			$.get(base_url + "del_scheme/" + $(this).attr('href'), function (data) {
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: 'Delete successful.'
					});
					if ($(row).hasClass('child')) {
						table.row($(row).prev('tr')).remove().draw();
					} else {
						table.row($(this).parents('tr')).remove().draw();
					}
				}
			});
		}
	});
	
	$("#schemes_table").DataTable({
		dom: "<'row'<'col-sm-4'l><'col-sm-4'B><'col-sm-4'f>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-5'i><'col-sm-7'p>>",
		buttons: [
			'print', 'copy', 'csv', 'colvis'
		],
		"ordering": false,
		"responsive": true,
		"autoWidth": false,
		"ajax": {
			url: base_url + "get_schemes",
			type: 'GET'
		},
		"oSearch": { "bSmart": false, "bRegex": true, "sSearch": "" }
	});
});
