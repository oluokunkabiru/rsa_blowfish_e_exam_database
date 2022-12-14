$(document).ready(function () {

	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 4000
	});

	$("#year").datepicker({
		format: "yyyy",
		viewMode: "years",
		minViewMode: "years"
	});

	$("#dob").datepicker({
		format: "yyyy-mm-dd",
	});

	$("#admissions_table").DataTable({
		dom: 'Bfrtip',
		buttons: [
			{
				extend: 'print',
				// extend: "stripHtml",
				orientation: 'landscape',
				pageSize: 'LEGAL',
				exportOptions:{
					stripHtml:false,
					columns: 'th:not(:last-child)'
				},

			},
			// 'print',
			'copy', 
			'csv', 
			'colvis'
		],
		"ordering": false,
		"responsive": true,
		"autoWidth": false,
		"ajax": {
			url: base_url + "get_admissions",
			type: 'GET'
		},
		"oSearch": { "bSmart": false, "bRegex": true, "sSearch": "" }
	});

	$("#admission_form").validate({
		ignore: "",
		focusInvalid: false,
		invalidHandler: function (form, validator) {
			if (!validator.numberOfInvalids())
				return;
			$('#modal-admit').animate({
				scrollTop: $(validator.errorList[0].element).offset().top
			}, 2000);

		},
		errorClass: "err_msg",
		rules: {
			term: "required",
			year: {
				required: true,
				minlength: 4,
				maxlength: 4,
			},
			present_class: "required",
			applied_class: "required",
			surname: "required",
			firstname: "required",
			gender: "required",
			home_address: "required",
			phone: "required",
			email: {
				required: true,
				email: true,
			},
			dob: "required",
			religion: "required",
			nationality: "required",
			state: "required",
			lga: "required",
			fathers_surname: "required",
			fathers_firstname: "required",
			fathers_occupation: "required",
			fathers_office_address: "required",
			fathers_phone: "required",
			fathers_email: "required",
			mothers_surname: "required",
			mothers_firstname: "required",
			mothers_occupation: "required",
			mothers_office_address: "required",
			mothers_phone: "required",
			mothers_email: "required",
			// guardians_surname: "required",
			// guardians_firstname: "required",
			// guardians_address: "required",
			// guardians_phone: "required",
			// guardians_email: "required",
			// former_school_name: "required",
			// former_school_address: "required",
			health_issues: "required",
			elamin_attraction: "required",
			//passport: "required",
			//transaction_reference: "required"
		},
		messages: {
			term: "Please select a term",
		},
		submitHandler(form) {
			let tref = acronym + Math.floor((Math.random() * 1000000000) + 1) + 'BA';
			$('#transaction_reference').val(tref);
			form.submit();
		}
	});

	function urldecode(url) {
		return decodeURIComponent(url.replace(/\+/g, ' '));
	}

	function populateForm(frm, data) {
		$.each(data, function (key, value) {
			$('[name=' + key + ']', frm).val(value);
			$('[name=' + key + ']', frm).prop('disabled', true);
		});
		$('#review_avatar').attr('src', base_url + 'assets/images/passports/' + data.passport);
		$("#review_passport").val(data.passport);
		$('.adm-accept').prop('disabled', false);
		$('.adm-reject').prop('disabled', false);
		$('.adm-pending').prop('disabled', false);
		approved = false;
		if (data.status == 'accepted') {
			$('.adm-accept').prop('disabled', true);
		}else if(data.status == 'rejected'){
			$('.adm-reject').prop('disabled', true);
		}else{
			$('.adm-pending').prop('disabled', true);
		}
	}

	var data = [];
	var approved = false;

	$(document).on('click', '.adm-review', function (e) {
		data = JSON.parse(urldecode($(this).attr('data')));
		//console.table(data);
		//$('#admission_review_form :input').prop('disabled', true);
		populateForm('#admission_review_form', data);
	});

	$(document).on('click', '.adm-reject, .adm-accept, .adm-pending', async function (e) {
		e.preventDefault();
		let frm = '#admission_review_form';
		var confirmText = ($(this).hasClass('adm-accept') ? 'accept' : ($(this).hasClass('adm-reject') ? 'reject' : 'make pending'));
		 Swal.fire({
			title: 'Are you sure you want to ' + ($(this).hasClass('adm-accept') ? 'accept' : ($(this).hasClass('adm-reject') ? 'reject' : 'make pending')) + ' this application',
			icon: 'question',
			showDenyButton: true,
			showCancelButton: true,
			confirmButtonText: confirmText.toUpperCase(),
			denyButtonText: 'Cancel',
		  }).then(async (result) => {
			/* Read more about isConfirmed, isDenied below */
			
			if (result.value==true) {
				let action = $(this).hasClass('adm-accept') ? 'accept' : ($(this).hasClass('adm-reject') ? 'reject' : 'pending');
				// console.log(action);
				// console.log(approved);

			// if (approved) {
				if (action == 'accept') {
					$.each(data, function (key, value) {
						//$('[name=' + key + ']', frm).val(value);
						data[key] = $('[name=' + key + ']', frm).val();
					});
					data['arm'] = $("#arm").val();
					data['passport'] = $("#review_passport").val();//getBase64Image(document.getElementById("review_avatar"));
					data['house'] = $("#house").val();
					data['status'] = (action == 'accept') ? 'accepted' : (action == 'reject' ? 'rejected' : 'pending');
					data['reason'] = $('#reason').val();
					data['add_pg'] = $('#add_pg').val() ?? 0;
					delete data.creator;
				} else {
					let dd = data;
					data = {
						'status': (action == 'accept') ? 'accepted' : (action == 'reject' ? 'rejected' : 'pending'),
						'reason': $('#reason').val(),
						'learner_id': dd.learner_id,
						'pg_id': dd.pg_id,
						'add_pg': $('#add_pg').val() ?? 0,
					};
				}

				let response = await fetch(base_url + 'edit_admission/' + $('#admission_review_form input[name="id"]').val(), {
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
							title: 'Admission ' + (action == 'accept' ? 'Accepted' : 'Rejected') + ' Successfully.'
						})
						$('#admission_review_form').trigger("reset");
						$('#modal-review').modal('hide');
						$('#admissions_table').DataTable().ajax.reload();
					} else {
						Toast.fire({
							icon: 'error',
							title: 'Error Updating Admission. ' + data.message
						})
					}
				});
				$(this).closest('div').find(':input').prop('disabled', false);
			} else {

				if (action == 'accept') {
					$.each(data, function (key, value) {
						$('[name=' + key + ']', frm).val(value);
						$('[name=' + key + ']', frm).prop('disabled', false);
					});
					$('.adm-accept').prop('disabled', false);
					$('.adm-reject').prop('disabled', true);
					$('.adm-pending').prop('disabled', true);
					$('#modal-admit').animate({
						scrollTop: $('#term').offset().top
					}, 2000);
				} else {
					$('[name=' + 'reason' + ']', frm).prop('disabled', false);
					if (action == 'reject') {
						$('.adm-reject').prop('disabled', false);
						$('.adm-pending').prop('disabled', true);
					} else {
						$('.adm-reject').prop('disabled', true);
						$('.adm-pending').prop('disabled', false);
					}
					$('.adm-accept').prop('disabled', true);
				// }
				// approved = true;
			}

			} 
		  })

	// 	if (confirm('Are you sure you want to ' + ($(this).hasClass('adm-accept') ? 'accept' : ($(this).hasClass('adm-reject') ? 'reject' : 'make pending')) + ' this application')) {
	// 		//$(this).closest('div').find(':input').prop('disabled', true);//disable all action buttons
			
	// 		let action = $(this).hasClass('adm-accept') ? 'accept' : ($(this).hasClass('adm-reject') ? 'reject' : 'pending');

	// 		if (approved) {
	// 			if (action == 'accept') {
	// 				$.each(data, function (key, value) {
	// 					//$('[name=' + key + ']', frm).val(value);
	// 					data[key] = $('[name=' + key + ']', frm).val();
	// 				});
	// 				data['arm'] = $("#arm").val();
	// 				data['passport'] = $("#review_passport").val();//getBase64Image(document.getElementById("review_avatar"));
	// 				data['house'] = $("#house").val();
	// 				data['status'] = (action == 'accept') ? 'accepted' : (action == 'reject' ? 'rejected' : 'pending');
	// 				data['reason'] = $('#reason').val();
	// 				data['add_pg'] = $('#add_pg').val() ?? 0;
	// 				delete data.creator;
	// 			} else {
	// 				let dd = data;
	// 				data = {
	// 					'status': (action == 'accept') ? 'accepted' : (action == 'reject' ? 'rejected' : 'pending'),
	// 					'reason': $('#reason').val(),
	// 					'learner_id': dd.learner_id,
	// 					'pg_id': dd.pg_id,
	// 					'add_pg': $('#add_pg').val() ?? 0,
	// 				};
	// 			}

	// 			let response = await fetch(base_url + 'edit_admission/' + $('#admission_review_form input[name="id"]').val(), {
	// 				method: 'POST',
	// 				headers: {
	// 					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
	// 				},
	// 				body: JSON.stringify(data)
	// 			}).then(function (response) {
	// 				return response.json();
	// 			}).then(function (data) {
	// 				if (data.status == 'success') {
	// 					Toast.fire({
	// 						icon: 'success',
	// 						title: 'Admission ' + (action == 'accept' ? 'Accepted' : 'Rejected') + ' Successfully.'
	// 					})
	// 					$('#admission_review_form').trigger("reset");
	// 					$('#modal-review').modal('hide');
	// 					$('#admissions_table').DataTable().ajax.reload();
	// 				} else {
	// 					Toast.fire({
	// 						icon: 'error',
	// 						title: 'Error Updating Admission. ' + data.message
	// 					})
	// 				}
	// 			});
	// 			$(this).closest('div').find(':input').prop('disabled', false);
	// 		} else {

	// 			if (action == 'accept') {
	// 				$.each(data, function (key, value) {
	// 					$('[name=' + key + ']', frm).val(value);
	// 					$('[name=' + key + ']', frm).prop('disabled', false);
	// 				});
	// 				$('.adm-accept').prop('disabled', false);
	// 				$('.adm-reject').prop('disabled', true);
	// 				$('.adm-pending').prop('disabled', true);
	// 				$('#modal-admit').animate({
	// 					scrollTop: $('#term').offset().top
	// 				}, 2000);
	// 			} else {
	// 				$('[name=' + 'reason' + ']', frm).prop('disabled', false);
	// 				if (action == 'reject') {
	// 					$('.adm-reject').prop('disabled', false);
	// 					$('.adm-pending').prop('disabled', true);
	// 				} else {
	// 					$('.adm-reject').prop('disabled', true);
	// 					$('.adm-pending').prop('disabled', false);
	// 				}
	// 				$('.adm-accept').prop('disabled', true);
	// 			}
	// 			approved = true;
	// 		}

	// 	// }
	// } 
