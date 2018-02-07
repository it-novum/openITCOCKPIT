'use strict';
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

App.Controllers.ServicesBrowserController = Frontend.AppController.extend({
	host_uuid: null,
	service_uuid: null,
	components: ['WebsocketSudo', 'Ajaxloader', 'Rrd', 'Externalcommand', 'Utils', 'Qr', 'Time'],

	_initialize: function(){
		var self = this;

		self.Time.setup();
		self.Ajaxloader.setup();
		self.Externalcommand.setup();
		self.Utils.flapping();
		self.Qr.setup();

		/*
		 * Render Datepickers
		 */
		$('#CommitServiceDowntimeFromDate').datepicker({
			format: self.getVar('dateformat')
		});

		$('#CommitServiceDowntimeToDate').datepicker({
			format: self.getVar('dateformat')
		});

		/*
		 * Binding click events
		 */
		$('.submitRescheduleService').click(function(){
			self.WebsocketSudo.send(self.WebsocketSudo.toJson('rescheduleService', [self.host_uuid, self.service_uuid, $('#nag_commandSatelliteId').val()]));
			self.Externalcommand.refresh();
		});

		$('#submitCommitPassiveResult').click(function(){
			self.WebsocketSudo.send(
				self.WebsocketSudo.toJson('commitPassiveServiceResult', [
					self.host_uuid,
					self.service_uuid,
					$('#CommitPassiveResultComment').val(),
					$('#CommitPassiveResultStatus').val(),
					$('#CommitPassiveResultForceHardstate').prop('checked'),
					$('#CommitPassiveResultRepetitions').val()
				]));
			self.Externalcommand.refresh();
		});

		$('#submitEnableOrDisableHostFlapdetection').click(function(){
			self.WebsocketSudo.send(
				self.WebsocketSudo.toJson('enableOrDisableServiceFlapdetection', [
						self.host_uuid,
						self.service_uuid,
						$('#enableOrDisableHostFlapdetectionCondition').val()
					]
				));
			self.Externalcommand.refresh();
		});

		$('#submitCustomServiceNotification').click(function(){
			var $notification_broadcast = $('#CommitCustomServiceNotificationBroadcast'),
				$notification_forced = $('#CommitCustomServiceNotificationForced'),
				is_broadcast = $notification_broadcast.prop('checked'),
				is_forced = $notification_forced.prop('checked'),
				type = 0;

			if(is_broadcast && is_forced){
				type = 3;
			}else if(is_forced){
				type = 2;
			}else if(is_broadcast){
				type = 1;
			}

			self.WebsocketSudo.send(
				self.WebsocketSudo.toJson('sendCustomServiceNotification', [
					self.host_uuid,
					self.service_uuid,
					type,
					$('#CommitCustomServiceNotificationAuthor').val(),
					$('#CommitCustomServiceNotificationComment').val()
				]));
			self.Externalcommand.refresh();
		});

		$('#submitServicestateAck').click(function(){

			var sticky = 0;

			if($('#CommitServicestateAckSticky').prop('checked') == true){
				sticky = 2;
			}

			self.WebsocketSudo.send(
				self.WebsocketSudo.toJson('submitServicestateAck', [
						self.host_uuid,
						self.service_uuid,
						$('#CommitServicestateAckComment').val(),
						$('#CommitServicestateAckAuthor').val(),
						sticky
					]
				));
			self.Externalcommand.refresh();
		});

		$('#submitCommitServiceDowntime').click(function(){
			self.validateDowntimeInput();
		});

		$('#submitEnableNotifications').click(function(){
			if($('#enableNotificationsIsEnabled').val().toString() == '1'){
				self.WebsocketSudo.send(self.WebsocketSudo.toJson('submitDisableServiceNotifications', [self.host_uuid, self.service_uuid]));
			}else{
				self.WebsocketSudo.send(self.WebsocketSudo.toJson('submitEnableServiceNotifications', [self.host_uuid, self.service_uuid]));
			}
			self.Externalcommand.refresh();
		});

		self.host_uuid = self.getVar('hostUuid');
		self.service_uuid = self.getVar('serviceUuid');

		$('#nagShowLongOutput').click(function(){
			$('#nag_longout_preview').hide();
			$('#nag_longoutput_container').show();
			$('#nag_longoutput_loader').show();
			$('#nag_longoutput_content').html('');
			$.ajax({
				url: "/Services/longOutputByUuid/" + encodeURIComponent(self.service_uuid),
				type: "GET",
				cache: false,
				error: function(){
				},
				success: function(){
				},
				complete: function (response) {
					$('#nag_longoutput_content').html(response.responseText);
					$('#nag_longoutput_loader').hide();
				}
			});
		});

		self.WebsocketSudo.setup(self.getVar('websocket_url'), self.getVar('akey'));

		self.WebsocketSudo._errorCallback = function(){
			var html = '';

			html += '<div class="alert alert-danger alert-block">';
			html += '  <a href="#" data-dismiss="alert" class="close">×</a>';
			html += '  <h5 class="alert-heading"><i class="fa fa-warning"></i> Error</h5>';
			html += '  Could not connect to SudoWebsocket Server';
			html += '</div>';

			$('#error_msg').html(html);
		};

		self.WebsocketSudo.connect();
		self.WebsocketSudo._success = function (e) {
			//self.WebsocketSudo.keepAlive();
			return true;
		};

		self.WebsocketSudo._callback = function (transmitted) {
			return true;
		};

		$('#host_browser_service_table').dataTable({
			'bPaginate': false,
			'bFilter': true,
			'bInfo': false,
			'bStateSave': true
		});

		$('div.dataTables_filter')
			.attr('style', 'width: 100% !important;padding-right: 20px;')
			.children('.input-group')
			.attr('style', 'width: 100% !important;');

		$('.nag_command')
			.mouseenter(function(){
				//$(this).addClass('label bg-color-blueLight');
				$(this).addClass('text-primary pointer');
			})
			.mouseleave(function(){
				//$(this).removeClass('label bg-color-blueLight');
				$(this).removeClass('text-primary pointer');
			});

		if($('#serviceHasGraphs').val() == 1){
			self.loadGraph($('#graph-filter-from').val(), $('#graph-filter-to').val(), $('#graph-filter-value').val());
		}

		$('#apply-graph-filter').click(function(){
			if($('#serviceHasGraphs').val() == 1){
				self.loadGraph($('#graph-filter-from').val(), $('#graph-filter-to').val(), $('#graph-filter-value').val());
			}
		});

	},

	loadGraph: function(startDate, endDate, $serviceValue){
		var self = this,
			host_and_service_uuids = {};

		host_and_service_uuids[self.host_uuid] = [[self.service_uuid, $serviceValue]];
		$('#graph_loader').show();
		$('#graph').hide();
		self.Rrd.setup({
			url: '/Graphgenerators/fetchGraphData.json',
			host_and_service_uuids: host_and_service_uuids,
			selector: '#graph',
			ds: $('#GraphgeneratorServicerule').val(),
			display_threshold_lines: true,
			timezoneOffset: this.Time.timezoneOffset //Rename to user timesone offset
		});

		var time_period = {
				start: startDate,
				end: endDate
			};

		self.Rrd.fetchRrdData(time_period, function(){
			$('#graph').show();
			self.Rrd.renderGraphForBrowser();
			$('#graph_loader').hide();
		});

		$('#renderGraph').hide();
		$('#apendGraph').show();
		$('#addGraph').show();
		$('#truncateGraph').show();
	},

	validateDowntimeInput: function(){
		var self = this;

		self.Ajaxloader.show();
		var fromData = $('#CommitServiceDowntimeFromDate').val();
		var fromTime = $('#CommitServiceDowntimeFromTime').val();
		var toData = $('#CommitServiceDowntimeToDate').val();
		var toTime = $('#CommitServiceDowntimeToTime').val();

		$.ajax({
			url: '/downtimes/validateDowntimeInputFromBrowser',
			type: 'POST',
			cache: false,
			data: {
				from: fromData + ' ' + fromTime,
				to: toData + ' ' + toTime
			},
			error: function(){
			},
			success: function (response) {
				if (response == 1) {
					self.WebsocketSudo.send(
						self.WebsocketSudo.toJson('submitServiceDowntime', [
								self.host_uuid,
								self.service_uuid,
								fromData + ' ' + fromTime,
								toData + ' ' + toTime,
								$('#CommitServiceDowntimeComment').val(),
								$('#CommitServiceDowntimeAuthor').val()
							]
						));
					$('#nag_command_schedule_downtime').modal('hide');
					self.Externalcommand.refresh();
				} else {
					$('#validationErrorServiceDowntime').show();
				}
				self.Ajaxloader.hide();
			},
			complete: function (response) {
			}
		});

	}
});

