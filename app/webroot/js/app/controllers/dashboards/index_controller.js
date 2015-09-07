'use strict';
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

App.Controllers.DashboardsIndexController = Frontend.AppController.extend({
	$gridstack: null,
	tabId: null,

	components: [
		'Ajaxloader',
	],


	//components: [
	//	'Rrd',
	//	'Utils',
	//	'WidgetTrafficLight',
	//	'WidgetTacho',
	//	'WidgetServiceStatusList',
	//	'WidgetHostStatusList',
	//	'WidgetMap',
	//	'Ajaxloader',
	//	'WidgetBrowser',
	//	'WidgetNotice',
	//	'WidgetGraphgenerator',
	//	'Overlay',
	//	'BootstrapModal',
	//	'Uuid',
	//],



	/**
	 * @constructor
	 * @return {void}
	 */
	_initialize: function(){
		this.Ajaxloader.setup();
		this.buildGridstack();
		this.tabId = this.getVar('tabId');
		var self = this;
		
		// Bind click event to change widget title
		$('.changeTitle').click(function(){
			var widgetId = $(this).data('widget-id');
			bootbox.prompt({
				title: self.getVar('lang').newTitle,
				value: $('#widget-title-'+widgetId).html(),
				callback: function(value){
					if(value !== null){
						//The user entered a new widget name
						$('#widget-title-'+widgetId).html(value);
						self.Ajaxloader.show();
						$.ajax({
							url: "/dashboards/updateTitle",
							type: "POST",
							data: {title: value, widgetId: widgetId},
							error: function(){},
							success: function(response){
								self.Ajaxloader.hide();
							}.bind(self),
							complete: function(response) {
							}
						});
					}
				}
			});
		});
		
		//Bind click event to change widget color
		$("[select-color='true']").click(function(){
			var $colorChoosDiv = $(this).parent().parent().parent();
			var $selectButton = $colorChoosDiv.find('a');
			var widgetId = $selectButton.data('widget-id');
			var newColor = $(this).attr('class');
			
			var newColorJs = newColor.replace('bg-', 'jarviswidget-');
			var oldColorJs = $selectButton.attr('current-color');
			$selectButton.removeClass(oldColorJs);
			$selectButton.addClass(newColorJs);
			$selectButton.attr('current-color', newColorJs);
			
			$('#widget-color-'+widgetId).removeClass(oldColorJs);
			$('#widget-color-'+widgetId).addClass(newColorJs);
			
			self.Ajaxloader.show();
			$.ajax({
				url: "/dashboards/updateColor",
				type: "POST",
				data: {color: newColor, widgetId: widgetId},
				error: function(){},
				success: function(response){
					self.Ajaxloader.hide();
				}.bind(self),
				complete: function(response) {
				}
			});
		});
		
		//Bind click event to delete widget
		$('.deleteWidget').click(function(){
			var widgetId = $(this).data('widget-id');
			self.Ajaxloader.show();
			self.$gridstack.data('gridstack').remove_widget($(this).parents('.grid-stack-item'));
			
			$.ajax({
				url: "/dashboards/deleteWidget",
				type: "POST",
				data: {widgetId: widgetId},
				error: function(){},
				success: function(response){
					self.Ajaxloader.hide();
					self.updatePosition();
				}.bind(self),
				complete: function(response) {
				}
			});
		});
	},
	
	buildGridstack: function(){
		this.$gridstack = $('.grid-stack');
		var options = {
			animate: false,
			cell_height: 10,
			cell_width: 10,
			 draggable: {
				handle: '.jarviswidget header[role="heading"]',
			},
			always_show_resize_handle: true,
			vertical_margin: 10
		};
		this.$gridstack.gridstack(options);
		var _self = this;
		this.$gridstack.on('dragstop', function(event, ui){
			//var widgets = event.currentTarget.children;
			_self.updatePosition();
		});
		
		this.$gridstack.on('resizestop', function(event, ui){
			_self.updatePosition();
		});
	},
	
	updatePosition: function(widgets){
		this.Ajaxloader.show();
		var data = [];
		$('.grid-stack-item').each(function(intKey, object){
			var widget = {};
			var $object = $(object);
			var node = $object.data('_gridstack_node');
			if(typeof node !== 'undefined'){
				// _gridstack_node is the internal object. We need to use this, because $object.data('gs-x'); will return wrong result. I guess because the internal GS event isn't finished at all
				widget.id = $object.data('widget-id');
				widget.row = node.x;
				widget.col = node.y;
				widget.width = node.width;
				widget.height = node.height;
				data.push(widget);
			}

		});
		
		$.ajax({
			url: "/dashboards/updatePosition",
			type: "POST",
			data: {data: data, tabId: this.tabId},
			error: function(){},
			success: function(response){
				this.Ajaxloader.hide();
			}.bind(this),
			complete: function(response) {
			}
		});
	}
	
});
