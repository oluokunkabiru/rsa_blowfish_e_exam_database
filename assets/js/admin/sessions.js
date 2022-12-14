$(document).ready(function () {
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 4000
	});

	$("#add_session_form").validate({
		ignore: "",
		focusInvalid: false,
		invalidHandler: function (form, validator) {
			if (!validator.numberOfInvalids())
				return;
			$('#modal-session').animate({
				scrollTop: $(validator.errorList[0].element).offset().top
			}, 2000);

		},
		errorClass: "err_msg",
		rules: {
			academic_year: "required",
			first_term: "required",
			second_term: "required",
			third_term: "required",
			current_term: "required",
		},
		messages: {

		},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.add-session').attr('disabled', true);
			$('.add-session').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
			let data = {
				'academic_year': $('#academic_year').val(),
				'first_term': $('#first_term').val(),
				'second_term': $('#second_term').val(),
				'third_term': $('#third_term').val(),
				'current_term': $('#current_term').val(),
			};

			let response = await fetch(base_url + 'add_session', {
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
						title: 'Session Added Successfully.'
					})
					$('#add_session_form').trigger("reset");
					$('#modal-session').modal('hide');
					$('#sessions_table').DataTable().ajax.reload();
				} else if (data.status == 'info'){
					Toast.fire({
						icon: 'info',
						title: 'Session info: '+data.message
					})
				} else {
					Toast.fire({
						icon: 'error',
						title: 'Error Adding Session. ' + data.message
					})
				}
			});
			$('.add-session').html('<i class="fa fa-save"></i> Save Session');
			$('.add-session').attr('disabled', false);
		}
	});

	$("#edit_session_form").validate({
		ignore: "",
		focusInvalid: false,
		invalidHandler: function (form, validator) {
			if (!validator.numberOfInvalids())
				return;
			$('#modal-session-edit').animate({
				scrollTop: $(validator.errorList[0].element).offset().top
			}, 2000);

		},
		errorClass: "err_msg",
		rules: {
			edit_academic_year: "required",
			edit_first_term: "required",
			edit_second_term: "required",
			edit_third_term: "required",
			edit_current_term: "required",
		},
		messages: {
			
		},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.edit-session').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
			$('.edit-session').attr('disabled', true);
			let data = {
				'academic_year': $('#edit_academic_year').val(),
				'first_term': $('#edit_first_term').val(),
				'second_term': $('#edit_second_term').val(),
				'third_term': $('#edit_third_term').val(),
				'current_term': $('#edit_current_term').val(),
			};

			let response = await fetch(base_url + 'edit_session/' + $('#edit_session_form').attr('action'), {
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
						title: 'Session Updated Successfully.'
					})
					$('#edit_session_form').trigger("reset");
					$('#modal-session-edit').modal('hide');
					$('#sessions_table').DataTable().ajax.reload();
				} else {
					Toast.fire({
						icon: 'error',
						title: 'Error Updating Session. ' + data.message
					})
				}
			});
			$('.edit-session').html('<i class="fa fa-save"></i> Update Session');
			$('.edit-session').attr('disabled', false);
		}
	});

	function urldecode(url) {
		return decodeURIComponent(url.replace(/\+/g, ' '));
	}

	$(document).on("click", ".s-edit", function (e) {
		//console.log(urldecode($(this).attr('data')))
		var data = JSON.parse(urldecode($(this).attr('data')));
		//console.log(data.photo)
		$('#edit_academic_year').val(data.academic_year);
		$('#edit_first_term').val(data.first_term_start+' - '+data.first_term_end);
		$('#edit_second_term').val(data.second_term_start+' - '+data.second_term_end);
		$('#edit_third_term').val(data.third_term_start+' - '+data.third_term_end);
		$('#edit_current_term').val(data.current_term);
		$('#edit_session_form').attr('action', $(this).attr('href'));
	});


	$(document).on("click", ".s-del", function (e) {
		e.preventDefault();
		var table = $('#sessions_table').DataTable();
		var row = $(this).parents('tr');

		if (confirm('Are you sure you want to delete?')) {
			$.get(base_url + "del_session/" + $(this).attr('href'), function (data) {
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

	$(document).on("click", ".s-active", function(e){
		e.preventDefault();
		var table = $('#sessions_table').DataTable();
		var row = $(this).parents('tr');
		// var confirmText = ($(this).hasClass('adm-accept') ? 'accept' : ($(this).hasClass('adm-reject') ? 'reject' : 'make pending'));
		 Swal.fire({
			title: 'Are you sure you want to Activate this Session?\n(Any other Active Session will become inactive and all activities will be performed on this session.)',
			icon: 'question',
			showDenyButton: true,
			showCancelButton: true,
			confirmButtonText: 'Activate',
			denyButtonText: 'Cancel',
		  }).then(async (result) => {
			/* Read more about isConfirmed, isDenied below */
			
			if (result.value==true) {
				$.get(base_url + "activate_session/" + $(this).attr('href'), function (data) {
					if (data.status == 'success') {
						Toast.fire({
							icon: 'success',
							title: 'Activation successful.'
						});
						$('#sessions_table').DataTable().ajax.reload();
					}
				});
			}
		})
		// if (confirm('Are you sure you want to Activate this Session?\n(Any other Active Session will become inactive and all activities will be performed on this session.)')) {
			
		// }
	});

	$('.daterange').daterangepicker({
		locale: {
			format: 'YYYY-MM-DD'
		}
	});

	$("#sessions_table").DataTable({
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
			url: base_url + "get_sessions",
			type: 'GET'
		},
		"oSearch": { "bSmart": false, "bRegex": true, "sSearch": "" }
	});
});
