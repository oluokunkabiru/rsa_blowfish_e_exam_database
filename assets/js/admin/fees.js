localStorage.clear();
//$("input[data-bootstrap-switch]").each(function() {
//$("#channel").bootstrapSwitch('state', $(this).prop('checked'));
//});
$("#channel").bootstrapSwitch({
	onSwitchChange: function (e) {
		//console.log(e.target.value+' '+$(this).prop('checked'));
		if ($(this).prop('checked') == false) {
			$('#admin_charges').val('0.00');
			if ($('#transaction_reference').val() != '') {
				localStorage.setItem('ts', $('#transaction_reference').val());
			}
			$('#transaction_reference').val(acronym + Math.floor((Math.random() * 100000000) + 1) + 'BF');
		} else {
			$('#admin_charges').val('500.00');
			if (localStorage.getItem('ts') != '') {
				$('#transaction_reference').val(localStorage.getItem('ts'));
			}
		}
		$('#admin_charges').trigger('change');
	}
});
$("#edit_channel").bootstrapSwitch({
	onSwitchChange: function (e) {
		//console.log(e.target.value+' '+$(this).prop('checked'));
		if ($(this).prop('checked') == false) {
			if ($('#edit_transaction_reference').val() != '') {
				localStorage.setItem('ts', $('#edit_transaction_reference').val());
			}
			$('#edit_transaction_reference').val(acronym + Math.floor((Math.random() * 100000000) + 1) + 'BF');
		} else {
			if (localStorage.getItem('ts') != '') {
				$('#edit_transaction_reference').val(localStorage.getItem('ts'));
			}
		}
	}
});

$(function () {
	$("#payments_table").DataTable({
		//dom: 'Blfrtip',//'<"top"i>rt<"bottom"flp><"clear">'
		dom: "<'row'<'col-sm-4'l><'col-sm-4'B><'col-sm-4'f>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-5'i><'col-sm-7'p>>",
		buttons: [
			'print', 'copy', 'csv', 'colvis'
		],
		//buttons: [ 'copy', 'excel', 'pdf', 'colvis' ],
		"order": [
			[0, "asc"]
		],
		"responsive": true,
		"autoWidth": false,
		"ajax": {
			url: base_url + "getFees",
			type: 'GET'
		},
		"oSearch": { "bSmart": false, "bRegex": true, "sSearch": "" }
	});

	$("#discounts_table").DataTable({
		dom: "<'row'<'col-sm-4'l><'col-sm-4'B><'col-sm-4'f>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-5'i><'col-sm-7'p>>",
		buttons: [
			'print', 'copy', 'csv', 'colvis'
		],
		"order": [
			[0, "asc"]
		],
		"responsive": true,
		"autoWidth": false,
		"ajax": {
			url: base_url + "get_discounts",
			type: 'GET'
		},
		"oSearch": { "bSmart": false, "bRegex": true, "sSearch": "" }
	});

	$("#schedules_table").DataTable({
		dom: "<'row'<'col-sm-4'l><'col-sm-4'B>kb<'col-sm-4'f>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-5'i><'col-sm-7'p>>",
		buttons: [
			'print', 'copy', 'csv', 'colvis'
		],
		"order": [
			//[0, "asc"], [1, "asc"],
		],
		"responsive": true,
		"autoWidth": false,
		"ajax": {
			url: base_url + "get_schedules",
			type: 'GET'
		},
		"oSearch": { "bSmart": false, "bRegex": true, "sSearch": "" }
	});
});

//-------------
//- DONUT CHART -
//-------------
// Get context with jQuery - using jQuery's .get() method.


