App.Helpers.UIBlocker = Class.extend({
	_blockUiOptions: {
		fadeIn: 50,
		fadeOut: 150,
		message: '&nbsp;Bitte warten', // blockElement won't display with an empty message.
		overlayCSS: {
			backgroundColor: '#fff'
		}
	},
	_blockUiOptions: {
		fadeIn: 50,
		fadeOut: 50,
		message: '&nbsp;', // blockElement won't display with an empty message.
		overlayCSS:  {
			backgroundColor: '#fff',
			opacity:	  	 0.6,
			cursor:		  	 'wait',
			backgroundImage: 'url(/img/ajax_loader_gray_32.gif)',
			backgroundPosition: 'center center',
			backgroundRepeat: 'no-repeat'
		}
	},
	blockElement: function(element) {
		$(element).addClass('uiblocker-loading');
		// backup the stupid css defaults for restoring them later
		var backupDefaults = $.blockUI.defaults.css;
		$.blockUI.defaults.css = {};
		$(element).block(this._blockUiOptions);
		// restore the defaults.
		$.blockUI.defaults.css = backupDefaults;
	},
	unblockElement: function(element) {
		$(element).removeClass('uiblocker-loading');
		$(element).unblock(this._blockUiOptionDefaults);
	}
});
App.Main.UIBlocker = new App.Helpers.UIBlocker();