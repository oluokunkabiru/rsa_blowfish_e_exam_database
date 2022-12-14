$(document).ready(function () {

	const params = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,
width=600,height=300,left=100,top=100`;

	//Initialize Select2 Elements
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

	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 4000
	});

	$("#sales_table").DataTable({
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
			url: base_url + "get_sold_tickets",
			type: 'GET'
		},
		"oSearch": { "bSmart": false, "bRegex": true, "sSearch": "" }
	});
	$("#meals_table").DataTable({
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
			url: base_url + "get_meal_tickets",
			type: 'GET'
		},
		"oSearch": { "bSmart": false, "bRegex": true, "sSearch": "" }
	});

	$(document).on('change keyup keydown', '#buy_quantity', function () {
		$("#buy_amount").val(parseInt($(this).val()) * 500);
	});

	$('#buy_quantity').val(1);
	$('#buy_quantity').trigger('change');

	$('#buy_tickets_form').validate({
		ignore: "",
		focusInvalid: false,
		errorClass: "err_msg",
		rules: {},
		messages: {},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.buy-tickets').attr('disabled', true);
			$('.buy-tickets').html('<i class="fa fa-spinner fa-spin"></i> Processing...');

			let data = {
				learner_id: $('#buy_learner_id').val(),
				amount: $('#buy_amount').val(),
				quantity: $('#buy_quantity').val(),
			};
			let response = await fetch(base_url + $('#buy_tickets_form').attr('action'), {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
				},
				body: JSON.stringify(data)
			}).then(function (response) {
				return response.json();
			}).then(function (data) {
				$('.buy-tickets').attr('disabled', false);
				$('.buy-tickets').html('<i class="fa fa-money"></i> Buy Tickets');
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: 'Tickets bought Successfully.'
					})
					$('#modal-buy').modal('hide');
					$('#sales_table').DataTable().ajax.reload();
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

	$('#print_ticket_form').validate({
		ignore: "",
		focusInvalid: false,
		errorClass: "err_msg",
		rules: {},
		messages: {},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.print-tickets').attr('disabled', true);
			$('.print-tickets').html('<i class="fa fa-spinner fa-spin"></i> Processing...');

			let data = {
				learner_id: $('#learner_id').val()
			};
			let response = await fetch(base_url + $('#print_ticket_form').attr('action'), {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
				},
				body: JSON.stringify(data)
			}).then(function (response) {
				return response.json();
			}).then(function (data) {
				$('.print-tickets').attr('disabled', false);
				$('.print-tickets').html('<i class="fa fa-print"></i> Print Tickets');
				if (data.status == 'success') {
					Toast.fire({
						icon: 'success',
						title: 'Ticket Generated Successful.'
					})
					$('#modal-print').modal('hide');
					$('#meals_table').DataTable().ajax.reload();
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

	$(document).on("click", ".tk-print", function (e) {
		e.preventDefault();

		open(base_url + 'print_ticket_page/' + $(this).attr('href'), 'test', params);
	});

});
