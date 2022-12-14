$(document).ready(function () {

	var discount = 0;
	var discount_amount;
	var charge = parseInt(charges);	
	var learners = dcs;
	
	$('#learner').autocomplete({
		minlength: 4,
		source: function (request, response) {
			$.ajax({
				url: base_url + 'search_learners?term='+request.term,
				method: "post",
				dataType: 'json',
				contentType: "application/json;charset=utf-8",
				/* headers: {
					"Authorization": "Bearer " + '<?= ENGINE_TOKEN ?>'
				}, */
				/* data: JSON.stringify({
					term: request.term
				}), */
				beforeSend: function () {
					$('#learner_id').val('');
					// $("#birs_id").addClass('color-change-loader');
				},
				success: function (data) {
					//response(data.data);
					response($.map(data, function (item) {
						// console.log(item)
						return {
							label: item.name,
							value: (item.name).split(' (')[0],
							id: item.id,
							avatar:item.passport
						}
					}));
				},
				error: function (err) {
					alert(err);
				},
				complete: function () {
					//$("#birs_id").removeClass('color-change-loader');
				}
			});
		},
		select: function (event, ui) {
			//$('#learners_name').val((ui.item.label).split(' (')[0]);
			$('#learner_id').val(ui.item.id);
			$("#learner_avatar").attr('src',base_url+"assets/images/passports/"+ui.item.avatar);
			$('#class').trigger('change');
		}
	});

	

	//$(document).on('change keyup', '#firstname,#surname', function () {
	$(document).on('change keyup', '#learner', function () {
		$('#class').trigger('change');
		// alert('hello')
	});

	function parseLocaleNumber(stringNumber) {
		var thousandSeparator = (1111).toLocaleString().replace(/1/g, '');
		var decimalSeparator = (1.1).toLocaleString().replace(/1/g, '');

		return parseFloat(stringNumber
			.replace(new RegExp('\\' + thousandSeparator, 'g'), '')
			.replace(new RegExp('\\' + decimalSeparator), '.')
		);
	}




	//check for two names
	var tagCheckRE = new RegExp("(\\w+)(\\s+)(\\w+)");
	jQuery.validator.addMethod("tagcheck", function (value, element) {
		//return tagCheckRE.test(value);
		if (tagCheckRE.test(value))
			return true;
		else return false;
	}, "At least two words.");

	$("#check_result_form").validate({
		errorClass: "err_msg",
		rules: {
			// learner_id: "required",
			learner: {
				required: true,
				tagcheck: true,
			},
			// surname: "required",
			// firstname: "required",
			resultcheckingpin: "required"
		},
		messages: {
			learner: {
				required: "Please enter student fullname",
				tagcheck: "Please enter student surname and firstname",
			},
			// surname: "Please enter student's surname",
			// firstname: "Please enter student's first name",
			resultcheckingpin: "Examination pin required",
		},
		submitHandler: async function (form, event) {
			//initialize paystack payment
			event.preventDefault();

			// payWithPaystack()
			let data ={
				'learner_id' : $("#learner_id").val(),
				'name':$("#learner").val(),
				'pins' : $("#resultcheckingpin").val()
			}
			// console.log(data)
			// form.submit(data);
			let response = await fetch(base_url + 'verify_pin',
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
				if(data.status=="success"){
					$("#pinerror").text("");
					location.href = base_url+'checkresults/review_result/'+data.student;
					
				}else{
					$("#pinerror").text(data.message);
				}
			});

		}
	});


	$("#result_fee_form").validate({
		errorClass: "err_msg",
		rules: {
			fullname: {
				required: true,
				tagcheck: true,
			},
			email: {
				required: true,
				email: true,
			},
			phone: "required",
			
		},
		messages: {
			fullname: {
				required: "Please enter your fullname",
				tagcheck: "Please enter surname and firstname",
			},
			email: {
				required: "Please provide an email",
				email: "Please enter a valid email",
			},
			phone: "Please provide a valid phone number",
		},
		submitHandler: function (form) {
			//initialize paystack payment
			payWithPaystack()
			//form.submit();
		}
	});



	function payWithPaystack() {
		var handler = PaystackPop.setup({
			key: PK,
			email: $('#email').val(),
			amount: charge * 100,
			currency: "NGN",
			ref: acronym+'EXAM' + Math.floor((Math.random() * 1000000000) + 1) + 'CHECK', // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
			firstname: $('#fullname').val(),//$('#surname').val(),
			lastname: '',//$('#firstname').val(),
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
			subaccount:'ACCT_x3il7jfl5ogwgr8',
			percentage_charge:0.1,

			callback: function (response) {
				//alert('success. transaction ref is ' + response.reference);
				$('#transaction_reference').val(response.reference);
				if ($('#transaction_reference').val() != '') {
					swal({
						title: "Congratulations!",
						text: "Result PIN payment was successful!\n" + 'Your PIN is  is ' + response.reference+"\nPLEASE WAIT...",
						type: "success",
						icon: "success",
						showConfirmButton: false,
						allowOutsideClick: false,
						closeOnClickOutside: false,
					});
					send_receipt();
				}
				//form.submit();


			},
			onClose: function () {
				//alert('window closed');
			}
		});
		handler.openIframe();
	}

	async function send_receipt() {
		let data = {
			'name': $('#fullname').val().trim(),
			'amount': charge,
			'email': $('#email').val().trim(),
			'phone': $('#phone').val().trim(),
			'pins': $('#transaction_reference').val(),
			'number_used':0,
			'status':'active',
		};
		let response = await fetch(base_url + 'result_pin',
			{
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
				},
				body: JSON.stringify(data)
			}).then(function (response) {
				//swal.close();
				swal.showConfirmButton = true;
				return response.json();
			}).then(function (data) {
				// console.log(data);
				// location.href = base_url;
			});


	}

	
	//send_receipt();
});
