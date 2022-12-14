$(document).ready(function () {
	$("#birthdate, #employed").datepicker({
		format: "yyyy-mm-dd",
	});


	$(document).on("click", "#startCamBtn", function () {
		cam_caller = 'add';
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
			Webcam.reset();
			var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
			document.getElementById(cam_caller == 'add' ? 'passport' : 'edit_passport').value = raw_image_data;
		});
	}

});
