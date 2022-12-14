$(document).ready(function () {
	$.ajax({
		method: "GET",
		url: base_url + 'display_gallery',
		dataType: "json",
		success:function(data){
			var gallery ="";
			$.each(data, function (key, value) {
				gallery += value;
				// console.log(value);
			})
			$('#galleriesimage').html(gallery)
		}
	
	  })
	//   .done(
	// 	//   $('#galleriesimage').html(json)
	// 	//   console.log(data)
	// 	// json => console.log(json),
	//   );
	$("#deleteme").click(function(){
		var img = $(this).attr('img');
		// alert(img)
		$.ajax({
			method: "POST",
			url: base_url + 'gallery_delete/'+img,
			dataType: "json",
			success:function(data){
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: data.message
					})
					$('#delete-gallery').modal("hide");
					$.ajax({
						method: "GET",
						url: base_url + 'display_gallery',
						dataType: "json",
						success:function(data){
							var gallery ="";
							$.each(data, function (key, value) {
								gallery += value;
								// console.log(value);
							})
							$('#galleriesimage').html(gallery)
						}
					
					  })

				}else{
					Toast.fire({
						icon: 'danger',
						title: data.message
					})
				}
			}
		
		  })

	})
	$('#delete-gallery').on('show.bs.modal', function(e) {
		var img = $(e.relatedTarget).attr('deletesrc');
		var id = $(e.relatedTarget).attr('id');
		$("#delete-image").attr("src", img);
		$("#deleteme").attr("img", id);

	})

// json => console.log(json)
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 4000
	});

	$("#gallery_form").validate({
		ignore: "",
		focusInvalid: false,
		invalidHandler: function (form, validator) {
			if (!validator.numberOfInvalids())
				return;
			$('#modal-gallery').animate({
				scrollTop: $(validator.errorList[0].element).offset().top
			}, 2000);

		},
		errorClass: "err_msg",
		rules: {
			image: "required",
			category: "required"
		},
		submitHandler: async function (form, event) {
			//initialize paystack payment
			event.preventDefault();

			// payWithPaystack()
			let data ={
				'category' : $("#category").val(),
				'image':$("#images").val(),
			}
			// console.log(data)
			// form.submit(data);
			let response = await fetch(base_url + 'add_gallery',
			{
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
				},
				body: JSON.stringify(data)
			}).then(function (response) {
				//swal.close();
				// swal.showConfirmButton = true;
				// console.log(response)
				return response.json();
			}).then(function (data) {
				// console.log(data)
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: data.message
					});
					  $('#modal-gallery').modal("hide");

					$.ajax({
						method: "GET",
						url: base_url + 'display_gallery',
						dataType: "json",
						success:function(data){
							var gallery ="";
							$.each(data, function (key, value) {
								gallery += value;
								// console.log(value);
							})
							$('#galleriesimage').html(gallery)
						}
					
					  })
					//   ('#modal-gallery').modal("hide");
					//   alert('success')
					// $('#galleriesimage').load(location.href + " #galleriesimage")

					
				}else{
					Toast.fire({
						icon: 'error',
						title: data.message
					});
				}
			});

		}

	});

	function urldecode(url) {
		return decodeURIComponent(url.replace(/\+/g, ' '));
	}


	/*
	CAMERA
	*/
	
	function getBase64Image(img) {
		var canvas = document.createElement("canvas");
		canvas.width = img.width;
		canvas.height = img.height;
		var ctx = canvas.getContext("2d");
		ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
		var dataURL = canvas.toDataURL("image/png");
		return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
	}

	$(document).on("change", "#image", function () {
		// alert(23)
		setTimeout(function () {
			var base64 = getBase64Image(document.getElementById("avatar"));
			document.getElementById('images').value = base64;
		}, 500);
	});




	

});
