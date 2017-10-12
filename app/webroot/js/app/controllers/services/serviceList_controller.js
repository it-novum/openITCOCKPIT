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

App.Controllers.ServicesServiceListController = Frontend.AppController.extend({
	$table: null,

	components: ['Utils', 'Masschange', 'Rrd', 'WebsocketSudo', 'Externalcommand', 'Ajaxloader', 'Time'],

	_initialize: function() {
		this.Time.setup();
		this.Utils.flapping();
		this.Rrd.bindPopup({
			Time: this.Time
		});
		
		this.Masschange.setup({
			'controller': 'services',
			'group': 'servicegroups',
			'checkboxattr': 'servicename'
		});
		
		/*
		 * Bind change event on serviceListHostId
		 */
        $('#serviceListHostId').change(function(){
           	window.location = '/services/serviceList/'+$(this).val();
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
			for(var key in this.Masschange.selectedUuids){
				this.WebsocketSudo.send(this.WebsocketSudo.toJson('rescheduleServiceWithQuery', [this.Masschange.selectedUuids[key]]));
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
			for(var key in this.Masschange.selectedUuids){
				this.WebsocketSudo.send(this.WebsocketSudo.toJson('submitServiceAckWithQuery', [this.getVar('hostUuid'), this.Masschange.selectedUuids[key], $('#CommitServiceAckComment').val(), $('#CommitServiceAckAuthor').val(), sticky]));
			}
			this.Externalcommand.refresh();
		}.bind(this));
		
		/*
		 * Disable notifications
		 */
		$('#submitDisableNotifications').click(function(){
			for(var key in this.Masschange.selectedUuids){
				this.WebsocketSudo.send(this.WebsocketSudo.toJson('submitDisableServiceNotifications', [this.getVar('hostUuid'), this.Masschange.selectedUuids[key]]));
			}
			this.Externalcommand.refresh();
		}.bind(this));
		
		/*
		 * Enable notifications
		 */
		$('#submitEnableNotifications').click(function(){
			for(var key in this.Masschange.selectedUuids){
				this.WebsocketSudo.send(this.WebsocketSudo.toJson('submitEnableServiceNotifications', [this.getVar('hostUuid'), this.Masschange.selectedUuids[key]]));
			}
			this.Externalcommand.refresh();
		}.bind(this));
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
					for(var key in this.Masschange.selectedUuids){
						this.WebsocketSudo.send(this.WebsocketSudo.toJson('submitServiceDowntime', [this.getVar('hostUuid'), this.Masschange.selectedUuids[key], fromData+' '+fromTime, toData+' '+toTime, $('#CommitServiceDowntimeComment').val(), $('#CommitServiceDowntimeAuthor').val()]));
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
	}
});

