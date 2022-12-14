$(document).ready(function () {
// $("select").select2();
	let cam_caller = null;

	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 4000
	});

	function populate_stats(ldata) {
		if (ld_data != null && ld_data != "") {
			barChart.config.data.labels = ldata.learner_distribution.labels;
			barChart.config.data.datasets[0].data = ldata.learner_distribution.males;
			barChart.config.data.datasets[1].data = ldata.learner_distribution.females;
			barChart.update();

			$('#nl_diff, #rl_diff, #tl_diff, #ttl_diff').removeClass('text-success text-danger text-warning');
			$('#nl_diff, #rl_diff, #tl_diff, #ttl_diff').each(function () {
				$(this).addClass(ldata[$(this).attr('id')] > 0 ? 'text-success' : (ldata[$(this).attr('id')] < 0 ? 'text-danger' : 'text-warning'))
				$(this).html('<i class="fas ' + (ldata[$(this).attr('id')] > 0 ? 'fa-caret-up' : (ldata[$(this).attr('id')] < 0 ? 'fa-caret-down' : 'fa-caret-left')) + '"></i> ' + ldata[$(this).attr('id')] + '%');
			})

			$('#new_learners_this_year').html(ldata.new_learners_this_year);
			$('#returning_learners_this_year').html(ldata.returning_learners_this_year);
			$('#transfered_learners_this_year').html(ldata.transfered_learners_this_year);
			$('#total_learners_this_year').html(ldata.total_learners_this_year);
		}
	}

	var regChecker = new RegExp("\\b(" + acronym + "\/)(\\d{4}\/)(\\d{4})\\b");
	jQuery.validator.addMethod("validRegno", function (value, element) {
		if (regChecker.test(value)) return true;
		else return false;
	}, "Must match " + acronym + "/2020/0042");

	$("#add_learner_form").validate({
		ignore: "",
		focusInvalid: false,
		invalidHandler: function (form, validator) {
			if (!validator.numberOfInvalids())
				return;
			$('#modal-learner').animate({
				scrollTop: $(validator.errorList[0].element).offset().top
			}, 2000);

		},
		errorClass: "err_msg",
		rules: {
			surname: "required",
			firstname: "required",
			//othername: "required",
			regno: {
				required: true,
				validRegno: true,
			},
			birthdate: "required",
			regdate: "required",
			class: "required",
			arm: "required",
			classroom: "required",
			gender: "required",
			genotype: "required",
			blood_group: "required",
			house: "required",
			religion: "required",
			country: "required",
			state: "required",
			lga: "required",
			passport: "required",
		},
		messages: {
			/* email: {
				required: "Please provide an email",
				email: "Please enter a valid email",
			},
			surname: "Please enter student's surname",
			firstname: "Please enter student's first name", */
		},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.add-learner').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
			$('.add-learner').attr('disabled', true);
			let data = {
				'surname': $('#surname').val(),
				'firstname': $('#firstname').val(),
				'othername': $('#othername').val(),
				'regno': $('#regno').val(),
				'birthdate': $('#birthdate').val(),
				'regdate': $('#regdate').val(),
				'class': $('#class').val(),
				'arm': $('#arm').val(),
				'classroom': $('#classroom').val(),
				'gender': $("input[name='gender']:checked").val(),
				'genotype': $('#genotype').val(),
				'blood_group': $('#blood_group').val(),
				'house': $('#house').val(),
				'religion': $('#religion').val(),
				'nationality': $('#nationality').val(),
				'state': $('#state').val(),
				'lga': $('#lga').val(),
				'session': $('#academic_year').val(),
				'passport': $('#passport').val(),
			};

			let response = await fetch(base_url + 'add_learner', {
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
						title: 'Learner Added Successfully.'
					})
					$('#add_learner_form').trigger("reset");
					$('#modal-learner').modal('hide');
					$('#learners_table').DataTable().ajax.reload();
					populate_stats(data.data);
				} else if (data.status == 'info') {
					Toast.fire({
						icon: 'info',
						title: 'Info: ' + data.message
					})
				} else {
					Toast.fire({
						icon: 'error',
						title: 'Error Adding Learner. ' + data.message
					})
				}
			});
			$('.add-learner').html('<i class="fa fa-save"></i> Add Learner');
			$('.add-learner').attr('disabled', false);
		}
	});

	$("#edit_learner_form").validate({
		ignore: "",
		focusInvalid: false,
		invalidHandler: function (form, validator) {
			if (!validator.numberOfInvalids())
				return;
			$('#modal-learner').animate({
				scrollTop: $(validator.errorList[0].element).offset().top
			}, 2000);

		},
		errorClass: "err_msg",
		rules: {
			edit_surname: "required",
			edit_firstname: "required",
			//edit_othername: "required",
			edit_regno: {
				required: true,
				validRegno: true,
			},
			edit_birthdate: "required",
			edit_regdate: "required",
			edit_class: "required",
			edit_arm: "required",
			edit_classroom: "required",
			edit_gender: "required",
			edit_genotype: "required",
			edit_blood_group: "required",
			edit_house: "required",
			edit_religion: "required",
			edit_country: "required",
			edit_state: "required",
			edit_lga: "required",
			edit_passport: "required",
		},
		messages: {
			/* email: {
				required: "Please provide an email",
				email: "Please enter a valid email",
			},
			surname: "Please enter student's surname",
			firstname: "Please enter student's first name", */
		},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.edit-learner').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
			$('.edit-learner').attr('disabled', true);
			let data = {
				'surname': $('#edit_surname').val(),
				'firstname': $('#edit_firstname').val(),
				'othername': $('#edit_othername').val(),
				'regno': $('#edit_regno').val(),
				'birthdate': $('#edit_birthdate').val(),
				'regdate': $('#edit_regdate').val(),
				'class': $('#edit_class').val(),
				'arm': $('#edit_arm').val(),
				'classroom': $('#edit_classroom').val(),
				'gender': $("input[name='edit_gender']:checked").val(),
				'house': $('#edit_house').val(),
				'genotype': $('#edit_genotype').val(),
				'blood_group': $('#edit_blood_group').val(),
				'religion': $('#edit_religion').val(),
				'nationality': $('#edit_nationality').val(),
				'state': $('#edit_state').val(),
				'lga': $('#edit_lga').val(),
				'session': $('#edit_academic_year').val(),
				'passport': $('#edit_passport').val(),
			};

			let response = await fetch(base_url + 'edit_learner/' + $('#edit_learner_form').attr('action'), {
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
						title: 'Learner Updated Successfully.'
					})
					$('#edit_learner_form').trigger("reset");
					$('#modal-learner-edit').modal('hide');
					$('#learners_table').DataTable().ajax.reload();
					populate_stats(data.data);
				} else {
					Toast.fire({
						icon: 'error',
						title: 'Error Updating Learner. ' + data.message
					})
				}
			});
			$('.edit-learner').html('<i class="fa fa-save"></i> Update Learner');
			$('.edit-learner').attr('disabled', false);
		}
	});

	function urldecode(url) {
		return decodeURIComponent(url.replace(/\+/g, ' '));
	}

	$(document).on("click", ".l-edit", function (e) {
		//console.log(urldecode($(this).attr('data')))
		var data = JSON.parse(urldecode($(this).attr('data')));
		//console.log(data.academic_year)
		$('#edit_surname').val(data.surname);
		$('#edit_firstname').val(data.firstname);
		$('#edit_othername').val(data.othername);
		$('#edit_regno').val(data.regno);
		$('#edit_birthdate').val(data.birthdate);
		$('#edit_regdate').val(data.regdate);
		$('#edit_class').val(data.class);
		$('#edit_arm').val(data.arm);
		$('#edit_classroom').val(data.classroom);
		//$('#edit_gender').val(data.gender);
		$("input[name=edit_gender][value='" + data.gender + "']").prop("checked", true);
		$('#edit_genotype').val(data.genotype);
		$('#edit_blood_group').val(data.blood_group);
		$('#edit_house').val(data.house);
		$('#edit_religion').val(data.religion);
		$('#edit_nationality').val(data.nationality);
		$('#edit_state').val(data.state);
		$('#edit_lga').val(data.lga);
		$('#edit_passport').val(data.passport);
		if (data.academic_year !== null) $('#edit_session').val(data.academic_year);
		$('#edit_avatar').attr('src', base_url + 'assets/images/passports/' + data.passport);


		$('#edit_learner_form').attr('action', $(this).attr('href'));
		setTimeout(function () {
			var base64 = getBase64Image(document.getElementById("edit_avatar"));
			// alert(base64);
			document.getElementById('edit_passport').value = base64;
		}, 500);
	});

	$(document).on("click", ".l-del", function (e) {
		e.preventDefault();
		var table = $('#learners_table').DataTable();
		var row = $(this).parents('tr');

		if (confirm('Are you sure you want to delete?')) {
			$.get(base_url + "del_learner/" + $(this).attr('href'), function (data) {
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

	$(document).on('click', '.l-ed', function (e) {
		e.preventDefault();
		var table = $('#learners_table').DataTable();
		var row = $(this).parents('tr');
		let action = $(this).attr('data');
		var btn = $(this);
		var confirmText = (action == 1 ? 'ENABLE' : 'DISABLE');
		Swal.fire({
			title: 'Are you sure you want to ' +  confirmText + ' this learner?',
			icon: 'question',
			showDenyButton: true,
			showCancelButton: true,
			confirmButtonText: confirmText.toUpperCase(),
			denyButtonText: 'Cancel',
		  }).then(async (result) => {
			$.get(base_url + "ed_learner/" + $(this).attr('href') + '/?action=' + action, function (data) {
				//data = JSON.parse(data);
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: (action == 1 ? 'Enable' : 'Disable') + ' successful.'
					});
					//$('#learners_table').DataTable().ajax.reload();
					/* if ($(row).hasClass('child')) {
						//table.row($(row).prev('tr')).remove().draw();
						
					} else {
						//table.row($(this).parents('tr')).remove().draw();
					} */
					if (action == 1) {
						btn.removeClass('btn-success');
						btn.addClass('btn-danger');
						btn.attr('data', 0);
						btn.html('<i class="fa fa-user-times"></i> Disable');
					} else {
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
	})
		// if (confirm('Are you sure you want to ' + (action == 1 ? 'ENABLE' : 'DISABLE') + ' this learner?')) {
		// 	$.get(base_url + "ed_learner/" + $(this).attr('href') + '/?action=' + action, function (data) {
		// 		//data = JSON.parse(data);
		// 		if (data.status == 'success') {
		// 			Toast.fire({
		// 				icon: 'success',
		// 				title: (action == 1 ? 'Enable' : 'Disable') + ' successful.'
		// 			});
		// 			//$('#learners_table').DataTable().ajax.reload();
		// 			/* if ($(row).hasClass('child')) {
		// 				//table.row($(row).prev('tr')).remove().draw();
						
		// 			} else {
		// 				//table.row($(this).parents('tr')).remove().draw();
		// 			} */
		// 			if (action == 1) {
		// 				btn.removeClass('btn-success');
		// 				btn.addClass('btn-danger');
		// 				btn.attr('data', 0);
		// 				btn.html('<i class="fa fa-user-times"></i> Disable');
		// 			} else {
		// 				btn.removeClass('btn-danger');
		// 				btn.addClass('btn-success');
		// 				btn.attr('data', 1);
		// 				btn.html('<i class="fa fa-user-plus"></i> Enable');
		// 			}
		// 		} else {
		// 			Toast.fire({
		// 				icon: 'error',
		// 				title: 'Error in ' + (action == 1 ? 'Enabling' : 'Disabling') + ' Student: ' + data.message
		// 			})
		// 		}
		// 	});

		// }
	});

	$("#learners_table").DataTable({
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
			url: base_url + "get_learners",
			type: 'GET'
		},
		"oSearch": { "bSmart": false, "bRegex": true, "sSearch": "" }
	});



	// Disabled Student
	$("#disabled_learners_table").DataTable({
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
			url: base_url + "get_learners_disabled",
			type: 'GET'
		},
		"oSearch": { "bSmart": false, "bRegex": true, "sSearch": "" }
	});

// Alumni
	$("#alumni_learners_table").DataTable({
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
			url: base_url + "get_learners_alumni",
			type: 'GET'
		},
		"oSearch": { "bSmart": false, "bRegex": true, "sSearch": "" }
	});

	$("#birthdate, #regdate, #edit_birthdate, #edit_regdate").datepicker({
		format: "yyyy-mm-dd",
	});


	//-------------
	//- BAR CHART -
	//-------------
	//console.log(labels)
	if (ld_data != null && ld_data != "") {
		var areaChartData = {
			labels: ld_data.labels,//['PRI1', 'JSS1', 'JSS2', 'JSS3', 'SS1', 'SS2', 'SS3'],//lb.split(","),
			datasets: [{
				label: 'BOYS',
				backgroundColor: 'rgba(60,141,188,0.9)',
				borderColor: 'rgba(60,141,188,0.8)',
				pointRadius: false,
				pointColor: '#3b8bba',
				pointStrokeColor: 'rgba(60,141,188,1)',
				pointHighlightFill: '#fff',
				pointHighlightStroke: 'rgba(60,141,188,1)',
				data: ld_data.males//[65, 59, 80, 81, 56, 55, 40].reverse()//md.split(",")
			},
			{
				label: 'GIRLS',
				backgroundColor: 'rgba(210, 214, 222, 1)',
				borderColor: 'rgba(210, 214, 222, 1)',
				pointRadius: false,
				pointColor: 'rgba(210, 214, 222, 1)',
				pointStrokeColor: '#c1c7d1',
				pointHighlightFill: '#fff',
				pointHighlightStroke: 'rgba(220,220,220,1)',
				data: ld_data.females//[65, 59, 80, 81, 56, 55, 40]
			},
			]
		}

		var barChartCanvas = $('#barChart').get(0).getContext('2d')
		var barChartData = jQuery.extend(true, {}, areaChartData)
		var temp0 = areaChartData.datasets[0]
		var temp1 = areaChartData.datasets[1]
		barChartData.datasets[0] = temp0
		barChartData.datasets[1] = temp1

		var barChartOptions = {
			responsive: true,
			maintainAspectRatio: false,
			datasetFill: false,
			scalesShowLabels: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						/* callback: function (value, index, values) {
							return '₦' + Number(value).toLocaleString();
						} */
					}
				}],
			},
			tooltips: {
				callbacks: {
					label: function (tooltipItem, data) {
						var label = data.datasets[tooltipItem.datasetIndex].label || '';

						if (label) {
							label += ': ';//': ₦ ';
						}
						label += tooltipItem.yLabel;//(Math.round(tooltipItem.yLabel * 100) / 100).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
						return label;
					}
				}
			}
		}

		var barChart = new Chart(barChartCanvas, {
			type: 'bar',
			data: barChartData,
			options: barChartOptions
		})

	}

	$("#channel").bootstrapSwitch({
		onSwitchChange: function (e) {
			//console.log(e.target.value+' '+$(this).prop('checked'));
			if ($(this).prop('checked') == false) {
				if ($('#transaction_reference').val() != '') {
					localStorage.setItem('ts', $('#transaction_reference').val());
				}
				$('#transaction_reference').val(acronym + Math.floor((Math.random() * 100000000) + 1) + 'BF');
			} else {
				if (localStorage.getItem('ts') != '') {
					$('#transaction_reference').val(localStorage.getItem('ts'));
				}
			}
		}
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
		ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
		var dataURL = canvas.toDataURL("image/png");
		return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
	}

	$(document).on("change", "#student_image", function () {
		setTimeout(function () {
			var base64 = getBase64Image(document.getElementById("avatar"));
			// alert(base64);
			document.getElementById('passport').value = base64;
		}, 500);
	});

	$(document).on("change", "#edit_student_image", function () {
		setTimeout(function () {
			var base64 = getBase64Image(document.getElementById("edit_avatar"));
			// alert(base64)
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


	$(document).on('change blur keyup', '#regdate', function () {
		let rn = $(this).val().split('-')[0];
		let cr = $('#regno').val().split('/');
		if (rn != '' && rn != null)
			$('#regno').val(cr[0] + '/' + rn + '/' + (cr[2] ?? ''));
	});

	$(document).on('change blur keyup', '#edit_regdate', function () {
		let rn = $(this).val().split('-')[0];
		let cr = $('#edit_regno').val().split('/');
		if (rn != '' && rn != null)
			$('#edit_regno').val(cr[0] + '/' + rn + '/' + (cr[2] ?? ''));
	});

});
