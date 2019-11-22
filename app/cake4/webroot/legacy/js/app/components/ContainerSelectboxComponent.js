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

App.Components.ContainerSelectboxComponent = Frontend.Component.extend({
    Ajaxloader: null,

    callback: function(containerId){
    },

    setup: function(Ajaxloader){
        this.Ajaxloader = Ajaxloader;
    },

    setCallback: function(callback){
        this.callback = callback;
    },

    addContainerEventListener: function(options){
        var self = this;

        var defaults = {
            event: 'change',
            optionGroupFieldTypes: {}
        };

        options = $.extend({}, defaults, options);

        $(options.selectBoxSelector).on(options.event, function(){
            var containerId = parseInt($(this).val(), 10),
                ajaxUrl;

            if(isNaN(containerId) || containerId <= 0){
                return;
            }

            ajaxUrl = options.ajaxUrl.replace(':selectBoxValue:', containerId);
            self.Ajaxloader.show();

            $.ajax({
                url: ajaxUrl,
                type: 'post',
                dataType: 'json',
                error: function(){
                },
                success: function(){
                },
                complete: function(response){
                    var fieldType,
                        key,
                        $querySelect;
                    if(Object.keys(options.optionGroupFieldTypes).length > 0){
                        for(fieldType in response.responseJSON){
                            $querySelect = $(options.optionGroupFieldTypes[fieldType]);
                            var oldValues = ($querySelect.val()) ? $querySelect.val() : [];
                            $querySelect.html('');
                            $querySelect.attr('data-placeholder', options.dataPlaceholder);

                            if(Object.keys(response.responseJSON[fieldType]).length > 0){
                                $querySelect.attr('data-placeholder', options.dataPlaceholder);
                            }
                            self.getFilteredSelectionsForOptionGroup($querySelect, oldValues, response.responseJSON[fieldType], fieldType);
                            $querySelect.trigger('chosen:updated');
                        }
                    }

                    for(fieldType in response.responseJSON){
                        $querySelect = $(options.fieldTypes[fieldType]);
                        var oldValues = ($querySelect.val()) ? $querySelect.val() : [];
                        $querySelect.html('');
                        $querySelect.attr('data-placeholder', options.dataPlaceholder);

                        self.getFilteredSelections($querySelect, oldValues, response.responseJSON[fieldType]);
                        $querySelect.trigger("chosen:updated");
                    }
                    self.Ajaxloader.hide();

                    self.callback(containerId);
                }
            });
        });
    },

    getFilteredSelections: function($querySelect, values, newData){
        values = (values instanceof Array) ? values : [values];
        for(var key in newData){
            var selected = false;
            if(in_array(newData[key].key, values)){
                selected = true;
            }
            this.addOptionsForInputField($querySelect, newData[key].key, newData[key].value, selected);
        }
    },

    getFilteredSelectionsForOptionGroup: function($querySelect, values, newData, typeKey){
        var optgroupLabel = null;
        var $optGroupObject = null;
        values = (values instanceof Array) ? values : [values];
        for(var key in newData){

            for(var subKey in newData[key].value){
                if(optgroupLabel != subKey){
                    optgroupLabel = subKey;
                    this.addOptionGroupForInputField($querySelect, typeKey + '_' + newData[key].key, optgroupLabel);
                    $optGroupObject = $('#' + typeKey + '_' + newData[key].key);
                }

                for(var k in newData[key]['value'][subKey]){
                    var selected = false;
                    if(in_array(k, values)){
                        selected = true;
                    }
                    this.addOptionsForInputField($optGroupObject, k, newData[key]['value'][subKey][k], selected);
                }
            }
        }
    },

    addOptionsForInputField: function($querySelect, optionKey, optionValue, selected){
        if(this.Controller.name === 'services' && this.Controller.action === 'add'){
            if($querySelect.selector === '#ServiceServicetemplateId'){
                $querySelect.append(
                    $('<option>')
                );
            }
        }

        $querySelect.append(
            $('<option>', {
                value: optionKey,
                text: optionValue,
                selected: selected
            })
        );
    },

    addOptionGroupForInputField: function($querySelect, id, optionGroupLabel){
        $querySelect.append(
            $('<optgroup>', {
                id: id,
                label: optionGroupLabel
            })
        );
    }
});
