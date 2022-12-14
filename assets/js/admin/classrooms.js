$(document).ready(function(){
	$("select").select2();
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 4000
	});
	$("#class_arms").html($(".class_arms"));
	// $(".class_arms").removeClass("d-none");
	$("#class").on('change', function(){
		var myclass = $("#class").val();
		if (myclass=="Alumni"){
			$("#class_arms").html($(".alumni_arms"));

		}else{
			$("#class_arms").html($(".class_arms"));
		}
		// alert($("#class").val());
	});
	$("#add_classroom_form").validate({
		ignore: "",
		focusInvalid: false,
		invalidHandler: function (form, validator) {
			if (!validator.numberOfInvalids())
				return;
			$('#modal-classroom').animate({
				scrollTop: $(validator.errorList[0].element).offset().top
			}, 2000);

		},
		errorClass: "err_msg",
		rules: {
			academic_year: "required",
			class: "required",
			arms: "required",
		},
		messages: {

		},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.add-classroom').attr('disabled', true);
			$('.add-classroom').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
			var checked = [];
			
			$("input[name='arms[]']:checked").each(function(){
				checked.push($(this).val());

			});
			
			let data = {
				'academic_year': $('#classroom_academic_year').val(),
				'class': $('#class').val().join(','),
				'arms': checked.join(','),
			};

			let response = await fetch(base_url + 'add_classrooms', {
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
						title: 'Classroom Added Successfully.'
					})
					$('#add_classroom_form').trigger("reset");
					$('#modal-classroom').modal('hide');
					$('#classrooms_table').DataTable().ajax.reload();
				} else if (data.status == 'info'){
					Toast.fire({
						icon: 'info',
						title: 'Classroom info: '+data.message
					})
				} else {
					Toast.fire({
						icon: 'error',
						title: 'Error Adding Classroom. ' + data.message
					})
				}
			});
			$('.add-classroom').html('<i class="fa fa-save"></i> Save Classroom');
			$('.add-classroom').attr('disabled', false);
		}
	});

	$("#edit_classroom_form").validate({
		ignore: "",
		focusInvalid: false,
		invalidHandler: function (form, validator) {
			if (!validator.numberOfInvalids())
				return;
			$('#modal-classroom-edit').animate({
				scrollTop: $(validator.errorList[0].element).offset().top
			}, 2000);

		},
		errorClass: "err_msg",
		rules: {
			edit_academic_year: "required",
			edit_class: "required",
			edit_arm: "required",
		},
		messages: {
			
		},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.edit-classroom').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
			$('.edit-classroom').attr('disabled', true);
			let data = {
				'academic_year': $('#edit_classroom_academic_year').val(),
				'class': $('#edit_class').val(),
				'arm': $('#edit_arm').val(),
			};

			let response = await fetch(base_url + 'edit_classroom/' + $('#edit_classroom_form').attr('action'), {
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
						title: 'Classroom Updated Successfully.'
					})
					$('#edit_classroom_form').trigger("reset");
					$('#modal-classroom-edit').modal('hide');
					$('#classrooms_table').DataTable().ajax.reload();
				} else {
					Toast.fire({
						icon: 'error',
						title: 'Error Updating Classroom. ' + data.message
					})
				}
			});
			$('.edit-classroom').html('<i class="fa fa-save"></i> Update Classroom');
			$('.edit-classroom').attr('disabled', false);
		}
	});


	function urldecode(url) {
		return decodeURIComponent(url.replace(/\+/g, ' '));
	}
	$(document).on("click", ".c-edit", function (e) {
		var data = JSON.parse(urldecode($(this).attr('data')));
		$('#edit_classroom_academic_year').val(data.academic_year);
		$('#edit_class').val(data.class);
		$('#edit_arm').val(data.arm);
		$('#edit_classroom_form').attr('action', $(this).attr('href'));
	});

	$(document).on("click", ".c-del", function (e) {
		e.preventDefault();
		var table = $('#classrooms_table').DataTable();
		var row = $(this).parents('tr');

		if (confirm('Are you sure you want to delete?')) {
			$.get(base_url + "del_classroom/" + $(this).attr('href'), function (data) {
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
	
	$("#classrooms_table").DataTable({
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
			url: base_url + "get_classrooms",
			type: 'GET'
		},
		"oSearch": { "bSmart": false, "bRegex": true, "sSearch": "" }
	});
});
