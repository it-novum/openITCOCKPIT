// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

App.Controllers.ExportsIndexController = Frontend.AppController.extend({
	$textarea: null,
	$progressbar: null,
	$progressbarText: null,
	$progressbarBar: null,
	$progressbarContainer: null,
	$log: null,
	/**
	 * @constructor
	 * @return {void} 
	 */
	
	components: ['WebsocketSudo'],

	_initialize: function(){
		/*
		 * Fix for ugly FireFox behavior :(
		 */
		$('#exportAll').prop( "disabled", false);

		

		var exportRecords = {
			129: {
				task: 'export_started',
				text: 'Export started',
				finished: 0
			},
			132: {
				task: 'export_create_default_config',
				text: "Create default configuration",
				finished: 1
			}
		};

		
		$('#launchExport').click(function(){
			$.ajax({
				url: '/exports/launchExport',
				type: "GET",
				success: function(data) {
					console.log(data);
				},
				complete: function() {
				}
			});
			
			//Update export status
			var worker = function(){
				$.ajax({
					url: '/exports/broadcast.json',
					type: "GET",
					success: function(response){
						console.log(response);
					},
					complete: function() {
						// Schedule the next request when the current one's complete
						setTimeout(worker, 5000);
					}
				});
			};
			worker();
		});
		
		

		/*
		 * Bind click events
		 */
		$('#exportAll').click(function(){
			this.WebsocketSudo.send(this.WebsocketSudo.toJson('runCompleteExport', [$('#CreateBackup').prop('checked')]));
			$('#exportAll').prop( "disabled", true);
			this.$progressbar.show();
			this.$log.show();
		}.bind(this));
		
		
		this.$textarea =  $('.form-control textarea');
		this.$progressbar = $('#exportProgressbar');
		this.fetchProgressbar();
		this.$log = $('#logoutput');

		this.WebsocketSudo.setup(this.getVar('websocket_url'), this.getVar('akey'));
		
		this.WebsocketSudo._errorCallback = function(){
			$('#error_msg').html('<div class="alert alert-danger alert-block"><a href="#" data-dismiss="alert" class="close">×</a><h5 class="alert-heading"><i class="fa fa-warning"></i> Error</h5>Could not connect to SudoWebsocket Server</div>');
		}
		
		this.WebsocketSudo.connect();
		this.WebsocketSudo._success = function(e){
			return true;
		}.bind(this)
		
		this.WebsocketSudo._callback = function(transmitted){
			if(transmitted.category == 'notification'){
				this.$progressbarText.html(transmitted.payload);
				
				//Replace uuids
				var RegExObject = new RegExp('('+this.getVar('uuidRegEx')+')', 'g');
				transmitted.payload = transmitted.payload.replace(RegExObject, '<a href="/forward/index/uuid:$1/action:edit">$1</a>');
				if(transmitted.payload.match('Warning')){
					this.$log.append('<div class="txt-color-orangeDark">'+transmitted.payload+'</div>');
				}else if(transmitted.payload.match('Error')){
					this.$log.append('<div class="txt-color-red">'+transmitted.payload+'</div>');
				}else{
					this.$log.append('<div>'+transmitted.payload+'</div>');
				}
			}
			
			return true;
		}.bind(this);
		
		this.WebsocketSudo._event = function(transmitted){
			if(transmitted.category == 'done'){
				this.$progressbarBar.removeClass('bg-color-purple');
				this.$progressbarBar.addClass('bg-color-green');
				this.$progressbarText.html(transmitted.payload);
				this.$log.append('<div class="txt-color-green">'+transmitted.payload+'</div>');
				this.$progressbar.removeClass('progress-striped');
				this.$progressbar.removeClass('active');
			}
			return true;
			
		}.bind(this);
	},
	
	fetchProgressbar: function(){
		this.$progressbarBar = $(this.$progressbar.find('div')[1]);
		this.$progressbarText = $(this.$progressbar.find('div')[2]);
	}
	
});