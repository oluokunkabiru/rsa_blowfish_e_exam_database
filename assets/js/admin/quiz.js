$(document).ready(function () {
	$("#quizFinish").on('click', function () {
		if (confirm('Are you sure you want to END this assessment?')) {
			$('#quiz_form').append('<input type="hidden" name="quizSubmit" value="finish" />');
			$('#quiz_form').submit();
		}
	});

	//var countDownDate = new Date("Jan 5, 2021 15:37:25").getTime();
	var countDownDate = new Date(end_time).getTime();

	// Update the count down every 1 second
	var x = setInterval(function () {

		// Get today's date and time
		var now = new Date().getTime();

		// Find the distance between now and the count down date
		var distance = countDownDate - now;

		// Time calculations for days, hours, minutes and seconds
		var days = Math.floor(distance / (1000 * 60 * 60 * 24));
		var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		var seconds = Math.floor((distance % (1000 * 60)) / 1000);

		// Display the result in the element with id="demo"
		/* document.getElementById("assessment_time").innerHTML = days + "d " + hours + "h "
			+ minutes + "m " + seconds + "s "; */
		document.getElementById("assessment_time").innerHTML = hours + "h "
			+ minutes + "m " + seconds + "s ";

		// If the count down is finished, write some text
		if (distance < 0) {
			clearInterval(x);
			document.getElementById("assessment_time").innerHTML = "EXPIRED";
			$('#quiz_form').append('<input type="hidden" name="quizSubmit" value="finish" />');
			$('#quiz_form').submit();
		}
	}, 1000);
});
