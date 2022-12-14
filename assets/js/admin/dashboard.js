$(document).ready(function () {
	//var countDownDate = new Date("Jan 5, 2021 15:37:25").getTime();
	var countDownDate = new Date("Oct 20, 2021").getTime();

	// Update the count down every 1 second
	var x = setInterval(function () {

		// Get today's date and time
		var now = new Date().getTime();

		// Find the distance between now and the count down date
		var distance = countDownDate - now;

		// Time calculations for days, hours, minutes and seconds
		var weeks = Math.floor(distance / (1000 * 60 * 60 * 24 * 7));
		var days = Math.floor(distance / (1000 * 60 * 60 * 24));
		var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		var seconds = Math.floor((distance % (1000 * 60)) / 1000);

		// Display the result in the element with id="demo"
		/* document.getElementById("assessment_time").innerHTML = days + "d " + hours + "h "
			+ minutes + "m " + seconds + "s "; */
		document.getElementById("anniversary_time").innerHTML = '<span style="border: solid 1px #fff; border-radius: 2px; padding: 2px;"><strong>' 
		+ ((String(weeks).length > 1) ? weeks : '0'+weeks) + '</strong> | Weeks</span> : <span style="border: solid 1px #fff; border-radius: 2px; padding: 2px;"><strong>' 
		+ ((String(hours).length > 1) ? hours : '0'+hours) + '</strong> | Hours</span> : <span style="border: solid 1px #fff; border-radius: 2px; padding: 2px;"><strong>'
			+ ((String(minutes).length > 1) ? minutes : '0'+minutes) + '</strong> | Minutes</span> : <span style="border: solid 1px #fff; border-radius: 2px; padding: 2px;"><strong>' 
			+ ((String(seconds).length > 1) ? seconds : '0'+seconds) + '</strong> | Seconds</span> to ' 
			+ acronym + ' Anniversary';

		// If the count down is finished, write some text
		if (distance < 0) {
			clearInterval(x);
			document.getElementById("anniversary_time").innerHTML = "";
		}
	}, 1000);





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

if (ld_data != null && ld_data != "") {
	var areaChartData = {
		labels: ld_data.labels,//['PRI1', 'JSS1', 'JSS2', 'JSS3', 'SS1', 'SS2', 'SS3'],//lb.split(","),
		datasets: [{
			label: 'BOYS',
			backgroundColor: 'rgba(60,141,188,0.9)',
			borderColor: 'rgba(60,141,188,0.8)',
			pointRadius: false,
			pointColor: '#3b8bba',
			pointStrokeColor: 'rgba(60,141,188,1)',
			pointHighlightFill: '#fff',
			pointHighlightStroke: 'rgba(60,141,188,1)',
			data: ld_data.males//[65, 59, 80, 81, 56, 55, 40].reverse()//md.split(",")
		},
		{
			label: 'GIRLS',
			backgroundColor: 'rgba(210, 214, 222, 1)',
			borderColor: 'rgba(210, 214, 222, 1)',
			pointRadius: false,
			pointColor: 'rgba(210, 214, 222, 1)',
			pointStrokeColor: '#c1c7d1',
			pointHighlightFill: '#fff',
			pointHighlightStroke: 'rgba(220,220,220,1)',
			data: ld_data.females//[65, 59, 80, 81, 56, 55, 40]
		},
		]
	}

	var barChartCanvas = $('#barChart').get(0).getContext('2d')
	var barChartData = jQuery.extend(true, {}, areaChartData)
	var temp0 = areaChartData.datasets[0]
	var temp1 = areaChartData.datasets[1]
	barChartData.datasets[0] = temp0
	barChartData.datasets[1] = temp1

	var barChartOptions = {
		responsive: true,
		maintainAspectRatio: false,
		datasetFill: false,
		scalesShowLabels: true,
		scales: {
			yAxes: [{
				ticks: {
					beginAtZero: true,
					/* callback: function (value, index, values) {
						return '₦' + Number(value).toLocaleString();
					} */
				}
			}],
		},
		tooltips: {
			callbacks: {
				label: function (tooltipItem, data) {
					var label = data.datasets[tooltipItem.datasetIndex].label || '';

					if (label) {
						label += ': ';//': ₦ ';
					}
					label += tooltipItem.yLabel;//(Math.round(tooltipItem.yLabel * 100) / 100).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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

}








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

var barChartCanvas = $('#barCharts').get(0).getContext('2d')
var barChartData = jQuery.extend(true, {}, areaChartData)
var temp0 = areaChartData.datasets[0]
// console.log(areaChartData);
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

var barChar = new Chart(barChartCanvas, {
	type: 'bar',
	data: barChartData,
	options: barChartOptions
})




function populate_stats(ldata) {
	if (ld_data != null && ld_data != "") {
		barChart.config.data.labels = ldata.learner_distribution.labels;
		barChart.config.data.datasets[0].data = ldata.learner_distribution.males;
		barChart.config.data.datasets[1].data = ldata.learner_distribution.females;
		barChart.update();

		$('#nl_diff, #rl_diff, #tl_diff, #ttl_diff').removeClass('text-success text-danger text-warning');
		$('#nl_diff, #rl_diff, #tl_diff, #ttl_diff').each(function () {
			$(this).addClass(ldata[$(this).attr('id')] > 0 ? 'text-success' : (ldata[$(this).attr('id')] < 0 ? 'text-danger' : 'text-warning'))
			$(this).html('<i class="fas ' + (ldata[$(this).attr('id')] > 0 ? 'fa-caret-up' : (ldata[$(this).attr('id')] < 0 ? 'fa-caret-down' : 'fa-caret-left')) + '"></i> ' + ldata[$(this).attr('id')] + '%');
		})

		$('#new_learners_this_year').html(ldata.new_learners_this_year);
		$('#returning_learners_this_year').html(ldata.returning_learners_this_year);
		$('#transfered_learners_this_year').html(ldata.transfered_learners_this_year);
		$('#total_learners_this_year').html(ldata.total_learners_this_year);
	}
}

// populate_stats(ld_data);




});
