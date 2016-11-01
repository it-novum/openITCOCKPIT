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

App.Controllers.ServicesIndexController = Frontend.AppController.extend({


	$table: null,

	components: ['Ajaxloader', 'Utils', 'Rrd', 'Masschange', 'WebsocketSudo', 'Externalcommand', 'Ajaxloader'],
	
	_initialize: function() {
		this.Ajaxloader.setup();
		this.Utils.flapping();
		this.Rrd.bindPopup({
			Time: this.Time,
		});
		var self = this;
		$('.select_datatable').click(function(){
			self.fnShowHide($(this).attr('my-column'), $(this).children());
		});
		
		/*
		 * Bind change event for host chosen box
		 */
		$('#host_id').change(function(){
			var hostId = $(this).val();

			//console.log("/Services/loadServices/"+encodeURIComponent(hostId)+".json");
			self.Ajaxloader.show();
			$.ajax({
				url: "/Services/loadServices/"+encodeURIComponent(hostId)+".json",
				type: "POST",
				cache: false,
				error: function(){},
				success: function(){},
				complete: function(response){
					console.log(response.responseText);
					//$(response_container).html(response.responseText);
					self.Ajaxloader.hide();
				}.bind(this)
			});
		});
		
		
		this.Masschange.setup({
			'controller': 'services',
			'group': 'servicegroups',
			'checkboxattr': 'servicename',
			'storeUuidsAsArray': true
		});
		
		//Create sudo server websocket connection
		this.Ajaxloader.setup();
		
		this.WebsocketSudo.setup(this.getVar('websocket_url'), this.getVar('akey'));
		
		this.WebsocketSudo._errorCallback = function(){
			$('#error_msg').html('<div class="alert alert-danger alert-block"><a href="#" data-dismiss="alert" class="close">×</a><h5 class="alert-heading"><i class="fa fa-warning"></i> Error</h5>Could not connect to SudoWebsocket Server</div>');
		}
		
		this.WebsocketSudo.connect();
		this.WebsocketSudo._success = function(e){
			return true;
		}.bind(this)
		
		this.WebsocketSudo._callback = function(transmitted){
			return true;
		}.bind(this);
		
		this.Externalcommand.setup();
		
		/*
		 * Reschedule services
		 */
		$('#submitRescheduleService').click(function(){
			for(var serviceUuid in this.Masschange.uuidsArray){
				this.WebsocketSudo.send(this.WebsocketSudo.toJson('rescheduleServiceWithQuery', [serviceUuid]));
			}
			this.Externalcommand.refresh();
		}.bind(this));
		
		/*
		 * Set planned maintenance time
		 */
		$('#submitCommitServiceDowntime').click(function(){
			this.validateDowntimeInput();
			
		}.bind(this));
		
		/*
		 * Service ACK
		 */
		$('#submitServiceAck').click(function(){
			var sticky = 0;
			
			if($('#CommitServiceAckSticky').prop('checked') == true){
				sticky = 2;
			}
			for(var serviceUuid in this.Masschange.uuidsArray){
				//First parameter, hostUuid, seconde serviceUuid
				this.WebsocketSudo.send(this.WebsocketSudo.toJson('submitServiceAckWithQuery', [this.Masschange.uuidsArray[serviceUuid], serviceUuid, $('#CommitServiceAckComment').val(), $('#CommitServiceAckAuthor').val(), sticky]));
			}
			this.Externalcommand.refresh();
		}.bind(this));
		
		/*
		 * Disable notifications
		 */
		$('#submitDisableNotifications').click(function(){
			for(var serviceUuid in this.Masschange.uuidsArray){
				this.WebsocketSudo.send(this.WebsocketSudo.toJson('submitDisableServiceNotifications', [this.Masschange.uuidsArray[serviceUuid], serviceUuid]));
			}
			this.Externalcommand.refresh();
		}.bind(this));
		
		/*
		 * Enable notifications
		 */
		$('#submitEnableNotifications').click(function(){
			for(var serviceUuid in this.Masschange.uuidsArray){
				this.WebsocketSudo.send(this.WebsocketSudo.toJson('submitEnableServiceNotifications', [this.Masschange.uuidsArray[serviceUuid], serviceUuid]));
			}
			this.Externalcommand.refresh();
		}.bind(this));
		
	},
	fnShowHide: function( iCol, inputObject){
		/* Get the DataTables object again - this is not a recreation, just a get of the object */
		var oTable = this.$table.dataTable();
	
		var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
		if(bVis == true){
			inputObject.prop('checked', false);
		}else{
			inputObject.prop('checked', true);
		}
		oTable.fnSetColumnVis( iCol, bVis ? false : true );
	},
	
	validateDowntimeInput: function(){
		this.Ajaxloader.show();
		var fromData = $('#CommitServiceDowntimeFromDate').val();
		var fromTime = $('#CommitServiceDowntimeFromTime').val();
		var toData = $('#CommitServiceDowntimeToDate').val();
		var toTime = $('#CommitServiceDowntimeToTime').val();
		
		ret = $.ajax({
			url: "/downtimes/validateDowntimeInputFromBrowser",
			type: "POST",
			cache: false,
			data: {from: fromData+' '+fromTime, to: toData+' '+toTime},
			error: function(){},
			success: function(response){
				if(response == 1){
					for(var serviceUuid in this.Masschange.uuidsArray){
						this.WebsocketSudo.send(this.WebsocketSudo.toJson('submitServiceDowntime', [this.Masschange.uuidsArray[serviceUuid], serviceUuid, fromData+' '+fromTime, toData+' '+toTime, $('#CommitServiceDowntimeComment').val(), $('#CommitServiceDowntimeAuthor').val()]));
						}
					$('#nag_command_schedule_downtime').modal('hide');
					this.Externalcommand.refresh();
				}else{
					$('#validationErrorServiceDowntime').show();
				}
				this.Ajaxloader.hide();
			}.bind(this),
			complete: function(response) {
			}
		});
	},
	
	loadServices: function(hostId){
		this.Ajaxloader.show();
		$.ajax({
			url: "/Services/loadServices/"+encodeURIComponent(hostId)+".json",
			type: "POST",
			cache: false,
			error: function(){},
			success: function(){},
			complete: function(response){
				console.log(response.responseText);
				//$(response_container).html(response.responseText);
				this.Ajaxloader.hide();
			}.bind(this)
		});
	}
});