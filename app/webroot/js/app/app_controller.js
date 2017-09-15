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

Frontend.AppController = Frontend.Controller.extend({
    /**
     * Holds the DOM element of this controller.
     *
     * @var DOMElement
     */
    _dom: null,
    $: null,
    /**
     * Holds the currently open dialog
     *
     * @var DOMElement
     */
    _dialog: null,
    /**
     * These components will be merged with the sub-controllers' components
     *
     * @return void
     */
    baseComponents: ['ListFilter', 'ImageChooser', 'FileChooser', 'WebsocketSudo', 'Time'],
    ajaxSelectedHosts: [],
    ajaxSelectedServices: [],

    /**
     * Initializer
     *
     * @return void
     */
    _init: function () {
        var self = this;
        this._dom = $('div.controller.' + this._frontendData.controller + '_' + this._frontendData.action);
        this.$ = this._dom.find.bind(this._dom);
        this.Time.setup();
        var arrayOfItnAjax = {};
        var arrayOfMultiples = {};
        var arrayOfContainers = {};
        var arrayOfSelected = {};

        var selectBoxes = $('.chosen');
        for (var i in selectBoxes) {
            if (typeof selectBoxes[i].attributes !== 'undefined' && selectBoxes[i].attributes !== null) {
                var selectType = typeof selectBoxes[i].attributes['multiple'] !== 'undefined' ? 'multiple' : 'single';
                if (selectType === 'multiple') {
                    $(selectBoxes[i]).chosen({
                        placeholder_text_single: 'Please choose',
                        placeholder_text_multiple: 'Please choose',
                        allow_single_deselect: true, // This will only work if the first option has a blank text.
                        search_contains: true,
                        enable_split_word_search: true,
                        select_all_buttons: true,
                        width: '100%' // Makes the graph responsive.
                    });
                    if(typeof selectBoxes[i].attributes['id'] !== 'undefined'){
                        arrayOfMultiples[selectBoxes[i].attributes['id'].value] = 1;
                    }
                } else {
                    $(selectBoxes[i]).chosen({
                        placeholder_text_single: 'Please choose',
                        placeholder_text_multiple: 'Please choose',
                        allow_single_deselect: true, // This will only work if the first option has a blank text.
                        search_contains: true,
                        enable_split_word_search: true,
                        width: '100%' // Makes the graph responsive.
                    });
                }

                if(typeof selectBoxes[i].attributes['itn-ajax'] !== 'undefined' && typeof selectBoxes[i].attributes['id'] !== 'undefined'){
                    arrayOfItnAjax[selectBoxes[i].attributes['id'].value] = selectBoxes[i].attributes['itn-ajax'].value;
                    if(typeof selectBoxes[i].attributes['itn-ajax-container'] !== 'undefined'){
                        arrayOfContainers[selectBoxes[i].attributes['id'].value] = selectBoxes[i].attributes['itn-ajax-container'].value;
                    }
                    if(typeof selectBoxes[i].attributes['itn-ajax-selected-type'] !== 'undefined'){
                        arrayOfSelected[selectBoxes[i].attributes['id'].value] = selectBoxes[i].attributes['itn-ajax-selected-type'].value;
                    }
                }
            }
        }

        var myItnTimeout;
        var lastTyped = '';
        var itnAjaxLoading = false;
        $('.chosen-container').bind('keyup',function(event) {

            var currentItnAjaxId = $(this).attr('id').replace('_chosen', '');
            var termInput = $('#'+currentItnAjaxId+'_chosen input');
            var termInputValue = termInput.val();
            if (typeof arrayOfItnAjax[currentItnAjaxId] === 'undefined' || termInputValue.length < 1)
                return false;

            var isMultiple = typeof arrayOfMultiples[currentItnAjaxId] !== 'undefined';
            var containerId = typeof arrayOfContainers[currentItnAjaxId] !== 'undefined' ? arrayOfContainers[currentItnAjaxId] : '';
            var ajaxType;
            if(arrayOfItnAjax[currentItnAjaxId].indexOf('Host') !== -1){
                ajaxType = 'host';
                if(isMultiple) {
                    self.ajaxSelectedHosts = $('#' + currentItnAjaxId).val();
                }else if($('#' + currentItnAjaxId).val() != '0' && $('#' + currentItnAjaxId).val() != null && self.ajaxSelectedHosts.indexOf($('#' + currentItnAjaxId).val()) != -1){
                    self.ajaxSelectedHosts.push($('#' + currentItnAjaxId).val());
                }
            }else{
                ajaxType = 'service';
                if(isMultiple) {
                    self.ajaxSelectedServices = $('#' + currentItnAjaxId).val();
                }else if($('#' + currentItnAjaxId).val() != '0' && $('#' + currentItnAjaxId).val() != null && self.ajaxSelectedServices.indexOf($('#' + currentItnAjaxId).val()) != -1){
                    self.ajaxSelectedServices.push($('#' + currentItnAjaxId).val());
                }
            }
            if(itnAjaxLoading){
                $('#'+currentItnAjaxId+'_chosen li.no-results').text('Loading...');
            }
            if(lastTyped == termInputValue)
                return false;

            lastTyped = termInputValue;
            clearTimeout(myItnTimeout);

            $('#'+currentItnAjaxId+'_chosen li.no-results').text('Loading...');
            itnAjaxLoading = true;
            myItnTimeout = setTimeout(function () {
                if (termInputValue.length >= 1) {
                    $.ajax({
                        method: 'POST',
                        url: arrayOfItnAjax[currentItnAjaxId],
                        data: {term: termInputValue, selected: ajaxType==='host' ? self.ajaxSelectedHosts : self.ajaxSelectedServices, 'containerId': $(containerId).val(), isMultiple : isMultiple},
                        success: function (data) {
                            $('#' + currentItnAjaxId).html(data).trigger('chosen:updated');
                            termInput.val(termInputValue);
                            if(isMultiple){
                                termInput.width(((termInputValue.length + 1) * 8) + 'px');
                            }
                            itnAjaxLoading = false;
                        }
                    });
                }
            }, 1000);

        });

        this._updateHeaderExportRunning();
        this._initComponents();
        this._initialize(); // Intented to be overwritten.
        this._initUiLibrary(); // Should not be overwritten.

        /* After you click a value, it prevents the closure of drop-down */
        $('.stayOpenOnClick').click(function (event) {
            event.stopPropagation();
        });
    },

    _initUiLibrary: function () {
        moment().locale('de').format(); // moment.js initialization is required for the datetimepicker.

        $('.datetimepicker input.form-control').datetimepicker({
            language: 'de',
            useSeconds: true
        });
        $('.datetimepicker .input-group-addon') // A click on the glyph shows the datetimepicler.
            .css({
                cursor: 'pointer'
            })
            .click(function () {
                $(this).parent().find('input').focus();
            });
    },


    /**
     * Initializer, this one should be used by sub controllers
     *
     * @return void
     */
    _initialize: function () {

    },

    /**
     * Initializes global UI components
     *
     * @return void
     */
    _initComponents: function () {
        var self = this;
        /*
         if (typeof $().chosen === 'function') {


         $('.chosen').chosen({
         placeholder_text_single: 'Please choose',
         placeholder_text_multiple: 'Please choose',
         allow_single_deselect: true, // This will only work if the first option has a blank text.
         search_contains: true,
         enable_split_word_search: true,
         //   select_all_buttons: true,
         width: '100%' // Makes the graph responsive.
         });
         }
         */
        this.ImageChooser.setup(this._dom);
        this.FileChooser.setup(this._dom);

        //this.CkEditor.setupEditors();
        App.Main.UIBlocker = new App.Helpers.UIBlocker();
        this.ListFilter.render();
        this._dom.find('.js-tabs a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        // automatic AJAX posts and loadWidget rendering
        this._dom.find('.dialog-ajax-form').each(function (i, el) {
            self._ajaxDialogFormSetup($(el));
        });
    },
    /**
     * Returns the DOM element of the controller
     *
     * @return DOMElement
     */
    getDomElement: function () {
        return this._dom;
    },
    /**
     * Returns Server-side state value for mobile-check
     *
     * @return {boolean}
     */
    isMobile: function () {
        return this.getVar('isMobile');
    },
    /**
     * If the current request was made via ajax, bind the submit event, make an ajax
     * POST request and update the dialog
     * TODO: make URL and loadWidget options configurable
     * @return {void}
     */
    _ajaxDialogFormSetup: function ($form, callback) {
        var self = this;

        if (!this.isAjax()) {
            return;
        }

        var urlObject = {
            controller: this.name,
            action: this.action,
            pass: this._frontendData.params.pass,
            plugin: this._frontendData.params.plugin,
            named: this._frontendData.params.named
        };
        $form.submit(function (e) {
            e.preventDefault();
            App.Main.UIBlocker.blockElement(this._dom);
            App.Main.loadWidget(urlObject, {
                target: self._dom.parent(),
                data: $form.serialize(),
                parentController: self.parentController,
                onComplete: function (controller, response) {
                    App.Main.UIBlocker.unblockElement(self._dom);
                    if (typeof callback === 'function') {
                        callback(controller, response);
                    }
                }
            });
            return false;
        });
    },
    /**
     * If the current request was made via ajax, bind the submit event, make an ajax
     * POST request and update the dialog
     * TODO: make this more configurable
     * @return {void}
     */
    openDialog: function (url, onClose) {
        var self = this;

        self._dialog = new App.Dialog({
            onClose: onClose
        });
        self._dialog.blockUi();
        App.Main.loadWidget(url, {
            parentController: self,
            target: self._dialog.getContent(),
            onComplete: function () {
                self._dialog.show();
                self._dialog.unblockUi();
            }
        });
    },

    _updateHeaderExportRunning: function(){
        if(this.getVar('exportRunningHeaderInfo')){
            this.WebsocketSudo.setup(this.getVar('websocket_url'), this.getVar('akey'));
            this.WebsocketSudo.connect();

            this.WebsocketSudo._success = function(e){
                return true;
            }.bind(this)

            this.WebsocketSudo._dispatcher = function(transmitted){
                if(transmitted.running && !$('#i-export-running-checker').hasClass('fa-spin')){
                    $('#i-export-running-checker').removeClass('fa-retweet').addClass('fa-spin fa-refresh txt-color-red');
                }else if(!transmitted.running && $('#i-export-running-checker').hasClass('fa-spin')){
                    $('#i-export-running-checker').removeClass('fa-spin fa-refresh txt-color-red').addClass('fa-retweet');
                }
            }.bind(this);
        }
    }
});
