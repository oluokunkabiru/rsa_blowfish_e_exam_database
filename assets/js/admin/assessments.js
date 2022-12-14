$(document).ready(function () {
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 4000
	});

	const params = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,
width=600,height=300,left=100,top=100`;

	let subjects = [];

	$(".date").datepicker({
		format: "yyyy-mm-dd",
	});

	$("#start_time, #edit_start_time, #pop_start_time").datetimepicker({
		format: 'HH:mm',//"LT",
	});
	$('#end_time, #edit_end_time, #pop_end_time').datetimepicker({
		format: 'HH:mm',//'LT',
		useCurrent: false
	})
	$("#start_time").on("change.datetimepicker", function (e) {
		$('#end_time').datetimepicker('minDate', e.date);
	});
	$("#end_time").on("change.datetimepicker", function (e) {
		$('#start_time').datetimepicker('maxDate', e.date);
	});
	$("#edit_start_time").on("change.datetimepicker", function (e) {
		$('#edit_end_time').datetimepicker('minDate', e.date);
	});
	$("#edit_end_time").on("change.datetimepicker", function (e) {
		$('#edit_start_time').datetimepicker('maxDate', e.date);
	});

	$("#assessments_table").DataTable({
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
			url: base_url + "get_assessments",
			type: 'GET'
		},
		"oSearch": { "bSmart": false, "bRegex": true, "sSearch": "" }
	});

	$("#questions_table").DataTable({
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
			url: base_url + "get_questions",
			type: 'GET'
		},
		"oSearch": { "bSmart": false, "bRegex": true, "sSearch": "" }
	});

	$(document).on('change', '#class, #term, #arm', async function () {
		getCAs($('#class').val());//class, 
		await getClassSubjects($('#subject'));//class
	});
	$('#class').trigger('change');

	$(document).on('change', '#edit_class, #edit_term, #edit_arm', async function () {
		getCAs($('#edit_class').val());//class, 
		await getClassSubjectsEdit();//class
	});


	async function getClassSubjects(element) {
		$.ajax({
			url: encodeURI(base_url + 'get_class_subjects/' + (element.is($('#pop_subject')) ? $('#pop_class').val() : $('#class').val())),
			type: 'GET',
			success: function (data) {
				element.empty();
				if (element.is($('#pop_subject'))) {
					subjects = JSON.parse(data);
					element.append('<option value="multiple">MULTIPLE SUBJECTS</option>');

					$('#pop_subjects tbody').empty();
					let aq = '';
					$.each(subjects, function (a, b) {
						aq += `<tr><td style="vertical-align:middle;">${a + 1}</td>` +
							`<td style="vertical-align:middle;"><input type="checkbox" name="psub[]" id="psub[]" class="psub" value="${b.id}"/></td>` +
							`<td style="vertical-align:middle;">${b.name}</td>` +
							`<td><input type="number" name="pques[]" id="pques[]" class="form-control" step="1" min="0"></td></tr>`;
					});
					$('#pop_subjects tbody').append(aq);
				}
				$.each(JSON.parse(data), function (a, b) {
					element.append(`<option value="${b.id}">${b.name}</option>`)
				});
				getAssessmentQuestions();
			},
			error: function (a) {
				console.log(a)
			}
		});
	}

	async function getClassSubjects2() {
		$.ajax({
			url: encodeURI(base_url + 'get_class_subjects/' + $('#q_class').val()),
			type: 'GET',
			success: function (data) {
				$('#q_subject').empty();
				$.each(JSON.parse(data), function (a, b) {
					$('#q_subject').append(`<option value="${b.id}">${b.name}</option>`)
				});
			},
			error: function (a) {
				console.log(a)
			}
		}).done(function () {
			getSubjectTopics();
		});
	}

	$('#q_subject').change(function () {
		getSubjectTopics();
	})

	async function getSubjectTopics() {
		$.ajax({
			url: encodeURI(base_url + 'get_subject_topics/' + $('#q_subject').val()),
			type: 'GET',
			success: function (data) {
				$('#q_topic').empty();
				$.each(JSON.parse(data), function (a, b) {
					$('#q_topic').append(`<option value="${b.id}">${b.topic}</option>`)
				});
			},
			error: function (a) {
				console.log(a)
			}
		});
	}

	async function getClassSubjectsEdit() {
		$.ajax({
			url: encodeURI(base_url + 'get_class_subjects/' + $('#edit_class').val()),
			type: 'GET',
			success: function (data) {
				$('#edit_subject').empty();
				$.each(JSON.parse(data), function (a, b) {
					$('#edit_subject').append(`<option value="${b.id}">${b.name}</option>`)
				});
			},
			error: function (a) {
				console.log(a)
			}
		});
	}

	function getCAs(cs) {
		let cas = 0;
		$.each(schemes, function (a, b) {
			if (cs == b.class) {
				cas = ((b.sharing).split('-')).length - 1;
				return;
			}
		});

		let type = '<option value="exam">Exam</option>';
		for (var p = 0; p < cas; p++) {
			type += `<option value="c.a ${p + 1}">C.A ${p + 1}</option>`;
		}
		//type += '<option value="mock">Mock</option>';
		typeo = '<option value="every">Everything</option>';

		$('#type, #q_assessment, #edit_q_assessment').empty();
		//$('#type, #q_assessment, #edit_q_assessment').append('<option value="all">ALL</option>');
		$('#type, #q_assessment, #edit_q_assessment').append(type);
		//$('#type').append(typeo);
	}

	$(document).on('change', '#subject, #type', async function () {
		getAssessmentQuestions();
	})
	$(document).on('change', '#edit_subject, #edit_type', async function () {
		getAssessmentQuestionsEdit();
	})

	function getAssessmentQuestions() {
		$.ajax({
			url: encodeURI(base_url + 'get_questions_where?term=' + $('#term').val() + '&class_name=' + $('#class').val() + '&arm=' + $('#arm').val() + '&subject_id=' + $('#subject').val() + '&assessment=' + $('#type').val().toLowerCase()),
			type: 'GET',
			success: function (data) {
				$('#assessment_questions tbody').empty();
				let aq = '';
				$.each(data.data, function (a, b) {
					let type = b.type == 'true_false' ? 'True/False' : (b.type == 'multiple_choice' ? 'Multiple Choice' : 'Single Answer');
					aq += `<tr><td style="vertical-align:middle;">${a + 1}</td><td style="vertical-align:middle;"><input type="checkbox" name="ques[]" id="ques[]" class="ques" value="${b.id}"/></td><td style="vertical-align:middle;"><small>${type}</small></td><td><div class="attachment-block mb-0"><small>${b.question}</small></div></td></tr>`;
				});
				$('#assessment_questions tbody').append(aq);
			},
			error: function (a) {
				console.log(a)
			}
		});
	}

	$(document).on('click', '.ques', function () {
		let count = $('input.ques:checked').length;
		$('#qcount').html(count);
	});

	$('#add_assessment_form').validate({
		ignore: "",
		focusInvalid: false,
		errorClass: "err_msg",
		rules: {},
		messages: {},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.add-assessment').attr('disabled', true);
			$('.add-assessment').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
			var checked = []
			$("input[name='ques[]']:checked").each(function () {
				checked.push(parseInt($(this).val()));
			});
			let data = {
				session: $('#session').val(),
				term: $('#term').val(),
				type: $('#type').val(),
				class: $('#class').val(),
				arm: $('#arm').val(),
				subject_id: $('#subject').val(),
				date: $('#date').val(),
				start_time: $('#start_time').val(),
				end_time: $('#end_time').val(),
				duration: $('#duration').val(),
				marks: $('#marks').val(),
				questions: checked,//JSON.stringify(checked),
			};
			let response = await fetch(base_url + $('#add_assessment_form').attr('action'), {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
				},
				body: JSON.stringify(data)
			}).then(function (response) {
				return response.json();
			}).then(function (data) {
				$('.add-assessment').attr('disabled', false);
				$('.add-assessment').html('<i class="fa fa-search"></i> Save Assessment');
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: 'Assessment Added Successfully.'
					})
					$('#modal-assessment').modal('hide');
					$('#assessments_table').DataTable().ajax.reload();
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

	/*
		EDIT
	*/
	$(document).on("click", ".as-edit", async function (e) {
		var data = JSON.parse(urldecode($(this).attr('data')));
		console.log(data);
		$('#edit_session').val(data.session);
		$('#edit_term').val(data.term);
		$('#edit_type').val(data.type);
		$('#edit_class').val(data.class);
		$('#edit_arm').val(data.arm);
		$('#edit_subject').val(data.subject);
		$('#edit_date').val(data.date);
		$('#edit_start_time').val(data.start_time);
		$('#edit_end_time').val(data.end_time);
		$('#edit_duration').val(data.duration);
		$('#edit_marks').val(data.marks);
	});
	async function getClassSubjectsEdit() {
		$.ajax({
			url: encodeURI(base_url + 'get_class_subjects/' + $('#edit_q_class').val()),
			type: 'GET',
			success: function (data) {
				$('#edit_q_subject').empty();
				$.each(JSON.parse(data), function (a, b) {
					$('#edit_q_subject').append(`<option value="${b.id}">${b.name}</option>`)
				});
				getAssessmentQuestionsEdit();
			},
			error: function (a) {
				console.log(a)
			}
		});
	}

	function getAssessmentQuestionsEdit() {
		$.ajax({
			url: encodeURI(base_url + 'get_questions_where?term=' + $('#edit_term').val() + '&class_name=' + $('#edit_class').val() + '&arm=' + $('#edit_arm').val() + '&subject_id=' + $('#edit_subject').val()),
			type: 'GET',
			success: function (data) {
				$('#edit_assessment_questions tbody').empty();
				let aq = '';
				$.each(data.data, function (a, b) {
					let type = b.type == 'true_false' ? 'True/False' : (b.type == 'multiple_choice' ? 'Multiple Choice' : 'Single Answer');
					aq += `<tr><td style="vertical-align:middle;">${a + 1}</td><td style="vertical-align:middle;"><input type="checkbox" name="edit_ques[]" id="edit_ques[]" class="ques" value="${b.id}"/></td><td style="vertical-align:middle;"><small>${type}</small></td><td><div class="attachment-block mb-0"><small>${b.question}</small></div></td></tr>`;
				});
				$('#edit_assessment_questions tbody').append(aq);
			},
			error: function (a) {
				console.log(a)
			}
		});
	}

	$('#edit_assessment_form').validate({
		ignore: "",
		focusInvalid: false,
		errorClass: "err_msg",
		rules: {},
		messages: {},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.edit-assessment').attr('disabled', true);
			$('.edit-assessment').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
			var checked = []
			$("input[name='edit_ques[]']:checked").each(function () {
				checked.push(parseInt($(this).val()));
			});
			let data = {
				session: $('#edit_session').val(),
				term: $('#edit_term').val(),
				type: $('#edit_type').val(),
				class: $('#edit_class').val(),
				arm: $('#edit_arm').val(),
				subject_id: $('#edit_subject').val(),
				date: $('#edit_date').val(),
				start_time: $('#edit_start_time').val(),
				end_time: $('#edit_end_time').val(),
				duration: $('#edit_duration').val(),
				marks: $('#edit_marks').val(),
				questions: checked,//JSON.stringify(checked),
			};
			let response = await fetch(base_url + 'edit_assessment/' + $('#edit_assessment_form').attr('action'), {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
				},
				body: JSON.stringify(data)
			}).then(function (response) {
				return response.json();
			}).then(function (data) {
				$('.edit-assessment').attr('disabled', false);
				$('.edit-assessment').html('<i class="fa fa-search"></i> Save Assessment');
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: 'Assessment Updated Successfully.'
					})
					$('#modal-assessment-edit').modal('hide');
					$('#assessments_table').DataTable().ajax.reload();
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

	/*	DELETE	*/
	$(document).on("click", ".as-del", function (e) {
		e.preventDefault();
		var table = $('#assessments_table').DataTable();
		var row = $(this).parents('tr');

		if (confirm('Are you sure you want to delete?')) {
			$.get(base_url + "del_assessment/" + $(this).attr('href'), function (data) {
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

	$(document).on("click", ".as-quiz", function (e) {
		e.preventDefault();
		location.href = base_url + 'quiz_assessment/' + $(this).attr('href') + '/1'
		//open(base_url + 'show_results/' + $(this).attr('href'), 'test', params);
	});


	/*		
		POP
	*/
	$("#pop_start_time").on("change.datetimepicker", function (e) {
		$('#pop_end_time').datetimepicker('minDate', e.date);
	});
	$("#pop_end_time").on("change.datetimepicker", function (e) {
		$('#pop_start_time').datetimepicker('maxDate', e.date);
	});

	$("#pop_table").DataTable({
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
			url: base_url + "get_pop_assessments",
			type: 'GET'
		},
		"oSearch": { "bSmart": false, "bRegex": true, "sSearch": "" }
	});

	$(document).on('change', '#pop_class, #pop_term, #pop_arm, #pop_category', async function () {
		getCAs($('#pop_class').val());//class, 
		await getClassSubjects($('#pop_subject'));//class
	});
	$('#pop_class').trigger('change');

	$(document).on('change', '#pop_subject', function () {
		//$('#tb_holder').empty();
		if ($(this).val() == "multiple") {
			$('#tb_header').text('Subjects: ');
			$('#pop_subjects').removeClass('d-none');
			$('#pop_topics').addClass('d-none');
		} else {
			$('#tb_header').text('Topics: ');
			$('#pop_subjects').addClass('d-none');
			$('#pop_topics').removeClass('d-none');
			$.ajax({
				url: encodeURI(base_url + 'get_subject_topics/' + $(this).val()),
				type: 'GET',
				success: function (data) {
					$('#pop_topics tbody').empty();
					let bbb = '';
					$.each(JSON.parse(data), function (a, b) {
						bbb += `<tr><td style="vertical-align:middle;">${a + 1}</td>`
							+ `<td style="vertical-align:middle;"><input type="checkbox" name="ptopic[]" id="ptopic[]" class="psub" value="${b.id}"/></td>`
							+ `<td style="vertical-align:middle;">${b.topic}</td>`
							+ `<td><input type="number" name="pobj[]" id="pobj[]" class="form-control" step="1" min="0"></td>`
							+ `<td><input type="number" name="psubj[]" id="psubj[]" class="form-control" step="1" min="0"></td>`
							+ `<td><input type="number" name="pess[]" id="pess[]" class="form-control" step="1" min="0"></td>`
							+ `</tr>`;
					});
					$('#pop_topics tbody').append(bbb);
				},
				error: function (a) {
					console.log(a)
				}
			});
		}
	});

	/*	ADD		*/
	$('#add_pop_form').validate({
		ignore: "",
		focusInvalid: false,
		errorClass: "err_msg",
		rules: {},
		messages: {},
		submitHandler: async function (form, event) {
			event.preventDefault();
			//$('.add-pop').attr('disabled', true);
			$('.add-pop').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
			var checked = [];
			var qchecked = [];
			if ($("#pop_subject").val() == "multiple") {
				$("input[name='psub[]']:checked").each(function () {
					let qv = parseInt($(this).closest('tr').find("input[name='pques[]'").val());
					if (!isNaN(qv) || qv == 0) {
						checked.push(parseInt($(this).val()));
						qchecked.push(qv);
					}
				});
			} else {
				$("input[name='ptopic[]']:checked").each(function () {
					let pobj = parseInt($(this).closest('tr').find("input[name='pobj[]'").val());
					let psubj = parseInt($(this).closest('tr').find("input[name='psubj[]'").val());
					let pess = parseInt($(this).closest('tr').find("input[name='pess[]'").val());
					if ((!isNaN(pobj) && pobj > 0) || (!isNaN(psubj) && psubj > 0) || (!isNaN(pess) && pess > 0)) {
						//checked.push(parseInt($(this).val()));
						let topic = {
							'topic': $(this).val(),
							'questions': {
								'multiple_choice': pobj ?? 0,
								'short_answer': psubj ?? 0,
								'essay': pess ?? 0
							}

						};
						qchecked.push(JSON.stringify(topic));
					}
				});
			}


			let data = {
				session: $('#pop_session').val(),
				term: $('#pop_term').val(),
				type: $('#pop_type').val(),
				class: $('#pop_class').val(),
				arm: $('#pop_arm').val(),
				subject_ids: $("#pop_subject").val() == "multiple" ? checked : $("#pop_subject").val(),
				date: $('#pop_date').val(),
				start_time: $('#pop_start_time').val(),
				end_time: $('#pop_end_time').val(),
				duration: $('#pop_duration').val(),
				marks: $('#pop_marks').val(),
				questions: qchecked,//$("input[name='pques[]']").map(function () {if ($(this).val() != "")return $(this).val();}).get(),
			};
			let response = await fetch(base_url + $('#add_pop_form').attr('action'), {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
				},
				body: JSON.stringify(data)
			}).then(function (response) {
				return response.json();
			}).then(function (data) {
				$('.add-pop').attr('disabled', false);
				$('.add-pop').html('<i class="fa fa-search"></i> Save POP Assessment');
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: 'POP Assessment Added Successfully.'
					})
					$('#modal-pop').modal('hide');
					$('#pop_table').DataTable().ajax.reload();
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

	$(document).on("click", ".p-view", function (e) {
		e.preventDefault();
		open(base_url + 'show_pop_assessment/' + $(this).attr('href'), 'test', params);
	});





	/*
		QUESTIONS
	*/
	$(document).on('change', '#q_class', async function () {
		await getClassSubjects2();
	});

	$('#q_class').trigger('change');

	$(document).on('change', '#q_type', function () {
		if ($(this).val() == 'comprehension') {
			//remove and disable others
			$('.not-comprehension').addClass('d-none');
			$('.not-comprehension *').prop('disabled', true);
			//show and enable comprehension
			$('.comprehension').removeClass('d-none');
			$('.comprehension *').prop('disabled', false);
		} else {
			$('.not-comprehension').removeClass('d-none');
			$('.not-comprehension *').prop('disabled', false);
			$('.comprehension').addClass('d-none');
			$('.comprehension *').prop('disabled', true);
			if ($(this).val() == 'multiple_choice') {
				$('.q_mc').removeClass('d-none'); $('.q_mc *').prop('disabled', false);
				$('.q_tf').addClass('d-none'); $('.q_tf *').prop('disabled', true);
				$('.q_sa').addClass('d-none'); $('.q_sa *').prop('disabled', true);
			} else if ($(this).val() == 'true_false') {
				$('.q_mc').addClass('d-none'); $('.q_mc *').prop('disabled', true);
				$('.q_tf').removeClass('d-none'); $('.q_tf *').prop('disabled', false);
				$('.q_sa').addClass('d-none'); $('.q_sa *').prop('disabled', true);
			} else if ($(this).val() == 'short_answer') {
				$('.q_mc').addClass('d-none'); $('.q_mc *').prop('disabled', true);
				$('.q_tf').addClass('d-none'); $('.q_tf *').prop('disabled', true);
				$('.q_sa').removeClass('d-none'); $('.q_sa *').prop('disabled', false);
			} else {
				$('.q_mc').addClass('d-none'); $('.q_mc *').prop('disabled', true);
				$('.q_tf').addClass('d-none'); $('.q_tf *').prop('disabled', true);
				$('.q_sa').addClass('d-none'); $('.q_sa *').prop('disabled', true);
			}
		}

	});

	//add questions to comprehension
	$(document).on('click', '.cqst', function () {
		let unique = Date.now();
		let qstn = '<div class="col-md-12"><div class="callout callout-info"><div class="row"><div class="col-11">';
		qstn += '<div class="form-group"><h6>Question:</h6><textarea data-type="' + $(this).val() + '" id="comp_ques[]" name="comp_ques[]" class="form-control form-control-sm" cols="30" rows="2"></textarea></div>';
		qstn += '<div class="row"><div class="col-md-12"><div class="form-group"><h6 for="">Options: </h6>';
		if ($(this).val() == 'objective') {
			qstn += '<table class="table q_mc">';
			let alpha = ["A", "B", "C", "D"];
			for (i = 0; i < alpha.length; i++) {
				qstn += '<tr><td width="5%">' + alpha[i] + '</td><td width="90%"><input type="text" name="comp_options[]" id="comp_options[]" class="form-control form-control-sm"></td>' +
					'<td width="5%"><input type="radio" name="' + unique + '_comp_q_answermc[]" id="comp_q_answermc[]" value="' + i + '" class="form-control form-control-sm"></td></tr>'
			}
			qstn += '</table>';
		} else if ($(this).val() == 'subjective') {
			qstn += '<table class="table q_sa">' +
				'<tr>' +
				'<td width=""><input type="text" name="comp_q_answersa[]" id="comp_q_answersa[]" class="form-control form-control-sm" required></td>' +
				'</tr>' +
				'</table>';
		} else {
			qstn += '<table class="table q_tf">' +
				'<tr>' +
				'<td width="">True <input type="hidden" name="comp_optiontf[][]" id="comp_optiontf[][]" value="true"></td>' +
				'<td width=""><input type="radio" name="' + unique + '_comp_q_answertf[]" id="comp_q_answertf[]" value="true" class="form-control form-control-sm"></td>' +
				'</tr>' +
				'<tr>' +
				'<td width="">False <input type="hidden" name="comp_optiontf[][]" id="comp_optiontf[][]" value="false"></td>' +
				'<td width=""><input type="radio" name="' + unique + '_comp_q_answertf[]" id="comp_q_answertf[]" value="false" class="form-control form-control-sm"></td>' +
				'</tr>' +
				'</table>';
		}
		qstn += '</div></div></div>';
		qstn += '</div><div class="col-1 text-danger"><i class="fa fa-trash rmq"></i></div></div></div></div>';
		$('.comprehension-questions').append(qstn);
	});

	//remove comprehension question
	$(document).on('click', '.rmq', function () {
		$(this).parents(':eq(2)').remove();
	});

	function chunk(arr, chunkSize) {
		if (chunkSize <= 0) return [];//throw "Invalid chunk size";
		var R = [];
		for (var i = 0, len = arr.length; i < len; i += chunkSize)
			R.push(arr.slice(i, i + chunkSize));
		return R;
	}

	$('#add_question_form').validate({
		ignore: "",
		focusInvalid: false,
		errorClass: "err_msg",
		rules: {},
		messages: {},
		submitHandler: async function (form, event) {
			event.preventDefault();
			//$('.add-question').attr('disabled', true);
			//$('.add-question').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
			let data = {};
			if ($('#q_type').val() == 'comprehension') {//comprehension questions
				let questions = [];
				let mcc = 0;
				let sac = 0;
				let tfc = 0;
				$(document).find("textarea[name='comp_ques[]'").each(function () {
					var qtype = $(this).attr('data-type') == 'objective' ? 'multiple_choice' : ($(this).attr('data-type') == 'subjective' ? 'short_answer' : 'true_false');

					var options = [];
					let answer = '';

					if (qtype == 'multiple_choice') {
						let qo = [];
						$("input[name='comp_options[]']").each(function () {
							qo.push($(this).val());
						});
						let ochunks = chunk(qo, 4);
						options = ochunks[mcc];
						let qa = [];
						$("input[name$='_comp_q_answermc[]']:checked").each(function () {
							qa.push($(this).val());
						});
						answer = qa[mcc];
						mcc++;
					} else if (qtype == 'true_false') {
						let qo = [];
						$("input[name='comp_optionf[]']").each(function () {
							qo.push($(this).val());
						});
						let ochunks = chunk(qo, 4);
						options = ochunks[tfc];
						let qa = [];
						$("input[name$='_comp_q_answertf[]']:checked").each(function () {
							qa.push($(this).val());
						});
						answer = qa[tfc];
						tfc++;
					} else {
						let qa = [];
						$("input[name='comp_q_answersa[]']").each(function () {
							qa.push($(this).val());
						});
						answer = qa[sac];
						sac++;
					}

					let qoo = {
						question: $(this).val(),
						options: options,
						answer: answer,
						type: qtype,
					};

					questions.push(qoo);
				});



				data = {
					session: $('#q_session').val(),
					class: $('#q_class').val(),
					term: $('#q_term').val(),
					assessment: $('#q_assessment').val(),
					subject_id: $('#q_subject').val(),
					topic_id: $('#q_topic').val(),
					type: $('#q_type').val(),
					passage: $('#q_passage').val(),
					questions: questions,
				};
				//console.log(data);
			} else {//not comprehension question
				var options = [];

				if ($('#q_type').val() == 'multiple_choice') {
					$("input[name='options[]']").each(function () {
						options.push($(this).val());
					});
				}
				if ($('#q_type').val() == 'true_false') {
					$("input[name='optiontf[]']").each(function () {
						options.push($(this).val());
					});
				}

				data = {
					session: $('#q_session').val(),
					class: $('#q_class').val(),
					term: $('#q_term').val(),
					assessment: $('#q_assessment').val(),
					subject_id: $('#q_subject').val(),
					topic_id: $('#q_topic').val(),
					type: $('#q_type').val(),
					question: $('#q_question').val(),
					options: options,
					answer: $('#q_type').val() == 'true_false' ? $("input[name='q_answertf']:checked").val() : ($('#q_type').val() == 'short_answer' ? $("#q_answersa").val() : ($('#q_type').val() == 'multiple_choice' ? $("input[name='q_answermc']:checked").val() : "")),
				};
			}


			let response = await fetch(base_url + $('#add_question_form').attr('action'), {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
				},
				body: JSON.stringify(data)
			}).then(function (response) {
				return response.json();
			}).then(function (data) {
				$('.add-question').attr('disabled', false);
				$('.add-question').html('<i class="fa fa-search"></i> Save Question');
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: 'Question Added Successfully.'
					})
					$('#q_question').val("");
					$("#q_answersa").val("");
					$("input[name='options[]'], input[name='optiontf[]']").each(function () {
						$(this).val("");
					});
					$("input:radio").each(function () {
						$(this).prop('checked', false);
					});
					$('#modal-question').modal('hide');
					$('#questions_table').DataTable().ajax.reload();
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


	/*
		EDIT
	*/
	function urldecode(url) {
		return decodeURIComponent(url.replace(/\+/g, ' '));
	}
	$(document).on("click", ".q-edit", async function (e) {
		var data = JSON.parse(urldecode($(this).attr('data')));
		//console.log(data);
		let frm = $('#edit_question_form');
		$('#edit_q_session').val(data.session);
		$('#edit_q_class').val(data.class_name);
		$('#edit_q_term').val(data.term);
		$('#edit_q_assessment').val(data.assessment);
		$('#edit_q_type').val(data.type);
		$('#edit_q_question').val(data.question);
		$('#edit_q_session').val(data.session);
		$('#edit_q_type').trigger('change');
		new Promise(function (resolve, reject) {
			$('#edit_q_class').trigger('change');
			resolve();
		}).then(function (result) {
			$('#edit_q_subject').val(data.subject_id);
		}).then(function (result) {//clear fields
			//multiple choice
			$('[name="edit_options[]"]', frm).each(function (c) {
				$(this).val("")
				$('[name="edit_q_answermc"]', frm).eq(c).removeAttr('checked', '');
			})
			//true false
			$('[name="edit_q_answertf"]', frm).each(function (c) {
				$('[name="edit_q_answertf"]', frm).eq(c).removeAttr('checked', '');
			})
			//short answer
			$('#edit_q_answersa').val("");
		}).then(function (result) {
			if (data.type == 'multiple_choice') {
				$.each(JSON.parse(data.options), function (a, b) {
					$('[name="edit_options[]"]', frm).eq(a).val(b.value);
					if (a == data.answer) {
						$('[name="edit_q_answermc"]', frm).eq(a).attr('checked', 'checked');
					}
				})
			} else if (data.type == 'true_false') {
				$.each(JSON.parse(data.options), function (a, b) {
					//$('[name="edit_optionsf[]"]', frm).eq(a).val(b.value);
					if (b.index == data.answer) {
						$('[name="edit_q_answertf"]', frm).eq(a).attr('checked', 'checked');
					}
				})
			} else if (data.type == 'short_answer') {
				$('#edit_q_answersa').val(data.answer);
			} else { }
		});

		$('#edit_question_form').attr('action', $(this).attr('href'));
	});

	$(document).on('change', '#edit_q_class', async function () {
		await getClassSubjects2Edit();
	});

	async function getClassSubjects2Edit() {
		$.ajax({
			url: encodeURI(base_url + 'get_class_subjects/' + $('#edit_q_class').val()),
			type: 'GET',
			async: false,
			success: function (data) {
				$('#edit_q_subject').empty();
				$.each(JSON.parse(data), function (a, b) {
					$('#edit_q_subject').append(`<option value="${b.id}">${b.name}</option>`)
				});
			},
			error: function (a) {
				console.log(a)
			}
		});
	}



	function getCAsEdit(cs) {
		let cas = 0;
		$.each(schemes, function (a, b) {
			if (cs == b.class) {
				cas = ((b.sharing).split('-')).length - 1;
				return;
			}
		});

		let type = '<option value="exam">Exam</option>';
		for (var p = 0; p < cas; p++) {
			type += `<option value="c.a ${p + 1}">C.A ${p + 1}</option>`;
		}
		type += '<option value="mock">Mock</option>';

		$('#edit_q_type').empty();
		$('#edit_q_type').append(type);
	}

	$(document).on('change', '#edit_q_type', function () {
		if ($(this).val() == 'multiple_choice') {
			$('.edit_q_mc').removeClass('d-none'); $('.edit_q_mc *').prop('disabled', false);
			$('.edit_q_tf').addClass('d-none'); $('.edit_q_tf *').prop('disabled', true);
			$('.edit_q_sa').addClass('d-none'); $('.edit_q_sa *').prop('disabled', true);
		} else if ($(this).val() == 'true_false') {
			$('.edit_q_mc').addClass('d-none'); $('.edit_q_mc *').prop('disabled', true);
			$('.edit_q_tf').removeClass('d-none'); $('.edit_q_tf *').prop('disabled', false);
			$('.edit_q_sa').addClass('d-none'); $('.edit_q_sa *').prop('disabled', true);
		} else if ($(this).val() == 'short_answer') {
			$('.edit_q_mc').addClass('d-none'); $('.edit_q_mc *').prop('disabled', true);
			$('.edit_q_tf').addClass('d-none'); $('.edit_q_tf *').prop('disabled', true);
			$('.edit_q_sa').removeClass('d-none'); $('.edit_q_sa *').prop('disabled', false);
		} else {
			$('.edit_q_mc').addClass('d-none'); $('.edit_q_mc *').prop('disabled', true);
			$('.edit_q_tf').addClass('d-none'); $('.edit_q_tf *').prop('disabled', true);
			$('.edit_q_sa').addClass('d-none'); $('.edit_q_sa *').prop('disabled', true);
		}
	});

	$('#edit_question_form').validate({
		ignore: "",
		focusInvalid: false,
		errorClass: "err_msg",
		rules: {},
		messages: {},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.edit-question').attr('disabled', true);
			$('.edit-question').html('<i class="fa fa-spinner fa-spin"></i> Processing...');

			var options = [];
			var optioni = [];

			if ($('#edit_q_type').val() == 'multiple_choice') {
				$("input[name='edit_options[]']").each(function () {
					options.push($(this).val());
				});
			}
			if ($('#edit_q_type').val() == 'true_false') {
				$("input[name='edit_optiontf[]']").each(function () {
					options.push($(this).val());
				});
			}

			let data = {
				session: $('#edit_q_session').val(),
				class: $('#edit_q_class').val(),
				term: $('#edit_q_term').val(),
				assessment: $('#edit_q_assessment').val(),
				subject_id: $('#edit_q_subject').val(),
				type: $('#edit_q_type').val(),
				question: $('#edit_q_question').val(),
				options: options,
				//options_index: optioni,
				answer: $('#edit_q_type').val() == 'true_false' ? $("input[name='edit_q_answertf']:checked").val() : ($('#edit_q_type').val() == 'short_answer' ? $("#edit_q_answersa").val() : ($('#edit_q_type').val() == 'multiple_choice' ? $("input[name='edit_q_answermc']:checked").val() : "")),
			};
			let response = await fetch(base_url + 'edit_question/' + $('#edit_question_form').attr('action'), {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
				},
				body: JSON.stringify(data)
			}).then(function (response) {
				return response.json();
			}).then(function (data) {
				$('.edit-question').attr('disabled', false);
				$('.edit-question').html('<i class="fa fa-search"></i> Save Question');
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: 'Question Updated Successfully.'
					})
					$('#edit_q_question').val("");
					$("#edit_q_answersa").val("");
					$("input[name='edit_options[]'], input[name='edit_optiontf[]']").each(function () {
						$(this).val("");
					});
					/* $("input:radio").each(function () {
						$(this).prop('checked', false);
					}); */
					$('#modal-question-edit').modal('hide');
					$('#questions_table').DataTable().ajax.reload();
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


	/*	DELETE QUESTION	*/
	$(document).on("click", ".q-del", function (e) {
		e.preventDefault();
		var table = $('#questions_table').DataTable();
		var row = $(this).parents('tr');

		if (confirm('Are you sure you want to delete?')) {
			$.get(base_url + "del_question/" + $(this).attr('href'), function (data) {
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
});
