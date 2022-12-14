$(document).ready(function () {
	$('#about_about, #about_vision, #about_mission').summernote({
		height: 100,
		toolbar: [
		  ['style', ['style']],
		  ['font', ['bold', 'italic', 'underline', 'clear', 'superscript', 'subscript']],
		  ['color', ['color']],
		  ['para', ['ol', 'ul', 'paragraph']],
		  ['insert', ['link']],
		  ['view', ['fullscreen', 'help', 'undo', 'redo']],
		]
	});

	$("#name, #address, #email, #email_admission, #email_payment,#about_about,#about_vision,#about_mission, #email_info, #email_result, #email_help, #phone, #bank_charges, #admission_sss,#admission_jss,#bank_charges_result, #admission_pry,#admission_nur,#bank_activity, #bank_sss, #bank_jss,#bank_nur,#bank_pry,#bank_prye, #bank_sss_exam, #bank_jss_exam").on("keydown keyup change", function () {
		$("#school_name").html($('#name').val());
		$("#school_address").html($('#address').val());
		$("#school_email").html($('#email').val());
		$("#school_phone").html($('#phone').val());
		$("#school_bank_charges").html($('#bank_charges').val());
		$("#school_bank_charges_result").html($('#bank_charges_result').val());
		$("#school_admission_sss").html($('#admission_sss').val());
		$("#school_admission_jss").html($('#admission_jss').val());
		$("#school_admission_pry").html($('#admission_pry').val());
		$("#school_admission_nur").html($('#admission_nur').val());
		$("#school_bank_activity").html($('#bank_activity').val());
		$("#school_bank_jss").html($('#bank_jss').val());
		$("#school_bank_sss").html($('#bank_sss').val());
		$("#school_bank_nur").html($('#bank_nur').val());
		$("#school_bank_pry").html($('#bank_pry').val());
		$("#school_bank_prye").html($('#bank_prye').val());
		$("#school_bank_jss_exam").html($('#bank_jss_exam').val());
		$("#school_bank_sss_exam").html($('#bank_sss_exam').val())
		$("#school_email_admission").html($('#email_admission').val())
		$("#school_email_info").html($('#email_info').val())
		$("#school_email_payment").html($('#email_payment').val())
		$("#school_email_result").html($('#email_result').val())
		$("#school_email_help").html($('#email_help').val())
		$("#school_about_about").html($('#about_about').val())
		$("#school_about_vision").html($('#about_vision').val())
		$("#school_about_mission").html($('#about_mission').val())
	});

	$("#name_font, #address_font, #email_font, #phone_font").on("keydown keyup change", function () {
		$("#school_name")[0].style.fontSize = $('#name_font').val() + "px";
		$("#school_address")[0].style.fontSize = $('#address_font').val() + "px";
		$("#school_email")[0].style.fontSize = $('#email_font').val() + "px";
		$("#school_phone")[0].style.fontSize = $('#phone_font').val() + "px";
	});

	$("#footer_text, #footer_url").on("keydown keyup change", function () {
		$("#school_footer_text").html($("#footer_text").val());
		$("#school_footer_url").attr("href", $("#footer_url").val());
	});

	$("#sidebar_text").on("keydown keyup change", function () {
		$("#school_sidebar_text").html($("#sidebar_text").val());
	});

	$("#school_acronym").on("keydown keyup change", function () {
		$("#school_acronym_text").html($("#school_acronym").val());
	});

	$("#default_colour, #sidebar_colour").on("keydown keyup change", function(){
		$("#school_colour_default").css("background", $("#default_colour").val());
		$("#school_colour_sidebar").css("background", $("#sidebar_colour").val());
	});

	/* function updateSettings() {
		uurl = base_url + $('#settings_update_form').attr('action');
		$.ajax({
			url: uurl,
			type: 'get',
			dataType: 'json',
			beforeSend: function(){
				$('#loadingModal').modal({backdrop: 'static', keyboard: false});
			},
			success: function (data){console.log(data);
				//alert(JSON.stringify(data));
				if(data['status'] == 'success')
					toastr.success(data['message'], 'Success:');
				else
					toastr.error(data['message'], 'Error:');
			}
		}).done(function(data){$('#loadingModal').modal('hide');});
	} */
});
