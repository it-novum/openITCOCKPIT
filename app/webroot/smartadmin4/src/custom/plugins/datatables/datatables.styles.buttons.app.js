/* Set the defaults for DataTables buttons */
$.extend(true, $.fn.dataTable.Buttons.defaults, {
	dom: {
		container: {
			className: 'dt-buttons'
		},
		button: {
			className: 'btn'
		}
	}
});

/* auto fill button class on popup */
$.fn.dataTable.AutoFill.classes.btn = 'btn btn-primary';