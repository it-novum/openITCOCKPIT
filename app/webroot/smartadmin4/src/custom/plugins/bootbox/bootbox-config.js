bootbox.setTemplates({
		dialog:
		'<div class="bootbox modal" tabindex="-1" role="dialog" aria-hidden="true">' +
		'<div class="modal-dialog">' +
		'<div class="modal-content">' +
		'<div class="modal-body"><div class="bootbox-body"></div></div>' +
		'</div>' +
		'</div>' +
		'</div>',
		header:
		'<div class="modal-header">' +
		'<h5 class="modal-title"></h5>' +
		'</div>',
		footer:
		'<div class="modal-footer"></div>',
		closeButton:
		'<button type="button" class="bootbox-close-button close" aria-hidden="true"><i class="fal fa-times"></i></button>',
		form:
		'<form class="bootbox-form"></form>',
		button:
		'<button type="button" class="btn"></button>',
		option:
		'<option></option>',
		promptMessage:
		'<div class="bootbox-prompt-message"></div>',
		inputs: {
			text:
			'<input class="bootbox-input bootbox-input-text form-control" autocomplete="off" type="text" />',
			textarea:
			'<textarea class="bootbox-input bootbox-input-textarea form-control"></textarea>',
			email:
			'<input class="bootbox-input bootbox-input-email form-control" autocomplete="off" type="email" />',
			select:
			'<select class="bootbox-input bootbox-input-select form-control"></select>',
			checkbox:
			'<div class="form-check checkbox"><label class="form-check-label"><input class="form-check-input bootbox-input bootbox-input-checkbox" type="checkbox" /></label></div>',
			radio:
			'<div class="form-check radio"><label class="form-check-label"><input class="form-check-input bootbox-input bootbox-input-radio" type="radio" name="bootbox-radio" /></label></div>',
			date:
			'<input class="bootbox-input bootbox-input-date form-control" autocomplete="off" type="date" />',
			time:
			'<input class="bootbox-input bootbox-input-time form-control" autocomplete="off" type="time" />',
			number:
			'<input class="bootbox-input bootbox-input-number form-control" autocomplete="off" type="number" />',
			password:
			'<input class="bootbox-input bootbox-input-password form-control" autocomplete="off" type="password" />',
			range:
			'<input class="bootbox-input bootbox-input-range form-control-range" autocomplete="off" type="range" />'
		}
	
});