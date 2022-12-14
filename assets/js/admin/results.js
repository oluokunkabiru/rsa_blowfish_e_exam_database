$(document).ready(function () {
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 4000
	});

	const params = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,
width=600,height=300,left=100,top=100`;

	$("#results_table").DataTable({
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
			url: base_url + "get_results",
			type: 'GET'
		},
		"oSearch": { "bSmart": false, "bRegex": true, "sSearch": "" }
	});

	$('#submit_result_form').validate({
		ignore: "",
		focusInvalid: false,
		errorClass: "err_msg",
		rules: {},
		messages: {},
		submitHandler: async function (form, event) {
			event.preventDefault();
		 Swal.fire({
			title: 'Are you sure you want to submit ' + $('#s_term').val().toUpperCase() + ' term results for ' + $('#s_class').val().toUpperCase() + ' ' + $('#s_arm').val() + ', ' + $('#s_academic_year').val() + ' session?',
			icon: 'question',
			showDenyButton: true,
			showCancelButton: true,
			confirmButtonText: "Submit",
			denyButtonText: 'Cancel',
		  }).then(async (result) => {
			/* Read more about isConfirmed, isDenied below */
			// console.log(result);
			if (result.value==true) {
				$('.submit-result').attr('disabled', true);
				$('.submit-result').html('<i class="fa fa-spinner fa-spin"></i> Submitting...');
				let data = {
					session: $('#s_academic_year').val(),
					term: $('#s_term').val(),
					class: $('#s_class').val(),
					arm: $('#s_arm').val(),
				};
				let response = await fetch(base_url + $('#submit_result_form').attr('action'), {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
					},
					body: JSON.stringify(data)
				}).then(function (response) {
					return response.json();
				}).then(function (data) {
					$('.submit-result').attr('disabled', false);
					$('.submit-result').html('<i class="fa fa-search"></i> Submit Result');
					if (data.status == 'success') {
						Toast.fire({
							icon: 'success',
							title: 'Result Submitted Successfully.'
						})
						$('#modal-submit').modal('hide');
						$('#results_table').DataTable().ajax.reload();
					} else if (data.status == 'info') {
						Toast.fire({
							icon: 'info',
							title: 'Info: ' + data.message
						})
					} else {
						Toast.fire({
							icon: 'error',
							title: 'Error: ' + data.message
						})
					}
				});
			}
		})
			// if (confirm('Are you sure you want to submit ' + $('#s_term').val().toUpperCase() + ' term results for ' + $('#s_class').val().toUpperCase() + ' ' + $('#s_arm').val() + ', ' + $('#s_academic_year').val() + ' session?')) {
			
			// };

		},
	});

	$("#check_result_form").validate({
		ignore: "",
		focusInvalid: false,
		errorClass: "err_msg",
		rules: {},
		messages: {},
		submitHandler: async function (form, event) {
			event.preventDefault();
			/* $('.check-results').attr('disabled', true);
			$('.check-results').html('<i class="fa fa-spinner fa-spin"></i> Processing...'); */
			let data = {
				session: $('#c_academic_year').val(),
				term: $('#c_term').val(),
				class: $('#c_class').val(),
				arm: $('#c_arm').val()
			};
			let response = await fetch(base_url + $('#check_result_form').attr('action'), {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
				},
				body: JSON.stringify(data)
			}).then(function (response) {
				return response.json();
			}).then(function (data) {
				$('.check-results').attr('disabled', false);
				$('.check-results').html('<i class="fa fa-search"></i> Check Results');
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: 'Result Retrieved Successfully.'
					})
					$('#modal-check').modal('hide');

					if ($('#broadsheet').is(':checked'))
						open(base_url + 'broadsheet_results/' + data.id, 'test', params);
					else
						open(base_url + 'show_results/' + data.id, 'test', params);
				} else if (data.status == 'info') {
					Toast.fire({
						icon: 'info',
						title: 'Info: ' + data.message
					})
				} else {
					Toast.fire({
						icon: 'error',
						title: 'Error: ' + data.message
					})
				}
			});
		},
	});

	$(document).on("click", ".r-view", function (e) {
		e.preventDefault();
		console.log(base_url);
		open(base_url + 'show_results/' + $(this).attr('href'), 'test', params);
	});

	$(document).on("click", ".b-view", function (e) {
		e.preventDefault();
		open(base_url + 'broadsheet_results/' + $(this).attr('href'), 'test', params);
	});

	$(document).on("click", ".f-view", function (e) {
		e.preventDefault();
		open(base_url + 'sheet_results/' + $(this).attr('href'), 'test', params);
	});

	// Promotion and cummulative

	$(document).on("click", ".p-view", async function (e) {
		e.preventDefault();
		$('#modal-promotion').animate({
			scrollTop: $("#modal-promotion").offset().top
		}, 2000);
		// alert("hello");
		$("#modal-promotion").modal('show');
				let response = await fetch(base_url + 'promotion_results/' + $(this).attr('href'), {
					method: 'GET',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
					},
				}).then(function (response){
					return response.json();
				}).then(function (data) {
					// console.log(data.result);
					// data = JSON.stringify(response);
					// console.log(data);
					var setting = JSON.parse(data.default_settings.school);
					// console.log(setting.colour.default);
					var promotionclass = data.results[0].class  +'   '+data.results[0].session + ' SESSION ';
					$("#promotion-class").html(promotionclass)
					var promotion ="";
				promotion+=	'<table class="table table-striped table-bordered table-responsive">'
					promotion+='<caption class="p-0 m-0">'
					// promotion+=	'<h3>'+data.results[0].class  +' Promotion  '+data.results[0].session +'</h3>'
				promotion+=	'</caption>'
				promotion+=	'<input id="promotingclass" type="hidden" name="class" value="'+data.results[0].class +'">'
				promotion+=	'<thead style="display: table-header-group;">'
					promotion+=	'<tr style="background-color:'+ setting.colour.default +' ">'
					promotion+=	'	<th class="score-head">#</th>'
					promotion+=	'	<th class="score-head">NAME</th>'
					promotion+=	'<th class="score-head">REGNO</th>'
					promotion+=	'	<th class="score-head">AGE</th>'
					promotion+=	'	<th class="score-head">SEX</th>'
					promotion+=	'	<th class="score-head">FIRST TERM</th>'
					promotion+=	'	<th class="score-head">SECOND TERM</th>'
					promotion+=	'	<th class="score-head">THIRD TERM</th>'
					promotion+=	'		<th class="score-head">CUMMULATIVE</th>'
					promotion+=	'		<th class="score-head">AVERAGE</th>'
					promotion+=	'		<th class="score-head">ACTION</th>'
					promotion+=	'</tr>'
					promotion+=	'</thead>'
					promotion+=	'<tbody>'
					let i = 0;

					$.each(data.results, function (c, result) {
						promotion+=	'<tr style="page-break-inside:avoid; page-break-after:auto;">'
						promotion+=	'<td class="score"> '+ ++i +'</td>'
						promotion+=	'<td class="score"><span style="white-space: nowrap;">'+ result.name +'</span></td>'
						promotion+=	'<td class="score"><span style="white-space: nowrap;">'+result.regno ?? "" +'</span></td>'
						promotion+=	'<td class="score">'+result.age+'</td>'
						promotion+=	'<td class="score">'+result.gender+'</td>'
						promotion+=	'<td class="score">'+result.fcumulative+'</td>'
						promotion+=	'<td class="score">'+result.scumulative+'</td>'
						promotion+=	'<td class="score">'+result.tcumulative +'</td>'
						promotion+=	'<td class="score">'+result.totalcumulative +'</td>'
						
						promotion+=	'<td class="score">'+(result.totalcumulative/result.totalterm).toFixed(2)+'</td>'
						promotion+=	'<td class="score">'
						promotion+=	'<div class="custom-control custom-checkbox">'
						promotion+=	'<input type="checkbox" class="custom-control-input" id="student'+i+'" value="'+result.learner_id+'" name="student[]">'
						promotion+=	'<label class="custom-control-label" for="student'+i+'"></label>'
						promotion+=	'</div>'
						promotion+=	'</td>'
						
						promotion+=	'</tr>'
					});

					promotion+=	'<tr style="border: solid 1px #eee;page-break-inside:avoid; page-break-after:auto;">'
					promotion+=	'<td class="score" colspan="50">&nbsp;</td>'
					promotion+=	'</tr>'
					promotion+=	'</tbody>'
					promotion+=	'</table>'
					$("#promotiondata").html(promotion);
					
				})
					
				
			



		// open(base_url + 'promotion_results/' + $(this).attr('href'), 'test', params);
	
	
	});



	$(document).on('click','.promote-to-next-class', async function(e){
		e.preventDefault();
		var student = $("#promotionform").serialize();
		var classes = $("#promotingclass").val();
		// console.log(classes);
		var students = [];
			
			$("input[name='student[]']:checked").each(function(){
				students.push($(this).val());
			});

			// console.log(students.json());
			
		let response = await fetch(base_url + 'promoting_student/' + classes.replace(/\s/g, "-"), {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
			},
			body: JSON.stringify(students),
		}).then(function (response){
			return response.json();
		}).then(function (data) {
			// console.log(data);
			if (data.status == 'success') {
				Toast.fire({
					icon: 'success',
					title: data.message
				});
				$("#modal-promotion").modal('hide');

			}
		})
		// console.log(student);
	})

	$(document).on("click", ".c-view", function (e) {
		e.preventDefault();
		open(base_url + 'cumulative_results/' + $(this).attr('href'), 'test', params);
	});

	// end it

	$(document).on('click', '.approve, .reject', function (e) {
		e.preventDefault();
		let action = $(e.target).hasClass('approve') ? 1 : 2;

		Swal.fire({
			title: 'Are you sure you want to ' + (action == 1 ? 'APPROVE' : 'REJECT') + ' this result?',
			icon: 'question',
			showDenyButton: true,
			showCancelButton: true,
			confirmButtonText: action == 1 ? 'APPROVE' : 'REJECT',
			denyButtonText: 'Cancel',
		  }).then(async (result) => {
			if (result.value==true) {

			$.get(base_url + "act_results/" + $(this).attr('href') + '/?action=' + action, function (data) {
				data = JSON.parse(data);
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: (action == 1 ? 'Approval' : 'Rejection') + ' successful.'
					});
					$('#results_table').DataTable().ajax.reload();
				} else {
					Toast.fire({
						icon: 'error',
						title: 'Error in ' + (action == 1 ? 'Approving' : 'Rejecting') + ' Result: ' + data.message
					})
				}
			});
		}

		  })

		
		
		// if (confirm('Are you sure you want to ' + (action == 1 ? 'APPROVE' : 'REJECT') + ' this result?')) {
			

		// }
	});

	function action(id, action) {
		console.log(id + ' ' + action);
	}

});
