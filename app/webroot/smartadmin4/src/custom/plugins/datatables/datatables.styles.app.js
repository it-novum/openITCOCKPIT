/* Set the defaults for DataTables extended classes */
$.extend(true, $.fn.dataTableExt.oStdClasses, {
	"sFilterInput": "form-control border-top-left-radius-0 border-bottom-left-radius-0 ml-0 width-lg shadow-inset-1",
	"sLengthSelect": "form-control custom-select"
});	

/* Set the defaults for DataTables initialisation */
$.extend(true, $.fn.dataTable.defaults, {
	/*	--- Layout Structure 
		--- Options
		l	-	length changing input control
		f	-	filtering input
		t	-	The table!
		i	-	Table information summary
		p	-	pagination control
		r	-	processing display element
		B	-	buttons
		R	-	ColReorder
		S	-	Select

		--- Markup
		< and >				- div element
		<"class" and >		- div with a class
		<"#id" and >		- div with an ID
		<"#id.class" and >	- div with an ID and a class

		--- Further reading
		https://datatables.net/reference/option/dom
		--------------------------------------
	*/
	dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'l>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
	lengthMenu: [[10, 15, 25, 50, 100, -1], [10, 15, 25, 50, 100, "All"]],	
	language: {
		/* change the default text for 'next' and 'previous' with icons */
		paginate: {
			previous: "<i class='fal fa-chevron-left'></i>",
			next: "<i class='fal fa-chevron-right'></i>"
		},
		processing: '<div class="d-flex align-items-center justify-content-center fs-lg"><div class="spinner-border spinner-border-sm text-primary mr-2" role="status"><span class="sr-only"> Loading...</span></div> Processing...</div>',
		/* replace the default search lable text with a nice icon */
		search: '<div class="input-group-text d-inline-flex width-3 align-items-center justify-content-center border-bottom-right-radius-0 border-top-right-radius-0 border-right-0"><i class="fal fa-search"></i></div>',
		/* add search filter */
		searchPlaceholder: "Search",
		/* change text for zero records */
		zeroRecords: "No records to display"
	},
	initComplete: function(settings, json) {
		initApp.appForms('.dataTables_filter', 'has-length', 'has-disabled');
	}

});