// console.log(cl);
var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
var donutData = {
	labels: cl.split(","),
	datasets: [{
		label: cl.split(","),
		data: cd.split(","),
		backgroundColor: ["#0074D9", "#FF4136", "#2ECC40", "#FF851B", "#7FDBFF", "#B10DC9", "#FFDC00", "#001f3f", "#39CCCC", "#01FF70", "#85144b", "#F012BE", "#3D9970", "#111111", "#AAAAAA"], //['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
	}]
}
var donutOptions = {
	maintainAspectRatio: false,
	responsive: true,
	legend: {
		display: false
	},
	tooltips: {
		callbacks: {
			label: function (tooltipItem, data) {
				var label = data.datasets[tooltipItem.datasetIndex].label[tooltipItem.index] || '';
				if (label) {
					label += ': ₦ ';
				}
				label += (Math.round(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] * 100) / 100).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				let students = "Students: " + cs[tooltipItem.index];
				return [label, students];
			}
		}
	}
}
//Create pie or douhnut chart
// You can switch between pie and douhnut using the method below.
var donutChart = new Chart(donutChartCanvas, {
	type: 'doughnut',
	data: donutData,
	options: donutOptions
})

//-------------
//- BAR CHART -
//-------------

var areaChartData = {
	labels: lb.split(","),
	datasets: [{
		label: new Date().getFullYear(),//'2020',
		backgroundColor: 'rgba(60,141,188,0.9)',
		borderColor: 'rgba(60,141,188,0.8)',
		pointRadius: false,
		pointColor: '#3b8bba',
		pointStrokeColor: 'rgba(60,141,188,1)',
		pointHighlightFill: '#fff',
		pointHighlightStroke: 'rgba(60,141,188,1)',
		data: md.split(",")
	},
		/*{
		  label               : 'Electronics',
		  backgroundColor     : 'rgba(210, 214, 222, 1)',
		  borderColor         : 'rgba(210, 214, 222, 1)',
		  pointRadius         : false,
		  pointColor          : 'rgba(210, 214, 222, 1)',
		  pointStrokeColor    : '#c1c7d1',
		  pointHighlightFill  : '#fff',
		  pointHighlightStroke: 'rgba(220,220,220,1)',
		  data                : [65, 59, 80, 81, 56, 55, 40]
		},*/
	]
}

var barChartCanvas = $('#barChart').get(0).getContext('2d')
var barChartData = jQuery.extend(true, {}, areaChartData)
var temp0 = areaChartData.datasets[0]
//var temp1 = areaChartData.datasets[1]
barChartData.datasets[0] = temp0
//barChartData.datasets[1] = temp1

