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

App.Components.WidgetGraphgeneratorComponent = Frontend.Component.extend({

	Ajaxloader: null,

	graphgenerators: {},

	setAjaxloader: function(Ajaxloader){
		this.Ajaxloader = Ajaxloader;
	},

	initGraphs: function(){
		var self = this;
		$(document).on('change', '.graphSelectGraph', function(){
			var $object = $(this);
			var widgetId = parseInt($object.data('widget-id'), 10);
			var graphId = parseInt($object.val(), 10);
			self.saveGraph(widgetId, graphId);
		});

		var gridstack = $('.grid-stack');
		gridstack.on('resizestop', function(event, ui){
			var $element = $(ui.element);
			var widgetId = parseInt($element.data('widget-id'), 10);
			var widgetTypeId = parseInt($element.data('widget-type-id'), 10);
			//Only execute if there is an iframe


		}.bind(this));

		$('.graphContainer').each(function(key, object){
			this.initGraph(object);
		}.bind(this));
	},

	initGraph: function(object){
		var $object = $(object);
		var widgetId = parseInt($object.parents('.grid-stack-item').data('widget-id'), 10);
		var $container = $object;
		var $wrapper = $object.parents('.graph-body').find('.graphWrapper');
		var $widgetContainer = $object.parents('.grid-stack-item');
		this.graphgenerators[widgetId] = {
			container: $container,
			wrapper: $wrapper,
			idGraph: parseInt($container.data('id-graph'), 10),
			widgetContainer: $widgetContainer
		};

		if(this.graphgenerators[widgetId].idGraph > 0){
			//console.log($(this.maps[widgetId].widgetContainer));
			this.calculateHeightIframe($(this.graphgenerators[widgetId].widgetContainer));
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

	saveGraph: function(widgetId, graphId){
		this.Ajaxloader.show();
		$.ajax({
			url: "/dashboards/saveGraphId",
			type: "POST",
			data: {widgetId: widgetId, graphId: graphId},
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
		var $wrapper = this.graphgenerators[widgetId].wrapper;
		var $graphBody = $wrapper.parents('.graph-body').parent();
		$wrapper.html('<div class="text-center padding-top-50"><h1><i class="fa fa-cog fa-lg fa-spin"></i></h1></div>');
		this.Ajaxloader.show();
		$.ajax({
			url: "/dashboards/refresh",
			type: "POST",
			data: {widgetId: widgetId},
			error: function(){},
			success: function(response){
				if(response != ''){
					$graphBody.html(response);
					this.initGraph($graphBody.find('.graphContainer'));
					$('.chosen').chosen();
				}
				this.Ajaxloader.hide();
			}.bind(this),
			complete: function(response) {
			}
		});
	}

});

