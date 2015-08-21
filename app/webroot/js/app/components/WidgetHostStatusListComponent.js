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

App.Components.WidgetHostStatusListComponent = Frontend.Component.extend({
	updateInterval: 10000,
	interval: null,
	timers: {},
	inc: 0,
	setup: function(options, allWidgetParameters){
		var self = this,
			defaults = {
				ids:[]
			};
		self.intervalIds = {};
		self.dataTables = {};

		self.options = $.extend({}, defaults, options);

		$(document).on('click','.fa-arrow-left', function(){
			self.switchDirection($(this));
		});
		$(document).on('click','.fa-arrow-up', function(){
			self.switchDirection($(this));
		});
		$(document).on('click','.fa-pause', function(){
			self.pauseActive($(this));
		});
		$(document).on('click','.fa-play', function(){
			self.pauseActive($(this));
		});

		var extractWidgetElements = function($widgetContainer){
			var result = {
				hostsPerPage : ($widgetContainer.find('.hosts-per-page').val() === "") ? 3 : $widgetContainer.find('.hosts-per-page').val(),
				refreshInterval : ($widgetContainer.find('.refresh-interval').val() === "") ? 3 : $widgetContainer.find('.refresh-interval').val(),
				animationInterval : $widgetContainer.find('.slider-primary'),
				showUp : $widgetContainer.find('.filter-up'),
				showDown : $widgetContainer.find('.filter-down'),
				showUnreachable : $widgetContainer.find('.filter-unreachable'),
				showAcknowledged : $widgetContainer.find('.filter-acknowledged'),
				showDowntime : $widgetContainer.find('.filter-downtime')
			};
			return result;
		}

		$(document).on('click','.host_list_save', function(event){

			var scrollDirection = 'left',
				hostsPerPage = 5,
				refreshInterval = 180,
				animationInterval = 3,
				showUp = 1,
				showDown = 1,
				showUnreachable = 1,
				showAcknowledged = 0,
				showDowntime = 0,
				widgetId = self.getWidgetId($(this));

			var $widgetContainer = $(this).parents('[widget-id="'+widgetId+'"]'),
				$widgetElements = extractWidgetElements($widgetContainer),
				$slider = $widgetContainer.find('input.slider'),
				animation = 'fadeInRight';

			if($widgetContainer.find('.fa-arrow-up').hasClass('text-primary')){
				scrollDirection = 'top';
				animation = 'fadeInUp';
			}
			if(animation === 'fadeInRight'){
				$widgetContainer.find('.fa-arrow-left').addClass('text-primary');
			}else{
				$widgetContainer.find('.fa-arrow-up').addClass('text-primary');
			}

			$widgetContainer.find('.statusListServices').attr('animation', 'animated '+animation);

			if($widgetElements.hostsPerPage != ''){
				hostsPerPage = $widgetElements.hostsPerPage;
			}

			if($widgetElements.refreshInterval != ''){
				refreshInterval = $widgetElements.refreshInterval;
			}

			if($widgetElements.animationInterval.val() != ''){
				animationInterval = $widgetElements.animationInterval.val();
			}

			$slider.slider();
			$widgetContainer.find('.pagingIntervalValue').html(animationInterval);
			$slider.slider('on', 'slideStop', function(e){
				$widgetContainer.find('.pagingIntervalValue').html(this.value);
				if(this.tagName == 'INPUT'){
					if($(this).attr('intervalId')){
						self.changeInterval($(this).attr('intervalId'), parseInt(this.value * 1000));
					}
				}
			});
			$widgetContainer.find('.slider-slim').css('display','flex');

			if(!$widgetElements.showUp.prop('checked')){
				showUp = 0;
			}
			if(!$widgetElements.showDown.prop('checked')){
				showDown = 0;
			}
			if(!$widgetElements.showUnreachable.prop('checked')){
				showUnreachable = 0;
			}
			if($widgetElements.showAcknowledged.prop('checked')){
				showAcknowledged = 1;
			}
			if($widgetElements.showDowntime.prop('checked')){
				showDowntime = 1;
			}

			if(self.intervalIds[widgetId]){
				clearInterval(self.intervalIds[widgetId]);
				self.intervalIds[widgetId] = 0;
			}
			if(self.timers[widgetId]){
				clearInterval(self.timers[widgetId]);
				delete self.timers[widgetId];
			}

			self.options.loadWidgetData(widgetId, function(result){
				if(result.data.WidgetHostStatusList.id){
					saveData = {
						'Widget': {
							'id' : widgetId,
						},
						'WidgetHostStatusList':{
							'id' : result.data.WidgetHostStatusList.id,
							'widgetId' : widgetId,
							'scroll_direction' : scrollDirection,
							'hosts_per_page' : hostsPerPage,
							'refresh_interval' : refreshInterval,
							'animation_interval' : animationInterval,
							'show_up' : showUp,
							'show_down' : showDown,
							'show_unreachable' : showUnreachable,
							'show_acknowledged' : showAcknowledged,
							'show_downtime' : showDowntime
						}
					};
					self.options.saveWidgetData(saveData);
				}
				else{
					saveData = {
						'Widget': {
							'id' : widgetId,
						},
						'WidgetHostStatusList':{
							'widgetId' : widgetId,
							'scroll_direction' : scrollDirection,
							'hosts_per_page' : hostsPerPage,
							'refresh_interval' : refreshInterval,
							'animation_interval' : animationInterval,
							'show_up' : showUp,
							'show_down' : showDown,
							'show_unreachable' : showUnreachable,
							'show_acknowledged' : showAcknowledged,
							'show_downtime' : showDowntime
						}
					};
					self.options.saveWidgetData(saveData);
				}
			});

			var $object = $widgetContainer.find('.statusListHosts');

			if(self.dataTables[widgetId]){
				self.dataTables[widgetId].dataTable({
					destroy: true,
					'ajax': '/admin/dashboard/statusListHosts/'+$widgetElements.showUp.prop('checked')+'/'+$widgetElements.showDown.prop('checked')+'/'+$widgetElements.showUnreachable.prop('checked')+'/'+$widgetElements.showAcknowledged.prop('checked')+'/'+$widgetElements.showDowntime.prop('checked'),
					'iDisplayLength': parseInt($widgetElements.hostsPerPage),
					'bLengthChange': false,
					'sScrollY': '200px',
					'bAutoWidth': true,
					'pagingType': 'numbers',
					'fnInitComplete': function(){
						self.startSlider(self.dataTables[widgetId], widgetId);
					}
				});
			}else{
				var table = $object.dataTable({
					destroy: true,
					'ajax': '/admin/dashboard/statusListHosts/'+$widgetElements.showUp.prop('checked')+'/'+$widgetElements.showDown.prop('checked')+'/'+$widgetElements.showUnreachable.prop('checked')+'/'+$widgetElements.showAcknowledged.prop('checked')+'/'+$widgetElements.showDowntime.prop('checked'),
					'iDisplayLength': parseInt($widgetElements.hostsPerPage),
					'bLengthChange': false,
					'sScrollY': '200px',
					'bAutoWidth': true,
					'pagingType': 'numbers',
					'fnInitComplete': function(){
						self.startSlider(self.dataTables[widgetId], widgetId);
					}
				});
				self.dataTables[widgetId] = table;
			}

			var checkInterval = refreshInterval * 60000,
				intervalId = setInterval(function() {
					self.dataTables[widgetId].dataTable({
						destroy: true,
						'ajax': '/admin/dashboard/statusListHosts/'+$widgetElements.showUp.prop('checked')+'/'+$widgetElements.showDown.prop('checked')+'/'+$widgetElements.showUnreachable.prop('checked')+'/'+$widgetElements.showAcknowledged.prop('checked')+'/'+$widgetElements.showDowntime.prop('checked'),
						'iDisplayLength': parseInt($widgetElements.hostsPerPage),
						'bLengthChange': false,
						'sScrollY': '200px',
						'bAutoWidth': true,
						'pagingType': 'numbers',
						'fnInitComplete': function(){
							clearInterval(self.timers[widgetId]);
							delete self.timers[widgetId];
							self.startSlider(self.dataTables[widgetId], widgetId);
						}
					});
				}, checkInterval);
			self.intervalIds[widgetId] = intervalId;

			//restart Tab Rotation if needed
			if(allWidgetParameters['tabRotation'].tabRotationInterval > 0){
				allWidgetParameters['tabRotation'].tabRotationInterval = allWidgetParameters['tabRotation'].tabRotationInterval - 2000;
				var intervalId = setInterval(function() {
					$('.rotateTabs').find('.fa-refresh').addClass('fa-spin');
					setTimeout(function(){
						allWidgetParameters['nextTab'].nextTab();
					},2000);
				}, allWidgetParameters['tabRotation'].tabRotationInterval);
				allWidgetParameters['tabRotation'].tabIntervalId = intervalId;
			}
		});

		var hostStatusListIds = $('.host-status-list-body').each(function(i, elem){
			var currentId = self.getWidgetId($(elem));

			if(allWidgetParameters){
				if(typeof(allWidgetParameters[10]) !== "undefined"){
					var $widgetContainer = $(elem),
						animation = 'fadeInRight';

					if(allWidgetParameters[10][currentId].scroll_direction === 'top'){
						animation = 'fadeInUp';
					}

					$widgetContainer.find('.statusListHosts').attr('animation', 'animated '+animation);

					$widgetContainer.find('.hosts-per-page').val(allWidgetParameters[10][currentId].hosts_per_page);
					$widgetContainer.find('.refresh-interval').val(allWidgetParameters[10][currentId].refresh_interval);
					$widgetContainer.find('.slider-primary').attr('data-slider-value',allWidgetParameters[10][currentId].animation_interval);
					$widgetContainer.find('.pagingIntervalValue').html(allWidgetParameters[10][currentId].animation_interval);
					$widgetContainer.find('.slider-slim').css('display','flex');

					if(allWidgetParameters[10][currentId].show_up){
						$widgetContainer.find('.filter-up').prop('checked',true);
					}
					if(allWidgetParameters[10][currentId].show_down){
						$widgetContainer.find('.filter-down').prop('checked',true);
					}
					if(allWidgetParameters[10][currentId].show_unreachable){
						$widgetContainer.find('.filter-unreachable').prop('checked',true);
					}
					if(allWidgetParameters[10][currentId].show_acknowledged){
						$widgetContainer.find('.filter-acknowledged').prop('checked',true);
					}
					if(allWidgetParameters[10][currentId].show_downtime){
						$widgetContainer.find('.filter-downtime').prop('checked',true);
					}

					var $slider = $widgetContainer.find('input.slider');
					$slider.slider();

					$slider.slider('on', 'slideStop', function(e){
						$widgetContainer.find('.pagingIntervalValue').html(this.value);
						if(this.tagName == 'INPUT'){
							if($(this).attr('intervalId')){
								self.changeInterval($(this).attr('intervalId'), parseInt(this.value * 1000));
							}
						}
					});

					var $widgetElements = extractWidgetElements($widgetContainer);

					//Show default in Inputfield if User never saved Widget
					if($widgetContainer.find('.hosts-per-page').val() === ""){
						$widgetContainer.find('.hosts-per-page').val('3');
					}
					if($widgetContainer.find('.refresh-interval').val() === ""){
						$widgetContainer.find('.refresh-interval').val('3');
					}
					//========================================
					var $object = $widgetContainer.find('.statusListHosts'),
						table = $object.dataTable({
							destroy: true,
							'ajax': '/admin/dashboard/statusListHosts/'+$widgetElements.showUp.prop('checked')+'/'+$widgetElements.showDown.prop('checked')+'/'+$widgetElements.showUnreachable.prop('checked')+'/'+$widgetElements.showAcknowledged.prop('checked')+'/'+$widgetElements.showDowntime.prop('checked'),
							'iDisplayLength': parseInt($widgetElements.hostsPerPage),
							'bLengthChange': false,
							'sScrollY': '200px',
							'bAutoWidth': true,
							'pagingType': 'numbers',
							'fnInitComplete': function(){
								self.startSlider($object, currentId);
							}
						});

					var checkInterval = $widgetElements.refreshInterval * 60000,
						intervalId = setInterval(function() {
							var table = $object.dataTable({
								destroy: true,
								'ajax': '/admin/dashboard/statusListHosts/'+$widgetElements.showUp.prop('checked')+'/'+$widgetElements.showDown.prop('checked')+'/'+$widgetElements.showUnreachable.prop('checked')+'/'+$widgetElements.showAcknowledged.prop('checked')+'/'+$widgetElements.showDowntime.prop('checked'),
								'iDisplayLength': parseInt($widgetElements.hostsPerPage),
								'bLengthChange': false,
								'sScrollY': '200px',
								'bAutoWidth': true,
								'pagingType': 'numbers',
								'fnInitComplete': function(){
									clearInterval(self.timers[currentId]);
									delete self.timers[currentId];
									self.startSlider($object, currentId);
								}
							});
						}, checkInterval);
					self.intervalIds[currentId] = intervalId;
					self.dataTables[currentId] = table;
				}
			}
		});

	},

	getWidgetId: function($elem){
		var currentId = $elem.parents('.grid-stack-item').attr('widget-id');
		if(currentId === null){
			currentId = 0;
		}
		return currentId;
	},

	startSlider: function(dataTable, currentId){
		var self = this;
		self.setAnimationActive(dataTable.attr('animation'), currentId);
		var interval = 0,
			oSettings = dataTable.fnSettings(),
			intervalId = [],
			animationTime =  parseInt($(dataTable).parents('[widget-id="'+currentId+'"]').find('input.slider').attr('value') * 1000);

		intervalId[currentId] = self.startInterval(currentId, function(){
			if(dataTable.fnPagingInfo().iTotalPages === 1){
				self.stopInterval(intervalId[currentId], currentId);
			}
			interval = interval + oSettings._iDisplayLength;
			if(parseInt(interval / oSettings._iDisplayLength) >= dataTable.fnPagingInfo().iTotalPages){
				interval = 0;
			}
			oSettings._iDisplayStart = interval;
			oSettings._sActiveClass = dataTable.attr('animation');
			dataTable._fnDraw();
		}, animationTime);
	},

	stopSlider: function(intervalId){
		clearInterval(intervalId);
	},

	setAnimationActive: function(animationClass, currentId){
		if(animationClass.match(/Up/)){
			var $iconUp = $('[widget-id="'+currentId+'"]').find('.fa-arrow-up');
			$iconUp.addClass('text-primary');
		}else if(animationClass.match(/Right/)){
			var $iconLeft = $('[widget-id="'+currentId+'"]').find('.fa-arrow-left');
			$iconLeft.addClass('text-primary');
		}
	},

	switchDirection: function($clickElement){
		var widgetId = $clickElement.parents('.grid-stack-item').attr('widget-id');

		if($clickElement.hasClass('fa-arrow-left')){
			$clickElement.parent().find('i').removeClass('text-primary');
			$clickElement.addClass('text-primary');
			$clickElement.parents('[widget-id="'+widgetId+'"]').find('.status_datatable').attr({'animation': 'animated fadeInRight'});
		}
		else if($clickElement.hasClass('fa-arrow-up')){
			$clickElement.parent().find('i').removeClass('text-primary');
			$clickElement.addClass('text-primary');
			$clickElement.parents('[widget-id="'+widgetId+'"]').find('.status_datatable').attr({'animation': 'animated fadeInUp'});
		}
	},

	pauseActive: function($clickElement){
		var self = this,
			widgetId = $clickElement.parents('.grid-stack-item').attr('widget-id');
		if($clickElement.hasClass('fa-pause')){
			$clickElement.switchClass('fa-pause', 'fa-play text-primary', 100, 'easeInOutCirc', function(){
				var $slider = $clickElement.parents('[widget-id="'+widgetId+'"]').find('input.slider');
				if($slider.attr('intervalId')){
					self.stopInterval($slider.attr('intervalId'), widgetId);
				}
			});
		}
		else if($clickElement.hasClass('fa-play')){
			$clickElement.switchClass('fa-play text-primary', 'fa-pause', 100, 'easeInOutCirc');
			var $dataTable = $clickElement.parents('[widget-id="'+widgetId+'"]').find('.status_datatable');
			self.startSlider($($dataTable[1]).dataTable(), widgetId);
		}
	},

	startInterval: function(currentId, cb, updateInterval){
		if(!this.timers[currentId]){
			var animationIntervalId = setInterval(cb, updateInterval);
			this.timers[currentId] = animationIntervalId;
			$('[widget-id="'+currentId+'"]').find('input.slider').attr({
				'intervalId': animationIntervalId
			});
		}else{
			this.stopInterval(this.timers[currentId]);
			var animationIntervalId = setInterval(cb, updateInterval);
			this.timers[currentId] = animationIntervalId;
			$('[widget-id="'+currentId+'"]').find('input.slider').attr({
				'intervalId': animationIntervalId
			});
		}
		return this.timers[currentId];
	},

	stopInterval: function(intervalId, widgetId){
		if(!this.timers[widgetId]) return;
		clearInterval(intervalId);
		delete this.timers[widgetId];
	},

	changeInterval: function(intervalId, newUpdateInterval){
		if(!this.timers[intervalId]) return;
		clearInterval(this.timers[intervalId][0]);
		this.timers[intervalId] = [setInterval(this.timers[intervalId][1], newUpdateInterval), this.timers[intervalId][1]];
	},

});
