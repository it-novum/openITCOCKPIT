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
    this.selected = [];


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

        $('#' + this.id).chosen(defaultOptions);
    };

    this.setCallback = function(callback){
        this.callback = callback;
    };

    this.addOptions = function(options){
        var $element = $('#' + this.id);
        $element.empty();
        for(var key in options){
            var current = options[key];
            $element.append('<option value="' + current.key + '">'+htmlspecialchars(current.value)+'</option>');
        }
        this.triggerUpdate();
    };

    this.triggerUpdate = function(){
        $('#' + this.id).trigger('chosen:updated');
    };

    this.setSelected = function(selected){
        this.selected = selected;
    };

    this.getSelected = function(){
        return this.selected;
    };
}


