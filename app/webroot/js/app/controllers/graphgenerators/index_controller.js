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

App.Controllers.GraphgeneratorsIndexController = Frontend.AppController.extend({
	host_uuid: null,
	host_name: '',
	service_uuid: null,
	service_name: '',
	currently_loaded_service_rules: {}, // Keeps track of the currently chosen and therefore applied service rules.
	user_default_timeout: 2000,
	debug: false,

	_service_rules_timeout_id: 0,
	_service_rules_remove_timeout_id: 0,

	components: ['Ajaxloader', 'Rrd', 'BootstrapModal', 'Overlay'],

	_initialize: function(){
		this.Ajaxloader.setup();
		this.BootstrapModal.setup({
			content: window.bootstrapModalContent,
			on_close: function(){
				$('#saveGraph').prop('disabled', false);
			}
		});
		this.Overlay.setup({$ui: $('#widget-grid')});

		this.$services_select_box = $('#GraphgeneratorServiceUuid');

		this.bindChangeEventForHostSelectBox(); // Retrieve the services of the chosen host when the host is changed.
		this.bindChangeEventForServicesSelectBox(); // Retrieve the services rules for when the services is changed.
		this.bindChangeEventForServiceRules(); // Fetches the data and updates the graph when the service rules are changed.
		this.bindClickEventForRefreshGraphButton();
		this.bindClickEventForResetGraphButton();

		this.initValidation(); // This is done once.
		this.bindClickEventForSave();
		this.deselectHostSelectBox(); // Necessity for Firefox.

		this.renderGraphConfiguration(window.App.loaded_graph_config);
	},

	/**
	 * Renders the `Servicerules` if a valid configurationw as given.
	 * @param config
	 */
	renderGraphConfiguration: function(config){
		if(Object.keys(config).length == 0){
			if(this.debug){
				console.info('No configuration found to load');
			}
			return; // No configuration loaded.
		}
		config = config['HostAndServices'];

		for(var host_id in config){
			for(var service_id in config[host_id]['services']){
				//console.log(host_id + ' ' + service_uuid);
				var host_uuid = config[host_id]['host_uuid'],
					host_name = config[host_id]['host_name'],
					service_uuid = config[host_id]['services'][service_id]['service_uuid'],
					service_name = config[host_id]['services'][service_id]['service_name'],
					data_sources = config[host_id]['services'][service_id]['data_sources'],
					host = {
						id: host_id,
						uuid: host_uuid,
						name: host_name
					},
					service = {
						id: service_id,
						uuid: service_uuid,
						name: service_name
					},
					onComplete = function(){
						for(var i in data_sources){
							var ds = data_sources[i],
								$obj = $('#AjaxServicerule_' + service_uuid + '_' + ds);

							if($obj.length == 0){
								return;
							}

							$obj
								.prop('checked', true)
								.trigger('change');
						}
					};

				this._loadServiceRule(host, service, onComplete);
			}
		}
	},

	deselectHostSelectBox: function(){
		$('#GraphgeneratorHostUuid').find(':selected').prop('selected', false).trigger('chosen:updated');
	},

	initValidation: function(){
		$.validator.addMethod(
			'custom_datetime',
			function(value, element){
				var matches = value.match(/^(\d{2})\.(\d{2})\.(\d{4}) (\d{2}):(\d{2}):(\d{2})$/);
				if(matches === null){
					return false;
				}else{
					// now lets check the date sanity
					var year = parseInt(matches[3], 10);
					var month = parseInt(matches[2], 10) - 1; // months are 0-11
					var day = parseInt(matches[1], 10);
					var hour = parseInt(matches[4], 10);
					var minute = parseInt(matches[5], 10);
					var second = parseInt(matches[6], 10);
					var date = new Date(year, month, day, hour, minute, second);

					return !(date.getFullYear() !== year
						|| date.getMonth() != month
						|| date.getDate() !== day
						|| date.getHours() !== hour
						|| date.getMinutes() !== minute
						|| date.getSeconds() !== second);
				}
			},
			'Please enter a valid date and time.'
		);

		$('#GraphgeneratorIndexForm').validate({
			//debug: true,
			//errorClass: 'has-error',
			//validClass: 'has-success',
			rules: {
				'data[Graphgenerator][name]': {
					required: true,
					minlength: 3
				},
				'data[Graphgenerator][time]': {
					required: true,
					minlength: 3
				}
			},
			messages: {
				// Just to provide an example
				//'data[Graphgenerator][graph_configuration_name]': {
				//	required: 'It is aber required!'
				//}
			},
			errorPlacement: function($error, $element){
				var $icon = $('<i>', {
						class: 'fa fa-exclamation-triangle',
						'data-toggle': 'tooltip',
						'data-placement': 'right',
						title: $error.text()
					}).css({
						'font-size': '22px',
						color: '#a11b1b',
						padding: '0 5px',
						transition: 'all 2'
					}),
					$warning = $('<div>', {
						class: 'icon-warning input-group-btn'
					}).css({
						'transition': 'all 2s'
					}).append($icon);

				$icon.tooltip();
				var $parent = $element.parent('.input-group');

				var $icon_warning = $parent.find('.icon-warning');
				if($icon_warning.length){
					$icon_warning.find('.fa').tooltip('destroy');
					$icon_warning.remove();
				}

				$parent.append($warning);
				$icon.show();

				return true;
			},
			highlight: function(element){
				$(element).parents('.input-group').removeClass('has-success').addClass('has-error');
			},
			unhighlight: function(element){
				var $parent = $(element).parents('.input-group');
				$parent.removeClass('has-error').addClass('has-success');
				$parent.find('.icon-warning').html('');
			}
		});
	},

	/**
	 * @param {Object} data
	 * @param {Function} on_complete
	 */
	_saveGraphTemplate: function(data, on_complete){
		var self = this,
			url = '/graphgenerators/saveGraphTemplate.json';

		data = typeof data === 'object' ? data : {};
		self.xhrRequestData(url, on_complete, data);
	},

	bindClickEventForSave: function(){
		var self = this,
			onSaveClickCallback = function(){
				var $this = $(this),
					$form = $this.parents('form');

				// Fix all forms without name -> jquery.validate requires all inputs to have a name...
				$form.find('input:not([name])').attr('name', 'noname');
				$this.prop('disabled', true);

				if(!$form.valid()){
					// TODO replace both statements with a notification
					console.warn('Form isn\'t valid. The data wasn\'t saved.');
					$this.prop('disabled', false);

					return;
				}

				var name = $form.find('#GraphgeneratorName').val(),
					time = $form.find('#GraphgeneratorRelativeTime').val(),
					save_service_rules = self.getCurrentServiceRulesForSave(),
					submitData = {
						GraphgenTmpl: {
							name: name,
							relative_time: time
						},
						GraphgenTmplConf: []
					};

				if( App.loaded_graph_config != null &&
					App.loaded_graph_config.GraphgenTmpl != null &&
					App.loaded_graph_config.GraphgenTmpl.id > 0
				){
					submitData.GraphgenTmpl.id = App.loaded_graph_config.GraphgenTmpl.id;
				}

				for(var host_uuid in save_service_rules){
					for(var service_id in save_service_rules[host_uuid]){
						submitData['GraphgenTmplConf'].push({
							//graphgen_tmpl_id: App.loaded_graph_config.GraphgenTmpl.id,
							service_id: service_id,
							data_sources: JSON.stringify(save_service_rules[host_uuid][service_id])
						});
					}
				}

				if(Object.keys(save_service_rules).length == 0){
					self.BootstrapModal.show('was-not-saved-no-service-chosen', true);

					return;
				}
				self._saveGraphTemplate(submitData, function(response){
					if(response.responseJSON && response.responseJSON.success){
						self.BootstrapModal.show('successfully-saved');
					}else{
						self.BootstrapModal.show('not-saved');
					}
				});
			};

		$('#saveGraph').on('click', onSaveClickCallback);
	},

	/**
	 * @returns {Object} - An array of objects.
	 */
	getCurrentServiceRulesForSave: function(){
		var service_rules = {};

		$('.servicerule_control').each(function(index, checkbox){
			var $checkbox = $(checkbox);

			if($checkbox.length == 0 || $checkbox.prop('checked') == false){
				return;
			}

			var ds_number = $checkbox.val(),
				service_id = $checkbox.data('service-id'),
				host_uuid = $checkbox.data('host-uuid');

			if(typeof service_rules[host_uuid] !== 'object'){
				service_rules[host_uuid] = {};
			}
			if(typeof service_rules[host_uuid][service_id] !== 'object'){
				service_rules[host_uuid][service_id] = [];
			}

			service_rules[host_uuid][service_id].push(ds_number);
		});

		return service_rules;
	},

	/**
	 * @returns {Object} - An array of objects.
	 */
	getCurrentServiceRules: function(){
		var service_rules = {};

		$('.servicerule_control').each(function(index, checkbox){
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

			if(typeof service_rules[host_uuid] !== 'object'){
				service_rules[host_uuid] = {};
			}
			if(typeof service_rules[host_uuid][service_uuid] !== 'object'){
				service_rules[host_uuid][service_uuid] = {};
			}
			if(typeof service_rules[host_uuid][service_uuid][ds_number] !== 'object'){
				service_rules[host_uuid][service_uuid][ds_number] = {};
			}

			service_rules[host_uuid][service_uuid][ds_number]['service_id'] = service_id;
			service_rules[host_uuid][service_uuid][ds_number]['service_name'] = service_name;
			service_rules[host_uuid][service_uuid][ds_number]['service_rule_name'] = service_rule_name;
			service_rules[host_uuid][service_uuid][ds_number]['host_name'] = host_name;
		});

		return service_rules;
	},

	/**
	 * @param {String} url
	 * @param {Function} on_complete
	 * @param {Object} data The data to be transmitted.
	 * @param {Object} options The additional parameters to jQuery's $.ajax() function.
	 */
	xhrRequestData: function(url, on_complete, data, options){
		data = (data == null ? {} : data);
		options = (options == null ? {} : options);

		var self = this,
			defaults = {
				url: url,
				type: 'post',
				data: data,
				dataType: 'json',
				error: function(){},
				success: function(){},
				complete: function(response){
					on_complete(response);
					self.Ajaxloader.hide();
				}
			};
		$.extend(defaults, options);

		self.Ajaxloader.show();
		$.ajax(defaults);
	},

	bindChangeEventForHostSelectBox: function(){
		var self = this;

		$('#GraphgeneratorHostUuid').change(function(){
			var host_id = $(this).val(),
				host_name = $(this).find(':selected').text(),
				$services_select_box = self.$services_select_box,
				onComplete = function(response){
					$services_select_box
						.html('')
						.trigger('chosen:updated')
						.trigger('chosen:activate');

					if(!response.responseJSON || response.responseJSON.sizeof <= 0){
						return;
					}

					var response_json = response.responseJSON,
						content = [],
						index;

					for(index in response_json.Services){
						content.push(
							$('<option>', {
								value: response_json.Services[index].uuid,
								text: response_json.Services[index].name,
								data: {
									'service-id': response_json.Services[index].service_id
								}
							})
						);
					}
					$.fn.append.apply($services_select_box, content);

					$services_select_box
						.val(null)
						.trigger('chosen:updated')
						.trigger('chosen:activate');

					self._updateServicesSelectBox();
				};

			//console.info(host_uuid);
			if(host_id == '0'){ // The user has clicked on the 'X' to deselect the currently selected item.
				self.$services_select_box
					.html('')
					.trigger('chosen:updated');
				$(this).trigger('chosen:activate');

				return; // Prevent a XHR request.
			}

			// Store the uuid for further uses. The services select box uses this value.
			self.host_uuid = window.App.host_uuids[host_id];
			self.host_id = host_id;
			self.host_name = host_name;

			// Do the AJAX request and execute the callback function.
			self._loadServicesByHostId(host_id, onComplete);
		});
	},

	/**
	 * @param {number} host_id
	 * @param {function} on_complete
	 */
	_loadServicesByHostId: function(host_id, on_complete){
		on_complete = typeof on_complete == 'function' ? on_complete : function(){};
		var url = '/Graphgenerators/loadServicesByHostId/' + parseInt(host_id, 10) + '.json',
			self = this;

		self.xhrRequestData(url, on_complete);
	},


	bindChangeEventForServicesSelectBox: function(){
		var self = this;

		self.$services_select_box.change(function(){
			if(!self.host_uuid){
				return;
			}

			var $serviceSelectBox = $(this);

			self.service_uuid = $serviceSelectBox.val();
			self.service_name = $serviceSelectBox.find(':selected').text();
			self.service_id = $serviceSelectBox.find(':selected').data('service-id');

			var host = {
					id: self.host_id,
					uuid: self.host_uuid,
					name: self.host_name
				},
				service = {
					id: self.service_id,
					uuid: self.service_uuid,
					name: self.service_name
				},
				on_complete = function(){
					// Update the select box. Gray out already chosen items.
					self._updateServicesSelectBox();

					// Select the first value and update the fancy chosen select box.
					$serviceSelectBox.val(null).trigger('chosen:updated');
				};

			self._loadServiceRule(host, service, on_complete);
		});
	},

	/**
	 * @param {Object} host With keys `uuid`, `id` and `name`.
	 * @param {Object} service With keys `uuid`, `id` and `name`.
	 * @param {Function} on_complete
	 */
	_loadServiceRule: function(host, service, on_complete){
		on_complete = typeof on_complete == 'function' ? on_complete : function(){};

		var self = this,
			url = '/Graphgenerators/loadServiceruleFromService/' + encodeURIComponent(host.uuid) +
				'/' + encodeURIComponent(service.uuid) + '.json',
			$target = $('#serviceRules'),

			on_complete_xhr = function(response){
				if(!response.responseJSON || response.responseJSON.sizeof <= 0)
					return;

				var response_json = response.responseJSON,
					perfdata_structure = response_json.perfdataStructure,
					$chosen_div;

				$chosen_div = self._addServiceRuleEntry(perfdata_structure, host, service);

				$target.append($chosen_div); // Append the result to the DOM.
				$chosen_div.slideDown('fast'); // Displays it with an effect.

				if(typeof self.currently_loaded_service_rules[host.id] != 'object'){
					self.currently_loaded_service_rules[host.id] = {};
				}

				self.currently_loaded_service_rules[host.id][service.uuid] = true;

				on_complete($chosen_div);
			};

		// Do the AJAX request and execute the callback function.
		if(service.uuid != '0'){
			self.xhrRequestData(url, on_complete_xhr, {}, {
				async: false
			});
		}
	},

	/**
	 * @param {Array} perfdata_structure
	 * @param {Object} host With key `name`.
	 * @param {Object} service With key `name`.
	 * @returns {jQuery}
	 */
	_addServiceRuleEntry: function(perfdata_structure, host, service){
		var self = this,
			rows = [],
			key,

			$chosen_div = $('<div>', {
				class: 'chosen-service',
				style: 'display: none' // this allows to slide it down (display it with an effect of jQuery)
			}),
			$titleRowDiv = $('<div>', {
				class: 'row title-row'
			}),
			$title_host_name = $('<span>', {
				text: host.name,
				class: 'col-md-5 title',
				style: 'overflow: hidden'
			}),
			$title_service_name = $('<span>', {
				text: service.name,
				class: 'col-md-5 title',
				style: 'overflow: hidden'
			}),
			$removeIcon = $('<a>', {
				class: 'glyphicon glyphicon-remove',
				title: 'Remove this service',
				css: {
					cursor: 'pointer',
					'text-decoration': 'none'
				},
				click: function(){
					var $this = $(this),
						$chosen_service = $this.parents('.chosen-service'),
						$service_rule = $chosen_service.find('input.servicerule_control'),
						host_uuid = $service_rule.data('host-uuid'),
						service_uuid = $service_rule.data('service-uuid');

					// Set the state of the removed service to 'unloaded'.
					self.currently_loaded_service_rules[host.id][service_uuid] = false;

					// Hide it with a pretty animation and finally remove it.
					$chosen_service.slideUp('fast', function(){
						$(this).remove();

						if(self._service_rules_timeout_id != 0){
							clearTimeout(self._service_rules_timeout_id);
						}

						self._service_rules_timeout_id = setTimeout(function(){
							var time_period = self.getConfiguredTimePeriod(),
								service_rules = self.getCurrentServiceRules();

							if(Object.keys(service_rules).length == 0){ // Last entry was removed.
								self.Rrd.resetGraph();
								$('.graph_legend').hide();
							} else { // Update the graph
								self.Overlay.deactivateUi();
								self.Rrd.drawServiceRules(service_rules, time_period, function(){
									self.Overlay.activateUi();
								});
							}

							self._updateServicesSelectBox(); // Update the select box - re-activates the item.
						}, self.user_default_timeout);
					});
				}
			});

		for(key in perfdata_structure){
			var current_object = perfdata_structure[key],
				ds = current_object.ds,
				name = current_object.name,
				unit = current_object.unit,
				$row = $('<div>', { 'class': 'row' }),
				$ajax_service_rule_label = $('<label>', {
					'class': 'col col-md-6 control-label text-left',
					'text': name + ' (' + unit + ')'
				}),
				$switch_span = $('<span>', { 'class': 'onoffswitch' }),
				$checkbox_input = $('<input>', {
					type: 'checkbox',
					class: 'onoffswitch-checkbox servicerule_control',
					id: 'AjaxServicerule_' + service.uuid + '_' + ds,
					value: ds,
					data: {
						'service-rule-name': name,
						'service-uuid': service.uuid,
						'service-name': service.name,
						'service-id': service.id,
						'host-uuid': host.uuid,
						'host-name': host.name
					}
				}),
				$label = $('<label>', {
					'for': 'AjaxServicerule_' + service.uuid + '_' + ds,
					'class': 'onoffswitch-label'
				}),
				$span = $('<span>', {
					'data-swchon-text': name,
					'data-swchoff-text': name,
					'class': 'onoffswitch-inner'
				}),
				$other_span = $('<span>', { 'class': 'onoffswitch-switch' });

			$label
				.append($span)
				.append($other_span);

			$switch_span
				.append($checkbox_input)
				.append($label);

			$row
				.append($ajax_service_rule_label)
				.append($switch_span);

			rows.push($row);
		}

		$titleRowDiv.append($title_host_name);
		$titleRowDiv.append($title_service_name);
		$titleRowDiv.append('&nbsp;');
		$titleRowDiv.append($removeIcon);

		$chosen_div.append($titleRowDiv);
		$.fn.append.apply($chosen_div, rows);

		return $chosen_div;
	},

	/**
	 * Bind the change event for the Servicerule switches.
	 */
	bindChangeEventForServiceRules: function(){
		var self = this;

		$(document).on('change', '.servicerule_control', function(){
			if(self._service_rules_timeout_id != 0){
				clearTimeout(self._service_rules_timeout_id);
				self._service_rules_timeout_id = 0;
			}
			self._service_rules_timeout_id = setTimeout(function(){
				self.Ajaxloader.show();
				self._updateGraphByServiceRules();
				clearTimeout(self._service_rules_timeout_id);
				self._service_rules_timeout_id = 0;
			}, self.user_default_timeout);
		});
	},

	_updateGraphByServiceRules: function(){
		var self = this,
			service_rules = self.getCurrentServiceRules(),
			time_period = self.getConfiguredTimePeriod(),
			host_and_service_uuids = {},
			service_uuid,
			host_uuid;

		// No update (and no deactivation of the UI of the user) when no services rules are activated!
		if(Object.keys(service_rules).length == 0){
			self.Ajaxloader.hide();
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

		self.Overlay.deactivateUi();

		self.Rrd.setup({
			url: '/Graphgenerators/fetchGraphData/.json',
			host_and_service_uuids: host_and_service_uuids,
			selector: '#graph',
			height: '350px',
			timezoneOffset: this.Time.timezoneOffset, //Rename to user timesone offset
			timeout_in_ms: self.user_default_timeout,
			error_callback: function(response, status){
				self.Overlay.activateUi(); // Allow the user to use the UI again.
				self.BootstrapModal.show('request-took-to-long');
			},
			// Caution: This function is a little tricky as it uses it's own context and the context of RrdComponent!
			update_plot: function(event, plot, action){
				var axes = plot.getAxes(),
					min = axes.xaxis.min.toFixed(2),
					max = axes.xaxis.max.toFixed(2),
					start_timestamp = parseInt(min, 10), // Timestamp in seconds
					end_timestamp = parseInt(max, 10),
					start_date = new Date(parseInt(min, 10)), // Timestamp in milliseconds
					end_date = new Date(parseInt(max, 10)),
					formatted_start_date = sprintf('%d.%d.%d %02d:%02d:%02d',
						start_date.getDate(), start_date.getMonth() + 1, start_date.getFullYear(),
						start_date.getHours(), start_date.getMinutes(), start_date.getSeconds()),
					formatted_end_date = sprintf('%d.%d.%d %02d:%02d:%02d',
						end_date.getDate(), end_date.getMonth() + 1, end_date.getFullYear(),
						end_date.getHours(), end_date.getMinutes(), end_date.getSeconds()),
					time_range = { // Convert the timestamp from ms to seconds.
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
					self.Overlay.deactivateUi();
					$('#GraphgeneratorStart').val(formatted_start_date);
					$('#GraphgeneratorEnd').val(formatted_end_date);
					update_plot_this.drawServiceRules(self.getCurrentServiceRules(), time_range, function(){
						self.Overlay.activateUi();
					});
				}, self.user_default_timeout);
			}
		});

		self.Rrd.drawServiceRules(service_rules, time_period, function(){
			$('.graph_legend').show();
			self.Ajaxloader.hide();
			self.Overlay.activateUi();
		});

		$('#resetGraph').show();
	},

	_updateServicesSelectBox: function(){
		var self = this,
			$service_select_box = $('#GraphgeneratorServiceUuid');

		if(!self.currently_loaded_service_rules[self.host_id]){
			return; // Do nothing.
		}

		var activated_service_uuids = [];
		for(var loaded_service_uuid in self.currently_loaded_service_rules[self.host_id]){
			if(!self.currently_loaded_service_rules[self.host_id][loaded_service_uuid]){
				continue;
			}

			activated_service_uuids.push(loaded_service_uuid);
		}

		var all_service_uuids = [];
		$service_select_box.find('option').each(function(){
			if($(this).val() == '0'){
				return; // Jump over empty item.
			}
			all_service_uuids.push($(this).val());
		});

		// Deactivate all active items in the select box.
		for(var i = 0; i < all_service_uuids.length; i++){
			var service_uuid = all_service_uuids[i],
				$option = $service_select_box.find('option[value="' + service_uuid + '"]');

			if($.inArray(service_uuid, activated_service_uuids) != -1){ // Item found.
				$option.attr('disabled', ''); // Gray out the active items.
			}else{
				$option.removeAttr('disabled'); // Activate all other items.
			}
		}

		$service_select_box.trigger('chosen:updated'); // Update the fancy checkbox.
	},

	/**
	 * Returns the currently configured time period.
	 *
	 * @return {Object} The configured time period in the 'start' and 'end' fields as timestamps.
	 * 					If the field couldn't be found, the default value will be returned.
	 * 					The default value is 3600 seconds (3 hours).
	 */
	getConfiguredTimePeriod: function(){
		//console.log(this.Time.getCurrentTimeWithOffset(0).getTime());
		var $field = $('#GraphgeneratorRelativeTime'),
			now = parseInt(this.Time.getCurrentTimeWithOffset(0).getTime() / 1000, 10),
			substract_seconds,
			result;

		if($field.length > 0){
			substract_seconds = parseInt($field.val(), 10);
		}else{
			substract_seconds = 3600 * 3;
		}

		result = {
			'start': now - substract_seconds,
			'end': now
		};
		//console.log(result);

		return result;
	},

	bindClickEventForResetGraphButton: function(){
		var self = this;

		$('#resetGraph').click(function(){
			self.Rrd.resetGraph();
			$('#serviceRules').html('');

			// Reset 'Starttime' and 'Endtime' field to it's default
			var $start_time_text_field = $('#GraphgeneratorStart'),
				$stop_time_text_field = $('#GraphgeneratorEnd');

			$start_time_text_field.val($start_time_text_field.data('default-date'));
			$stop_time_text_field.val($stop_time_text_field.data('default-date'));

			// Reset 'Host' select box
			$('#GraphgeneratorHostUuid').val(null).trigger('chosen:updated');

			// Reset 'Services' select box
			self.$services_select_box.val(null).trigger('chosen:updated');

			// Resets the loaded services rules
			self.currently_loaded_service_rules = [];

			$('.graph_legend').hide();
		});
	},

	bindClickEventForRefreshGraphButton: function(){
		var self = this;

		$('#refreshGraph').on('click', function(){
			self.Ajaxloader.show();

			// Clear the timeout, if any.
			if(self._service_rules_timeout_id != 0){
				clearTimeout(self._service_rules_timeout_id);
				self._service_rules_timeout_id = 0;
			}
			self._updateGraphByServiceRules();
		});
	}
});
