App.Dialog = Class.extend({
	domElement: null,
	options: null,
	defaultMarkup: '<div class="modal-header">'
		+ '	<button class="close" data-dismiss="modal">×</button>'
		+ '	<h3></h3>'
		+ '</div>'
		+ '<div class="modal-body">'
		+ '</div>'
		+ '<div class="modal-footer">'
		+ '	<a href="#" class="btn" data-dismiss="modal">Schließen</a>'
		+ '</div>',
	init: function(options) {
		var defaultOptions = {
			classes: ['dialog'],
			title: null,
			content: '',
			closeButtonSelector: '.close-control',
			addCloseButton: true,
			appendToDomBeforeShow: true,
			// Will be called after the close() action was triggered
			onClose: null,
			fade: true,
			headerAndFooter: false
		};

		this.options = defaultOptions;
		this.options = jQuery.extend(this.options, options);
		this.domElement = $('<div/>');
		this.domElement.addClass('modal');
		if(this.options.fade) {
			this.domElement.addClass('fade');
		}

		for(var i in this.options.classes) {
			this.domElement.addClass(this.options.classes[i]);
		}

		var contentElement = $('<div/>');
		contentElement.addClass('modal-content');

		if(this.options.headerAndFooter) {
			contentElement.html(this.defaultMarkup);
			contentElement.find('.modal-body').html(this.options.content);
			if(this.options.title) {
				contentElement.find('.modal-header h3').html(this.options.title);
			} else {
				contentElement.find('.modal-header h3').remove();
			}
		} else {
			contentElement.html(this.options.content);
		}
		
		var dialogElement = $('<div class="modal-dialog"/>').html(contentElement);
		this.domElement.append(dialogElement);

		if(this.options.appendToDomBeforeShow) {
			$('body').append(this.domElement);
		}

		this._addHandlers();
	},
	/**
	* Set the dialog title
	* 
	* @param {string} newTitle
	*/
	setTitle: function (newTitle) {
		this.domElement.find('div.title').html(newTitle);

		this.options.title = newTitle;
		return this;
	},
	/**
	* The the dialog content.
	* 
	* @param {string} newContent
	*/
	setContent: function (newContent) {
		this.getContent().html(newContent);
		this._addHandlers();
		return this;
	},
	/**
	* Returns the DOM node which contains the dialog content.
	* 
	* @return HTMLElement
	*/
	getContent: function() {
		return this.domElement.find('div.modal-content');
	},
	/**
	* Create and show the dialog
	*/
	show: function() {
		if(this.options.appendToDomBeforeShow) {
			if($('.dialog').length > 0) {
				$('.dialog').remove();
			}
			$('body').append(this.domElement);
		} else {

		}
		
		this.domElement.modal();
		this.domElement.on('hidden.bs.modal', function() {
			return this._close();
		}.bind(this));
	},
	/**
	* Add event handlers
	*/
	_addHandlers: function() {

	},
	_close: function() {
		if(typeof this.options.onClose == 'function') {
			this.options.onClose(this);
		}
		this.domElement.remove();
	},
	close: function() {
		this.domElement.modal('hide');
	},
	blockUi: function() {
		this.domElement.addClass('loading');
		// backup the stupid css defaults for restoring them later
		var backupDefaults = $.blockUI.defaults.css;
		$.blockUI.defaults.css = {};
		this.domElement.block({
			fadeIn: 50,
			fadeOut: 50,
			message: '&nbsp;', // blockElement won't display with an empty message.
			overlayCSS:  {
				backgroundColor: '#fff',
				opacity:	  	 0.6,
				cursor:		  	 'wait',
				backgroundImage: 'url(/img/loaderb64.gif)',
				backgroundPosition: 'center center',
				backgroundRepeat: 'no-repeat'
			}
		});
		// restore the defaults.
		$.blockUI.defaults.css = backupDefaults;
	},
	unblockUi: function() {
		this.domElement.removeClass('loading');
		this.domElement.unblock();
	}
});