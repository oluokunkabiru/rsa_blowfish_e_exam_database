$(document).ready(function(){
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 4000
	});

	$("#add_subject_form").validate({
		ignore: "",
		focusInvalid: false,
		invalidHandler: function (form, validator) {
			if (!validator.numberOfInvalids())
				return;
			$('#modal-subject').animate({
				scrollTop: $(validator.errorList[0].element).offset().top
			}, 2000);

		},
		errorClass: "err_msg",
		rules: {
			name: "required",
			class: "required",
			short: "required",
		},
		messages: {

		},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.add-subject').attr('disabled', true);
			$('.add-subject').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
			
			let data = {
				'name': $('#name').val(),
				'short': $('#short').val(),
				'gradable_scorable': $('#gs').val(),
				'class': $('#subject_class').val().join(','),
			};

			let response = await fetch(base_url + 'add_subjects', {
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
						title: 'Subject Added Successfully.'
					})
					$('#add_subject_form').trigger("reset");
					$('#modal-subject').modal('hide');
					$('#subjects_table').DataTable().ajax.reload();
				} else if (data.status == 'info'){
					Toast.fire({
						icon: 'info',
						title: 'Subject info: '+data.message
					})
				} else {
					Toast.fire({
						icon: 'error',
						title: 'Error Adding Subject. ' + data.message
					})
				}
			});
			$('.add-subject').html('<i class="fa fa-save"></i> Save Subject');
			$('.add-subject').attr('disabled', false);
		}
	});

	$("#edit_subject_form").validate({
		ignore: "",
		focusInvalid: false,
		invalidHandler: function (form, validator) {
			if (!validator.numberOfInvalids())
				return;
			$('#modal-subject-edit').animate({
				scrollTop: $(validator.errorList[0].element).offset().top
			}, 2000);

		},
		errorClass: "err_msg",
		rules: {
			edit_name: "required",
			edit_subject_class: "required",
			edit_short: "required",
		},
		messages: {
			
		},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.edit-subject').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
			$('.edit-subject').attr('disabled', true);
			let data = {
				'name': $('#edit_name').val(),
				'short': $('#edit_short').val(),
				'gradable_scorable': $('#edit_gs').val(),
				'class': $('#edit_subject_class').val().join(','),
			};

			let response = await fetch(base_url + 'edit_subject/' + $('#edit_subject_form').attr('action'), {
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
						title: 'Subject Updated Successfully.'
					})
					$('#edit_subject_form').trigger("reset");
					$('#modal-subject-edit').modal('hide');
					$('#subjects_table').DataTable().ajax.reload();
				} else {
					Toast.fire({
						icon: 'error',
						title: 'Error Updating Subject. ' + data.message
					})
				}
			});
			$('.edit-subject').html('<i class="fa fa-save"></i> Update Subject');
			$('.edit-subject').attr('disabled', false);
		}
	});


	function urldecode(url) {
		return decodeURIComponent(url.replace(/\+/g, ' '));
	}
	$(document).on("click", ".sj-edit", function (e) {
		var data = JSON.parse(urldecode($(this).attr('data')));
		$('#edit_name').val(data.name);
		$('#edit_subject_class').val(data.class);
		$('#edit_short').val(data.short);
		$('#edit_gs').val(data.gradable_scorable);
		$('#edit_subject_form').attr('action', $(this).attr('href'));
	});

	$(document).on("click", ".sj-del", function (e) {
		e.preventDefault();
		var table = $('#subjects_table').DataTable();
		var row = $(this).parents('tr');

		if (confirm('Are you sure you want to delete?')) {
			$.get(base_url + "del_subject/" + $(this).attr('href'), function (data) {
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
	
	$("#subjects_table").DataTable({
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
			url: base_url + "get_subjects",
			type: 'GET'
		},
		"oSearch": { "bSmart": false, "bRegex": true, "sSearch": "" }
	});
});
