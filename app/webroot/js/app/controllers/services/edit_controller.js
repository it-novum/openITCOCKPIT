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

App.Controllers.ServicesEditController = Frontend.AppController.extend({
	$contacts: null,
	$contactgroups: null,
	$tagsinput: null,
	lang: null,

	components: ['Highlight', 'Ajaxloader', 'CustomVariables'],

	_initialize: function() {
		var self = this;
		this.Ajaxloader.setup();
		this.CustomVariables.setup({
			controller: 'Services',
			ajaxUrl: 'addCustomMacro',
			macrotype: 'SERVICE',
			customVariablesCounter: this.getVar('customVariablesCount') + 1,
			onClick: function(){
				self.hosttemplateManager._onChangeMacro();
				self.hosttemplateManager._activateOrUpdateMacroRestore();
			}
		});
		
		/* Contact inherit stuff */
		$('#inheritContacts').click(function(){
			this.inherit();
		}.bind(this));
		
		if(this.getVar('ContactsInherited').inherit == true){
			$('#serviceContactSelects').block({
				message: null,
				overlayCSS: {
					opacity: 0.0,
					cursor: 'not-allowed'
				}
			});
		}
		/* Contact inherit stuff end */
		
		/*
		 * Fix chosen width, if rendered in a tab
		 */
		$("[data-toggle='tab']").click(function(){
			$('.chosen-container').css('width', '100%');
		});

		this.$contacts = $('#ServiceContact');
		this.$contactgroups = $('#ServiceContactgroup');

		this.lang = [];
		this.lang[1] = this.getVar('lang_minutes');
		this.lang[2] = this.getVar('lang_seconds');
		this.lang[3] = this.getVar('lang_and');

		this.fieldMap = {};
		//Default HTML input fields
		this.fieldMap['description'] = 'Description';
		this.fieldMap['notes'] = 'Notes';
		this.fieldMap['max_check_attempts'] = 'MaxCheckAttempts';
		this.fieldMap['name'] = 'Name';

		//chosen boxes
		this.fieldMap['command_id'] = 'CommandId';
		this.fieldMap['notify_period_id'] = 'NotifyPeriodId';
		this.fieldMap['check_period_id'] = 'CheckPeriodId';

		//checkboxes
		this.fieldMap['notify_on_recovery'] = 'NotifyOnRecovery';
		this.fieldMap['notify_on_warning'] = 'NotifyOnWarning';
		this.fieldMap['notify_on_unknown'] = 'NotifyOnUnknown';
		this.fieldMap['notify_on_critical'] = 'NotifyOnCritical';
		this.fieldMap['notify_on_flapping'] = 'NotifyOnFlapping';
		this.fieldMap['notify_on_downtime'] = 'NotifyOnDowntime';
		this.fieldMap['flap_detection_enabled'] = 'FlapDetectionEnabled';
		this.fieldMap['flap_detection_on_ok'] = 'FlapDetectionOnOk';
		this.fieldMap['flap_detection_on_warning'] = 'FlapDetectionOnWarning';
		this.fieldMap['flap_detection_on_unknown'] = 'FlapDetectionOnUnknown';
		this.fieldMap['flap_detection_on_critical'] = 'FlapDetectionOnCritical';
		this.fieldMap['is_volatile'] = 'IsVolatile';
		this.fieldMap['freshness_checks_enabled'] = 'FreshnessChecksEnabled';
		this.fieldMap['freshness_threshold'] = 'FreshnessThreshold';
		this.fieldMap['command_id'] = 'CommandId';
		this.fieldMap['eventhandler_command_id'] = 'EventhandlerCommandId';
		this.fieldMap['process_performance_data'] = 'ProcessPerformanceData';
		this.fieldMap['active_checks_enabled'] = 'ActiveChecksEnabled';

		//slider
		this.fieldMap['check_interval'] = 'Checkinterval';
		this.fieldMap['retry_interval'] = 'Retryinterval';
		this.fieldMap['notification_interval'] = 'Notificationinterval';

		//Fancy javascript junk
		this.fieldMap['tags'] = 'Tags';
		this.fieldMap['priority'] = 'stars-rating-5';

		this.fieldMap['contact'] = 'Contact';
		this.fieldMap['contactgroup'] = 'Contactgroup';

		// Render fancy tags input
		this.$tagsinput = $('.tagsinput');
		this.$tagsinput.tagsinput();

		// Flapdetection checkbox control
		$('input[type="checkbox"]#ServiceFlapDetectionEnabled').on('change.flapDetect', this.checkFlapDetection);

		this.checkFlapDetection();

		/*
		 * Freshness settings checkbox control
		 */
		$('input[type="checkbox"]#ServiceFreshnessChecksEnabled').on('change.fresshnessChecks', function(){
			this.checkFreshnessSettings();
		}.bind(this));
		this.checkFreshnessSettings();

		var $serviceNotificationIntervalField = $('#ServiceNotificationinterval');
		var onSlideStop = function(ev){
			if(ev.value == null){
				ev.value = 0;
			}

			$('#_' + $(this).attr('id')).val(ev.value);
			$(this)
				.val(ev.value)
				.trigger('change');
			var min = parseInt(ev.value / 60, 10);
			var sec = parseInt(ev.value % 60, 10);
			$($(this).attr('human')).html(min + " " + self.lang[1] + " " + self.lang[3] + " " + sec + " " + self.lang[2]);
		};

		var $slider = $('input.slider');
		$slider.slider({ tooltip: 'hide' });
		$slider.slider('on', 'slide', onSlideStop);
		$slider.slider('on', 'slideStop', onSlideStop);

		// Input this.fieldMap for sliders
		var onChangeSliderInput = function(){
			var $this = $(this);
			$('#' + $this.attr('slider-for'))
				.slider('setValue', parseInt($this.val(), 10), true)
				.val($this.val())
				.attr('value', $this.val());
			$serviceNotificationIntervalField.trigger('change');
			var min = parseInt($this.val() / 60, 10);
			var sec = parseInt($this.val() % 60, 10);
			$($this.attr('human')).html(min + " " + self.lang[1] + " " + self.lang[3] + " " + sec + " " + self.lang[2]);
		};
		$('.slider-input').on('change.slider', onChangeSliderInput);
			//.on('keyup', onChangeSliderInput);

		// Render fancy tags input
		//$('.tagsinput').tagsinput();

		/*
		 * Bind change event for the check command selectbox
		 */
		$('#ServiceCommandId').on('change.serviceCommand', function(){
			self.loadParametersByCommandId($(this).val(), $('#ServiceServicetemplateId').val(), $('#CheckCommandArgs'));
		});

		// Bind change event for the check command selectbox
		var $event_handler_command_args = $('#EventhandlerCommandArgs');
		$('#ServiceEventhandlerCommandId').on('change.commandId', function(){
			var id = $(this).val();
			if(id && id != '0'){
				// self.loadParameters(id, $('#EventhandlerCommandArgs'));
				self.loadNagParametersByCommandId(id, $('#ServiceServicetemplateId').val(), $event_handler_command_args);
			}else{
				$event_handler_command_args.html('');
			}
		});


		/**
		 * Mainly does two things:
		 *
		 *	1.	Fills up the input fields of the form. The values are used from the chosen Hosttemplate.
		 *		This part was refactored.
		 *
		 *	2.	Allows to reset the values to their defaults. The defaults depend on the chosen Hosttemplate.
		 *		This part was newly created.
		 *
		 *	onChangeField()			- When a field gets changed, this method will be called.
		 *	onClickRestoreDefault() - A click on the restore button.
		 */
		self.hosttemplateManager = {
			isRestoreFunctionalityInitialized: false,
			isInitializedOnce: false,

			/**
			 * Initialize the event listeners.
			 */
			init: function(){
				this.updateHosttemplateValues(	// Updates the fields based on the decision of the user/template.
					this.initRestoreDefault		// Initializes the restore functionality after the template values have been loaded.
				);
			},

			_onChangeMacro: function(){
				var currentValueCount = 0,
					allCurrentValues = {},
					caseInsensitive = true; // Thats the default value. It isn't configurable yet.

				var $customVariablesContainer = $('#customVariablesContainer');
				$customVariablesContainer.children().each(function(){
					var name = $(this).find('.macroName').val();
					var value = $(this).find('.macroValue').val();
					if(caseInsensitive){
						allCurrentValues[name.toUpperCase()] = value.toUpperCase();
					}else{
						allCurrentValues[name] = value;
					}
					currentValueCount++;
				});

				var templateValues = {};
				for(var key in self.hosttemplateManager.currentCustomVariables){
					var obj = self.hosttemplateManager.currentCustomVariables[key];
					if(caseInsensitive){
						templateValues[obj.name.toUpperCase()] = obj.value.toUpperCase();
					}else{
						templateValues[obj.name] = obj.value;
					}
				}

				var isIdenticalWithTemplate = true;
				if(Object.keys(templateValues).length != currentValueCount){
					isIdenticalWithTemplate = false;
				}
				if(isIdenticalWithTemplate){
					for(var name in templateValues){
						if(!allCurrentValues.hasOwnProperty(name)){
							isIdenticalWithTemplate = false;
							break;
						}

						if(templateValues[name] !== allCurrentValues[name]){
							isIdenticalWithTemplate = false;
							break;
						}
					}
				}

				self.hosttemplateManager._createOrUpdateMacroRestoreIcon(isIdenticalWithTemplate);
			},


			_restoreHostMacrosFromTemplate: function(){
				//Loading the macros of the hosttemplate
				self.CustomVariables.loadMacroFromTemplate(
						self.hosttemplateManager.currentTemplate.id,
						self.hosttemplateManager._activateOrUpdateMacroRestore
					);
			},

			_createOrUpdateMacroRestoreIcon: function(isIdenticalWithTemplate){
				var $macroContainer = $('.service-macro-settings'),
					$icon = $macroContainer.find('.fa-chain-default, .fa-chain-non-default'),
					defaultClasses = 'fa fa-chain margin-left-10 ',
					greenIconClass = defaultClasses + 'txt-color-green fa-chain-default',
					redIconClass = defaultClasses + 'txt-color-red fa-chain-non-default',
					currentIconClass = (isIdenticalWithTemplate ? greenIconClass : redIconClass);

				if(!$icon.length){ // Create icon.
					$icon = $('<i>', {
						class: currentIconClass
					});
					$macroContainer.prepend($icon);
				}

				if(!isIdenticalWithTemplate){
					$icon.off('click');
					$icon.on('click', self.hosttemplateManager._restoreHostMacrosFromTemplate);
				}

				// Update the class of the icon.
				$icon.attr('class', (isIdenticalWithTemplate ? greenIconClass : redIconClass));
			},

			_activateOrUpdateMacroRestore: function(response){ // Called once a template is chosen.
				var $customVariablesContainer = this;
				var allCurrentValues = {};
				$('#customVariablesContainer').children().each(function(index){
					var fields = {
						$name: $(this).find('.macroName'),
						$value: $(this).find('.macroValue')
					};

					allCurrentValues[fields.$name.val()] = fields.$value.val();

					for(var key in fields){
						if(!fields.hasOwnProperty(key)){
							continue;
						}
						var $field = fields[key];

						$field
							.off('change.restoreDefault')
							.off('keyup')
							.on('change.restoreDefault', self.hosttemplateManager._onChangeMacro)
							.on('keyup', self.hosttemplateManager._onChangeMacro);

						self.hosttemplateManager._onChangeMacro();
					}
				});
				self.hosttemplateManager._onChangeMacro();

				// The event has to be used through "document". This is because the original function is attached the same way.
				// Otherwise it is executed before the original delete function is executed!
				$(document).off('click.macroRemove', '.deleteMacro');
				$(document).on('click.macroRemove', '.deleteMacro', self.hosttemplateManager._onChangeMacro);
			},

			deactivateRestoreFunctionality: function(){
				for(var key in self.fieldMap){
					var fieldId = 'Service' + self.fieldMap[key];
					var $field = $('#' + fieldId);
					var $fieldFormGroup = $field.parents('.form-group');

					$fieldFormGroup.find('input, select')
						.not('[type="hidden"]')
						.not('[type="checkbox"]')
						.off('change.restoreDefault');
					$fieldFormGroup.find('.fa-chain, .fa-chain-broken')
						.remove();
				}

				var $hostMacroSettings = $('.service-macro-settings');
				$hostMacroSettings.find('.fa-chain-default, .fa-chain-non-default').remove();
				$hostMacroSettings.off('click.MacroRemove', '.deleteMacro');

				self.hosttemplateManager.isRestoreFunctionalityInitialized = false;
			},

			onClickRestoreDefault: function(){
				var $field = $(this);
				var fieldType = self.hosttemplateManager.getFieldType($field);
				var inputId = $field.attr('id') || '';
				var keyName;
				if(inputId.match(/stars-rating/)){
					keyName = getObjectKeyByValue(self.fieldMap, 'stars-rating-5');
				}else{
					keyName = getObjectKeyByValue(self.fieldMap, inputId.replace(/^(Service)/, ''));
				}
				var templateDefaultValue = self.hosttemplateManager.currentTemplate[keyName];
				if(typeof templateDefaultValue === 'undefined'){
					templateDefaultValue = $field.prop('data-template-default');
				}
				if(in_array(keyName, ['contact', 'contactgroup'])){
					switch(keyName){
						case 'contact':
							templateDefaultValue = self.hosttemplateManager.currentContact.map(function(elem){ return elem.id });
							break;
						case 'contactgroup':
							templateDefaultValue = self.hosttemplateManager.currentContactGroup.map(function(elem){ return elem.id });
							break;
					}
				}
				// console.log('onClickRestoreDefault()');
				if($field.prop('disabled')){
					return;
				}
				if(fieldType === 'checkbox'){
					// FIX: Values like '1', '0', true and false have to be parsed here.
					if(templateDefaultValue == '0'){
						templateDefaultValue = false;
					}else{
						templateDefaultValue = !!templateDefaultValue;
					}
					$field
						.prop('checked', templateDefaultValue)
						.trigger('change');
				}else if(fieldType === 'select'){ // The tag is a <select>
					$field
						.val(templateDefaultValue)
						.trigger('chosen:updated')
						.trigger('change');
				}else if(fieldType === 'radio'){
					$field.parent().find('input').each(function(){
						if($(this).val() != templateDefaultValue){
							return;
						}

						$(this)
							.prop('checked', true)
							.trigger('change');
					});
				}else if($field.hasClass('slider')){
					var $otherInput = $field.parents('.form-group').find('input[type=number]');
					$otherInput
						.val(templateDefaultValue)
						.trigger('change');
					$field.trigger('change');
				}else if($field.hasClass('tagsinput')){ // Tags input field
					var tags = templateDefaultValue.split(',');
					$field.tagsinput('removeAll');
					for(var key in tags){
						$field.tagsinput('add', tags[key]);
					}
				}else{ // Normal (text) input field
					$field
						.val(templateDefaultValue)
						.trigger('change'); // Trigger the "change" event
				}
			},

			getFieldType: function($field){
				var fieldType = $field.attr('type');
				if(!fieldType){
					fieldType = $field.prop('tagName').toLowerCase();
				}
				return fieldType;
			},

			onChangeField: function(event){
				var $field = $(this);
				var $label = null;
				var inputId = $field.attr('id') || '';
				var keyName;
				if(inputId.match(/stars-rating/)){
					keyName = getObjectKeyByValue(self.fieldMap, 'stars-rating-5');
				}else{
					keyName = getObjectKeyByValue(self.fieldMap, inputId.replace(/^(Service)/, ''));
				}
				var templateDefaultValue = self.hosttemplateManager.currentTemplate[keyName];
				var templateDefaultTitle = '';
				if(typeof templateDefaultValue === 'undefined'){
					templateDefaultValue = $field.prop('data-template-default');
				}
				if(in_array(keyName, ['contact', 'contactgroup'])){
					switch(keyName){
						case 'contact':
							templateDefaultValue = self.hosttemplateManager.currentContact.map(function(elem){ return elem.id });
							templateDefaultTitle = self.hosttemplateManager.currentContact.map(function(elem){ return elem.name });
							break;
						case 'contactgroup':
							templateDefaultValue = self.hosttemplateManager.currentContactGroup.map(function(elem){ return elem.id });
							templateDefaultTitle = self.hosttemplateManager.currentContactGroup.map(function(elem){ return elem.Container.name });
							break;
					}
					templateDefaultTitle = templateDefaultTitle.join(', ');
				}
				var fieldType = self.hosttemplateManager.getFieldType($field);
				var nonDefaultClassName = 'fa fa-chain-broken fa-chain-non-default txt-color-red';
				var defaultClassName = 'fa fa-chain fa-chain-default txt-color-green';
				var defaultTitle = 'Default value';
				var restoreDefaultTitle;
				if(templateDefaultTitle != ''){
					restoreDefaultTitle = 'Restore template default: "' + templateDefaultTitle + '"';
				}else{
					restoreDefaultTitle = 'Restore template default: "' + templateDefaultValue + '"';
				}

				// "null" is no restorable default. Instead it's treated like "do nothing for this field".
				if(typeof templateDefaultValue === 'undefined' || templateDefaultValue === null){
					return;
				}

				// Get the value of the field
				var fieldValue = null;
				switch(fieldType){
					case 'checkbox':
						fieldValue = $field.is(':checked');
						// FIX: Values like '1', '0', true and false have to be parsed here.
						if(templateDefaultValue == '0'){
							templateDefaultValue = false;
						}else{
							templateDefaultValue = !!templateDefaultValue;
						}

						break;

					case 'radio':
						fieldValue = $field.parents('.form-group').find('[name="' + $field.attr('name') + '"]:checked').val();
						break;

					case 'select':
						fieldValue = $field.val();
						if(in_array(keyName, ['contact', 'contactgroup'])){
							if(fieldValue === null){
								fieldValue = [];
							}
						}else{
							restoreDefaultTitle = 'Restore default: "' + $field.find('option[value="' + templateDefaultValue + '"]').text() + '"';
						}
						break;

					default:
						fieldValue = $field.val();
						break;
				}

				if(fieldValue === null){
					return;
				}

				var wrappedOnClickRestore = function(){
						self.hosttemplateManager.onClickRestoreDefault.call($field);
					},
					$restoreDefaultIcon = $field.parents('.form-group').find('.fa-chain, .fa-chain-broken'),
					isEqual = (is_scalar(fieldValue) && is_scalar(templateDefaultValue) && fieldValue == templateDefaultValue) ||
						(is_array(fieldValue) && is_array(templateDefaultValue) && is_array_equal(fieldValue, templateDefaultValue));

				if(isEqual){
					if(!$restoreDefaultIcon.length){ // Icon doesn't exist -> create one
						$restoreDefaultIcon = $('<i>', {
							'class': defaultClassName,
							'title': defaultTitle
						});
						$field.parents('.form-group').append($restoreDefaultIcon);
					}else{ // Icon exists already
						$restoreDefaultIcon
							.attr({
								'class': defaultClassName,
								'title': defaultTitle
							})
							.off('click')
					}
				}else{
					if(!$restoreDefaultIcon.length){ // Icon doesn't exist -> create one
						$restoreDefaultIcon = $('<i>', {
							'class': nonDefaultClassName,
							'title': restoreDefaultTitle
						});
						$restoreDefaultIcon.on('click', wrappedOnClickRestore);
						$field.parents('.form-group').append($restoreDefaultIcon);
					}else{ // Icon exists already
						$restoreDefaultIcon
							.attr({
								'class': nonDefaultClassName,
								'title': restoreDefaultTitle
							})
							.off('click')
							.on('click', wrappedOnClickRestore)
					}
				}
			},

			/**
			 * Initalizes the restore functionality. The default values depend on the chosen Hosttemplate.
			 */
			initRestoreDefault: function(){
				//self.hosttemplateManager.deactivateRestoreFunctionality();
				// console.log('initRestoreDefault()');
				// Bind on all predefined inputs to allow to restore their defaults.
				for(var key in self.fieldMap){
					if(!self.fieldMap.hasOwnProperty(key)){
						return;
					}
					var $field = $('#Service' + self.fieldMap[key]);
					var fieldType = $field.attr('type');
					if(!fieldType && $field.prop('tagName') != null){
						fieldType = $field.prop('tagName').toLowerCase();
					}

					switch(fieldType){
						case 'text':
						case 'checkbox':
							self.hosttemplateManager.onChangeField.call($field); // Call once for this field
							$field.on('change.restoreDefault', self.hosttemplateManager.onChangeField);
							$field.on('keyup', self.hosttemplateManager.onChangeField);
							break;

						case 'radio':
							var $radioFields = $field.parents('.form-group').find('[name="' + $field.attr('name') + '"]');
							$radioFields.each(function(){
								self.hosttemplateManager.onChangeField.call($(this));
								$(this).on('change.restoreDefault', function(){
									self.hosttemplateManager.onChangeField.call($(this));
								});
							});
							break;

						case 'select':
							self.hosttemplateManager.onChangeField.call($field);
							$field.on('change.restoreDefault', self.hosttemplateManager.onChangeField);
							break;

						case 'number':
							self.hosttemplateManager.onChangeField.call($field);
							$field.on('change.restoreDefault', self.hosttemplateManager.onChangeField);
							break;

						case 'default':
							break;
					}
				}
				self.hosttemplateManager.isRestoreFunctionalityInitialized = true;
				self.hosttemplateManager.isInitializedOnce = true;
			},

			updateHosttemplateValues: function(onComplete){
				self.hosttemplateManager.currentTemplate = {};
				var $selectBoxHosttemplate = $('#ServiceServicetemplateId');

				var ajaxCompleteCallback = function(response){
					// console.log(response.responseJSON);
					var responseObject = response.responseJSON;
					if(responseObject.code === 'not_authenticated' || responseObject.servicetemplate.length == 0){
						return;
					}
					var hosttemplateId = $selectBoxHosttemplate.val();

					self.hosttemplateManager.currentTemplate = responseObject.servicetemplate.Servicetemplate;
					self.hosttemplateManager.currentContact = responseObject.servicetemplate.Contact;
					self.hosttemplateManager.currentContactGroup = responseObject.servicetemplate.Contactgroup;
					self.hosttemplateManager.currentCustomVariables = responseObject.servicetemplate.Customvariable;

					// For debugging purposes only // TODO remove before commit
					window.currentTemplate = responseObject.servicetemplate.Servicetemplate;
					window.currentContact = responseObject.servicetemplate.Contact;
					window.currentContactGroup = responseObject.servicetemplate.Contactgroup;

					window.currentCustomVariable = responseObject.servicetemplate.Customvariable;

					if(self.hosttemplateManager.currentTemplate.id != hosttemplateId){
						self.Ajaxloader.hide();

						return;
					}

					if(self.hosttemplateManager.isInitializedOnce){ // After it was initialized once, replace the values
						// Update the interface input self.fieldMap out of the hosttemplate JSON data
						for(var key in self.fieldMap){
							//modifying values of sliders
							if(in_array(key, ['check_interval', 'retry_interval', 'notification_interval'])){
								self.updateSlider({
									value: responseObject.servicetemplate.Servicetemplate[key],
									selector: self.fieldMap[key]
								});
							}else if(key == 'priority'){
								$('#Servicestars-rating-' + responseObject.servicetemplate.Servicetemplate[key])
									.prop('checked', true)
									.parents('.form-group').find('input[type=radio]');
										// .prop('data-template-default', templateDefaultValue);
							}else if(key == 'tags'){ //modifying value for tags
								self.updateTags({tags: responseObject.servicetemplate.Servicetemplate[key]});
							}else if(in_array(key, [ // modifying value for fancy checkboxes
								'notify_on_recovery',
								'notify_on_warning',
								'notify_on_unknown',
								'notify_on_critical',
								'notify_on_downtime',
								'notify_on_flapping',
								'notify_on_downtime',
								'flap_detection_enabled',
								'flap_detection_on_ok',
								'flap_detection_on_warning',
								'flap_detection_on_unknown',
								'flap_detection_on_critical',
								'is_volatile',
								'freshness_checks_enabled',
								'process_performance_data',
								'active_checks_enabled'
							])){
								self.updateCheckbox({
									value: responseObject.servicetemplate.Servicetemplate[key],
									selector: self.fieldMap[key]
								});
							}else if(in_array(key, [ // modifying value of selectbox
								'notify_period_id',
								'command_id',
								'check_period_id',
								'eventhandler_command_id'
							])){
								self.updateSelectbox({
									value: responseObject.servicetemplate.Servicetemplate[key],
									selector: self.fieldMap[key]
								});
							}else{
								//modifying value for default input this.fieldMap
								$('#Service'+self.fieldMap[key]).val(responseObject.servicetemplate.Servicetemplate[key]);
							}
						}

						//Updating associated data
						//Contacts
						var selectedContacts = [];
						$(responseObject.servicetemplate.Contact).each(function(intIndex, jsonContact){
							selectedContacts.push(jsonContact.id);
						});
						self.updateSelectbox({value: selectedContacts, selector: '#ServiceContact', prefix: 'false'});

						//Contactgroups
						var selectedContactgroups = [];
						$(responseObject.servicetemplate.Contactgroup).each(function(intIndex, jsonContactgroup){
							selectedContactgroups.push(jsonContactgroup.id);
						});
						self.updateSelectbox({value: selectedContactgroups, selector: '#ServiceContactgroup', prefix: 'false'});

					}

					// Loading the macros of the hosttemplate if no own macros exist. Otherwise only create the
					// restore icon.
					var hostHasOwnMacros = $('.service-macro-settings').find('input[type=hidden]').length > 0;
					if(hostHasOwnMacros){
						self.hosttemplateManager._activateOrUpdateMacroRestore();
					}else{
						self.CustomVariables.loadMacroFromTemplate(
							self.hosttemplateManager.currentTemplate.id,
							self.hosttemplateManager._activateOrUpdateMacroRestore
						);
					}

					self.Ajaxloader.hide();

					onComplete(); // Gets called only for the first AJAX request
				};

				var onChangeHosttemplate = function(){
					self.hosttemplateManager.isRestoreFunctionalityInitialized = true;
					var templateId = parseInt($(this).val(), 10);
					if(templateId <= 0){
						self.hosttemplateManager.currentTemplate = {};
						self.hosttemplateManager.deactivateRestoreFunctionality();

						return false;
					}

					$('#content').find('.fa-link').remove(); // Removes all elements with class fa-link
					self.Ajaxloader.show();

					$.ajax({
						url: "/Services/loadTemplateData/" + encodeURIComponent(templateId) + ".json",
						type: "POST",
						error: function(){},
						success: function(){},
						complete: ajaxCompleteCallback
					});
				};

				// // Bind change event for the servicetemplate selectbox.
				// $('#ServiceServicetemplateId').on('change.serviceContainer', onChangeHosttemplate);

				// Call first time (without a change to the values of the fields) because the field is obligatory.
				if(parseInt($selectBoxHosttemplate.val(), 10) > 0){
					onChangeHosttemplate.call($selectBoxHosttemplate);
				}else{
					self.hosttemplateManager.isInitializedOnce = true;
				}

				// Bind change event on the hosttemplate selectbox and load the template settings.
				$selectBoxHosttemplate.on('change.hostTemplate', function(){
					onChangeHosttemplate.call(this);
					// self.loadParametersFromTemplate($(this).val());

					// Load the arguments (with values) for the command id and event handler command id fields (once).
					var $serviceCommandId = $('#ServiceCommandId'),
						$eventhandlerCommandId = $('#ServiceEventhandlerCommandId'),
						$serviceTemplateId = $('#ServiceServicetemplateId');

					self.loadParametersByCommandId($serviceCommandId.val(), $('#ServiceServicetemplateId').val(), $('#CheckCommandArgs'));
					self.loadNagParametersByCommandId($eventhandlerCommandId.val(), $('#ServiceServicetemplateId').val(), $('#EventhandlerCommandArgs'));
				});

				// Bind change event for the check command selectbox.
				/*$('#ServiceCommandId').on('change.hostCommand', function(){
					self.loadParameters($(this).val());
				});*/
				if($('#ServiceCommandId').val() !== null && $('#ServiceServicetemplateId').val() != 0){
					//self.loadParametersFromTemplate($('#ServiceServicetemplateId').val());
					//self.loadParameters(responseObject.servicetemplate.Servicetemplate.id);
				}
			}
		};

		self.hosttemplateManager.init();
	},

	checkFlapDetection: function(){
		var disable = null;
		if(!$('input[type="checkbox"]#ServiceFlapDetectionEnabled').prop('checked')){
			disable = true;
		}
		$('.flapdetection_control').prop('disabled', disable);
	},

	checkFreshnessSettings: function(){
		var readonly = null;
		if(!$('input[type="checkbox"]#ServiceFreshnessChecksEnabled').prop('checked')){
			readonly = true;
			$('#ServiceFreshnessThreshold').val('');
		}
		$('#ServiceFreshnessThreshold').prop('readonly', readonly);
	},

	updateTags: function(_options){
		var options = _options || {};
		options.tags = _options.tags || "";
		options.remove = _options.remove || true;

		if(options.remove === true){
			this.$tagsinput.tagsinput('removeAll');
		}
		this.$tagsinput.tagsinput('add', options.tags);
	},

	updateSlider: function(_options){
		// console.log('updateSlider called with options: ' + _options);
		// console.log(_options);
		var options = _options || {};
		options.value = parseInt(_options.value, 10) || 0;
		options.selector = _options.selector || null;
		$('#Service'+options.selector).slider('setValue', options.value);
		$('#_Service'+options.selector).val(options.value);
		$('#Service'+options.selector).val(options.value);
		$('_#Service'+options.selector).trigger('keyup');

		var $helptext = $('#Service'+options.selector+'_human');

		var min = parseInt(options.value / 60, 10);
		var sec = parseInt(options.value % 60, 10);
		$helptext.html(min + " " + this.lang[1] + " " + this.lang[3] + " " + sec + " " + this.lang[2]);
	},

	updateCheckbox: function(_options){
		var options = _options || {};
		options.value = _options.value || null;
		options.selector = _options.selector || '';

		if(options.value === null || options.value == 0 || options.value == false){
			$('input[type="checkbox"]#Service'+options.selector).prop('checked', false);
			this.checkFlapDetection();
			return false;
		}

		$('input[type="checkbox"]#Service'+options.selector).prop('checked', true).trigger('change');

		this.checkFlapDetection();
		return true;
	},

	updateSelectbox: function(_options){
		var options = _options || {};
		options.value = _options.value || 0;
		options.selector = _options.selector || '';
		options.prefix = _options.prefix || "#Service";

		if(options.prefix == 'false'){
			options.prefix = '';
		}

		$(options.prefix+options.selector).val(options.value);
		$(options.prefix+options.selector).trigger("chosen:updated");
	},

	loadParametersByCommandId: function(command_id, servicetemplate_id, $target){
		var self = this;
		if(!command_id || !servicetemplate_id || !$target || !$target.length){
			throw new Error('Invalid argument given');
		}
		this.Ajaxloader.show();
		$.ajax({
			url: '/Services/loadParametersByCommandId/' + encodeURIComponent(command_id) + '/' + encodeURIComponent(servicetemplate_id),
			type: 'POST',
			error: function(){},
			success: function(){},
			complete: function(response){
				$target.html(response.responseText);
				self.Ajaxloader.hide();
			}
		});
	},

	loadNagParametersByCommandId: function(command_id, servicetemplate_id, $target){
		var self = this;
		if(!command_id || !servicetemplate_id || !$target || !$target.length){
			throw new Error('Invalid argument given');
		}
		this.Ajaxloader.show();
		$.ajax({
			url: '/Services/loadNagParametersByCommandId/' + encodeURIComponent(command_id) + '/' + encodeURIComponent(servicetemplate_id),
			type: 'POST',
			error: function(){},
			success: function(){},
			complete: function(response){
				$target.html(response.responseText);
				self.Ajaxloader.hide();
			}
		});
	},

	/**
	 * @param {String} command_id
	 * @param {jQuery} $target
	 */
	loadParameters: function(command_id, $target){
		this.Ajaxloader.show();
		$.ajax({
			url: "/Services/loadArgumentsAdd/" + encodeURIComponent(command_id),
			type: "POST",
			error: function(){},
			success: function(){},
			complete: function(response){
				$target.html(response.responseText);
				this.Ajaxloader.hide();
			}.bind(this)
		});
	},

	loadParametersFromTemplate: function(servicetemplate_id){
		$.ajax({
			url: "/Services/loadServicetemplatesArguments/"+encodeURIComponent(servicetemplate_id),
			type: "POST",
			error: function(){},
			success: function(){},
			complete: function(response){
				// console.log(response);
				$('#CheckCommandArgs').html(response.responseText);
				this.Ajaxloader.hide();
			}.bind(this)
		});
	},
	
	inherit: function(){
		$inheritCheckbox = $('#inheritContacts');
		if($inheritCheckbox.prop('checked') == true){
			$('#serviceContactSelects').block({
				message: null,
				overlayCSS: {
					opacity: 0.0,
					cursor: 'not-allowed'
				}
			});
			
			//Remove selection of the select boxes
			document.getElementById('ServiceContact').selectedIndex = -1;
			document.getElementById('ServiceContactgroup').selectedIndex = -1;
			
			//Set selected in selectbox for contacs
			var Contact = this.getVar('ContactsInherited').Contact;
			if(Contact != null){
				for(var ContactId in Contact){
					$('#ServiceContact :nth-child('+ContactId+')').prop('selected', true);
				}
			}

			//Set selected in selectbox for contact groups
			var Contactgroup = this.getVar('ContactsInherited').Contactgroup;
			if(Contactgroup != null){
				for(var ContactgroupId in Contactgroup){
					$('#ServiceContactgroup :nth-child('+ContactgroupId+')').prop('selected', true);
				}
			}

			$('#ServiceContact').prop('disabled', true);
			$('#ServiceContactgroup').prop('disabled', true);
		}else{
			$('#serviceContactSelects').unblock();
			$('#ServiceContact').prop('disabled', false);
			$('#ServiceContactgroup').prop('disabled', false);
			
			//Remove selection of the select boxes
			document.getElementById('ServiceContact').selectedIndex = -1;
			document.getElementById('ServiceContactgroup').selectedIndex = -1;
		}
		$('#ServiceContact').trigger("chosen:updated");
		$('#ServiceContactgroup').trigger("chosen:updated");
	}
});