var barChartOptions = {
	responsive: true,
	maintainAspectRatio: false,
	datasetFill: false,
	scalesShowLabels: true,
	scales: {
		yAxes: [{
			ticks: {
				callback: function (value, index, values) {
					return '₦' + Number(value).toLocaleString();
				}
			}
		}],
	},
	tooltips: {
		callbacks: {
			label: function (tooltipItem, data) {
				var label = data.datasets[tooltipItem.datasetIndex].label || '';

				if (label) {
					label += ': ₦ ';
				}
				label += (Math.round(tooltipItem.yLabel * 100) / 100).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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

$("#year").datepicker({
	format: "yyyy",
	viewMode: "years",
	minViewMode: "years"
});

$("#created_at").datetimepicker({
	format: "YYYY-MM-DD HH:mm:ss"
});

const Toast = Swal.mixin({
	toast: true,
	position: 'top-end',
	showConfirmButton: false,
	timer: 3000
});

var tagCheckRE = new RegExp("(\\w+)(\\s+)(\\w+)");
jQuery.validator.addMethod("tagcheck", function (value, element) {
	//return tagCheckRE.test(value);
	if (tagCheckRE.test(value))
		return true;
	else return false;
}, "At least two words.");

$("#add_payment_form").validate({
	errorClass: "err_msg",
	rules: {
		payer: {
			required: true,
			tagcheck: true,
		},
		email: {
			required: true,
			email: true,
		},
		phone: "required",
		/* surname: "required",
		firstname: "required", */
		payment_learner_id: "required",
		year: {
			required: true,
			minlength: 4,
			maxlength: 4,
		},
		activity_fees: "required",
		admin_charges: "required",
		school_fees: "required",
		fee_discount: "required",
		discount_percent: "required",
		transaction_reference: "required",
		total: "required",
		created_at: "required",
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
		/* surname: "Please enter student's surname",
		firstname: "Please enter student's first name", */
		payment_learner_id: "Please select a Learner",
		year: "Please provide a year",
		fee: "Fee is required",
	},
	submitHandler: async function (form, event) {
		event.preventDefault();
		$('.add-payment').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
		$('.add-payment').attr('disabled', true);
		let data = {
			'payer': $('#payer').val(),
			'student': $('#payment_learner_id option:selected').text(),//$('#firstname').val() + ' ' + $('#surname').val(),
			'learner_id': $('#payment_learner_id').val(),
			'class': $('#class').val(),
			'year': $('#year').val(),
			'term': $('#term').val() + ' Term',
			'school_fees': $('#school_fees').val(),
			'activity_fees': $('#activity_fees').val(),
			'admin_charges': $('#admin_charges').val(),
			'fee_discount': $('#fee_discount').val(),
			'discount_percent': $('#discount_percent').val(),
			'total': $('#total').val(),
			'email': $('#email').val(),
			'phone': $('#phone').val(),
			'transaction_reference': $('#transaction_reference').val(),
			'created_at': $('#created_at').val(),
			'admin_add': true,
			'user_id': id,
			'channel': $('#channel').prop('checked') == true ? 'web' : 'bank',
			'discount_percent': $('#discount_percent').val(),
		};

		let response = await fetch(base_url + 'fees/details', {
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
					title: 'Fee Payment Added.'
				})
				$('#add_payment_form').trigger("reset");
				$('#modal-payment').modal('hide');
				$('#payments_table').DataTable().ajax.reload();
			} else {
				Toast.fire({
					icon: 'error',
					title: 'Error Adding Payment. ' + data.message
				})
			}
		});
		$('.add-payment').html('<i class="fa fa-save"></i> Save Payment');
		$('.add-payment').attr('disabled', false);
	}
});

$("#add_discount_form").validate({
	errorClass: "err_msg",
	rules: {
		/* discount_surname: "required",
		discount_firstname: "required", */
		discount_learner_id: "required",
		//discount_othername: "required",
		discount_discount: "required",
	},
	messages: {
		/* discount_surname: "Please provide your surname",
		discount_firstname: "Please provide your firstname", */
		discount_firstname: "Please select a learner",
		//othername: "Please provide your othername"
		discount_discount: "Please provide discount percentage",
	},
	submitHandler: async function (form, event) {
		event.preventDefault();
		$('.add-discount').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
		$('.add-discount').attr('disabled', true);
		let data = {
			'learner_id': $('#discount_learner_id').val(),
			'discount': $('#discount_discount').val()
		};
		let response = await fetch(base_url + 'add_discount', {
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
					title: 'Fee Discount Added.'
				})
				$('#add_discount_form').trigger("reset");
				$('#modal-discount').modal('hide');
				$('#discounts_table').DataTable().ajax.reload();
			} else {
				Toast.fire({
					icon: 'error',
					title: 'Error Adding Discount. ' + data.message
				})
			}
		});
		$('.add-discount').html('<i class="fa fa-save"></i> Save Discount');
		$('.add-discount').attr('disabled', false);
	}
});



