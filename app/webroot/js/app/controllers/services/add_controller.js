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

App.Controllers.ServicesAddController = Frontend.AppController.extend({
    $contacts: null,
    $contactgroups: null,
    $servicegroups: null,
    $tagsinput: null,
    lang: null,

    components: ['Highlight', 'Ajaxloader', 'CustomVariables'],

    _initialize: function(){
        var self = this;

        this.Ajaxloader.setup();
        this.CustomVariables.setup({
            controller: 'Services',
            ajaxUrl: 'addCustomMacro',
            macrotype: 'SERVICE',
            onClick: function(){
                self.servicetemplateManager._onChangeMacro();
                self.servicetemplateManager._activateOrUpdateMacroRestore();
            }
        });
        /*
         * Fix chosen width, if rendered in a tab
         */
        $("[data-toggle='tab']").click(function(){
            $('.chosen-container').css('width', '100%');
        });

        this.$contacts = $('#ServiceContact');
        this.$contactgroups = $('#ServiceContactgroup');
        this.$servicegroups = $('#ServiceServicegroup');

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
        this.fieldMap['servicegroup'] = 'Servicegroup';

        // Render fancy tags input
        this.$tagsinput = $('.tagsinput');
        this.$tagsinput.tagsinput();


        this.loadInitialData('#ServiceHostId');

        var ChosenAjaxObj = new ChosenAjax({
            id: 'ServiceHostId' //Target select box
        });

        ChosenAjaxObj.setCallback(function(searchString){
            $.ajax({
                dataType: "json",
                url: '/hosts/loadHostsByString.json',
                data: {
                    'angular': true,
                    'filter[Host.name]': searchString
                },
                success: function(response){
                    ChosenAjaxObj.addOptions(response.hosts);
                }
            });
        });

        ChosenAjaxObj.render();

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
        $slider.slider({tooltip: 'hide'});
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
            //self.loadParameters($(this).val());
            self.loadParametersByCommandId($(this).val(), $('#ServiceServicetemplateId').val(), $('#CheckCommandArgs'));
        });

        // Bind change event for the check command selectbox
        $('#ServiceEventhandlerCommandId').on('change.commandId', function(){
            self.loadNagParametersByCommandId($(this).val(), $('#ServiceServicetemplateId').val(), $('#EventhandlerCommandArgs'));
        });


        /**
         * Mainly does two things:
         *
         *    1.    Fills up the input fields of the form. The values are used from the chosen Hosttemplate.
         *        This part was refactored.
         *
         *    2.    Allows to reset the values to their defaults. The defaults depend on the chosen Hosttemplate.
         *        This part was newly created.
         *
         *    onChangeField()            - When a field gets changed, this method will be called.
         *    onClickRestoreDefault() - A click on the restore button.
         */
        self.servicetemplateManager = {
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
                for(var key in self.servicetemplateManager.currentCustomVariables){
                    var obj = self.servicetemplateManager.currentCustomVariables[key];
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

                self.servicetemplateManager._createOrUpdateMacroRestoreIcon(isIdenticalWithTemplate);
            },

            _restoreHostMacrosFromTemplate: function(){
                //Loading the macros of the hosttemplate
                self.CustomVariables.loadMacroFromTemplate(
                    self.servicetemplateManager.currentTemplate.id,
                    self.servicetemplateManager._activateOrUpdateMacroRestore
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
                    $icon.on('click', self.servicetemplateManager._restoreHostMacrosFromTemplate);
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
                            .on('change.restoreDefault', self.servicetemplateManager._onChangeMacro)
                            .on('keyup', self.servicetemplateManager._onChangeMacro);

                        self.servicetemplateManager._onChangeMacro();
                    }
                });
                self.servicetemplateManager._onChangeMacro();

                // The event has to be used through "document". This is because the original function is attached the same way.
                // Otherwise it is executed before the original delete function is executed!
                $(document).off('click.macroRemove', '.deleteMacro');
                $(document).on('click.macroRemove', '.deleteMacro', self.servicetemplateManager._onChangeMacro);
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

                self.servicetemplateManager.isRestoreFunctionalityInitialized = false;
            },

            onClickRestoreDefault: function(){
                var $field = $(this);
                var fieldType = self.servicetemplateManager.getFieldType($field);
                var inputId = $field.attr('id') || '';
                var keyName;
                if(inputId.match(/stars-rating/)){
                    keyName = getObjectKeyByValue(self.fieldMap, 'stars-rating-5');
                }else{
                    keyName = getObjectKeyByValue(self.fieldMap, inputId.replace(/^(Service)/, ''));
                }
                var templateDefaultValue = self.servicetemplateManager.currentTemplate[keyName];
                if(typeof templateDefaultValue === 'undefined'){
                    templateDefaultValue = $field.prop('data-template-default');
                }
                if(in_array(keyName, ['contact', 'contactgroup', 'servicegroup'])){
                    switch(keyName){
                        case 'contact':
                            templateDefaultValue = self.servicetemplateManager.currentContact.map(function(elem){
                                return elem.id
                            });
                            break;
                        case 'contactgroup':
                            templateDefaultValue = self.servicetemplateManager.currentContactGroup.map(function(elem){
                                return elem.id
                            });
                            break;
                        case 'servicegroup':
                            templateDefaultValue = self.servicetemplateManager.currentServiceGroup.map(function(elem){
                                return elem.id
                            });
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
                var templateDefaultValue = self.servicetemplateManager.currentTemplate[keyName];
                var templateDefaultTitle = '';
                if(typeof templateDefaultValue === 'undefined'){
                    templateDefaultValue = $field.prop('data-template-default');
                }
                if(in_array(keyName, ['contact', 'contactgroup', 'servicegroup'])){
                    switch(keyName){
                        case 'contact':
                            templateDefaultValue = self.servicetemplateManager.currentContact.map(function(elem){
                                return elem.id
                            });
                            templateDefaultTitle = self.servicetemplateManager.currentContact.map(function(elem){
                                return elem.name
                            });
                            break;
                        case 'contactgroup':
                            templateDefaultValue = self.servicetemplateManager.currentContactGroup.map(function(elem){
                                return elem.id
                            });
                            templateDefaultTitle = self.servicetemplateManager.currentContactGroup.map(function(elem){
                                return elem.Container.name
                            });
                            break;
                        case 'servicegroup':
                            templateDefaultValue = self.servicetemplateManager.currentServiceGroup.map(function(elem){
                                return elem.id
                            });
                            templateDefaultTitle = self.servicetemplateManager.currentServiceGroup.map(function(elem){
                                return elem.Container.name
                            });
                            break;
                    }
                    templateDefaultTitle = templateDefaultTitle.join(', ');
                }
                var fieldType = self.servicetemplateManager.getFieldType($field);
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
                        // FIX: Values like '1', '0', true and false have to be parsed here.
                        if(templateDefaultValue == '0'){
                            templateDefaultValue = false;
                        }else{
                            templateDefaultValue = !!templateDefaultValue;
                        }

                        fieldValue = $field.is(':checked');
                        templateDefaultValue = templateDefaultValue;
                        break;

                    case 'radio':
                        fieldValue = $field.parents('.form-group').find('[name="' + $field.attr('name') + '"]:checked').val();
                        break;

                    case 'select':
                        fieldValue = $field.val();
                        if(in_array(keyName, ['contact', 'contactgroup', 'servicegroup'])){
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
                    self.servicetemplateManager.onClickRestoreDefault.call($field);
                };
                var $restoreDefaultIcon = $field.parents('.form-group').find('.fa-chain, .fa-chain-broken');
                var isEqual = (is_scalar(fieldValue) && is_scalar(templateDefaultValue) && fieldValue == templateDefaultValue) ||
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
                self.servicetemplateManager.deactivateRestoreFunctionality();
                // console.log('initRestoreDefault()');
                // Bind on all predefined inputs to allow to restore their defaults.
                for(var key in self.fieldMap){
                    if(!self.fieldMap.hasOwnProperty(key)){
                        return;
                    }
                    var $field = $('#Service' + self.fieldMap[key]);
                    var fieldType = $field.attr('type');
                    if(!fieldType){
                        fieldType = $field.prop('tagName').toLowerCase();
                    }

                    switch(fieldType){
                        case 'text':
                        case 'checkbox':
                            self.servicetemplateManager.onChangeField.call($field); // Call once for this field
                            $field.on('change.restoreDefault', self.servicetemplateManager.onChangeField);
                            $field.on('keyup', self.servicetemplateManager.onChangeField);
                            break;

                        case 'radio':
                            var $radioFields = $field.parents('.form-group').find('[name="' + $field.attr('name') + '"]');
                            $radioFields.each(function(){
                                self.servicetemplateManager.onChangeField.call($(this));
                                $(this).on('change.restoreDefault', function(){
                                    self.servicetemplateManager.onChangeField.call($(this));
                                });
                            });
                            break;

                        case 'select':
                            self.servicetemplateManager.onChangeField.call($field);
                            $field.on('change.restoreDefault', self.servicetemplateManager.onChangeField);
                            break;

                        case 'number':
                            self.servicetemplateManager.onChangeField.call($field);
                            $field.on('change.restoreDefault', self.servicetemplateManager.onChangeField);
                            break;

                        case 'default':
                            break;
                    }
                }
                self.servicetemplateManager.isRestoreFunctionalityInitialized = true;
                self.servicetemplateManager.isInitializedOnce = true;
            },

            updateHosttemplateValues: function(onComplete){
                self.servicetemplateManager.currentTemplate = {};
                var $selectBoxHosttemplate = $('#ServiceServicetemplateId');

                var ajaxCompleteCallback = function(response){
                    // console.log(response.responseJSON);
                    var responseObject = response.responseJSON;
                    if(responseObject.code === 'not_authenticated' || responseObject.servicetemplate.length == 0){
                        return;
                    }
                    var hosttemplateId = $selectBoxHosttemplate.val(),
                        servicetemplate = responseObject.servicetemplate;

                    self.servicetemplateManager.currentTemplate = servicetemplate.Servicetemplate;
                    self.servicetemplateManager.currentContact = servicetemplate.Contact;
                    self.servicetemplateManager.currentContactGroup = servicetemplate.Contactgroup;
                    self.servicetemplateManager.currentServiceGroup = servicetemplate.Servicegroup;
                    self.servicetemplateManager.currentCustomVariables = servicetemplate.Customvariable;

                    // For debugging purposes only // TODO remove before commit
                    window.currentTemplate = servicetemplate.Servicetemplate;
                    window.currentContact = servicetemplate.Contact;
                    window.currentContactGroup = servicetemplate.Contactgroup;
                    window.currentServiceGroup = servicetemplate.Servicegroup;
                    window.currentCustomVariable = servicetemplate.Customvariable;

                    if(self.servicetemplateManager.currentTemplate.id != hosttemplateId){
                        self.Ajaxloader.hide();

                        return;
                    }

                    if(self.servicetemplateManager.isInitializedOnce){ // After it was initialized once, replace the values
                        // Update the interface input self.fieldMap out of the hosttemplate JSON data
                        for(var key in self.fieldMap){
                            //modifying values of sliders
                            if(in_array(key, ['check_interval', 'retry_interval', 'notification_interval'])){
                                self.updateSlider({
                                    value: servicetemplate.Servicetemplate[key],
                                    selector: self.fieldMap[key]
                                });
                            }else if(key == 'priority'){
                                $('#Servicestars-rating-' + servicetemplate.Servicetemplate[key])
                                    .prop('checked', true)
                                    .parents('.form-group').find('input[type=radio]');
                                // .prop('data-template-default', templateDefaultValue);
                            }else if(key == 'tags'){ //modifying value for tags
                                self.updateTags({tags: servicetemplate.Servicetemplate[key]});
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
                                    value: servicetemplate.Servicetemplate[key],
                                    selector: self.fieldMap[key]
                                });
                            }else if(in_array(key, [ // modifying value of selectbox
                                    'notify_period_id',
                                    'command_id',
                                    'check_period_id',
                                    'eventhandler_command_id'
                                ])){
                                self.updateSelectbox({
                                    value: servicetemplate.Servicetemplate[key],
                                    selector: self.fieldMap[key]
                                });
                            }else{
                                //modifying value for default input this.fieldMap
                                $('#Service' + self.fieldMap[key]).val(servicetemplate.Servicetemplate[key]);
                            }
                        }

                        //Updating associated data
                        //Contacts
                        var selectedContacts = [];
                        $(servicetemplate.Contact).each(function(intIndex, jsonContact){
                            selectedContacts.push(jsonContact.id);
                        });
                        self.updateSelectbox({value: selectedContacts, selector: '#ServiceContact', prefix: 'false'});

                        //Contactgroups
                        var selectedContactgroups = [];
                        $(servicetemplate.Contactgroup).each(function(intIndex, jsonContactgroup){
                            selectedContactgroups.push(jsonContactgroup.id);
                        });
                        self.updateSelectbox({
                            value: selectedContactgroups,
                            selector: '#ServiceContactgroup',
                            prefix: 'false'
                        });

                        //Servicegroups
                        var selectedServicegroups = [];
                        $(servicetemplate.Servicegroup).each(function(intIndex, jsonServicegroup){
                            selectedServicegroups.push(jsonServicegroup.id);
                        });
                        self.updateSelectbox({
                            value: selectedServicegroups,
                            selector: '#ServiceServicegroup',
                            prefix: 'false'
                        });
                    }

                    // Loading command arguments of the template
                    self.loadParametersByCommandId(servicetemplate.Servicetemplate.command_id, servicetemplate.Servicetemplate.id, $('#CheckCommandArgs'));
                    self.loadNagParametersByCommandId(servicetemplate.Servicetemplate.eventhandler_command_id, servicetemplate.Servicetemplate.id, $('#EventhandlerCommandArgs'));

                    // Loading the macros of the hosttemplate
                    self.CustomVariables.loadMacroFromTemplate(
                        self.servicetemplateManager.currentTemplate.id,
                        self.servicetemplateManager._activateOrUpdateMacroRestore
                    );

                    self.Ajaxloader.hide();

                    onComplete(); // Gets called only for the first AJAX request
                };

                var onChangeHosttemplate = function(){
                    self.servicetemplateManager.isRestoreFunctionalityInitialized = true;
                    var templateId = parseInt($(this).val(), 10);
                    if(templateId <= 0){
                        self.servicetemplateManager.currentTemplate = {};
                        self.servicetemplateManager.deactivateRestoreFunctionality();

                        return false;
                    }

                    $('#content').find('.fa-link').remove(); // Removes all elements with class fa-link
                    self.Ajaxloader.show();

                    $.ajax({
                        url: "/Services/loadTemplateData/" + encodeURIComponent(templateId) + ".json",
                        type: "POST",
                        cache: false,
                        error: function(){
                        },
                        success: function(){
                        },
                        complete: ajaxCompleteCallback
                    });
                };

                // Call first time (without a change to the values of the fields) because the field is obligatory.
                if(parseInt($selectBoxHosttemplate.val(), 10) > 0){
                    onChangeHosttemplate.call($selectBoxHosttemplate);
                }else{
                    self.servicetemplateManager.isInitializedOnce = true;
                }

                // Bind change event on the hosttemplate selectbox and load the template settings.
                $selectBoxHosttemplate.on('change.hostTemplate', onChangeHosttemplate);

                var $serviceCommandId = $('#ServiceCommandId'),
                    $serviceTemplateId = $('#ServiceServicetemplateId');
                if($serviceCommandId.val() !== null && $serviceTemplateId.val() != 0){
                    // self.loadParametersFromTemplate($('#ServiceServicetemplateId').val());
                    self.loadParametersByCommandId($serviceCommandId.val(), $serviceTemplateId.val(), $('#CheckCommandArgs'));
                }
            }
        };

        self.servicetemplateManager.init();
    },


    loadInitialData: function(selector, selectedHostIds, callback){
        var self = this;
        if(selectedHostIds == null || selectedHostIds.length < 1){
            selectedHostIds = [];
        }else{
            if(!Array.isArray(selectedHostIds)){
                selectedHostIds = [selectedHostIds];
            }
        }

        var selectedHostId = this.getVar('hostId');
        var requestParams = {
            'angular': true,
            'selected[]': selectedHostIds //ids
        };

        if(selectedHostId !== null){
            requestParams['selected'] = selectedHostId;
        }


        $.ajax({
            dataType: "json",
            url: '/hosts/loadHostsByString.json',
            data: requestParams,
            success: function(response){
                var $selector = $(selector);
                var list = self.buildList(response.hosts, selectedHostId);
                $selector.append(list);
                if(selectedHostId !== null){
                    $selector.val(selectedHostId);
                }else{
                    $selector.val([]);
                }

                $selector.trigger('chosen:updated');

                if(callback != undefined){
                    callback();
                }
            }
        });
    },


    buildList: function(data, selected){

        var html = '';
        for(var i in data){
            if(data[i].key == selected && selected !== null){
                html += '<option value="' + data[i].key + '" selected="selected">' + htmlspecialchars(data[i].value) + '</option>';
            }else{
                html += '<option value="' + data[i].key + '">' + htmlspecialchars(data[i].value) + '</option>';
            }
        }
        return html;
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
        $('#Service' + options.selector).slider('setValue', options.value);
        $('#_Service' + options.selector).val(options.value);
        $('#Service' + options.selector).val(options.value);
        $('_#Service' + options.selector).trigger('keyup');

        var $helptext = $('#Service' + options.selector + '_human');

        var min = parseInt(options.value / 60, 10);
        var sec = parseInt(options.value % 60, 10);
        $helptext.html(min + " " + this.lang[1] + " " + this.lang[3] + " " + sec + " " + this.lang[2]);
    },

    updateCheckbox: function(_options){
        var options = _options || {};
        options.value = _options.value || null;
        options.selector = _options.selector || '';

        if(options.value === null || options.value == 0 || options.value == false){
            $('input[type="checkbox"]#Service' + options.selector).prop('checked', false);
            this.checkFlapDetection();
            return false;
        }

        $('input[type="checkbox"]#Service' + options.selector).prop('checked', true).trigger('change');

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

        $(options.prefix + options.selector).val(options.value);
        $(options.prefix + options.selector).trigger("chosen:updated").change();
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
            cache: false,
            error: function(){
            },
            success: function(){
            },
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
            cache: false,
            error: function(){
            },
            success: function(){
            },
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
            cache: false,
            error: function(){
            },
            success: function(){
            },
            complete: function(response){
                $target.html(response.responseText);
                this.Ajaxloader.hide();
            }.bind(this)
        });
    },

    loadParametersFromTemplate: function(servicetemplate_id){
        $.ajax({
            url: "/Services/loadServicetemplatesArguments/" + encodeURIComponent(servicetemplate_id),
            type: "POST",
            cache: false,
            error: function(){
            },
            success: function(){
            },
            complete: function(response){
                // console.log(response);
                $('#CheckCommandArgs').html(response.responseText);
                this.Ajaxloader.hide();
            }.bind(this)
        });
    }
});
