App.Controllers.ConfigsIndexController = Frontend.AppController.extend({

	_initialize: function() {
		/*
		 * bind Click event on Save button
		 */
		$('#saveContent').click(function(){
			$.post( "/nagios_module/configs/saveConfig", { configfile: $('#ConfigConfigfile').val(), content: strip_tags($('.config_editor').html(), '<br>') }, function(){
				$("html, body").animate({ scrollTop: 0 }, "slow");
				$('#flashMessage').show();
			});
		});
		
	},
	
});