$("#add_schedule_form").validate({
	errorClass: "err_msg",
	rules: {
		schedule_class: "required",
		schedule_term: "required",
		schedule_amount: "required",
		schedule_type:	"required",
	},
	messages: {
		schedule_class: "Please select a Class",
		schedule_term: "Please a term",
		schedule_amount: "Please provide an amount",
		schedule_type: "Please specify schedule type e.g. tuition",
	},
	submitHandler: async function (form, event) {
		event.preventDefault();
		$('.add-discount').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
		$('.add-discount').attr('disabled', true);
		let data = {
			'class': $('#schedule_class').val(),
			'term': $('#schedule_term').val(),
			'type' : $("#schedule_type").val(),
			'amount': $('#schedule_amount').val(),

			
		};
		let response = await fetch(base_url + 'add_schedules', {
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
					title: 'Fee Schedule Added.'
				})
				$('#add_schedule_form').trigger("reset");
				$('#modal-schedule').modal('hide');
				$('#schedules_table').DataTable().ajax.reload();
			} else {
				Toast.fire({
					icon: 'error',
					title: 'Error Adding Schedule. ' + data.message
				})
			}
		});
		$('.add-schedule').html('<i class="fa fa-save"></i> Save Schedule');
		$('.add-schedule').attr('disabled', false);
	}
});

$("#edit_payment_form").validate({
	errorClass: "err_msg",
	rules: {
		edit_payer: {
			required: true,
			tagcheck: true,
		},
		edit_email: {
			required: true,
			email: true,
		},
		edit_phone: "required",
		edit_learnername: "required",
		edit_year: {
			required: true,
			minlength: 4,
			maxlength: 4,
		},
		edit_activity_fees: "required",
		edit_admin_charges: "required",
		edit_school_fees: "required",
		edit_fee_discount: "required",
		edit_discount_percent: "required",
		edit_transaction_reference: "required",
		edit_total: "required",
		edit_created_at: "required",
	},
	messages: {
		edit_fullname: {
			required: "Please enter your fullname",
			tagcheck: "Please enter surname and firstname",
		},
		edit_email: {
			required: "Please provide an email",
			email: "Please enter a valid email",
		},
		edit_phone: "Please provide a valid phone number",
		edit_learnername: "Please enter student's fullname",
		edit_year: "Please provide a year",
		edit_fee: "Fee is required",
	},
	submitHandler: async function (form, event) {
		event.preventDefault();
		$('.edit-payment').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
		$('.edit-payment').attr('disabled', true);
		let data = {
			'payer': $('#edit_payer').val().trim(),
			'student': $('#edit_learnername').val().trim(),
			'class': $('#edit_class').val(),
			'year': $('#edit_year').val(),
			'term': $('#edit_term').val() + ' Term',
			'school_fees': $('#edit_school_fees').val(),
			'activity_fees': $('#edit_activity_fees').val(),
			'admin_charges': $('#edit_admin_charges').val(),
			'fee_discount': $('#edit_fee_discount').val(),
			'discount_percent': $('#edit_discount_percent').val(),
			'total': $('#edit_total').val(),
			'email': $('#edit_email').val().trim(),
			'phone': $('#edit_phone').val().trim(),
			'transaction_reference': $('#edit_transaction_reference').val(),
			'created_at': $('#edit_created_at').val(),
			'admin_add': true,
			'user_id': id,
			'channel': $('#edit_channel').prop('checked') == true ? 'web' : 'bank',
			'learner_id': $('#learner_id').val()
		};

		let response = await fetch(base_url + 'edit_fee/' + $('#edit_payment_form').attr('action'), {
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
					title: 'Fee Payment Updated.'
				})
				$('#edit_payment_form').trigger("reset");
				$('#modal-payment-edit').modal('hide');
				$('#payments_table').DataTable().ajax.reload();
			} else if (data.status == 'info') {
				Toast.fire({
					icon: 'info',
					title: 'Info: ' + data.message
				})
			} else {
				Toast.fire({
					icon: 'error',
					title: 'Error Updating Payment: ' + data.message
				})
			}
		});
		$('.edit-payment').html('<i class="fa fa-save"></i> Update Payment');
		$('.edit-payment').attr('disabled', false);
	}
});

