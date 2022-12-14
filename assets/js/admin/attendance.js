$(document).ready(function () {

	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 4000
	});

	$('.daterange').daterangepicker({
		locale: {
			format: 'YYYY-MM-DD'
		}
	});

	$(".date").datepicker({
		format: "yyyy-mm-dd",
	});

	$(document).on('change', '#aa_activity', function () {
		if ($('#aa_activity').val() == 'Add') {
			$('#aa_attendance_form').attr('action', 'get_attendance');
			$('#single_date').removeClass('d-none');
			$('#range_date').addClass('d-none');
		} else {
			$('#aa_attendance_form').attr('action', 'check_attendance');
			$('#range_date').removeClass('d-none');
			$('#single_date').addClass('d-none');
		}
	});

	$('#aa_activity').trigger('change');

	$("#aa_attendance_form").validate({
		ignore: "",
		focusInvalid: false,
		errorClass: "err_msg",
		rules: {},
		messages: {},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.get-attendance').attr('disabled', true);
			$('.get-attendance').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
			let data = {
				activity: $('#aa_activity').val(),
				session: $('#aa_academic_year').val(),
				term: $('#aa_term').val(),
				class: $('#aa_class').val(),
				arm: $('#aa_arm').val(),
				single_date: $('#aa_single_date').val(),
				range_date: $('#aa_range_date').val(),
			};
			let response = await fetch(base_url + $('#aa_attendance_form').attr('action'), {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
				},
				body: JSON.stringify(data)
			}).then(function (response) {
				return response.json();
			}).then(function (data) {
				$('.get-attendance').attr('disabled', false);
				$('.get-attendance').html('<i class="fa fa-search"></i> Get Attendance');
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: 'Attendance Retrieved Successfully.'
					})
					$('#modal-add-attendance').modal('hide');
					if ($('#aa_activity').val() == 'Add')
						renderClassAdd(data.data);
					else renderClassCheck(data.data);
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

	//SHOW CLASS ATTENDANCE
	function renderClassAdd(data) {
		var ls = '<form role="form" action="" method="post" id="add_class_attendance_form" ><div class="row centered">' +
			'<div class="overlay-wrapper" style="width:100%">' +
			'<div class="overlay dark d-none"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>' +
			'<div class="col-md-12">' +
			'<div class="text-center">' +
			'<h4 class="m-0">' + data.data['class'] + ' ' + data.data['arm'] + '</h4>' +
			'<h6>' + '<span class="text-danger">' + data.data['term'] + ' Term</span> | ' + data.data['session'] + '</h6>' +
			'</div>' +
			'<input type="hidden" id="term" name="term" value="' + data.data['term'] + '" >' +
			'<input type="hidden" id="class" name="class" value="' + data.data['class'] + '" >' +
			'<input type="hidden" id="arm" name="arm" value="' + data.data['arm'] + '" >' +
			'<input type="hidden" id="date" name="date" value="' + data.data['date'] + '" >' +
			'<input type="hidden" id="session" name="session" value="' + data.data['session'] + '" >' +
			'<hr>' +
			'<div class="row"><div class="col-md-12 table-responsive">';

		ls += '<table class="table table-bordered text-nowrap">';
		ls += '<thead><tr><th style="width: 5%">#</th><th style="width:40%">Learner</th><th style="width: 55%">' + ((data.data['activity'] == "Add") ? data.data['single_date'] : data.data['range_date']) + '</th></tr></thead><tbody>';

		for (var i = 0; i < data.attendance.length; i++) {
			ls += '<tr><td>' + (i + 1) + '</td><td>' + data.attendance[i]['surname'] + ', ' + data.attendance[i]['firstname'] + ' ' + data.attendance[i]['othername'] + '</td><td class="p-0">' +
				'<input type="hidden" id="students[' + i + '][id]" name="students[' + i + '][id]" value="' + data.attendance[i]['id'] + '" >' +
				'<table class="no-border" width="100%"><tr>' +
				' <td><input type="radio" name="students[' + i + '][attendance]" value="1" ' + (data.attendance[i]['attendance'] == 1 ? 'checked' : '') + ' required /> Present</td>' +
				' <td><input type="radio" name="students[' + i + '][attendance]" value="2" ' + (data.attendance[i]['attendance'] == 2 ? 'checked' : '') + ' required /> Late</td>' +
				' <td><input type="radio" name="students[' + i + '][attendance]" value="3" ' + (data.attendance[i]['attendance'] == 3 ? 'checked' : '') + ' required /> Half Day</td>' +
				' <td><input type="radio" name="students[' + i + '][attendance]" value="0" ' + (data.attendance[i]['attendance'] == "0" ? 'checked' : '') + ' required /> Absent</td></tr></table>' +
				'</td></tr>';
		}

		ls += '</tbody></table>';

		ls += '</div></div>';

		ls += '<hr>' + (data.data['activity'] == "Add" ? '<div class="row p-0 m-0"><div class="col-12 text-center"><button class="btn btn-default add-class-attendance"><i class="fa fa-save"></i> Save Attendance</button></div></div>' : '') +
			'</div>' +
			'</div>' +
			'</div></form>';

		$('.c-body').empty();
		$('.c-body').append(ls);
	}

	$(document).on('click', '.add-class-attendance', async function (e) {
		e.preventDefault();

		var checker = true;
		$('#add_class_attendance_form input[type=radio]').each(function () {
			let name = $(this).attr('name');
			if (!$("input[name='" + name + "']").is(':checked')) {
				checker = false;
				$(this).addClass('is-warning');
				return;
			} else {
				$(this).removeClass('is-warning');
			}
		});

		if (checker == true) {
			$('.overlay.dark').removeClass('d-none');
			let data = $('#add_class_attendance_form').serialize();
			let response = await fetch(base_url + 'add_attendance', {
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
						title: 'Attendance Saved Successfully.'
					});
					let page = $('#add_class_attendance_form').attr('page');
					ldt[page] = $('#add_class_attendance_form').clone();
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
				title: 'Warning: ' + 'Some values are still unchecked!'
			})
		}
	});

	function renderClassCheck(data) {
		var ls = '<form role="form" action="" method="post" id="add_class_attendance_form" ><div class="row centered">' +
			'<div class="overlay-wrapper" style="width:100%">' +
			'<div class="overlay dark d-none"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>' +
			'<div class="col-md-12">' +
			'<div class="text-center">' +
			'<h4 class="m-0">' + data.data['class'] + ' ' + data.data['arm'] + '</h4>' +
			'<h6>' + '<span class="text-danger">' + data.data['term'] + ' Term</span> | ' + data.data['session'] + '</h6>' +
			'</div>' +
			'<input type="hidden" id="term" name="term" value="' + data.data['term'] + '" >' +
			'<input type="hidden" id="class" name="class" value="' + data.data['class'] + '" >' +
			'<input type="hidden" id="arm" name="arm" value="' + data.data['arm'] + '" >' +
			'<input type="hidden" id="date" name="date" value="' + data.data['date'] + '" >' +
			'<input type="hidden" id="session" name="session" value="' + data.data['session'] + '" >' +
			'<hr>' +
			'<div class="row"><div class="col-md-12 table-responsive">';

		ls += '<table class="table table-bordered text-nowrap">';
		ls += '<thead><tr><th>#</th><th>Learner</th>';
		var last_month = last_year = date = '';
		for (var c = 0; c < data.attendate.length; c++) {
			var atd = (data.attendate[c]['date']).split('-');
			if (c == 0) {
				last_year = atd[0];
				last_month = atd[1];
				date = data.attendate[c]['date'];
			} else if (last_year != atd[0]) date = data.attendate[c]['date'];
			else if(last_month != atd[1]){
				last_year = atd[0];
				last_month = atd[1];
				date = atd[0]+'-'+atd[1];
			}else if(last_month == atd[1]){
				last_year = atd[0];
				last_month = atd[1];
				date = atd[2];
			}
			ls += '<th class="text-center">' + date + '</th>';
		}
		ls += '<th class="text-center">Present/Absent/Total</th></tr></thead><tbody>';

		var total = 0;
		for (var i = 0; i < data.learners.length; i++) {
			var present = absent = halfday = late = 0;
			ls += '<tr>'
				+ '<td>' + (i + 1) + '</td>'
				+ '<td>' + data.learners[i]['surname'] + ', ' + data.learners[i]['firstname'] + ' ' + data.learners[i]['othername'] + '</td>';
			for (var v = 0; v < data.attendance.length; v++) {
				if (data.learners[i]['id'] == data.attendance[v]['learner_id']) {
					if (i == 0) total++;
					var fa = '';
					if (data.attendance[v]['attendance'] == 0) {fa = '<i class="fa fa-close" title="Absent"></i>';absent++;}
					if (data.attendance[v]['attendance'] == 1) {fa = '<i class="fa fa-check" title="Present"></i>';present++;}
					if (data.attendance[v]['attendance'] == 2) {fa = '<i class="fa fa-clock-o" title="Late"></i>';present++;}
					if (data.attendance[v]['attendance'] == 3) {fa = '<i class="fa fa-hourglass-half" title="Half Day"></i>';present++;}
					ls += '<td align="center">' + fa + '</td>';
				}
			}
			ls+='<td align="center"><span><small class="badge bg-green">'+present+'</small> / <small class="badge bg-red">'+absent+'</small> / <small class="badge bg-blue">'+total+'</small></span></td>'
			ls += '</tr>'
		}

		ls += '</tbody></table>';

		ls += '</div></div>';

		ls += '</div>' +
			'</div>' +
			'</div></form>';

		$('.c-body').empty();
		$('.c-body').append(ls);
	}

});
