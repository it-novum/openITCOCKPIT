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

App.Components.WidgetMapComponent = Frontend.Component.extend({

	Ajaxloader: null,

	maps: {},

	setAjaxloader: function(Ajaxloader){
		this.Ajaxloader = Ajaxloader;
	},

	initMaps: function(){
		var self = this;
		$(document).on('change', '.mapSelectMap', function(){
			var $object = $(this);
			var widgetId = parseInt($object.data('widget-id'), 10);
			var mapId = parseInt($object.val(), 10);
			self.saveMap(widgetId, mapId);
		});

		var gridstack = $('.grid-stack');
		gridstack.on('resizestop', function(event, ui){
			var $element = $(ui.element);
			var widgetId = parseInt($element.data('widget-id'), 10);
			var widgetTypeId = parseInt($element.data('widget-type-id'), 10);
			//Only execute if there is an iframe
			if(widgetTypeId == 14){
				if($element.find('iframe').height()){
					this.calculateHeightIframe($element);
				}
			}

		}.bind(this));

		$('.mapContainer').each(function(key, object){
			this.initMap(object);
		}.bind(this));
	},

	initMap: function(object){
		var $object = $(object);
		widgetId = parseInt($object.parents('.grid-stack-item').data('widget-id'), 10);
		var $container = $object;
		var $wrapper = $object.parents('.map-body').find('.mapWrapper');
		var $widgetContainer = $object.parents('.grid-stack-item');
		this.maps[widgetId] = {
			container: $container,
			wrapper: $wrapper,
			idMap: parseInt($container.data('id-map'), 10),
			widgetContainer: $widgetContainer
		};

		if(this.maps[widgetId].idMap > 0){
			//console.log($(this.maps[widgetId].widgetContainer));
			this.calculateHeightIframe($(this.maps[widgetId].widgetContainer));
		}
	},

	calculateHeightIframe: function(object){
		$element = $(object);
		if($element.find('iframe').height()){
			var totalHeight = $element.find('.grid-stack-item-content').height();
			var iframeHeight = totalHeight - 102;
			$element.find('iframe').height(iframeHeight);
		}
	},

	saveMap: function(widgetId, mapId){
		this.Ajaxloader.show();
		$.ajax({
			url: "/dashboards/saveMapId",
			type: "POST",
			data: {widgetId: widgetId, mapId: mapId},
			error: function(){},
			success: function(response){
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
					this.initMap($mapBody.find('.mapContainer'));
					$('.chosen').chosen();
				}
				this.Ajaxloader.hide();
			}.bind(this),
			complete: function(response) {
			}
		});
	}

});

