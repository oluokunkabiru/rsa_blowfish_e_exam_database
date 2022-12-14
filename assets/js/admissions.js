$(document).ready(function () {
// alert("kjhkhk")
	$("#year").datepicker({
		format: "yyyy",
		viewMode: "years",
		minViewMode: "years"
	});

	$("#dob").datepicker({
		autoclose: true,
		format: "yyyy-mm-dd",
	});

	$("#admission_form").validate({
		ignore: "",
		focusInvalid: false,
		invalidHandler: function (form, validator) {
			if (!validator.numberOfInvalids())
				return;
			$('html, body').animate({
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
			// health_issues: "required",
			elamin_attraction: "required",
			passport: "required",
			//transaction_reference: "required"
		},
		messages: {
			term: "Please select a term",
		},
		submitHandler(form) {
			/*swal({
				title: "Congratulations!",
				text: "Your registration was successful!",
				type: "success",
				icon: "success"
			});
			form.submit();*/
			payWithPaystack(form);
		}
	});

	function payWithPaystack(form) {
		var handler = PaystackPop.setup({
			key: PK,
			email: $('#email').val(),
			amount: parseInt($('#registration_fee').val()) * 100,
			currency: "NGN",
			ref: acronym + Math.floor((Math.random() * 1000000000) + 1) + 'A', // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
			firstname: $('#surname').val(),
			lastname: $('#firstname').val(),
			// label: "Optional string that replaces customer email"
			metadata: {
				custom_fields: [
					{
						display_name: "Mobile Number",
						variable_name: "mobile_number",
						value: $('#phone').val()
					}
				]
			},
			callback: function (response) {
				$('#transaction_reference').val(response.reference);
				if ($('#transaction_reference').val() != '') {
					form.submit();
				}
			},
			onClose: function () {
				//alert('window closed');
			}
		});
		handler.openIframe();
	}


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
				$('#photo_medium').html("Upload Passport <small>(200x200, Max 200kb)</small>");
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

	$(document).on("change", "#applied_class", function () {
		let cls = $('#applied_class').val();
		// alert(cls)
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
		console.log('cancel');
		Webcam.unfreeze();
		$('#pre_take_buttons').css('display', '');
		$('#post_take_buttons').css('display', 'none');
	}

	function save_photo() {
		Webcam.snap(function (data_uri) {
			$('#avatar').attr("src", data_uri);
			Webcam.reset();//shutdown camera stop capturing
			//change file upload url
			var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
			document.getElementById('passport').value = raw_image_data;
		});
	}

});
    /*$(document).on('click', '.success', function () {
	swal({
		title: "Congratulations!",
		text: "Your registration was successful!",
		type: "success",
		icon: "success"
	});//.then(function(){
		//window.location = "/login";
	//});
});*/
