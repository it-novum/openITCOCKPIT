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

App.Components.WidgetGrafanaComponent = Frontend.Component.extend({

    setAjaxloader: function(Ajaxloader){
        this.Ajaxloader = Ajaxloader;
    },

    initGrafana:function(){
        var self = this;
        $(document).on('change', '.GrafanaSelectHost', function(e){
            console.log('test');
            var $object = $(e.target);
            var widgetId = parseInt($object.data('widget-id'), 10);
            var hostId = parseInt($object.val(), 10);
            self.saveGrafanaId(widgetId, hostId);
        }.bind(this));
    },

    saveGrafanaId: function(widgetId, hostId){
        console.log('save grafana id fired');
        this.Ajaxloader.show();
        $.ajax({
            url: "/dashboards/saveGrafanaId",
            type: "POST",
            data: {widgetId: widgetId, hostId: hostId},
            error: function(){},
            success: function(response){
console.log('ajax success');
                this.Ajaxloader.hide();
                this.refresh(widgetId);
            }.bind(this),
            complete: function(response) {
            }
        });
    },

    refresh: function(widgetId){
        var $wrapper = this.maps[widgetId].wrapper;
        var $mapBody = $wrapper.parents('.map-body').parent();
        $wrapper.html('<div class="text-center padding-top-50"><h1><i class="fa fa-cog fa-lg fa-spin"></i></h1></div>');
        this.Ajaxloader.show();
        $.ajax({
            url: "/dashboards/refresh",
            type: "POST",
            data: {widgetId: widgetId},
            error: function(){},
            success: function(response){
                if(response != ''){
                    $mapBody.html(response);
                   // this.initMap($mapBody.find('.mapContainer'));
                    $('.chosen').chosen();
                }
                this.Ajaxloader.hide();
            }.bind(this),
            complete: function(response) {
            }
        });
    }

});