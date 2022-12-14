$(document).ready(function(){
    const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 4000
	});

    $("#parents_guardian_table").DataTable({
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
			url: base_url + "get_parents_guardian",
			type: 'GET'
		},
		"oSearch": { "bSmart": false, "bRegex": true, "sSearch": "" }
	});

    $('#add_pg_form').validate({
		ignore: "",
		focusInvalid: false,
		errorClass: "err_msg",
		rules: {},
		messages: {},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.add-pg').attr('disabled', true);
			$('.add-pg').html('<i class="fa fa-spinner fa-spin"></i> Processing...');

			let data = {
                'fathers_name': $('#fathers_name').val(),
                'fathers_occupation': $('#fathers_occupation').val(),
                'fathers_phone': $('#fathers_phone').val(),
                'fathers_email': $('#fathers_email').val(),
                'fathers_address': $('#fathers_address').val(),
                'mothers_name': $('#mothers_name').val(),
                'mothers_occupation': $('#mothers_occupation').val(),
                'mothers_phone': $('#mothers_phone').val(),
                'mothers_email': $('#mothers_email').val(),
                'mothers_address': $('#mothers_address').val(),
                'guardians_name': $('#guardians_name').val(),
                'guardians_occupation': $('#guardians_occupation').val(),
                'guardians_phone': $('#guardians_phone').val(),
                'guardians_email': $('#guardians_email').val(),
                'guardians_address': $('#guardians_address').val(),
            };//$('#add_pg_form').serialize();
			let response = await fetch(base_url + $('#add_pg_form').attr('action'), {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
				},
				body: JSON.stringify(data)
			}).then(function (response) {
				return response.json();
			}).then(function (data) {
				$('.add-pg').attr('disabled', false);
				$('.add-pg').html('<i class="fa fa-money"></i> Add Parents/Guardian');
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: 'Parents/Guardian added Successfully.'
					})
					$('#modal-pg').modal('hide');
					$('#parents_guardian_table').DataTable().ajax.reload();
				} else if (data.status == 'info') {
					Toast.fire({
						icon: 'info',
						title: 'Info: ' + data.message
					})
				} else {
					Toast.fire({
						icon: 'error',
						title: 'Error: ' + data.message
					})
				}
			});
		},
	});

    function urldecode(url) {
		return decodeURIComponent(url.replace(/\+/g, ' '));
	}
	$(document).on("click", ".pg-edit", function (e) {
		var data = JSON.parse(urldecode($(this).attr('data')));
		$('#edit_fathers_name').val(data.fathers_name);
		$('#edit_fathers_occupation').val(data.fathers_occupation);
		$('#edit_fathers_phone').val(data.fathers_phone);
		$('#edit_fathers_email').val(data.fathers_email);
		$('#edit_fathers_address').val(data.fathers_address);
		$('#edit_mothers_name').val(data.mothers_name);
		$('#edit_mothers_occupation').val(data.mothers_occupation);
		$('#edit_mothers_phone').val(data.mothers_phone);
		$('#edit_mothers_email').val(data.mothers_email);
		$('#edit_mothers_address').val(data.mothers_address);
		$('#edit_guardians_name').val(data.guardians_name);
		$('#edit_guardians_occupation').val(data.guardians_occupation);
		$('#edit_guardians_phone').val(data.guardians_phone);
		$('#edit_guardians_email').val(data.guardians_email);
		$('#edit_guardians_address').val(data.guardians_address);

		$('#edit_pg_form').attr('action', $(this).attr('href'));
	});

    $('#edit_pg_form').validate({
		ignore: "",
		focusInvalid: false,
		errorClass: "err_msg",
		rules: {},
		messages: {},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.edit-pg').attr('disabled', true);
			$('.edit-pg').html('<i class="fa fa-spinner fa-spin"></i> Processing...');

			let data = {
                'fathers_name': $('#edit_fathers_name').val(),
                'fathers_occupation': $('#edit_fathers_occupation').val(),
                'fathers_phone': $('#edit_fathers_phone').val(),
                'fathers_email': $('#edit_fathers_email').val(),
                'fathers_address': $('#edit_fathers_address').val(),
                'mothers_name': $('#edit_mothers_name').val(),
                'mothers_occupation': $('#edit_mothers_occupation').val(),
                'mothers_phone': $('#edit_mothers_phone').val(),
                'mothers_email': $('#edit_mothers_email').val(),
                'mothers_address': $('#edit_mothers_address').val(),
                'guardians_name': $('#edit_guardians_name').val(),
                'guardians_occupation': $('#edit_guardians_occupation').val(),
                'guardians_phone': $('#edit_guardians_phone').val(),
                'guardians_email': $('#edit_guardians_email').val(),
                'guardians_address': $('#edit_guardians_address').val(),
            };//$('#add_pg_form').serialize();
			let response = await fetch(base_url +'edit_parents_guardian/'+ $('#edit_pg_form').attr('action'), {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
				},
				body: JSON.stringify(data)
			}).then(function (response) {
				return response.json();
			}).then(function (data) {
				$('.edit-pg').attr('disabled', false);
				$('.edit-pg').html('<i class="fa fa-money"></i> Edit Parents/Guardian');
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: 'Parents/Guardian updated Successfully.'
					})
                    $('#edit_pg_form').trigger("reset");
					$('#modal-pg-edit').modal('hide');
					$('#parents_guardian_table').DataTable().ajax.reload();
				} else if (data.status == 'info') {
					Toast.fire({
						icon: 'info',
						title: 'Info: ' + data.message
					})
				} else {
					Toast.fire({
						icon: 'error',
						title: 'Error: ' + data.message
					})
				}
			});
		},
	});

    //Initialize Select2 Elements
	$('.select2').select2({
		ajax: {
			minimumInputLength: 2,
			url: base_url + 'search_learners',
			dataType: 'json',
			type: "GET",
			data: function (params) {
				return {
					term: params.term,
					//type: 'public'
				}
			},
			processResults: function (data) {
				return {
					results: $.map(data, function (item) {
						return {
							text: item.name,
							id: item.id
						}
					})
				};
			}
		}
	})

    $(document).on("click", ".pg-ward", function(e){
        $('#ward_pg').val($(this).attr('href'));
    });

    $('#ward_pg_form').validate({
		ignore: "",
		focusInvalid: false,
		errorClass: "err_msg",
		rules: {},
		messages: {},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.ward-pg').attr('disabled', true);
			$('.ward-pg').html('<i class="fa fa-spinner fa-spin"></i> Processing...');

			let data = {
                'learner_id': $('#ward_learner').val(),
                'parents_guardian_id': $('#ward_pg').val(),
            };
			let response = await fetch(base_url + $('#ward_pg_form').attr('action'), {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
				},
				body: JSON.stringify(data)
			}).then(function (response) {
				return response.json();
			}).then(function (data) {
				$('.ward-pg').attr('disabled', false);
				$('.ward-pg').html('<i class="fa fa-money"></i> Add Child/Ward to Parents/Guardian');
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: 'Child/Ward added to Parents/Guardian Successfully.'
					})
					$('#modal-pg-ward').modal('hide');
					$('#parents_guardian_table').DataTable().ajax.reload();
				} else if (data.status == 'info') {
					Toast.fire({
						icon: 'info',
						title: 'Info: ' + data.message
					})
				} else {
					Toast.fire({
						icon: 'error',
						title: 'Error: ' + data.message
					})
				}
			});
		},
	});

    $(document).on("click", ".pg-del", function (e) {
		e.preventDefault();
		var table = $('#parents_guardian_table').DataTable();
		var row = $(this).parents('tr');

		if (confirm('Are you sure you want to delete?')) {
			$.get(base_url + "del_parents_guardian/" + $(this).attr('href'), function (data) {
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
});