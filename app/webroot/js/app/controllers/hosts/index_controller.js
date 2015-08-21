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

App.Controllers.HostsIndexController = Frontend.AppController.extend({
	$table: null,
	/**
	 * @constructor
	 * @return {void} 
	 */
	
	components: ['Utils', 'Masschange', 'WebsocketSudo', 'Externalcommand', 'Ajaxloader'],
	
	_initialize: function(){
		var self = this;
		this.Masschange.setup({
			'controller': 'hosts',
			'group': 'hostgroups'
		});
		this.Utils.flapping();
		$('.select_datatable').click(function(){
			self.fnShowHide($(this).attr('my-column'), $(this).children());
		});
		
		$('#host_list').dataTable({
			"bPaginate": false,
			"bFilter": false,
			"bInfo": false,
			"bStateSave": true,
			"aoColumnDefs" : [ {
				"bSortable" : false,
				"aTargets" : [ "no-sort" ]
			} ],
			"fnInitComplete" : function(dtObject){
				var vCols = [];
				var $checkboxObjects = $('.select_datatable');
				
				//Enable all checkboxes
				$('.select_datatable').find('input').prop('checked', true);
				
				$.each(dtObject.aoColumns, function(count){
					if(dtObject.aoColumns[count].bVisible == false){
						//Uncheck checkboxes of hidden colums
						$checkboxObjects.each(function(intKey, object){
							var $object = $(object);
							if($object.attr('my-column') == count){
								var $input = $(object).find('input');
								$input.prop('checked', false);
							}
						});
					}
				});
				
			}
		});
		
		this.$table = $('#host_list');
		//console.log(this.$table.dataTable().fnSettings());
		
		
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
		 * Reschedule hosts
		 */
		$('#submitRescheduleHost').click(function(){
			for(var key in this.Masschange.selectedUuids){
				this.WebsocketSudo.send(this.WebsocketSudo.toJson('rescheduleHostWithQuery', [this.Masschange.selectedUuids[key], $('#nag_commandRescheduleHost').val()]));
			}
			this.Externalcommand.refresh();
		}.bind(this));
		
		/*
		 * Set planned maintenance time
		 */
		$('#submitCommitHostDowntime').click(function(){
			this.validateDowntimeInput();
			
		}.bind(this));
		
		/*
		 * Host ACK
		 */
		$('#submitHostAck').click(function(){
			var sticky = 0;
			
			if($('#CommitHostAckSticky').prop('checked') == true){
				sticky = 2;
			}
			for(var key in this.Masschange.selectedUuids){
				this.WebsocketSudo.send(this.WebsocketSudo.toJson('submitHostAckWithQuery', [this.Masschange.selectedUuids[key], $('#CommitHostAckComment').val(), $('#CommitHostAckAuthor').val(), sticky, $('#CommitHostAckType').val()]));
			}
			this.Externalcommand.refresh();
		}.bind(this));
		
		/*
		 * Disable notifications
		 */
		$('#submitDisableNotifications').click(function(){
			for(var key in this.Masschange.selectedUuids){
				this.WebsocketSudo.send(this.WebsocketSudo.toJson('submitDisableHostNotifications', [this.Masschange.selectedUuids[key], $('#disableNotificationsType').val()]));
			}
			this.Externalcommand.refresh();
		}.bind(this));
		
		/*
		 * Enable notifications
		 */
		$('#submitEnableNotifications').click(function(){
			for(var key in this.Masschange.selectedUuids){
				this.WebsocketSudo.send(this.WebsocketSudo.toJson('submitEnableHostNotifications', [this.Masschange.selectedUuids[key], $('#enableNotificationsType').val()]));
			}
			this.Externalcommand.refresh();
		}.bind(this));
	},
	
	validateDowntimeInput: function(){
		this.Ajaxloader.show();
		var fromData = $('#CommitHostDowntimeFromDate').val();
		var fromTime = $('#CommitHostDowntimeFromTime').val();
		var toData = $('#CommitHostDowntimeToDate').val();
		var toTime = $('#CommitHostDowntimeToTime').val();
		
		ret = $.ajax({
			url: "/downtimes/validateDowntimeInputFromBrowser",
			type: "POST",
			data: {from: fromData+' '+fromTime, to: toData+' '+toTime},
			error: function(){},
			success: function(response){
				if(response == 1){
					for(var key in this.Masschange.selectedUuids){
						this.WebsocketSudo.send(this.WebsocketSudo.toJson('submitHostDowntime', [this.Masschange.selectedUuids[key], fromData+' '+fromTime, toData+' '+toTime, $('#CommitHostDowntimeComment').val(), $('#CommitHostDowntimeAuthor').val(), $('#CommitHostDowntimeType').val()]));
						}
					$('#nag_command_schedule_downtime').modal('hide');
					this.Externalcommand.refresh();
				}else{
					$('#validationErrorHostDowntime').show();
				}
				this.Ajaxloader.hide();
			}.bind(this),
			complete: function(response) {
			}
		});
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
	}
});