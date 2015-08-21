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

App.Components.WidgetGraphgeneratorComponent = Frontend.Component.extend({

	user_default_timeout: 2000,
	_service_rules_timeout_id: 0,
	_service_rules_remove_timeout_id: 0,

	setup: function(options, allWidgetParameters, Overlay){
		var self = this,
			defaults = {
				ids:[]
			};
		self.intervalIds = {};
		self.options = $.extend({}, defaults, options);

		self.options.Overlay.setup({$ui: $('.graphgenerator-body')})

		$(document).on('submit','.graphgenerator_form', function(event){
			event.preventDefault();

			var $form = $(this),
				widgetId = self.getWidgetId($form),
				dataToSave = self.getData($form),
				error = false,
				$widgetContainer = $form.parents('.graphgenerator-body');

			if($widgetContainer.find('.data-source-select-box').val()){
				$title = $widgetContainer.find('.data-source-select-box').val();
			}else{
				$title = '';
			}

			if(dataToSave.dataSources.length === 0){
				dataToSave.dataSources[0] = "1";
				$widgetContainer.find('.onoffswitch-checkbox').first().prop('checked',true);
			}

			self.options.loadWidgetData(widgetId, function(data){
				if(data.data.Widget.service_id){
					saveData = {
						'Widget': {
							'id' : parseInt(widgetId),
							'service_id' : parseInt(dataToSave.serviceId),
						},
						'WidgetGraphgenerator':{
							'id' : data.data.WidgetGraphgenerator.id,
							'data_sources' : dataToSave.dataSources.join(','),
							'time' : dataToSave.time
						}
					};
					self.options.saveWidgetData(saveData);

				}else{
					saveData = {
						'Widget': {
							'id' : widgetId,
							'service_id' : dataToSave.serviceId,
						},
						'WidgetGraphgenerator':{
							'widget_id' : parseInt(widgetId),
							'data_sources' : dataToSave.dataSources.join(','),
							'time' : dataToSave.time
						}
					};
					self.options.saveWidgetData(saveData);
				}
			});
			$widgetContainer.find('.graphGenerator').attr('id','graphGenerator'+widgetId);

			//self.options.Ajaxloader.show();
			self.updateGraphByServiceRules(widgetId);

			if(self.lastResponse.responseJSON.Service.check_interval){
				var checkInterval = self.lastResponse.responseJSON.Service.check_interval;
			}else{
				var checkInterval = self.lastResponse.responseJSON.Servicetemplate.check_interval;
			}
			checkInterval = checkInterval*1000;

			var intervalId = setInterval(function() {
				//self.options.Ajaxloader.show();
				self.updateGraphByServiceRules(widgetId);
			}, checkInterval);
			self.intervalIds[widgetId] = intervalId;

			//restart Tab Rotation if needed
			if(allWidgetParameters['tabRotation'].tabRotationInterval > 0){
				allWidgetParameters['tabRotation'].tabRotationInterval = allWidgetParameters['tabRotation'].tabRotationInterval - 2000;
				var intervalId = setInterval(function() {
					$('.rotateTabs').find('.fa-refresh').addClass('fa-spin');
					setTimeout(function(){
						allWidgetParameters['nextTab'].nextTab();
					},2000);
				}, allWidgetParameters['tabRotation'].tabRotationInterval);
				allWidgetParameters['tabRotation'].tabIntervalId = intervalId;
			}
		});

		$(document).on('change','.selectGraphgeneratorService', function(e){
			var $selectBox = $(this),
				$widgetContainer = $(this).parents('.graphgenerator-body'),
				currentId = self.getWidgetId($widgetContainer);

			$widgetContainer.find('.dataSourceButtons').html();
			$widgetContainer.find('.dataSourceButtonsHeadline').css('display','none');
			$widgetContainer.find('.dataSourceButtons').css('display','none');
			$widgetContainer.find('.graph2Warning').css('display','none');

			if(parseInt($selectBox.val()) !== 0){
				$widgetContainer.find('.data-source-select').css('display','block');
				$.ajax({
					url: "/admin/dashboard/getAllRelatedInfoForService/" + encodeURIComponent($selectBox.val())+".json",
					type: "POST",
					dataType: "json",
					error: function(){},
					success: function(){},
					complete: function(response){
						self.lastResponse = response;
						//console.log(response.responseJSON);
						if(response.responseJSON !== false){
							$widgetContainer.find('.graphgenerator_save').prop('disabled',false);
							$widgetContainer.find('.graphWarning').detach();
							var counter = 1,
								serviceUuid = response.responseJSON.Service.uuid,
								serviceName = response.responseJSON.Service.name,
								serviceId = encodeURIComponent($selectBox.val()),
								hostUuid = response.responseJSON.Host.uuid,
								hostName = response.responseJSON.Host.name;

							if(serviceName === '' || !serviceName){
								serviceName = response.responseJSON.Servicetemplate.name;
							}

							$widgetContainer.find('.dataSourceButtons').html('');
							$.each(response.responseJSON.Servicestatus.perfdata, function (index, value) {
								var $switchContainer = $('<div>', { 'class': 'switchContainer'}),
								$switch_span = $('<span>', { 'class': 'onoffswitch' }),
								$checkbox_input = $('<input>', {
									type: 'checkbox',
									class: 'onoffswitch-checkbox servicerule_control',
									id: 'serviceRule_'+currentId+'_' + $selectBox.val() + '_' + index,
									value: counter,
									data: {
										'service-rule-name': index,
										'service-uuid': serviceUuid,
										'service-name': serviceName,
										'service-id': serviceId,
										'host-uuid': hostUuid,
										'host-name': hostName
									}
								}),
								$label = $('<label>', {
									'for': 'serviceRule_'+currentId+'_' + $selectBox.val() + '_' + index,
									'class': 'onoffswitch-label'
								}),
								$span = $('<span>', {
									'data-swchon-text': index,
									'data-swchoff-text': index,
									'class': 'onoffswitch-inner'
								}),
								$other_span = $('<span>', { 'class': 'onoffswitch-switch' });

								$label
									.append($span)
									.append($other_span);

								$switch_span
									.append($checkbox_input)
									.append($label);

								$switchContainer.append($switch_span);

								$widgetContainer.find('.dataSourceButtons').append($switchContainer);

								$widgetContainer.find('.data-source-select-box').trigger('chosen:updated');
								counter++;
							});
							$widgetContainer.find('.dataSourceButtonsHeadline').css('display','block');
							$widgetContainer.find('.dataSourceButtons').css('display','flex');
							$widgetContainer.find('.selectTimeFrameContainer').css('display','block');
						}else{
							$widgetContainer.find('.selectGraphgenerator').append($('<div/>',{
								class : 'graphWarning',
								style : 'padding:10px; color:#FF0000;',
								text : 'No performance data for chosen service! Please select a different one.'
							}));
							$widgetContainer.find('.graphgenerator_save').prop('disabled',true);
						}
					}
				});
			}else{
				$widgetContainer.find('.data-source-select').css('display','none');
			}

		});

		var graphIds = $('.graphgenerator-body').each(function(i, elem){
			var currentId = self.getWidgetId($(elem)),
				$widgetContainer = $(elem),
				$form = $(elem).find('form');

			if(allWidgetParameters){
				if(typeof(allWidgetParameters[14][currentId]) !== "undefined"){
					if(typeof(allWidgetParameters[14][currentId].serviceGone) === 'undefined'){
						if(allWidgetParameters[14][currentId].data_sources === ""){
							var serviceRules = "1";
						}
						else{
							var serviceRules = allWidgetParameters[14][currentId].data_sources.split(",");
						}
						$widgetContainer.find('.selectTimeFrame').val(allWidgetParameters[14][currentId].time);
						$.ajax({
							url: "/admin/dashboard/getAllRelatedInfoForService/" +allWidgetParameters[14][currentId].service_id+".json",
							type: "POST",
							dataType: "json",
							async: false,
							error: function(){},
							success: function(){},
							complete: function(response){
								self.lastResponse = response;

								var counter = 1,
									serviceUuid = response.responseJSON.Service.uuid,
									serviceName = response.responseJSON.Service.name,
									serviceId = allWidgetParameters[14][currentId].service_id,
									hostUuid = response.responseJSON.Host.uuid,
									hostName = response.responseJSON.Host.name;

								if(serviceName === '' || !serviceName){
									serviceName = response.responseJSON.Servicetemplate.name;
								}

								$widgetContainer.find('.dataSourceButtons').html('');
								$.each(response.responseJSON.Servicestatus.perfdata, function (index, value) {
									var found = $.inArray(""+counter+"", serviceRules),
										checked = false;

									if(found !== -1){
										checked = true;
									}

									var $switchContainer = $('<div>', { 'class': 'switchContainer'}),
									$switch_span = $('<span>', { 'class': 'onoffswitch' }),
									$checkbox_input = $('<input>', {
										type: 'checkbox',
										class: 'onoffswitch-checkbox servicerule_control',
										id: 'serviceRule_'+currentId+'_' + allWidgetParameters[14][currentId].service_id + '_' + index,
										value: counter,
										checked: checked,
										data: {
											'service-rule-name': index,
											'service-uuid': serviceUuid,
											'service-name': serviceName,
											'service-id': serviceId,
											'host-uuid': hostUuid,
											'host-name': hostName
										}
									}),
									$label = $('<label>', {
										'for': 'serviceRule_'+currentId+'_' + allWidgetParameters[14][currentId].service_id + '_' + index,
										'class': 'onoffswitch-label'
									}),
									$span = $('<span>', {
										'data-swchon-text': index,
										'data-swchoff-text': index,
										'class': 'onoffswitch-inner'
									}),
									$other_span = $('<span>', { 'class': 'onoffswitch-switch' });

									$label
										.append($span)
										.append($other_span);

									$switch_span
										.append($checkbox_input)
										.append($label);

									$switchContainer.append($switch_span);

									$widgetContainer.find('.dataSourceButtons').append($switchContainer);

									$widgetContainer.find('.data-source-select-box').trigger('chosen:updated');
									counter++;
								});
								$widgetContainer.find('.graphgenerator_form').css('display','none');
								$widgetContainer.find('.dataSourceButtonsHeadline').css('display','none');
								$widgetContainer.find('.dataSourceButtons').css('display','none');
								$widgetContainer.find('.selectTimeFrameContainer').css('display','none');
								$widgetContainer.find('.graphGenerator').attr('id','graphGenerator'+currentId);

								//self.options.Ajaxloader.show();
								self.updateGraphByServiceRules(currentId);
								checkInterval = allWidgetParameters[14][currentId].check_interval*1000;

								var intervalId = setInterval(function() {
									//self.options.Ajaxloader.show();
									self.updateGraphByServiceRules(currentId);
								}, checkInterval);
								self.intervalIds[currentId] = intervalId;
							}

						});
					}else{
						$('<div/>',{
							class : 'graph2Warning',
							style : 'color:#FF0000; padding-top: 10px;',
							text : allWidgetParameters[14][currentId].serviceGone
						}).insertBefore($widgetContainer.find('.graphgenerator_form'));
					}
				}
			}
		});
	},

	countJson: function(obj) {
		return Object.keys(obj).length;
	},

	getData: function(f){
		var dataSourceButtons = [];
		$.each($(f).parents('.graphgenerator-body').find('.onoffswitch-checkbox'), function (index, value) {
			if($(value).prop('checked')){
				dataSourcebutton = $(value).val();
				dataSourceButtons.push(dataSourcebutton);
			}
		});

		var data = {
			'serviceId' : f.find('.selectGraphgeneratorService').val(),
			'dataSources' : dataSourceButtons,
			'time' : f.parents('.graphgenerator-body').find('.selectTimeFrame').val(),
		};
		return data;
	},

	/**
	 * @param {jQuery} $elem
	 * @return {number}
	 */
	getWidgetId: function($elem){
		var currentId = $elem.parents('.grid-stack-item').attr('widget-id');
		if(currentId === null){
			currentId = 0;
		}
		return currentId;
	},

	updateGraphByServiceRules: function(widgetId){
		var self = this,
			widgetId = widgetId,
			service_rules = self.getCurrentServiceRules(widgetId),
			time_period = self.getConfiguredTimePeriod(widgetId),
			host_and_service_uuids = {},
			service_uuid,
			host_uuid;

		if(Object.keys(service_rules).length == 0){
			//self.options.Ajaxloader.hide();
			return;
		}

		for(host_uuid in service_rules){
			if(typeof host_and_service_uuids[host_uuid] !== 'object'){
				host_and_service_uuids[host_uuid] = [];
			}

			for(service_uuid in service_rules[host_uuid]){
				host_and_service_uuids[host_uuid].push(service_uuid);
			}
		}
		var $widgetContainer = $('[widget-id="'+widgetId+'"]');

		$widgetContainer.find('.graphgenerator_form').css('display','none');
		$widgetContainer.find('.dataSourceButtonsHeadline').css('display','none');
		$widgetContainer.find('.dataSourceButtons').css('display','none');
		$widgetContainer.find('.selectTimeFrameContainer').css('display','none');

		if(parseInt($widgetContainer.find('.graphContainer').height()) === 0){
			$widgetContainer.find('.graphgenerator-body').css('height','88%');
			if(parseInt($widgetContainer.find('.graphgenerator-body').height()) === 87){
				$widgetContainer.find('.graphgenerator-body').height($widgetContainer.height()-65);
				$widgetContainer.find('.graphgenerator-body').attr('height','250px');
			}
			$widgetContainer.find('.graphContainer').css('height','95%');
		}

		self.options.Overlay.deactivateUi();

		self.options.Rrd.setup({
			url: '/Graphgenerators/fetchGraphData/'+widgetId+'.json',
			host_and_service_uuids: host_and_service_uuids,
			selector: '#graphGenerator'+widgetId,
			height: '90%',
			async: false,
			timezoneOffset: self.options.Time.timezoneOffset,
			timeout_in_ms: self.user_default_timeout,
			error_callback: function(response, status){
				self.options.Overlay.activateUi();
				self.options.BootstrapModal.show('request-took-to-long');
			},
			flot_options: {
				zoom: {
					interactive: false // Deactivates zoom.
				},
				pan: {
					interactive: false // Deactivates pan.
				}
			},
			update_plot: function(event, plot, action){
				var axes = plot.getAxes(),
					min = axes.xaxis.min.toFixed(2),
					max = axes.xaxis.max.toFixed(2),
					start_timestamp = parseInt(min, 10),
					end_timestamp = parseInt(max, 10),
					start_date = new Date(parseInt(min, 10)),
					end_date = new Date(parseInt(max, 10)),
					formatted_start_date = sprintf('%d.%d.%d %02d:%02d:%02d',
						start_date.getDate(), start_date.getMonth() + 1, start_date.getFullYear(),
						start_date.getHours(), start_date.getMinutes(), start_date.getSeconds()),
					formatted_end_date = sprintf('%d.%d.%d %02d:%02d:%02d',
						end_date.getDate(), end_date.getMonth() + 1, end_date.getFullYear(),
						end_date.getHours(), end_date.getMinutes(), end_date.getSeconds()),
					time_range = {
						start: start_timestamp,
						end: end_timestamp
					},
					update_plot_this = this;

				// Clear current timeout
				if(self._service_rules_timeout_id > 0){
					clearTimeout(self._service_rules_timeout_id);
					self._service_rules_timeout_id = 0;
				}

				// Set new timeout
				self._service_rules_timeout_id = setTimeout(function(){
					self.options.Overlay.deactivateUi();
					$widgetContainer.find('#GraphgeneratorStart').val(formatted_start_date);
					$widgetContainer.find('#GraphgeneratorEnd').val(formatted_end_date);
					update_plot_this.drawServiceRules(self.getCurrentServiceRules(widgetId), time_range, function(){
						self.options.Overlay.activateUi();
					});
				}, self.user_default_timeout);
			}
		});
		self.options.Rrd.drawServiceRules(service_rules, time_period, function(){
			$widgetContainer.find('.graph_legend').show();
			//self.options.Ajaxloader.hide();
			self.options.Overlay.activateUi();
		});
	},

	getCurrentServiceRules: function(widgetId){
		var service_rules = {},
			widgetId = widgetId,
			$widgetContainer = $('[widget-id="'+widgetId+'"]');

		var $serviceRules = $widgetContainer.find('.servicerule_control');
		$.each($serviceRules,function(index, checkbox){
			var $checkbox = $(checkbox);
						if($checkbox.length == 0 || $checkbox.prop('checked') == false){
				return;
			}
			var ds_number = $checkbox.val(),
				service_rule_name = $checkbox.data('service-rule-name'),
				service_id = $checkbox.data('service-id'),
				service_name = $checkbox.data('service-name'),
				service_uuid = $checkbox.data('service-uuid'),
				host_uuid = $checkbox.data('host-uuid'),
				host_name = $checkbox.data('host-name');


			if(typeof service_rules[widgetId] !== 'object'){
				service_rules[widgetId] = {};
			}
			if(typeof service_rules[widgetId][host_uuid] !== 'object'){
				service_rules[widgetId][host_uuid] = {};
			}
			if(typeof service_rules[widgetId][host_uuid][service_uuid] !== 'object'){
				service_rules[widgetId][host_uuid][service_uuid] = {};
			}
			if(typeof service_rules[widgetId][host_uuid][service_uuid][ds_number] !== 'object'){
				service_rules[widgetId][host_uuid][service_uuid][ds_number] = {};
			}

			service_rules[widgetId][host_uuid][service_uuid][ds_number]['service_id'] = service_id;
			service_rules[widgetId][host_uuid][service_uuid][ds_number]['service_name'] = service_name;
			service_rules[widgetId][host_uuid][service_uuid][ds_number]['service_rule_name'] = service_rule_name;
			service_rules[widgetId][host_uuid][service_uuid][ds_number]['host_name'] = host_name;
		});
		return service_rules[widgetId];
	},

	getConfiguredTimePeriod: function(widgetId){
		var self = this,
			$field = $('[widget-id="'+widgetId+'"]').find('.selectTimeFrame'),
			now = parseInt(self.options.Time.getCurrentTimeWithOffset(0).getTime() / 1000, 10),
			substract_seconds,
			result;

		if($field.length > 0){
			substract_seconds = parseInt($field.val(), 10);
		}else{
			substract_seconds = 3600 * 3;
		}

		result = {
			'start': now - substract_seconds,
			'end': now,
		};
		return result;
	},

})
