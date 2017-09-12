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
	lang: [],
	tabRotationInterval: 0,
	tabRotationIntervalId: null,

	components: [
		'Ajaxloader',
		'Utils',
		'WidgetChart180',
		'WidgetStatusList',
		'WidgetTrafficLight',
		'WidgetTacho',
		'WidgetMap',
		'WidgetGraphgenerator',
		'WidgetGrafana',
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

		this.WidgetTrafficLight.setAjaxloader(this.Ajaxloader);
		this.WidgetTrafficLight.initTrafficlights();

		this.WidgetTacho.setAjaxloader(this.Ajaxloader);
		this.WidgetTacho.initTachos();

		this.WidgetMap.setAjaxloader(this.Ajaxloader);
		this.WidgetMap.initMaps();

		this.WidgetGraphgenerator.setAjaxloader(this.Ajaxloader);
		this.WidgetGraphgenerator.initGraphs();

		this.WidgetGrafana.setAjaxloader(this.Ajaxloader);
		this.WidgetGrafana.initGrafana();

		if(this.getVar('updateAvailable') === true){
			$('#updateAvailableModal').modal('show');
		}

		this.tabRotationInterval = parseInt(this.getVar('tabRotationInterval'), 10);

		this.lang = [];
		this.lang[1] = this.getVar('lang_minutes');
		this.lang[2] = this.getVar('lang_seconds');
		this.lang[3] = this.getVar('lang_and');
		this.lang[4] = this.getVar('lang_disabled');

		var self = this;

		$('.nav-tabs').sortable({
			update: function(){
				var $tabbar = $(this);
				var $tabs = $tabbar.children();
				var tabIdsOrdered = {};
				$tabs.each(function(key, tab){
					var $tab = $(tab);
					tabIdsOrdered[key] = parseInt($tab.data('tab-id'), 10);
				});
				self.Ajaxloader.show();
				$.ajax({
					url: "/dashboards/updateTabPosition",
					type: "POST",
					cache: false,
					data: {tabIdsOrdered: tabIdsOrdered},
					error: function(){},
					success: function(response){
						//console.log(response);
						self.Ajaxloader.hide();
					}.bind(self),
					complete: function(response) {
					}
				});
			},
			placeholder: 'tabTargetDestination'
		});

		//Bind click evento for noAutoUpdate
		$('#noAutoUpdate').click(function(){
			var askAgain = $('#dashboardAskAgain').prop('checked');
			if(askAgain === true){
				//Send AJAX request to disable update function
				self.Ajaxloader.show();
				$.ajax({
					url: "/dashboards/disableUpdate",
					type: "POST",
					cache: false,
					data: {tabId: self.tabId},
					error: function(){},
					success: function(response){
						//console.log(response);
						self.Ajaxloader.hide();
					}.bind(self),
					complete: function(response) {
					}
				});
			}
		});

		// Bind click event to create new widgets
		$('.addWidget').click(function(){
			var $object = $(this);
			var grid = self.$gridstack.data('gridstack');
			//grid.add_widget($.parseHTML(response.responseText),0,0,width,height,false);
			self.Ajaxloader.show();
			$.ajax({
				url: "/dashboards/add",
				type: "POST",
				cache: false,
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
						case 10:
							this.WidgetStatusList.initList($(widgetHtml).find('.statusListTable'));
							break;

						case 11:
							this.WidgetTrafficLight.initTrafficlight($(widgetHtml).find('.trafficlightContainer'));
							$('.chosen').chosen();
							break;

						case 12:
							this.WidgetTacho.initTacho($(widgetHtml).find('.tachometerContainer'));
							$('.chosen').chosen();
							break;

						case 14:
							this.WidgetMap.initMap($(widgetHtml).find('.mapWrapper'));
							$('.chosen').chosen();

						case 15:
							this.WidgetGraphgenerator.initGraph($(widgetHtml).find('.graphWrapper'));
							$('.chosen').chosen();
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
							cache: false,
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
				cache: false,
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
				cache: false,
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

		//Create tab rotation slider
		var $tabRotationSlider = $('#TabRotationInterval');
		$tabRotationSlider.slider({ tooltip: 'hide' });
		$tabRotationSlider.slider('on', 'slide', function(ev){
			if(ev.value == null){
				ev.value = 0;
			}

			var min = parseInt(ev.value / 60, 10);
			var sec = parseInt(ev.value % 60, 10);

			if(parseInt(ev.value, 10) === 0){
				//Disabled
				$('#TabRotationInterval_human').html(self.lang[4]);
			}else{
				$('#TabRotationInterval_human').html(min + " " + self.lang[1] + " " + self.lang[3] + " " + sec + " " + self.lang[2]);
			}
		});
		$tabRotationSlider.slider('on', 'slideStop', function(ev){
			// NOTICE BOOTSTRAP BUG
			// slideStop will get called twice due to a bug in bootstrap
			// If ev.target.form is undefiend, we return to avoid two AJAX requests :)

			if(typeof ev.target.form == 'undefined'){
				return true;
			}

			if(ev.value == null){
				ev.value = 0;
			}

			var min = parseInt(ev.value / 60, 10);
			var sec = parseInt(ev.value % 60, 10);

			if(parseInt(ev.value, 10) === 0){
				//Disabled
				$('#TabRotationInterval_human').html(self.lang[4]);
			}else{
				$('#TabRotationInterval_human').html(min + " " + self.lang[1] + " " + self.lang[3] + " " + sec + " " + self.lang[2]);
			}
			//Save new slider value
			self.Ajaxloader.show();

			$.ajax({
				url: "/dashboards/saveTabRotationInterval",
				type: "POST",
				cache: false,
				data: {value: parseInt(ev.value, 10)},
				error: function(){},
				success: function(response){
					self.Ajaxloader.hide();
					$('#tabRotateModal').modal('hide');
				}.bind(self),
				complete: function(response) {
				}
			});

			//Update tab rotation interval
			self.tabRotationInterval = parseInt(ev.value, 10);
			self.startTabRotationInterval();
		});

		//Start tab rotation
		if(this.tabRotationInterval > 0){
			this.startTabRotationInterval();
		}

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
			cache: false,
			data: {data: data, tabId: this.tabId},
			error: function(){},
			success: function(response){
				this.Ajaxloader.hide();
			}.bind(this),
			complete: function(response) {
			}
		});
	},

	startTabRotationInterval: function(){
		this.stopTabRotationInterval();
		if(this.tabRotationInterval > 0){
			// 2 seconds befor the tab switch is executet, we start the spin animation for the reload icon
			var self = this;
			var interval = (this.tabRotationInterval * 1000) - 2000;
			this.tabRotationIntervalId = setTimeout(function(){
				var _this = self;
				$('#tabRotationIcon').addClass('fa-spin');
				setTimeout(function(){
					window.location.href = '/dashboards/next/'+_this.tabId;
				}, 2000);
			}, interval);
		}
	},

	stopTabRotationInterval: function(){
		if(this.tabRotationIntervalId != null){
			clearTimeout(this.tabRotationIntervalId);
		}
	}

});