$("#edit_discount_form").validate({
	errorClass: "err_msg",
	rules: {
		edit_discount_surname: "required",
		edit_discount_firstname: "required",
		//discount_othername: "required",
		edit_discount_discount: "required",
	},
	messages: {
		edit_discount_surname: "Please provide your surname",
		edit_discount_firstname: "Please provide your firstname",
		//othername: "Please provide your othername"
		edit_discount_discount: "Please provide discount percentage",
	},
	submitHandler: async function (form, event) {
		event.preventDefault();
		$('.edit-discount').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
		$('.edit-discount').attr('disabled', true);
		let data = {
			'discount': $('#edit_discount_discount').val(),
			'learner_id': $('#edit_discount_learner_id').val()
		};
		let response = await fetch(base_url + 'edit_discount/' + $('#edit_discount_form').attr('action'), {
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
					title: 'Fee Discount Updated.'
				})
				$('#edit_discount_form').trigger("reset");
				$('#modal-discount-edit').modal('hide');
				$('#discounts_table').DataTable().ajax.reload();
			} else {
				Toast.fire({
					icon: 'error',
					title: 'Error Updating Discount. ' + data.message
				})
			}
		});
		$('.edit-discount').html('<i class="fa fa-save"></i> Save Discount');
		$('.edit-discount').attr('disabled', false);
	}
});

$("#edit_schedule_form").validate({
	errorClass: "err_msg",
	rules: {
		edit_schedule_class: "required",
		edit_schedule_term: "required",
		edit_schedule_amount: "required",
	},
	messages: {
		edit_schedule_class: "Please select a Class",
		edit_schedule_term: "Please a term",
		edit_schedule_amount: "Please provide an amount",
	},
	submitHandler: async function (form, event) {
		event.preventDefault();
		$('.edit-discount').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
		$('.edit-discount').attr('disabled', true);
		let data = {
			'class': $('#edit_schedule_class').val(),
			'term': $('#edit_schedule_term').val(),
			'amount': $('#edit_schedule_amount').val()
		};
		let response = await fetch(base_url + 'edit_schedule/' + $('#edit_schedule_form').attr('action'), {
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
					title: 'Fee Schedule Updated.'
				})
				$('#edit_schedule_form').trigger("reset");
				$('#modal-schedule-edit').modal('hide');
				$('#schedules_table').DataTable().ajax.reload();
			} else {
				Toast.fire({
					icon: 'error',
					title: 'Error Updating Schedule. ' + data.message
				})
			}
		});
		$('.edit-schedule').html('<i class="fa fa-save"></i> Save Schedule');
		$('.edit-schedule').attr('disabled', false);
	}
});

function urldecode(url) {
	return decodeURIComponent(url.replace(/\+/g, ' '));
}

$(document).on("click", ".p-edit", function (e) {
	var data = JSON.parse(urldecode($(this).attr('data')));
	$('#edit_payer').val(data.payer);
	$('#edit_learnername').val(data.student.replace(/\s\s+/g, ' '));
	$('#edit_class').val(data.class);
	$('#edit_year').val(data.year);
	$('#edit_term').val(data.term.split(' ')[0]);
	$('#edit_school_fees').val(parseFloat(data.school_fees.replace(/,/g, "")).toFixed(2));
	$('#edit_activity_fees').val(parseFloat(data.activity_fees.replace(/,/g, "")).toFixed(2));
	$('#edit_admin_charges').val(data.admin_charges);
	$('#edit_fee_discount').val((data.fee_discount == null || data.fee_discount == 0.00 || data.fee_discount == '') ? 0 : parseFloat(data.fee_discount.replace(/,/g, "")).toFixed(2));
	$('#edit_total').val(parseFloat(data.total.replace(/,/g, "")).toFixed(2));
	$('#edit_email').val(data.email);
	$('#edit_phone').val(data.phone);
	$('#edit_transaction_reference').val(data.transaction_reference); $('#edit_transaction_reference').attr('disabled', true);
	$('#edit_created_at').val(data.created_at);
	if (data.channel == 'web') $('#edit_created_at').attr('disabled', true);
	$('#edit_channel').val(data.channel); $('#edit_channel').bootstrapSwitch('disabled', true);
	$('#edit_payment_form').attr('action', $(this).attr('href'));
	$('#edit_dp').html(data.discount_percent);
	$('#edit_discount_percent').val(data.discount_percent);
});

