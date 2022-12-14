$(document).ready(function () {

	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 4000
	});

	$(document).on('change', '#ac_by, #ac_class', function () {
		if ($('#ac_by').val() == 'Subject') {
			$('.subject').removeClass('d-none');
			$('#ac_score_form').attr('action', 'get_scores_bysubject');
			$('#scores_title').html('Scores by Subject');
			getClassSubjects();
		} else {
			$('.subject').addClass('d-none');
			$('#ac_score_form').attr('action', 'get_scores_byclass');
			$('#scores_title').html('Scores by Class');
		}
	});

	$('#ac_class').trigger('change');

	async function getClassSubjects() {
		$.ajax({
			url: encodeURI(base_url + 'get_class_subjects/' + $('#ac_class').val()),
			type: 'GET',
			success: function (data) {
				$('#ac_subject').empty();
				$.each(JSON.parse(data), function (a, b) {
					$('#ac_subject').append(`<option value="${b.id}" class="${b.gradable_scorable}">${b.name}</option>`)
				});
			},
			error: function (a) {
				console.log(a)
			}
		});
	}

	$("#ac_score_form").validate({
		ignore: "",
		focusInvalid: false,
		errorClass: "err_msg",
		rules: {},
		messages: {},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.get-scores').attr('disabled', true);
			$('.get-scores').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
			let data = {
				activity: $('#ac_activity').val(),
				session: $('#ac_academic_year').val(),
				term: $('#ac_term').val(),
				by: $('#ac_by').val(),
				class: $('#ac_class').val(),
				arm: $('#ac_arm').val(),
				subject: $('#ac_subject').val(),
				subject_name: $('#ac_subject option:selected').text(),
				subject_gradable: $('#ac_subject option:selected').attr('class'),
			};
			let response = await fetch(base_url + $('#ac_score_form').attr('action'), {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
				},
				body: JSON.stringify(data)
			}).then(function (response) {
				return response.json();
			}).then(function (data) {
				$('.get-scores').attr('disabled', false);
				$('.get-scores').html('<i class="fa fa-search"></i> Get Scores');
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: 'Score Retrieved Successfully.'
					})
					//$('#ac_score_form').trigger("reset");
					$('#modal-add-check').modal('hide');
					if ($('#ac_by').val() == 'Class') renderClass(data.data);
					else renderSubject(data.data);
					//$('#subjects_table').DataTable().ajax.reload();
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

	/* function getScores() {
		$.ajax({
			url: $('#ac_score_form').attr('action'),
			type: 'POST',
			data: $('#ac_score_form').serialize(),
			dataType: 'jsonp',
			success: function (data) {
				console.log(data);
			}
		});
	} */

	function setCurrentLearner(lnr) {
		console.log(lnr)
		$('.c-body').empty();
		$('.c-body').append(ldt[lnr]);
	}


	//BY CLASS
	let ldt;
	function renderClass(data) {
		ldt = [];
		var lf = '<ul class="pagination pagination-sm m-0 float-right">';

		let cognitive = ['BEHAVIOUR', 'PUNCTUALITY', 'POLITENESS', 'NEATNESS', 'OBEDIENCE', 'ATTENTIVENESS', 'RELATIONSHIP WITH PEERS', 'ADJUSTMENT IN SCHOOL', 'RELATIONSHIP WITH STAFF'];

		for (var i = 0; i < data.learners.length; i++) {
			var ls = '<form action="" method="post" id="add_class_scores_form" page="' + i + '"><div class="row centered">' +
				'<div class="overlay-wrapper">' +
				'<div class="overlay dark d-none"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>' +
				'<div class="col-md-12">' +
				'<div class="text-center">' +
				'<h4 class="m-0">' + data.learners[i].surname.toUpperCase() + ', ' + data.learners[i].firstname.toUpperCase() + ' ' + data.learners[i].othername.toUpperCase() + '</h4>' +
				'<h6 class="m-0">' + data.learners[i].regno + ' | ' + data.learners[i].gender + '</h6>' +
				'<h6>' + data.data['class'] + ' ' + data.data['arm'] + ' | <span class="text-danger">' + data.data['term'] + ' Term</span> | ' + data.learners[i].academic_year + '</h6>' +
				'</div>' +
				'<input type="hidden" id="learner_id" name="learner_id" value="' + data.learners[i].id + '" >' +
				'<input type="hidden" id="classroom_id" name="classroom_id" value="' + data.learners[i].class_id + '" >' +
				'<input type="hidden" id="session" name="session" value="' + data.learners[i].academic_year + '" >' +
				'<input type="hidden" id="term" name="term" value="' + data.data['term'] + '" >' +
				'<input type="hidden" id="class" name="class" value="' + data.data['class'] + '" >' +
				'<input type="hidden" id="arm" name="arm" value="' + data.data['arm'] + '" >' +
				'<hr>' +
				'<div class="row">';
			//split and show scores
			var scores = data.learners[i].scores[data.data['term']] ?? [];
			$.each(data.subjects, function (a, b) {
				var score = (scores['s' + b.id] ?? []);
				ls += '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 row form-group pr-5">' +
					'<h6 class="col-4 ">' + b.name + '</h6>';
				if (b.gradable_scorable == 1) {
					$.each(data.scheme.sharing.split('-'), function (c, d) {
						ls += '<input type="' + (data.data['activity'] != 'Add' ? 'text' : 'number') + '" class="col-2 form-control form-control-sm" name="' + data.data['term'] + '[s' + b.id + '][]" id="' + data.data['term'] + '[s' + b.id + '][]" min="0" max="' + d.trim() + '" max-length="2" value="' + (score[c] ?? '') + '" placeholder="' + (data.data['activity'] == 'Add' ? '0 - ' + d.trim() : '') + '" ' + (data.data['activity'] != 'Add' ? 'disabled' : '') + '>';
					});
				} else {
					ls += '<select class="col-4 form-control form-control-sm" name="' + data.data['term'] + '[s' + b.id + '][]" id="' + data.data['term'] + '[s' + b.id + '][]" ' + (data.data['activity'] != 'Add' ? 'disabled' : '') + '>';
					//'<option value="">Select Grade</option><option>A</option><option>B</option><option>C</option><option>D</option><option>E</option><option>F</option>'+
					let ggrades = ['', 'A', 'B', 'C', 'D', 'E', 'F'];
					score[0] = score[0] ?? '';
					$.each(ggrades, function(e, f){
						ls+= '<option '+(score[0]==f ? 'selected':'')+'>'+f+'</option>';
					});
					ls+='</select>';
				}

				ls += '</div>';
			});

			ls += '</div>';
			ls += '<hr>';

			ls += '<div class="row">';
			var cogs = data.learners[i].scores[data.data['term']]['cognitive'] ?? [];
			for (var e = 0; e < cognitive.length; e++) {
				ls += '<div class="col-md-6"><table class="table no-border m-0 p-0"><tr class="bt-0"><td width="50%" class="p-1"><h6>' + cognitive[e] + '</h6></td>';
				for (var k = 0; k < 5; k++) {
					ls += '<td class="p-1"><span> </span><input type="radio" name="' + data.data['term'] + '[cognitive][' + e + ']" value="' + (k + 1) + '" ' + (cogs[e] == (k + 1) ? 'checked' : '') + ' /></td>';
				}
				ls += '</tr></table></div>';
			};
			ls += '<div class="col-md-5"><small><span class="text-danger">[Scale: 1 - 5]</span></small></div>';
			ls += '</div><hr>';

			var attendance = data.learners[i].scores[data.data['term']]['attendance'] ?? [];
			ls += '<div class="row">';
			ls += '<div class="col-md-6"><div class="form-group"><h6>DAYS PRESENT</h6><input type="number" min="0" step="1" class="form-control form-control-sm" name="' + data.data['term'] + '[attendance][days_present]" value="'+(attendance['days_present'] ?? '')+'" /></div></div>'
			ls += '<div class="col-md-6"><div class="form-group"><h6>DAYS ABSENT</h6><input type="number" min="0" step="1" class="form-control form-control-sm" name="' + data.data['term'] + '[attendance][days_absent]" value="'+(attendance['days_absent'] ?? '')+'" /></div></div>'
			ls += '</div><hr>';

			var comment = data.learners[i].scores[data.data['term']]['comment'] ?? [];
			ls += '<div class="row">' +
				'<div class="col-md-6"><div class="form-group"><h6>CLASS TEACHER\'S COMMENT</h6><input type="text" class="form-control form-control-sm" name="' + data.data['term'] + '[comment][teacher]" value="' + (comment['teacher'] ?? '') + '" max="70" /></div></div>' +
				'<div class="col-md-6"><div class="form-group"><h6>HEAD TEACHERS\'S COMMENT</h6><input type="text" class="form-control form-control-sm" name="' + data.data['term'] + '[comment][principal]" value="' + (comment['principal'] ?? '') + '" max="70" /></div></div>' +
				'</div>';

			ls += '<hr>' + (data.data['activity'] == "Add" ? '<div class="row p-0 m-0"><div class="col-12 text-center"><button class="btn btn-default add-class-scores"><i class="fa fa-save"></i> Save</button></div></div>' : '') +
				'</div>' +
				'</div>' +
				'</div></form>';
			if (i == 0) {
				$('.c-body').empty();
				$('.c-body').append(ls);
			}
			lf += '<li class="page-item"><a class="page-link text-danger ' + (i == 0 ? 'bg-danger' : '') + '" href="' + i + '">' + (i + 1) + '</a></li>';
			ldt.push(ls);
		}
		lf += '</ul>';
		$('.c-footer').empty();
		$('.c-footer').append(lf);
	}

	$(document).on('click', '.add-class-scores', async function (e) {
		e.preventDefault();

		var checker = true;
		$('#add_class_scores_form input[type=number]').each(function () {
			if (parseInt($(this).val()) > parseInt($(this).attr('max')) || parseInt($(this).val()) < parseInt($(this).attr('min'))) {
				checker = false;
				$(this).addClass('is-warning');
				return;
			} else {
				$(this).removeClass('is-warning');
			}
		});

		if (checker == true) {
			$('.overlay.dark').removeClass('d-none');
			let data = $('#add_class_scores_form').serialize();
			// console.log(data);
			let response = await fetch(base_url + 'add_scores_byclass', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
				},
				body: data
			}).then(function (response) {
				return response.json();
			}).then(function (data) {
				$('.overlay.dark').addClass('d-none');
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: 'Score Saved Successfully.'
					});
					let page = $('#add_class_scores_form').attr('page');
					ldt[page] = $('#add_class_scores_form').clone();
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
		} else {
			Toast.fire({
				icon: 'warning',
				title: 'Warning: Some values have exceeded the allowed limit!'
			})
		}

	});

	$(document).on('click', '.page-item', function (e) {
		e.preventDefault(); let nb = $(this).children('a').attr('href');
		$('.c-body').empty();
		$('.c-body').append(ldt[nb]);

		var lf = '<ul class="pagination pagination-sm m-0 float-right">';
		for (var i = 0; i < ldt.length; i++) {
			lf += '<li class="page-item"><a class="page-link text-danger ' + (i == nb ? 'bg-danger' : '') + '" href="' + i + '">' + (i + 1) + '</a></li>';
		}
		lf += '</ul>';
		$('.c-footer').empty();
		$('.c-footer').append(lf);
	});

	//BY SUBJECT
	function renderSubject(data) {
		let lsc = '<form action="" method="post" id="add_subject_scores_form">' +
			//'<div class="overlay-wrapper">' +
			//'<div class="overlay dark d-none"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>' +
			'<div class="row centered"><div class="col-md-12">' +

			'<div class="text-center">' +
			'<h3 class="m-0">' + data.data.subject_name + '</h3>' +
			'<h6>' + data.data['class'] + ' ' + data.data['arm'] + ' | <span class="text-danger">' + data.data['term'] + ' Term</span> | ' + data.data['session'] + '</h6>' +
			'</div><hr>' +

			'<input type="hidden" id="session" name="session" value="' + data.data['session'] + '" >' +
			'<input type="hidden" id="term" name="term" value="' + data.data['term'] + '" >' +
			'<input type="hidden" id="class" name="class" value="' + data.data['class'] + '" >' +
			'<input type="hidden" id="arm" name="arm" value="' + data.data['arm'] + '" >' +
			'<input type="hidden" id="subject" name="subject" value="' + data.data['subject'] + '" >' +

			'<div width="100%" class="table-responsive p-0"><table class="table table-bordered text-nowrap">' +
			'<thead>' +
			'<tr>' +
			'<th style="width: 5%">#</th>' +
			'<th style="width: 45%">Name</th>';

			if(data.data.subject_gradable==1){
				for (var x = 0; x < data.scheme.sharing.split('-').length - 1; x++) {
					lsc += '<th class="text-center" style="width: 10%">CA ' + (x + 1) + '</th>';
				};
				lsc += '<th class="text-center" style="width: 10%">Exam</th>' ;
			}else{
				lsc += '<th class="text-center" style="width: 50%">Grade</th>';
			}
			//'<th class="text-center" style="width: 10%">Total</th>' +
			'</tr>' +
			'</thead>' +
			'<tbody>';

		for (var i = 0; i < data.learners.length; i++) {
			lsc += '<tr>' +
				'<td>' + (i + 1) + '</td>' +
				'<td style="vertical-align:middle;">' + data.learners[i].surname.toUpperCase() + ', ' + data.learners[i].firstname.toUpperCase() + ' ' + data.learners[i].othername.toUpperCase() + '</td>';
			var scores = data.learners[i].scores ?? [];
			if(data.data.subject_gradable==1){
				let sum = 0;
				$.each(data.scheme.sharing.split('-'), function (c, d) {
					sum += parseInt(scores[c] != "" && scores[c] != null ? scores[c] : 0);
					lsc += '<td style="padding:.3rem;vertical-align:middle;"><input type="' + (data.data['activity'] != 'Add' ? 'text' : 'number') + '" class="form-control form-control-sm" name="' + data.data['term'] + '[' + data.learners[i].id + '][s' + data.data['subject'] + '][]" id="' + data.data['term'] + '[s' + data.data['subject'] + '][]" min="0" max="' + d.trim() + '" max-length="2" value="' + (scores[c] ?? '') + '" placeholder="' + (data.data['activity'] == 'Add' ? '0 - ' + d.trim() : '') + '" ' + (data.data['activity'] != 'Add' ? 'disabled' : '') + ' ></td>';
				});
			}else{
				lsc += '<td style="vertical-align:middle;"><select class="col-4 form-control form-control-sm" name="' + data.data['term'] + '[' + data.learners[i].id + '][s' + data.data['subject'] + '][]" id="' + data.data['term'] + '[s' + data.data['subject'] + '][]" ' + (data.data['activity'] != 'Add' ? 'disabled' : '') + '>';
				//'<option value="">Select Grade</option><option>A</option><option>B</option><option>C</option><option>D</option><option>E</option><option>F</option>'+
				let ggrades = ['', 'A', 'B', 'C', 'D', 'E', 'F'];
				scores[0] = scores[0] ?? '';
				$.each(ggrades, function(e, f){
					lsc+= '<option '+(scores[0]==f ? 'selected':'')+'>'+f+'</option>';
				});
				lsc+='</select></td>';
			}
			lsc +=//'<td class="text-center"><span id="total">' + sum + '</span></td>' +
				'</tr>';
		};


		lsc += '</tbody>' +
			'</table></div>' +
			(data.data['activity'] == "Add" ? '<div class="row p-0 m-0 pb-3"><div class="col-12 text-center"><button class="btn btn-default add-subject-scores"><i class="fa fa-save"></i> Save</button></div></div>' : '') +
			'</div></div>' +
			'</form>';

		$('.c-body').empty();
		$('.c-body').append(lsc);
		$('.c-footer').empty();
	}

	$(document).on('click', '.add-subject-scores', async function (e) {
		e.preventDefault();

		var checker = true;
		$('#add_subject_scores_form input[type=number]').each(function () {
			if (parseInt($(this).val()) > parseInt($(this).attr('max')) || parseInt($(this).val()) < parseInt($(this).attr('min'))) {
				checker = false;
				$(this).addClass('is-warning');
				return;
			} else {
				$(this).removeClass('is-warning');
			}
		});

		if (checker == true) {
			$('.overlay.dark').removeClass('d-none');
			let data = $('#add_subject_scores_form').serialize();
			let response = await fetch(base_url + 'add_scores_bysubject', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
				},
				body: data
			}).then(function (response) {
				return response.json();
			}).then(function (data) {
				$('.overlay.dark').addClass('d-none');
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: 'Score Saved Successfully.'
					});
					let page = $('#add_subject_scores_form').attr('page');
					ldt[page] = $('#add_subject_scores_form').clone();
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
		} else {
			Toast.fire({
				icon: 'warning',
				title: 'Warning: ' + 'Some values have exceeded the allowed limit!'
			})
		}
	});


});
