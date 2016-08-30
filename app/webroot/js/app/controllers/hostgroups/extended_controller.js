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

App.Controllers.HostgroupsExtendedController = Frontend.AppController.extend({

	data: null,

	$table: null,
	/**
	 * @constructor
	 * @return {void}
	 */

	components: ['Utils',  'Rrd', 'WebsocketSudo', 'Externalcommand', 'Ajaxloader'],
	_initialize: function(){
		var self = this;
		this.Utils.flapping();
		this.Rrd.bindPopup({
			Time: this.Time,
		});
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
		 * Set planned maintenance time
		 */
		$('#submitCommitHostgroupDowntime').click(function(){
			this.validateDowntimeInput();
			
		}.bind(this));
		
		/*
		 * Reschedule host group
		 */
		$('#submitRescheduleHostgroup').click(function(){
			this.WebsocketSudo.send(this.WebsocketSudo.toJson('rescheduleHostgroup', [this.getVar('hostgroupUuid'), $('#nag_commandRescheduleHostgroup').val()]));
			this.Externalcommand.refresh();
		}.bind(this));
		
		/*
		 * Hostgroup ACK
		 */
		$('#submitHostgroupAck').click(function(){
			var sticky = 0;
			
			if($('#CommitHostgroupAckSticky').prop('checked') == true){
				sticky = 2;
			}
			this.WebsocketSudo.send(this.WebsocketSudo.toJson('submitHostgroupAck', [this.getVar('hostgroupUuid'), $('#CommitHostgroupAckComment').val(), $('#CommitHostgroupAckAuthor').val(), sticky, $('#CommitHostgroupAckType').val()]));
			this.Externalcommand.refresh();
		}.bind(this));
		
		/*
		 * Disable notifications
		 */
		$('#submitDisableNotifications').click(function(){
			this.WebsocketSudo.send(this.WebsocketSudo.toJson('submitDisableHostgroupNotifications', [this.getVar('hostgroupUuid'), $('#disableNotificationsType').val()]));
			this.Externalcommand.refresh();
		}.bind(this));
		
		/*
		 * Enable notifications
		 */
		$('#submitEnableNotifications').click(function(){
			this.WebsocketSudo.send(this.WebsocketSudo.toJson('submitEnableHostgroupNotifications', [this.getVar('hostgroupUuid'), $('#enableNotificationsType').val()]));
			this.Externalcommand.refresh();
		}.bind(this));
		
		/*
		 * Bind change event on serviceListHostId
		 */
		$('#extendedHostId').change(function(){
			window.location = '/hostgroups/extended/'+$(this).val();
		});
		
		$('.showhide').click(function(){
			self.showHide($(this));
		});
		$('.state_filter').change(function(){
			self.filterState($(this).is(':checked'), $(this).attr('uuid'), $(this).attr('state'));
		});
		/*$('.hostname_filter').keyup(function(){
			self.filterHostname($(this).attr('id'), $(this).val());
		});*/

		$('[filter="true"]').keyup(function(){
			var $this = $(this);
			self.filter($this.attr('search_id'), $this.val(), $this.attr('needle'));
		});

		this.loadHosts();

	},
	showHide: function(showHideElement){
		if(showHideElement.hasClass('fa-plus-square-o')){
			showHideElement.removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
			$('.'+showHideElement.attr('showhide_uuid')).removeClass('hidden');
		}else{
			showHideElement.removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
			$('.'+showHideElement.attr('showhide_uuid')).addClass('hidden');
		}
	},

	loadHosts: function(){
		return;
		this.Ajaxloader.show();

		ret = $.ajax({
			url: "/hostgroups/loadHostsByHostgroup/"+this.getVar('hostgroupId')+".json",
			type: "POST",
			error: function(){},
			success: function(response){



				this.renderTable(response);
				this.Ajaxloader.hide();
			}.bind(this),
			complete: function(response) {
			}
		});
	},

	renderTable: function(data){
		$('#pageStatus').text(this.getVar('renderTableMessage'));


		var $table = $('#hostgroup_list');

		for(var f in data.hosts){
			$table.append(sprintf(
				'<tr>' +
					'<td>%s</td>' +
					'<td>%s</td>' +
				'</tr>',
				data.hosts[f].Host.name
			));
		}
		$('#pageStatus').parent().remove();
	},

	validateDowntimeInput: function(){
		this.Ajaxloader.show();
		var fromData = $('#CommitHostgroupDowntimeFromDate').val();
		var fromTime = $('#CommitHostgroupDowntimeFromTime').val();
		var toData = $('#CommitHostgroupDowntimeToDate').val();
		var toTime = $('#CommitHostgroupDowntimeToTime').val();
		
		ret = $.ajax({
			url: "/downtimes/validateDowntimeInputFromBrowser",
			type: "POST",
			data: {from: fromData+' '+fromTime, to: toData+' '+toTime},
			error: function(){},
			success: function(response){
				if(response == 1){
					this.WebsocketSudo.send(this.WebsocketSudo.toJson('submitHostgroupDowntime', [this.getVar('hostgroupUuid'), fromData+' '+fromTime, toData+' '+toTime, $('#CommitHostgroupDowntimeComment').val(), $('#CommitHostgroupDowntimeAuthor').val(), $('#CommitHostgroupDowntimeType').val()]));
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
	
	filterState: function(stateOn, uuid, state){
		if(stateOn){
			$('.'+uuid).filter('.state_'+state).removeClass('hidden');
		}else{
			$('.'+uuid).filter('.state_'+state).addClass('hidden');
		}
	},
	
	filter: function(search_id, searchValue, needle){
		var regexSearchValue = new RegExp(searchValue, 'gi');
		$('.'+search_id).children('[search="'+needle+'"]').each(function(key, object){
			var $object = $(object);
			if($object.html().match(regexSearchValue) === null){
				$object.parent().hide();
			}else{
				$object.parent().show();
			}
		});

	}
});