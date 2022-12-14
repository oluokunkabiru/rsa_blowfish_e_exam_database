$(document).ready(function () {
	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 4000
	});

	$("#topics_table").DataTable({
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
			url: base_url + "get_topics",
			type: 'GET'
		},
		"oSearch": { "bSmart": false, "bRegex": true, "sSearch": "" }
	});

	function getClassSubjects() {
		$.ajax({
			url: encodeURI(base_url + 'get_class_subjects/' + $('#topics_class').val()),
			type: 'GET',
			success: function (data) {
				$('#topics_subject').empty();
				$.each(JSON.parse(data), function (a, b) {
					$('#topics_subject').append(`<option value="${b.id}">${b.name}</option>`)
				});
			},
			error: function (a) {
				console.log(a)
			}
		});
	}

	$('#topics_class').change(function () {
		getClassSubjects();
	});
	$('#topics_class').trigger('change');

	$(document).on('click', '.rm-topic', function () {
		$(this).parent().closest('tr').remove();
	});

	$(document).on('click', '.add-topic', function () {
		$('#topics_add_table tbody').append('<tr>'
			+ '<td>'
			+ '<textarea name="topic[]" id="topic[]" cols="60" rows="1" class="form-control form-control-sm"></textarea>'
			+ '</td>'
			+ '<td><button type="button" class="btn btn-sm btn-danger rm-topic"><i class="fa fa-trash"></i></button></td>'
			+ '</tr>');
	});

	jQuery.validator.addMethod("topicRequired", function (value, elem) {
		// Use the name to get all the inputs and verify them
		var name = elem.name;
		return $('textarea[name="' + name + '"]').map(function (i, obj) { return $(obj).val(); }).get().every(function (v) { return v; });
	}, "This field is required");

	$("#add_topics_form").validate({
		ignore: "",
		focusInvalid: false,
		invalidHandler: function (form, validator) {
			if (!validator.numberOfInvalids())
				return;
			$('#modal-topics').animate({
				scrollTop: $(validator.errorList[0].element).offset().top
			}, 2000);

		},
		errorClass: "err_msg",
		rules: {
			term: "required",
			class: "required",
			subject: "required",
			"topic[]": "topicRequired",// "required",
		},
		messages: {
		},
		submitHandler: async function (form, event) {
			event.preventDefault();
			$('.add-topics').attr('disabled', true);
			$('.add-topics').html('<i class="fa fa-spinner fa-spin"></i> Processing...');

			let data = {
				'term': $('#topics_term').val(),
				'class': $('#topics_class').val(),
				'subject': $('#topics_subject').val(),
				'topics': $('textarea[name="topic[]"]').map(function () { return this.value; }).get(),
			};

			let response = await fetch(base_url + 'add_topics', {
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
						title: 'Topics Added Successfully.'
					})
					$('#add_topics_form').trigger("reset");
					$('#modal-topics').modal('hide');
					$('#topics_table').DataTable().ajax.reload();
				} else if (data.status == 'info') {
					Toast.fire({
						icon: 'info',
						title: 'Topics info: ' + data.message
					})
				} else {
					Toast.fire({
						icon: 'error',
						title: 'Error Adding Topics. ' + data.message
					})
				}
			});
			$('.add-topics').html('<i class="fa fa-save"></i> Save Topics');
			$('.add-topics').attr('disabled', false);
		}
	});

	$(document).on("click", ".tp-del", function (e) {
		e.preventDefault();
		var table = $('#topics_table').DataTable();
		var row = $(this).parents('tr');

		if (confirm('Are you sure you want to delete?')) {
			$.get(base_url + "del_topic/" + $(this).attr('href'), function (data) {
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
