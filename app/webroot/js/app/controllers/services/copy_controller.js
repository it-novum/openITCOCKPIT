// Copyright (C) <2018>  <it-novum GmbH>
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

App.Controllers.ServicesCopyController = Frontend.AppController.extend({

    _initialize: function(){
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
                    'filter[Hosts.name]': searchString
                },
                success: function(response){
                    ChosenAjaxObj.addOptions(response.hosts);
                }
            });
        });

        ChosenAjaxObj.render();
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

        $.ajax({
            dataType: "json",
            url: '/hosts/loadHostsByString.json',
            data: {
                'angular': true,
                'selected[]': selectedHostIds //ids
            },
            success: function(response){
                var $selector = $(selector);
                var list = self.buildList(response.hosts);
                $selector.append(list);
                $selector.val(selectedHostIds);
                $selector.trigger('chosen:updated');

                if(callback != undefined){
                    callback();
                }
            }
        });
    },


    buildList: function(data){
        var html = '';
        for(var i in data){
            html += '<option value="' + data[i].key + '">' + htmlspecialchars(data[i].value) + '</option>';
        }
        return html;
    },

});