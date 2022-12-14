$(document).ready(function () {

	var discount = 0;
	var discount_amount;
	var charge = parseInt(charges);	
	var activities = parseInt(activity);	
	var learners = dcs;
	// console.log(scs);	
	/* [
        {surname:"Dalhatu", firstname:"Ummukulthum", discount:5},
        {surname:"Dalhatu", firstname:"Amira", discount:5},
        {surname:"Dalhatu", firstname:"Khalifa", discount:5},		
        {surname:"Abba", firstname:"Maryam", discount:50},	
        {surname:"Arigu", firstname:"Michael", discount:50},
        {surname:"Bello", firstname:"Rukkayat", discount:50},
        {surname:"Mohammed", firstname:"Zulaiha", discount:30},
        {surname:"Jalo", firstname:"Halima", discount:50},
        {surname:"Mohammed", firstname:"Bamalli", discount:30},
        {surname:"Sani", firstname:"Hafsat", discount:50},
        {surname:"Umar", firstname:"Anisa", discount:10},
        {surname:"Arigu", firstname:"Asheesla", discount:50},
        {surname:"Umar", firstname:"Humaria", discount:10},
        {surname:"Sani", firstname:"Al-Amin", discount:50},
        //{surname:"Baiyee", firstname:"Shehu", discount:10},
        {surname:"Liman", firstname:"Sa'ada", discount:50}
    ]; */

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
							class: item.class,
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
			
			$("#class option").removeAttr('selected');
			$('#selectedclass').val(ui.item.class).text(ui.item.class).attr('selected', 'true').change();
			$("#class option:not(:selected)").attr('disabled', 'true');

			$("#learner_avatar").attr('src',base_url+"assets/images/passports/"+ui.item.avatar);
			$('#class').trigger('change');
		}
	});

	function getDiscount(sf) {
		let found = false;
		discount_amount = discount = 0;
		//let fname = $('#firstname').val().toLowerCase();
		//let lname = $('#surname').val().toLowerCase();
		let learner_id = $('#learner_id').val();
		//if (fname != '' && lname != '') {
		if (learner_id != '') {
			learners.forEach(function (a) {
				/* if (a.firstname.toLowerCase() === fname
					&& a.surname.toLowerCase() === lname) {*/
				if (a.learner_id == learner_id) {
					found = true;
					discount = parseInt(a.discount);
					var sfee = parseLocaleNumber($('#school_fees').html());
					//sfee - 
					discount_amount = parseInt((discount / 100.0) * parseInt(sf));//sfee));

					var da = new Intl.NumberFormat().format(discount_amount);
					$('#discount_amount').closest('tr').remove();
					$('#discount_percent').val(discount);
					var drow = "<tr><td>4.</td><td>Fee Discount (" + discount + "%)</td><td>&#8358; <span id='discount_amount' style='text-decoration: line-through;'>" + da + "</span></td></tr>";
					$('#summary_table tbody').append(drow);
					return;
				}
			});
		}
		if (found == false) {
			$('#discount_amount').closest('tr').remove();
		}
	}

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


	$("#year").datepicker({
		/* format: "yyyy",
		viewMode: "years",
		minViewMode: "years" */
		changeMonth: false,
		changeYear: true,
		showButtonPanel: true,
		//yearRange: '1950:2013', // Optional Year Range
		dateFormat: 'yy',
		onClose: function (dateText, inst) {
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			$(this).datepicker('setDate', new Date(year, 0, 1));
		}
	});

	//check for two names
	var tagCheckRE = new RegExp("(\\w+)(\\s+)(\\w+)");
	jQuery.validator.addMethod("tagcheck", function (value, element) {
		//return tagCheckRE.test(value);
		if (tagCheckRE.test(value))
			return true;
		else return false;
	}, "At least two words.");

	$("#fee_form").validate({
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
			surname: "required",
			firstname: "required",
			year: {
				required: true,
				minlength: 4,
				maxlength: 4,
			},
			fee: "required"
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
			surname: "Please enter student's surname",
			firstname: "Please enter student's first name",
			year: "Please provide a year",
			fee: "Fee is required",
		},
		submitHandler: function (form) {
			//initialize paystack payment
			// console.log(form);
			statusLearnerId = $('#learner_id').val();
			statusLearnerYear = $('#year').val().trim();
			statusLearnerClass = $('#class').val();
			statusLearnerTerm = $('#term').val() + ' Term';
			$.ajax({
				url: base_url + 'check_learners_paid_status?learner_id='+statusLearnerId+'&&year='+statusLearnerYear+'&&class='+statusLearnerClass+'&&term='+statusLearnerTerm,
				method: "get",
				dataType: 'json',
				contentType: "application/json;charset=utf-8",
				/* headers: {
					"Authorization": "Bearer " + '<?= ENGINE_TOKEN ?>'
				}, */
				/* data: JSON.stringify({
					term: request.term
				}), */
				
				success: function (data) {
					if (data){
						swal({
							title: "Already Paid!",
							text:  data.student+ " already paid!\n Class : "+ data.class +"\nTerm :  "+data.term+ '\nTransaction reference is ' + data.transaction_reference,
							type: "success",
							icon: "success",
							showConfirmButton: true,
							allowOutsideClick: true,
							closeOnClickOutside: true,
						});
					}else{
						// console.log("Not paid yet");
						payWithPaystack();
					}
					

					// response(data.data);
					// console.log(data);
					
				// console.log(data.student);
				},
				error: function (err) {
					alert(err);
				},
				complete: function () {
					//$("#birs_id").removeClass('color-change-loader');
				}
			});


			// alert($('#email').val());
			// payWithPaystack()
			//form.submit();
		}
	});

	function payWithPaystack() {
		var handler = PaystackPop.setup({
			key: PK,
			email: $('#email').val(),
			amount: $('#fee').val() * 100,
			currency: "NGN",
			ref: acronym + Math.floor((Math.random() * 1000000000) + 1) + 'F', // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
			firstname: $('#learner').val(),//$('#surname').val(),
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
			callback: function (response) {
				//alert('success. transaction ref is ' + response.reference);
				$('#transaction_reference').val(response.reference);
				if ($('#transaction_reference').val() != '') {
					swal({
						title: "Congratulations!",
						text: "Fee payment was successful!\n" + 'Transaction reference is ' + response.reference+"\nPLEASE WAIT...",
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
			'payer': $('#fullname').val().trim(),
			'student': $('#learner').val(),//$('#firstname').val().trim() + ' ' + $('#surname').val().trim(),
			'class': $('#class').val(),
			'year': $('#year').val().trim(),
			'term': $('#term').val() + ' Term',
			'school_fees': $('#school_fees').html(),
			'activity_fees': activities,
			'admin_charges': charge,
			'fee_discount': $('#discount_amount').html(),
			'discount_percent': $('#discount_percent').val(),
			'external_examination_fee': $('#external_exam_fee').val() ?? '',
			'graduation_fee': $('#graduation_fee').val() ?? '',
			'total': $('#amount').val(),
			'email': $('#email').val().trim(),
			'phone': $('#phone').val().trim(),
			'transaction_reference': $('#transaction_reference').val(),
			'user_id': 2,
			'channel': 'web',
			'learner_id': $('#learner_id').val(),
		};

		let response = await fetch(base_url + 'details',
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
				location.href = base_url;
			});


	}

	$(document).on('change', '#class, #term, #type', function () {
		let cls = $('#class').val();
		let term = $('#term').val();
		// let type = $('#type').val();
		// alert(cls);
		/* if(cls == 'Pre-Nursery' || cls == 'Nursery 1' || cls == 'Nursery 2'){
			if($('#term').val() == 'First')
				$('#fee').val('180000');
			else
				$('#fee').val('140000');
		}else if(cls.startsWith('Primary')){
			if($('#term').val() == 'First')
				$('#fee').val('220000');
			else
				$('#fee').val('180000');
		}else if(cls.startsWith('JSS')){
			if($('#term').val() == 'First')
				$('#fee').val('240000');
			else if($('#term').val() == 'Second' && cls == 'JSS 3')
				$('#fee').val('270000');
			else
				$('#fee').val('220000');
		}else{
			if($('#term').val() == 'First')
				$('#fee').val('350000');
			else if($('#term').val() == 'Second' && cls == 'SSS 3')
				$('#fee').val('420000');
			else
				$('#fee').val('270000');
		} */

		$.each(scs, function (a, b) {
			// console.log(b.amount)
			
			if (cls == b.class && term == b.term) {
				// console.log(b.amount)
				// console.log(b.payment_type)
				$('#fee').val(b.amount ? b.amount:0.00);
				// return;
			}
		});

		$('#grad_tr').empty();
		let grad_amt = 0;
		let external_exam = 0;

		if (cls == 'JSS 3' && term == "Second") {
			external_exam = jssexam
			let fm_external_exam = new Intl.NumberFormat().format(parseInt(external_exam));
			$('#grad_tr').append('<td>5.</td><td>External Examination Fee</td><td>&#8358;' + fm_external_exam + '<input type="hidden" name="external_exam_fee" id="external_exam_fee" value="' + fm_external_exam + '" /></td>');
		}
		if (cls == 'SSS 3' && term == "Second") {
			external_exam = sssexam
			let fm_external_exam = new Intl.NumberFormat().format(parseInt(external_exam));
			$('#grad_tr').append('<td>5.</td><td>External Examination Fee</td><td>&#8358;' + fm_external_exam + '<input type="hidden" name="external_exam_fee" id="external_exam_fee" value="' + fm_external_exam + '" /></td>');
		}
		if (cls == 'Primary 6' && term == "Second") {
			external_exam = prye;
			let fm_external_exam = new Intl.NumberFormat().format(parseInt(external_exam));
			$('#grad_tr').append('<td>4.</td><td>Common Entrance Examination Fee</td><td>&#8358;' + fm_external_exam + '<input type="hidden" name="external_exam_fee" id="external_exam_fee" value="' + fm_external_exam + '" /></td>');
		}
		//if (cls != 'Pre-Nursery' && cls != 'Nursery 1' && !cls.startsWith('JSS') && cls != 'SSS 1' && cls != 'SSS 2') {
		// if (cls == 'Nursery 2' || cls == 'Primary 6' || cls == 'SSS 3'|cls == "JSS 3") {
			if(cls == 'Nursery 2' && term == 'Third'){
				grad_amt =nurserygr;
				let fm_grad_amt = new Intl.NumberFormat().format(parseInt(grad_amt));
				$('#grad_tr').append('<td>4.</td><td>Graduation Fee</td><td>&#8358;' + fm_grad_amt + '<input type="hidden" name="graduation_fee" id="graduation_fee" value="' + fm_grad_amt + '" /</td>');
		
			}
			if( cls == 'Primary 6' && term == 'Third'){
				grad_amt = prygr;
				let fm_grad_amt = new Intl.NumberFormat().format(parseInt(grad_amt));
				$('#grad_tr').append('<td>4.</td><td>Graduation Fee</td><td>&#8358;' + fm_grad_amt + '<input type="hidden" name="graduation_fee" id="graduation_fee" value="' + fm_grad_amt + '" /</td>');
		
			}
			if( cls == 'SSS 3' && term == 'Third'){
				grad_amt = sssgr;
				let fm_grad_amt = new Intl.NumberFormat().format(parseInt(grad_amt));
				$('#grad_tr').append('<td>4.</td><td>Graduation Fee</td><td>&#8358;' + fm_grad_amt + '<input type="hidden" name="graduation_fee" id="graduation_fee" value="' + fm_grad_amt + '" /</td>');
		
			}
			if( cls == 'JSS 3' && term == 'Third'){
				grad_amt = jssgr;
				let fm_grad_amt = new Intl.NumberFormat().format(parseInt(grad_amt));
				$('#grad_tr').append('<td>4.</td><td>Graduation Fee</td><td>&#8358;' + fm_grad_amt + '<input type="hidden" name="graduation_fee" id="graduation_fee" value="' + fm_grad_amt + '" /</td>');
		
			}
			// +20000+ 20000+activity+activity

		$('#school_fees').html(new Intl.NumberFormat().format(parseInt($('#fee').val())||0));
		getDiscount($('#fee').val());
		$('#fee').val(parseInt($('#fee').val()) - discount_amount +parseInt(activity) +charge + parseInt(grad_amt) + parseInt(external_exam));
		$('#total_fees').html(new Intl.NumberFormat().format(parseInt($('#fee').val())||0) + '.00');
		$('#amount').val(new Intl.NumberFormat().format(parseInt($('#fee').val())||0) + '.00')
		//$('#summary_div').removeClass('d-none');
	});

	$('#class').trigger('change');
	//$('#summary_div').addClass('d-none');

	//send_receipt();
});
