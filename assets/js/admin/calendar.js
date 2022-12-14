$(function () {

	/* initialize the calendar
	 -----------------------------------------------------------------*/
	//Date for the calendar events (dummy data)
	var date = new Date()
	var d = date.getDate(),
		m = date.getMonth(),
		y = date.getFullYear()

	var Calendar = FullCalendar.Calendar;

	var calendarEl = document.getElementById('calendar');

	getEvents();



	function getEvents() {
		$.ajax({
			url: base_url + 'get_calendars',
			dataType: 'json',
			data: {
				// our hypothetical feed requires UNIX timestamps
				start: start_time,
				end: end_time
			},
			success: function (msg) {
				var events = msg.events;
				//callback(events);
				let calendarEvents = [];
				calendarEvents = events;
				//console.log(events)
				var calendar = new Calendar(calendarEl, {
					plugins: ['bootstrap', 'interaction', 'dayGrid', 'timeGrid'],
					header: {
						left: 'prev,next today',
						center: 'title',
						right: 'dayGridMonth,timeGridWeek,timeGridDay'
					},
					'themeSystem': 'bootstrap',
					//Random default events
					events: calendarEvents,
					eventRender: function (event, element) {
						if (event.icon) {
							element.find(".fc-title").prepend("<i class='fa fa-" + event.icon + "'></i>");
						}
					}
				});

				calendar.render();
			}
		});
	}

})
