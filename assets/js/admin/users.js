const Toast = Swal.mixin({
	toast: true,
	position: 'top-end',
	showConfirmButton: false,
	timer: 3000
});

$("#users_table").DataTable({
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
		url: base_url+"get_users",
		type: 'GET'
	},
});

$("#add_user_form").validate({
	errorClass: "err_msg",
	rules: {
		first_name: "required",
		last_name: "required",
		email: "required",
		phone: "required",
		gender: "required",
		role: "required",
		status: "required",
		password: {
			minlength: 5,
			required: true
		},
		cpassword: {
			minlength: 5,
			required: true,
			equalTo: "#password"
		}
	},
	messages: {
		first_name: "Enter first name",
		last_name: "Enter last name",
		email: "Provide an email",
		phone: "Provide a phone number",
		gender: "Select gender",
		role: "Assign a role",
		status: "Select Account Status",
		password: "Provide a password",
		cpassword: {
			required: "Provide password confirmation",
			equalTo: "Must be same as Password"
		}
	},
	submitHandler: async function(form, event) {
		event.preventDefault();
		$('.add-user').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
		$('.add-user').attr('disabled', true);
		let data = {
			'first_name': $('#first_name').val(),
			'last_name': $('#last_name').val(),
			'email': $('#email').val(),
			'phone': $('#phone').val(),
			'gender': $('#gender').val(),
			'role_id': $('#role').val(),
			'status': $('#status').val(),
			'password': $('#password').val(),
			'cpassword': $('#cpassword').val()
		};
		let response = await fetch(base_url + 'add_user', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
			},
			body: JSON.stringify(data)
		}).then(function(response) {
			return response.json();
		}).then(function(data) {
			if (data.status == 'success') {
				Toast.fire({
					icon: 'success',
					title: 'User Added.'
				})
				$('#add_user_form').trigger("reset");
				$('#modal-user').modal('hide');
				$('#users_table').DataTable().ajax.reload();
			} else {
				Toast.fire({
					icon: 'error',
					title: 'Error Adding User. ' + data.message
				})
			}
		});
		$('.add-user').html('<i class="fa fa-save"></i> Save User');
		$('.add-user').attr('disabled', false);
	}
});

$("#edit_user_form").validate({
	errorClass: "err_msg",
	rules: {
		first_name: "required",
		last_name: "required",
		email: "required",
		phone: "required",
		gender: "required",
		role: "required",
		edit_status: "required",
		password: {
			//minlength: 5,
			//required: true
		},
		cpassword: {
			//minlength: 5,
			//required: true,
			equalTo: "#password"
		}
	},
	messages: {
		first_name: "Enter first name",
		last_name: "Enter last name",
		email: "Provide an email",
		phone: "Provide a phone number",
		gender: "Select gender",
		role: "Assign a role",
		edit_status: "Select a status",
		password: "Provide a password",
		cpassword: {
			required: "Provide password confirmation",
			equalTo: "Must be same as Password"
		}
	},
	submitHandler: async function(form, event) {
		event.preventDefault();
		//$('.edit-user').html('<i class="fa fa-spinner fa-spin"></i> Processing...');
		//$('.edit-user').attr('disabled', true);
		let data = {
			'first_name': $('#edit_first_name').val(),
			'last_name': $('#edit_last_name').val(),
			'email': $('#edit_email').val(),
			'phone': $('#edit_phone').val(),
			'gender': $('#edit_gender').val(),
			'role_id': $('#edit_role').val(),
			'status': $('#edit_status').val(),
			'password': $('#edit_password').val(),
			'cpassword': $('#edit_cpassword').val()
		};
		let response = await fetch(base_url + 'edit_user/' + $('#edit_user_form').attr('action'), {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
			},
			body: JSON.stringify(data)
		}).then(function(response) {
			return response.json();
		}).then(function(data) {
			if (data.status == 'success') {
				Toast.fire({
					icon: 'success',
					title: 'User Updated.'
				})
				$('#edit_user_form').trigger("reset");
				$('#modal-user-edit').modal('hide');
				$('#users_table').DataTable().ajax.reload();
			} else {
				Toast.fire({
					icon: 'error',
					title: 'Error Updating User. ' + data.message
				})
			}
		});
		$('.edit-user').html('<i class="fa fa-save"></i> Update User');
		$('.edit-user').attr('disabled', false);
	}
});


function urldecode(url) {
	return decodeURIComponent(url.replace(/\+/g, ' '));
}

$(document).on("click", ".u-edit", function (e) {
	var data = JSON.parse(urldecode($(this).attr('data')));
	$('#edit_first_name').val(data.first_name);
	$('#edit_last_name').val(data.last_name);
	$('#edit_email').val(data.email);
	$('#edit_phone').val(data.phone);
	$('#edit_gender').val(data.gender);
	$('#edit_role').val(data.role_id);
	$('#edit_status').val(data.status);
	$('#edit_user_form').attr('action', $(this).attr('href'));
});

$(document).on("click", ".u-del", function (e) {
	e.preventDefault();
	var table = $('#users_table').DataTable();
	var row = $(this).parents('tr');

	if (confirm('Are you sure you want to delete?')) {
		$.get(base_url + "del_user/" + $(this).attr('href'), function (data) {
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