// })
	});

	$(document).on("click", ".adm-del", function (e) {
		e.preventDefault();
		
		// if (confirm('Are you sure you want to delete?')) {
			Swal.fire({
				title: 'Are you Sure you want to delete admission? \nYou are not allow to delete admission',
				icon: 'question',
				showDenyButton: true,
				showCancelButton: true,
				confirmButtonText: 'Delete',
				denyButtonText: `Don't Delete`,
			  }).then((result) => {
				/* Read more about isConfirmed, isDenied below */
				console.log(result);
				console.log(result.value);
				if (result.value==true) {
					// console.log(result)
					// Swal.fire('Saved!', '', 'success')
					// alert("confirm")
					// $.get(base_url + "del_admission/" + $(this).attr('href'), function (data) {
					// 	if (data.status == 'success') {
					// 		Toast.fire({
					// 			icon: 'success',
					// 			title: 'Delete successful.'
					// 		});
					// 		$('#admissions_table').DataTable().ajax.reload();
					// 	}
					// });
				//   Swal.fire('Saved!', '', 'success')
				} 
			  })

			// $.get(base_url + "del_admission/" + $(this).attr('href'), function (data) {
			// 	if (data.status == 'success') {
			// 		Toast.fire({
			// 			icon: 'success',
			// 			title: 'Delete successful.'
			// 		});
			// 		$('#admissions_table').DataTable().ajax.reload();
			// 	}
			// });
		// }
	});

	/*
	CAMERA
	*/
	$(document).on("click", "#startCamBtn", function () {
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
				$('#photo_medium').html("Upload Passport <small>(200x200, Max 200kb)");
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

	function getBase64Image(img) {
		var canvas = document.createElement("canvas");
		canvas.width = img.width;
		canvas.height = img.height;
		var ctx = canvas.getContext("2d");
		ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
		var dataURL = canvas.toDataURL("image/png");
		return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
	}

	$(document).on("change", "#student_image", function () {
		setTimeout(function () {
			var base64 = getBase64Image(document.getElementById("avatar"));
			document.getElementById('passport').value = base64;
		}, 500);
	});

	$(document).on("change", "#applied_class", function () {
		let cls = $('#applied_class').val();
		// console.log(cls)
		if (cls == 'Pre-Nursery' || cls.startsWith('Nursery') ) {
			$('#registration_fee').val(parseInt(nur));
			// alert(nur)
		} else if (cls.startsWith('Primary')) {
			$('#registration_fee').val(parseInt(pry));
		} else if (cls.startsWith('JSS')) {
			$('#registration_fee').val(parseInt(jss));
		} else {
			$('#registration_fee').val(parseInt(sss));
		}
	});
	$('#applied_class').trigger('change');


	function startCam() {
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
				alert('Webcam encountered an error.\nTry using another web browser.');
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
		Webcam.unfreeze();
		$('#pre_take_buttons').css('display', '');
		$('#post_take_buttons').css('display', 'none');
	}

	function save_photo() {
		Webcam.snap(function (data_uri) {
			$('#avatar').attr("src", data_uri);
			Webcam.reset();
			//change file upload url
			var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
			document.getElementById('passport').value = raw_image_data;
		});
	}

});