$(document).on("click", ".d-edit", function (e) {
	var data = JSON.parse(urldecode($(this).attr('data')));
	$('#edit_discount_learner').val(data.surname + ', ' + data.firstname + ' ' + data.othername);
	$('#edit_discount_learner_id').val(data.learner_id);
	$('#edit_discount_discount').val(data.discount);
	$('#edit_discount_form').attr('action', $(this).attr('href'));
});

$(document).on("click", ".s-edit", function (e) {
	console.log(urldecode($(this).attr('data')))
	var data = JSON.parse(urldecode($(this).attr('data')));
	console.log(data.class)
	$('#edit_schedule_class').val(data.class);
	$('#edit_schedule_term').val(data.term);
	$('#edit_schedule_amount').val(data.amount.split('.')[0]);
	$('#edit_schedule_form').attr('action', $(this).attr('href'));
});

$(document).on("click", ".d-del", function (e) {
	e.preventDefault();
	var table = $('#discounts_table').DataTable();
	var row = $(this).parents('tr');

	if (confirm('Are you sure you want to delete?')) {
		$.get(base_url + "del_discount/" + $(this).attr('href'), function (data) {
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

$(document).on("click", ".s-del", function (e) {
	e.preventDefault();
	var table = $('#schedules_table').DataTable();
	var row = $(this).parents('tr');

	if (confirm('Are you sure you want to delete?')) {
		$.get(base_url + "del_schedule/" + $(this).attr('href'), function (data) {
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

$('#admin_charges').val(charges);
$('#activity_fees').val(activity);
$('#fee_discount').val('0.00');
$('#dp').html('');

$(document).on('change keyup', '#firstname, #surname', function () {
	//getDiscount();
	$('#class').trigger('change');
});

function getDiscount() {
	/* let fn = $('#firstname').val().toLowerCase();
	let sn = $('#surname').val().toLowerCase();
	if (fn != '' && sn != '') {*/
	let ln = $('#payment_learner_id').val();
	$('#dp').html('');
	$('#fee_discount').val('0.00');
	$.each(dcs, function (a, b) {
		//if (fn === b.firstname.toLowerCase() && sn === b.surname.toLowerCase()) {
		if (ln == b.learner_id) {
			$('#dp').html(b.discount);
			$('#fee_discount').val(Math.round(parseFloat(b.discount / 100) * parseFloat($('#school_fees').val())));
			$('#discount_percent').val(parseFloat(b.discount).toFixed(2));
			return;
		}
	})
	/*} */
}

$(document).on('change keyup', '#class, #term, #admin_charges, #payment_learner_id', function () {
	let cls = $('#class').val();
	let term = $('#term').val();
	//$('#firstname').trigger('change');
	//setTimeout(function(){}, 500);
	$.each(scs, function (a, b) {
		if (cls == b.class && term == b.term) {
			$('#school_fees').val(b.amount);
			getDiscount();
			let discount = parseFloat($('#fee_discount').val());
			$('#total').val((parseFloat($('#admin_charges').val()) + parseFloat($('#activity_fees').val()) + parseFloat($('#school_fees').val()) - parseFloat(discount)).toFixed(2));
			return;
		}
	});
});

$('#class').trigger('change');

//Initialize Select2 Elements
// $(".userSelect").select2();
$('.select2').select2({
	ajax: {
		minimumInputLength: 2,
		url: base_url + 'search_learners',
		dataType: 'json',
		type: "GET",
		data: function (params) {
			return {
				term: params.term,
				//type: 'public'
			}
		},
		processResults: function (data) {
			return {
				results: $.map(data, function (item) {
					return {
						text: item.name,
						id: item.id
					}
				})
			};
		}
	}
})
