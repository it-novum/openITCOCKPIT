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

App.Components.CustomVariablesComponent = Frontend.Component.extend({
    $customVariablesContainer: null,
    $ajaxloader: null,

    setup: function(conf){
        conf = conf || {};
        this.ajaxUrl = conf.ajaxUrl || '/Hosttemplates/addCustomMacro';
        this.controller = conf.controller || 'Hosttemplates';
        this.macrotype = conf.macrotype || 'HOST';
        this.macroPrefix = conf.macroPrefix || '$_';
        this.macroSuffix = conf.macroSuffix || '$';
        this.illegalCharacters = conf.illegalCharacters || /[^\d\w\_]/g;
        this.onClick = conf.onClick || function(){
        };
        this.ajaxUrl = '/' + this.controller + '/addCustomMacro';

        $customVariablesContainer = $('#customVariablesContainer');
        this.$ajaxloader = $('#global_ajax_loader');

        var self = this;

        /*
         * Bind the click event to the trash icon and remove the macro from DOM
         */
        $(document).on("click", ".deleteMacro", function(e){
            $this = $(this);
            $this.parent().parent().remove();
        });

        /*
         * Bind the change event to the maco name input
         */
        $(document).on("change", ".macroName", function(e){
            $this = $(this);
            var clearName = $this.val().toUpperCase().replace(self.illegalCharacters, '');
            $this.parent().parent().find('span').html(self.macroPrefix + self.macrotype + clearName + self.macroSuffix);
            $this.val(clearName);
        });

        $('.addCustomMacro').on('click', function(){
            self.addCustomMacroSkeleton(self.onClick);
        });

    },

    addCustomMacroSkeleton: function(onSuccess){
        this.$button = $(this);
        this.$button.prop('disabled', true);
        this.$ajaxloader.show();
        ret = $.ajax({
            url: this.ajaxUrl + "/" + this.getNextId(),
            type: "GET",
            error: function(){
            },
            success: function(){
            },
            complete: function(response){


                $customVariablesContainer.append(response.responseText);

                this.$ajaxloader.fadeOut('slow');
                this.$button.prop('disabled', null);
                onSuccess();
            }.bind(this)
        });
    },

    getNextId: function(){
        var currentHighestValue = 1;
        var $custmVariableInputs = $('#customVariablesContainer').find('.macroName');

        $custmVariableInputs.each(function(key, currentInputField){
            var $currentInputField = $(currentInputField);

            var counterAttr = $currentInputField.attr('counter');
            if(typeof counterAttr !== typeof undefined && counterAttr !== false){
                var currentValue = parseInt(counterAttr, 10);
                if(currentValue > currentHighestValue){
                    currentHighestValue = currentValue;
                }
            }
        });

        console.log(currentHighestValue + 1);
        return currentHighestValue + 1;
    },

    loadMacroFromTemplate: function(template_id, onComplete){
        onComplete = typeof onComplete === 'function' ? onComplete : function(){
        };
        this.$button = $('.addCustomMacro');
        this.$button.prop('disabled', true);
        this.$ajaxloader.show();
        ret = $.ajax({
            url: "/" + this.controller + "/loadTemplateMacros/" + encodeURIComponent(template_id),
            type: "GET",
            dataType: "json",
            error: function(){
            },
            success: function(){
            },
            complete: function(response){
                //if(response.responseJSON.count > 0){ // what for???
                // }
                $customVariablesContainer.html(response.responseJSON.html);
                onComplete.call($customVariablesContainer, response);
                this.$ajaxloader.fadeOut('slow');
                this.$button.prop('disabled', null);
            }.bind(this)
        });
    }
});
