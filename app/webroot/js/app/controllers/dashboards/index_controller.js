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
	gridCallbacks: [],

	components: [
		'Ajaxloader',
		'Utils',
		'WidgetChart180',
		'WidgetStatusList',
	],

	_initialize: function(){
		this.Ajaxloader.setup();
		this.buildGridstack();
		this.tabId = this.getVar('tabId');
		this.WidgetChart180.bindEvents();
		this.WidgetStatusList.setup(this.Utils);
		this.WidgetStatusList.setAjaxloader(this.Ajaxloader);
		this.WidgetStatusList.initLists();
		this.gridCallbacks.push(this.updatePosition);
		
		
		var self = this;
		
		// Bind click event to create new widgets
		$('.addWidget').click(function(){
			var $object = $(this);
			var grid = self.$gridstack.data('gridstack');
			//grid.add_widget($.parseHTML(response.responseText),0,0,width,height,false);
			self.Ajaxloader.show();
			$.ajax({
				url: "/dashboards/add",
				type: "POST",
				data: {typeId: $object.data('type-id'), tabId: self.tabId},
				error: function(){},
				success: function(response){
					if(response !== ''){
						var widgetHtml = $.parseHTML(response);
						grid.add_widget(widgetHtml);
						//New widget added. So we need to save all the new positions
						self.updatePosition();
						
						//Do we need to call any javascript actions?
						switch(parseInt($object.data('type-id'), 10)){
						case 9:
							this.WidgetStatusList.initList($(widgetHtml).find('.statusListTable'));
							break;
						}
					}
					self.Ajaxloader.hide();
				}.bind(self),
				complete: function(response) {
				}
			});
			
		});
		
		// Bind click event to change widget title
		$(document).on('click', '.changeTitle', function(){
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
		$(document).on('click', "[select-color='true']", function(){
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
		$(document).on('click', '.deleteWidget', function(){
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
			//We can add multiple callbacks to this.gridCallbacks array. Each callback of the array will be fired
			for(var key in _self.gridCallbacks){
				_self.gridCallbacks[key].apply(_self, []);
			}
		});
		
		this.$gridstack.on('resizestop', function(event, ui){
			for(var key in _self.gridCallbacks){
				_self.gridCallbacks[key].apply(_self, []);
			}
		});
	},
	
	updatePosition: function(){
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
