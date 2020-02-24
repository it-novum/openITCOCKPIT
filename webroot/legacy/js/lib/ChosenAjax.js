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

function ChosenAjax(conf){
    conf = conf || {};
    this.id = conf.id || null;
    this.callback = conf.callback || function(){
    };
    this.oldTimeout = null;

    if(this.id === null){
        console.log('Required parameter id missing');
    }

    this.render = function(){
        var self = this;
        var defaultOptions = {
            placeholder_text_single: 'Please choose',
            placeholder_text_multiple: 'Please choose',
            allow_single_deselect: true, // This will only work if the first option has a blank text.
            search_contains: true,
            enable_split_word_search: true,
            width: '100%',
            search_callback: function(searchString){
                if(self.callback){

                    if(self.oldTimeout){
                        clearTimeout(self.oldTimeout);
                        self.oldTimeout = null;
                    }

                    self.oldTimeout = setTimeout(function(){
                        self.callback(searchString);
                    }, 500);
                }
            }
        };

        if(this.callback){
            defaultOptions['no_results_text'] = 'Search for ';
        }
        if($('#' + this.id).prop('multiple')){
            defaultOptions['select_all_buttons'] = true;
        }

        $('#' + this.id).chosen(defaultOptions);
    };

    this.setCallback = function(callback){
        this.callback = callback;
    };

    this.addOptions = function(options, keepLastElementSelected){
        if(typeof keepLastElementSelected === "undefined"){
            keepLastElementSelected = false;
        }

        var $element = $('#' + this.id);

        var lastSelectedElement = $element.val();

        $element.empty();

        if(options.length === 0){
            $element.append('<option></option>');
        }else{
            //No object select, add empty placeholder object to avoid that chosen selectes the first element in select box
            if(keepLastElementSelected === true){
                $element.append('<option></option>');
            }

            for(var key in options){
                var current = options[key];
                $element.append('<option value="' + current.key + '">' + htmlspecialchars(current.value) + '</option>');
            }

            if(keepLastElementSelected === true){
                if(lastSelectedElement){
                    $element.val(lastSelectedElement);
                }
            }
        }
        this.triggerUpdate();
    };

    this.addOptionGroups = function(options){
        var $element = $('#' + this.id);
        $element.empty();
        var tmpHostname = null;
        for(var key in options){
            var current = options[key];
            if(tmpHostname !== current.value.Host.name){
                tmpHostname = current.value.Host.name;
                var $optGroup = $('<optgroup>').attr('label', htmlspecialchars(tmpHostname));
            }

            var serviceName = current.value.Service.name;
            if(current.value.Service.name === null || current.value.Service.name === ''){
                serviceName = current.value.Servicetemplate.name
            }

            $optGroup.append('<option value="' + current.key + '">'+htmlspecialchars(tmpHostname)+'/'+htmlspecialchars(serviceName)+'</option>');
            $element.append($optGroup);
        }
        this.triggerUpdate();
    };

    this.triggerUpdate = function(){
        $('#' + this.id).trigger('chosen:updated');
    };

    this.setSelected = function(selected){
        $('#' + this.id).val(selected);
        this.triggerUpdate();
    };

    this.getSelected = function(){
        return $('#' + this.id).val();
    };

    this.getId = function(){
        return this.id;
    }
}


