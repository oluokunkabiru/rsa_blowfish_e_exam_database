$(document).ready(function () {
	$("select").select2();
	let cam_caller = null;

	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 4000
	});

	$("#add_teacher_form").validate({
		ignore: "",
		focusInvalid: false,
		invalidHandler: function (form, validator) {
			if (!validator.numberOfInvalids())
				return;
			$('#modal-teacher').animate({
				scrollTop: $(validator.errorList[0].element).offset().top
			}, 2000);

		},
		errorClass: "err_msg",
		rules: {
			surname: "required",
			firstname: "required",
			//othername: "required",
			birthdate: "required",
			employed: "required",
			staffno: "required",
			qualification: "required",
			course: "required",
			email: "required",
			phone: "required",
			gender: "required",
			religion: "required",
			nationality: "required",
			state: "required",
			lga: "required",
			level: "required",
			passport: "required",
			address: "required",
		},
		messages: {
			/* surname: "Please enter student's surname",
			firstname: "Please enter student's first name", */
		},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.add-teacher').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
			$('.add-teacher').attr('disabled', true);
			let data = {
				'surname': $('#surname').val(),
				'firstname': $('#firstname').val(),
				'othername': $('#othername').val(),
				'title': $('#title').val(),
				'birthdate': $('#birthdate').val(),
				'employed': $('#employed').val(),
				'staffno': $('#staffno').val(),
				'qualification': $('#qualification').val(),
				'course': $('#course').val(),
				'email': $('#email').val(),
				'phone': $('#phone').val(),
				'gender': $("input[name='gender']:checked").val(),
				'religion': $('#religion').val(),
				'level': $('#level').val().join(),
				'nationality': $('#nationality').val(),
				'state': $('#state').val(),
				'lga': $('#lga').val(),
				'session': $('#academic_year').val(),
				'passport': $('#passport').val(),
				'address': $('#address').val(),
			};

			let response = await fetch(base_url + 'add_teacher', {
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
						title: 'Teacher Added Successfully.'
					})
					$('#add_teacher_form').trigger("reset");
					$('#modal-teacher').modal('hide');
					$('#teachers_table').DataTable().ajax.reload();
					//populate_stats(data.data);
				} else if (data.status == 'info') {
					Toast.fire({
						icon: 'info',
						title: 'Info: ' + data.message
					})
				} else {
					Toast.fire({
						icon: 'error',
						title: 'Error Adding Teacher. ' + data.message
					})
				}
			});
			$('.add-teacher').html('<i class="fa fa-save"></i> Add Teacher');
			$('.add-teacher').attr('disabled', false);
		}
	});

	$("#edit_teacher_form").validate({
		ignore: "",
		focusInvalid: false,
		invalidHandler: function (form, validator) {
			if (!validator.numberOfInvalids())
				return;
			$('#modal-teacher').animate({
				scrollTop: $(validator.errorList[0].element).offset().top
			}, 2000);

		},
		errorClass: "err_msg",
		rules: {
			edit_surname: "required",
			edit_firstname: "required",
			//edit_othername: "required",
			edit_birthdate: "required",
			edit_employed: "required",
			edit_staffno: "required",
			edit_qualification: "required",
			edit_course: "required",
			edit_email: "required",
			edit_phone: "required",
			edit_gender: "required",
			edit_religion: "required",
			edit_nationality: "required",
			edit_state: "required",
			edit_lga: "required",
			edit_level: "required",
			edit_passport: "required",
			edit_address: "required",
		},
		messages: {
			/* edit_surname: "Please enter student's surname",
			edit_firstname: "Please enter student's first name", */
		},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.edit-teacher').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
			$('.edit-teacher').attr('disabled', true);
			let data = {
				'surname': $('#edit_surname').val(),
				'firstname': $('#edit_firstname').val(),
				'othername': $('#edit_othername').val(),
				'title': $('#edit_title').val(),
				'birthdate': $('#edit_birthdate').val(),
				'employed': $('#edit_employed').val(),
				'staffno': $('#edit_staffno').val(),
				'qualification': $('#edit_qualification').val(),
				'course': $('#edit_course').val(),
				'email': $('#edit_email').val(),
				'phone': $('#edit_phone').val(),
				'gender': $("input[name='edit_gender']:checked").val(),
				'religion': $('#edit_religion').val(),
				'level': $('#edit_level').val().join(),
				'nationality': $('#edit_nationality').val(),
				'state': $('#edit_state').val(),
				'lga': $('#edit_lga').val(),
				'session': $('#edit_academic_year').val(),
				'passport': $('#edit_passport').val(),
				'address': $('#edit_address').val(),
			};

			let response = await fetch(base_url + 'edit_teacher/' + $('#edit_teacher_form').attr('action'), {
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
						title: 'Teacher Updated Successfully.'
					})
					$('#edit_teacher_form').trigger("reset");
					$('#modal-teacher-edit').modal('hide');
					$('#teachers_table').DataTable().ajax.reload();
					//populate_stats(data.data);
				} else {
					Toast.fire({
						icon: 'error',
						title: 'Error Updating Teacher. ' + data.message
					})
				}
			});
			$('.edit-teacher').html('<i class="fa fa-save"></i> Update Teacher');
			$('.edit-teacher').attr('disabled', false);
		}
	});

	function urldecode(url) {
		return decodeURIComponent(url.replace(/\+/g, ' '));
	}

	$(document).on("click", ".t-classroom", function (e) {
		$('#ac_teacher_id').val($(this).attr('href'));
	});

	$(document).on("click", ".t-subject", function (e) {
		$('#as_teacher_id').val($(this).attr('href'));
	});

	$('#assign_classroom_form').validate({
		ignore: "",
		errorClass: "err_msg",
		rules: {
			ac_academic_year: "required",
			ac_class: "required",
			ac_arm: "required",
			ac_teacher_id: "required",
			ac_class_role: "required",
		},
		messages: {},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.assign-classroom').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
			$('.assign-classroom').attr('disabled', true);
			let data = {
				'session': $('#ac_academic_year').val(),
				'class': $('#ac_class').val(),
				'arm': $('#ac_arm').val(),
				'teacher_id': $('#ac_teacher_id').val(),
				'class_role': $('#ac_class_role').val(),
			};
			let response = await fetch(base_url + 'add_classteacher', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
				},
				body: JSON.stringify(data)
			}).then(function (response) {
				return response.json();
			}).then(function (data) {
				$('.assign-classroom').html('<i class="fa fa-save"></i> Save Assignment');
				$('.assign-classroom').attr('disabled', false);
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: 'Classroom Assignment Successfully.'
					})
					$('#assign_classroom_form').trigger("reset");
					$('#modal-assign-classroom').modal('hide');
					$('#classteachers_table').DataTable().ajax.reload();
					//populate_stats(data.data);
				} else if (data.status == 'info') {
					Toast.fire({
						icon: 'info',
						title: 'Info: ' + data.message
					})
				} else {
					Toast.fire({
						icon: 'error',
						title: 'Error Assigning Classroom Teacher. ' + data.message
					})
				}
			});
		}
	});

	$('#assign_subject_form').validate({
		ignore: "",
		errorClass: "err_msg",
		rules: {
			as_academic_year: "required",
			as_class: "required",
			as_arm: "required",
			as_teacher_id: "required",
			as_subject_id: "required",
		},
		messages: {},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.assign-subject').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
			$('.assign-subject').attr('disabled', true);
			let data = {
				'session': $('#as_academic_year').val(),
				'class': $('#as_class').val(),
				'arm': $('#as_arm').val(),
				'teacher_id': $('#as_teacher_id').val(),
				'subject_id': $('#as_subject_id').val(),
				'subject_name': $("#as_subject_id option:selected").text(),
			};
			let response = await fetch(base_url + 'add_subjectteacher', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
				},
				body: JSON.stringify(data)
			}).then(function (response) {
				return response.json();
			}).then(function (data) {
			$('.assign-subject').html('<i class="fa fa-save"></i> Save Assignment');
			$('.assign-subject').attr('disabled', false);
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: 'Subject Assignment Successfully.'
					})
					$('#assign_subject_form').trigger("reset");
					$('#modal-assign-subject').modal('hide');
					$('#subjectteachers_table').DataTable().ajax.reload();
				} else if (data.status == 'info') {
					Toast.fire({
						icon: 'info',
						title: 'Info: ' + data.message
					})
				} else {
					Toast.fire({
						icon: 'error',
						title: 'Error Assigning Subject Teacher. ' + data.message
					})
				}
			});
		}
	});

	$(document).on('change', ' #as_class', function () {
		$('.subject').removeClass('d-none');
		getClassSubjects();
	});
	$('#as_class').trigger('change');

	async function getClassSubjects() {
		$.ajax({
			url: encodeURI(base_url + 'get_class_subjects/' + $('#as_class').val()),
			type: 'GET',
			success: function (data) {
				$('#as_subject_id').empty();
				$.each(JSON.parse(data), function (a, b) {
					$('#as_subject_id').append(`<option value="${b.id}">${b.name}</option>`)
				});
			},
			error: function (a) {
				console.log(a)
			}
		});
	}

	$(document).on("click", ".t-edit", function (e) {
		//console.log(urldecode($(this).attr('data')))
		var data = JSON.parse(urldecode($(this).attr('data')));
		//console.log(data.academic_year)
		$('#edit_surname').val(data.surname);
		$('#edit_firstname').val(data.firstname);
		$('#edit_othername').val(data.othername);
		$('#edit_title').val(data.title);
		$('#edit_birthdate').val(data.birthdate);
		$('#edit_employed').val(data.employed);
		$('#edit_staffno').val(data.staffno);
		$('#edit_qualification').val(data.qualification);
		$('#edit_course').val(data.course);
		$('#edit_email').val(data.email);
		$('#edit_phone').val(data.phone);
		$("input[name=edit_gender][value='" + data.gender + "']").prop("checked", true);
		$('#edit_religion').val(data.religion);
		$('#edit_level').val(data.level.split(','));
		$('#edit_nationality').val(data.nationality);
		$('#edit_state').val(data.state);
		$('#edit_lga').val(data.lga);
		$('#edit_address').val(data.address);
		$('#edit_passport').val(data.passport);
		if (data.academic_year !== null) $('#edit_session').val(data.academic_year);
		$('#edit_avatar').attr('src', base_url + 'assets/images/teachers/' + data.passport);


		$('#edit_teacher_form').attr('action', $(this).attr('href'));
		setTimeout(function () {
			var base64 = getBase64Image(document.getElementById("edit_avatar"));
			document.getElementById('edit_passport').value = base64;
		}, 500);
	});

	$(document).on("click", ".t-del", function (e) {
		e.preventDefault();
		var table = $('#teachers_table').DataTable();
		var row = $(this).parents('tr');

		if (confirm('Are you sure you want to delete?')) {
			$.get(base_url + "del_teacher/" + $(this).attr('href'), function (data) {
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
	$(document).on('click', '.t-ed', function (e) {
		e.preventDefault();
		var table = $('#teachers_table').DataTable();
		var row = $(this).parents('tr');
		let action = $(this).attr('data');
		var btn = $(this);
		if (confirm('Are you sure you want to ' + (action == 1 ? 'ENABLE' : 'DISABLE') + ' this teacher?')) {
			$.get(base_url + "ed_teacher/" + $(this).attr('href') + '/?action=' + action, function (data) {
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: (action == 1 ? 'Enable' : 'Disable') + ' successful.'
					});
					if(action == 1){
						btn.removeClass('btn-success');
						btn.addClass('btn-danger');
						btn.attr('data', 0);
						btn.html('<i class="fa fa-user-times"></i> Disable');
					}else{
						btn.removeClass('btn-danger');
						btn.addClass('btn-success');
						btn.attr('data', 1);
						btn.html('<i class="fa fa-user-plus"></i> Enable');
					}
				} else {
					Toast.fire({
						icon: 'error',
						title: 'Error in ' + (action == 1 ? 'Enabling' : 'Disabling') + ' Student: ' + data.message
					})
				}
			});

		}
	});

	$(document).on("click", ".ct-del", function (e) {
		e.preventDefault();
		var table = $('#classteachers_table').DataTable();
		var row = $(this).parents('tr');

		if (confirm('Are you sure you want to delete?')) {
			$.get(base_url + "del_classteacher/" + $(this).attr('href'), function (data) {
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

	$(document).on("click", ".st-del", function (e) {
		e.preventDefault();
		var table = $('#subjectteachers_table').DataTable();
		var row = $(this).parents('tr');

		if (confirm('Are you sure you want to delete?')) {
			$.get(base_url + "del_subjectteacher/" + $(this).attr('href'), function (data) {
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

	$("#teachers_table").DataTable({
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
			url: base_url + "get_teachers",
			type: 'GET'
		},
		"oSearch": { "bSmart": false, "bRegex": true, "sSearch": "" }
	});

	$("#classteachers_table").DataTable({
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
			url: base_url + "get_classteachers",
			type: 'GET'
		},
		"oSearch": { "bSmart": false, "bRegex": true, "sSearch": "" }
	});

	$("#subjectteachers_table").DataTable({
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
			url: base_url + "get_subjectteachers",
			type: 'GET'
		},
		"oSearch": { "bSmart": false, "bRegex": true, "sSearch": "" }
	});

	$("#birthdate, #regdate, #edit_birthdate, #edit_regdate").datepicker({
		format: "yyyy-mm-dd",
	});



	$(document).on("click", "#startCamBtn", function () {
		cam_caller = 'add';
		$('#cam_holder').css('display', '');
		startCam();
	});
	$(document).on("click", "#edit_startCamBtn", function () {
		cam_caller = 'edit';
		$('#cam_holder').css('display', '');
		startCam();
	});

	$(document).on("click", "#preview_snapshot", function () {
		preview_snapshot();
	});
	$(document).on("click", "#cancel_preview", function () {
		cancel_preview();
	});
	$(document).on("click", "#save_photo", function () {
		save_photo();
		$('#cam_holder').hide();
	});

	$("#photo_medium").click(function () {
		$(this).text(function (i, text) {
			if (text === "Take Passport") {
				$('#photo_medium').html("Upload Passport <small>(200x200, 200kb)</small>");
				$('#startCamBtn').removeClass('d-none');
				$('#startUpload').addClass('d-none');
			} else {
				$('#photo_medium').text("Take Passport");
				$('#startUpload').removeClass('d-none');
				$('#startCamBtn').addClass('d-none');
				$('#cam_holder').hide();
			}
		})
	});
	$("#edit_photo_medium").click(function () {
		$(this).text(function (i, text) {
			if (text === "Take Passport") {
				$('#edit_photo_medium').html("Upload Passport <small>(200x200, 200kb)</small>");
				$('#edit_startCamBtn').removeClass('d-none');
				$('#edit_startUpload').addClass('d-none');
			} else {
				$('#edit_photo_medium').text("Take Passport");
				$('#edit_startUpload').removeClass('d-none');
				$('#edit_startCamBtn').addClass('d-none');
				$('#cam_holder').hide();
			}
		})
	});

	function getBase64Image(img) {
		var canvas = document.createElement("canvas");
		canvas.width = img.width;
		canvas.height = img.height;
		var ctx = canvas.getContext("2d");
		ctx.drawImage(img, 10, 10, canvas.width, canvas.height);
		var dataURL = canvas.toDataURL("image/png");
		return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
	}

	$(document).on("change", "#student_image", function () {
		setTimeout(function () {
			var base64 = getBase64Image(document.getElementById("avatar"));
			document.getElementById('passport').value = base64;
		}, 500);
	});

	$(document).on("change", "#edit_student_image", function () {
		setTimeout(function () {
			var base64 = getBase64Image(document.getElementById("edit_avatar"));
			document.getElementById('edit_passport').value = base64;
		}, 500);
	});

	function startCam() {//alert('start');
		try {
			Webcam.set({
				width: 600,
				height: 400,
				// final cropped size
				crop_width: 400,
				crop_height: 400,

				image_format: 'jpeg',
				jpeg_quality: 90,
				// flip horizontal (mirror mode)
				flip_horiz: true
			});
			Webcam.attach('#my_camera');
			Webcam.on('error', function (err) {
				alert('Webcam encountered an error.\nTry using another web browser.');//('error '+err);
			});
		} catch (e) {
			console.log('No Support Found')
		}
	}

	var shutter = new Audio();
	shutter.autoplay = false;
	shutter.src = navigator.userAgent.match(/Firefox/) ? js_url + 'webcam/shutter.ogg' : js_url + 'webcam/shutter.mp3';

	function preview_snapshot() {
		try { shutter.currentTime = 0; } catch (e) { ; }
		shutter.play();
		Webcam.freeze();

		$('#pre_take_buttons').css('display', 'none');
		$('#post_take_buttons').css('display', '');
	}

	function cancel_preview() {
		//console.log('cancel');
		Webcam.unfreeze();
		$('#pre_take_buttons').css('display', '');
		$('#post_take_buttons').css('display', 'none');
	}

	function save_photo() {
		Webcam.snap(function (data_uri) {
			if (cam_caller == 'add')
				$('#avatar').attr("src", data_uri);
			if (cam_caller == 'edit')
				$('#edit_avatar').attr('src', data_uri);
			Webcam.reset();//shutdown camera stop capturing
			//change file upload url
			var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
			document.getElementById(cam_caller == 'add' ? 'passport' : 'edit_passport').value = raw_image_data;
		});
	}

});
