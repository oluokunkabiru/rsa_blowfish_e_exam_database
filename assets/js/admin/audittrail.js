$("#trails_table").DataTable({
	dom: "<'row'<'col-sm-4'l><'col-sm-4'B><'col-sm-4'f>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-5'i><'col-sm-7'p>>",
	buttons: [
		'print', 'copy', 'csv', 'colvis'
	],
	/*"order": [
		[6, "desc"]
	],*/
	"ordering": false,
	"responsive": true,
	"autoWidth": false,
	/* "ajax": {
		url: base_url+"get_audittrail",
		type: 'GET'
	}, */
});
