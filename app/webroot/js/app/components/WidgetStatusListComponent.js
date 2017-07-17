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

App.Components.WidgetStatusListComponent = Frontend.Component.extend({
	
	Ajaxloader: null,
	lists: {},
	UtilsComponent: null,
	updateIntervalValue: 300000, //Ajax update interval of the lists in seconds (5 mins by default)
	//updateIntervalValue: 3000, // Development value (every 3 seconds)
	
	setAjaxloader: function(Ajaxloader){
		this.Ajaxloader = Ajaxloader;
	},
	
	setup: function(UtilsComponent){
		this.UtilsComponent = UtilsComponent;
		var self = this;
		$(document).on('click', '.stopRotation', function(){
			var $object = $(this);
			self.stopInterval($object.data('widget-id'));
			$object.hide();
			$object.parent().children('.startRotation').show();
		});
		
		$(document).on('click', '.startRotation', function(){
			var $object = $(this);
			self.startInterval($object.data('widget-id'));
			$object.hide();
			$object.parent().children('.stopRotation').show();
		});
		
		$(document).on('click', '.saveListSettings', function(){
			var $object = $(this);
			var widgetId = $object.data('widget-id');
		});
		
		$(document).on('click', '.listAnimateUp', function(){
			var $object = $(this);
			var widgetId = $object.data('widget-id');
			self.setAnimation(widgetId, 'fadeInUp');
			self.lists[widgetId].data.animation = 'fadeInUp';
			$object.addClass('btn-primary');
			$object.parent().children('.listAnimateRight').removeClass('btn-primary');
		});
		
		$(document).on('click', '.listAnimateRight', function(){
			var $object = $(this);
			var widgetId = $object.data('widget-id');
			self.setAnimation(widgetId, 'fadeInRight');
			self.lists[widgetId].data.animation = 'fadeInRight';
			$object.addClass('btn-primary');
			$object.parent().children('.listAnimateUp').removeClass('btn-primary');
		});
		
		$(document).on('click', '.saveListSettings', function(){
			var widgetId = $(this).data('widget-id');
			var $inputs = $('.checks-widget-'+widgetId);
			
			var widgetTypeId = parseInt($(this).parents('.grid-stack-item').data('widget-type-id'), 10);
			
			$inputs.each(function(key, input){
				var $input = $(input);
				self.lists[widgetId].data[$input.data('key')] = 0;
				if($input.prop('checked')){
					self.lists[widgetId].data[$input.data('key')] = 1;
				}
			}).bind(self);
            self.lists[widgetId].data['show_filter_search'] = self.lists[widgetId].datatable.fnSettings().oPreviousSearch.sSearch;
			self.saveSettings(widgetId, widgetTypeId);
		});
		
		//$(document).on('slideStop', '.slider-horizontal', function(e){
		//	var widgetId = $(this).parent().data('widget-id');
		//	self.lists[widgetId].data.animation_interval = parseInt(e.value, 10);
		//	self.updateInterval(widgetId);
		//});
		
		var gridstack = $('.grid-stack');
		gridstack.on('resizestop', function(event, ui){
			var $element = $(ui.element);
			var widgetId = parseInt($element.data('widget-id'), 10);
			var widgetTypeId = parseInt($element.data('widget-type-id'), 10);
			if(widgetTypeId == 9 || widgetTypeId == 10){
				//this.initList($element.children('.statusListTable'));
				self.refreshWidget(widgetId);
			}
		});
	},
	
	
	initLists: function(){
		var self = this;
		$('.statusListTable').each(function(key, object){
			this.initList(object, $(this).attr('data-widget-id'));
		}.bind(this));
	},
	
	initList: function(object, widgetId){
		var self = this;
		var $list = $(object);
		
		var $slider = $list.parent().parent().find('input.slider-slim');
		$slider.slider();
		$slider.slider('on', 'slideStop', function(e){
			var widgetId = $(this).parent().data('widget-id');
			self.lists[widgetId].data.animation_interval = parseInt(e.value, 10);
			self.updateInterval(widgetId);
		});
		
		$('.slider-horizontal').each(function(int, object){
			$(object).css('margin-bottom', '0px');
		});
		
		var widgetId = $list.data('widget-id');
		var sliderValue = parseInt($list.parent().parent().find('input.slider-slim').data('slider-value'), 10);
		var $widgetContainer = $list.parent().parent().parent().parent();
		var height = this.calculateHeight($widgetContainer);
		
		var recordsPerPage = this.calculateRowsByHeight(height);
		
		if(typeof this.lists[widgetId] != 'undefined'){
			if(typeof this.lists[widgetId].timer != 'undefined'){
				clearInterval(this.lists[widgetId].timer);
			}
		}

		$list.dataTable({
			'iDisplayLength': recordsPerPage,
			'bLengthChange': false,
			'sScrollY': height+'px',
			'bAutoWidth': true,
			'pagingType': 'numbers',
			'fnInitComplete': function(){
				this.lists[widgetId] = {
					datatable: $list,
					timer: null,
					lastUpdate: new Date().getTime(),
					data: {
						show_up: null,
						show_down: null,
						show_unreachable: null,
						show_acknowledged: null,
						show_downtime: null,
						animation: null,
						animation_interval: sliderValue,
					}
				};
				this.startRotate($list);
			}.bind(this)
		});

		self.lists[widgetId].datatable.fnFilter($('#filter-search-'+widgetId).val());
	},

	calculateHeight: function($widgetContainer){
		var height = parseInt($widgetContainer.height(), 10);
		
		var settingsBar = 64;
		var widgetHeader = 38;
		var datatablesSearchHeight = 46;
		var tableHeader = 34;
		var paginatorHeight = 43;
		var tolerance = 4;
		
		height = parseInt((height - settingsBar - widgetHeader - datatablesSearchHeight - tableHeader - paginatorHeight - tolerance), 10);
		return height
	},
	
	calculateRowsByHeight: function(height){
		height = parseInt(height, 10);
		return maxRows = Math.floor(height / 33.5);
	},
	
	startRotate: function($list){
		var oSettings = $list.fnSettings();
		oSettings._sActiveClass = $list.attr('animation');

		if($list.fnPagingInfo().iTotalPages > 1){
			this.startInterval($list.data('widget-id'));
		}
	},
	
	startInterval: function(widgetId){
		var $list = this.lists[widgetId].datatable;
		this.lists[widgetId].timer = setInterval(function(){
			var totalPages = $list.fnPagingInfo().iTotalPages -1;
			if(totalPages > 0){
				//Do not rotate, if the user searched
				var currentPage = $list.fnPagingInfo().iPage;
				var nextPage = currentPage + 1;
				if(nextPage > totalPages){
					nextPage = 0;
					
					//Check if we need to refresh the table content
					if((this.lists[widgetId].lastUpdate + this.updateIntervalValue) < new Date().getTime()){
						this.refreshWidget(widgetId);
					}
				}
				$list.fnPageChange(nextPage);
			}
			this.UtilsComponent.flapping();
		}.bind(this), (this.lists[widgetId].data.animation_interval * 1000));
	},
	
	stopInterval: function(widgetId){
		if(this.lists[widgetId].timer !== null){
			clearInterval(this.lists[widgetId].timer);
		}
	},
	
	setAnimation: function(widgetId, animation){
		var $list = this.lists[widgetId].datatable;
		var oSettings = $list.fnSettings();
		oSettings._sActiveClass = 'animated ' + animation;
	},
	
	saveSettings: function(widgetId, widgetTypeId){
		this.Ajaxloader.show();
		$.ajax({
			url: "/dashboards/saveStatuslistSettings",
			type: "POST",
			data: {settings: this.lists[widgetId].data, widgetId: widgetId, widgetTypeId: widgetTypeId},
			error: function(){},
			success: function(response){
				this.Ajaxloader.hide();
			}.bind(this),
			complete: function(response) {
			}
		});
	},
	
	updateInterval: function(widgetId){
		this.stopInterval(widgetId);
		this.startRotate(this.lists[widgetId].datatable);
	},
	
	refreshWidget: function(widgetId){
		if(this.lists[widgetId].timer !== null){
			clearInterval(this.lists[widgetId].timer);
		}
		this.Ajaxloader.show();
		
		var $tableContainer = this.lists[widgetId].datatable.parents('.tableContainer');
		$tableContainer.html('<div class="text-center padding-top-50"><h1><i class="fa fa-cog fa-lg fa-spin"></i></h1></div>');
		$.ajax({
			url: "/dashboards/refresh",
			type: "POST",
			data: {widgetId: widgetId},
			error: function(){},
			success: function(response){
				if(response !== ''){
					$tableContainer.html(response);
					this.lists[widgetId].datatable = null;
					this.initList($tableContainer.children('.statusListTable'));
				}
				this.Ajaxloader.hide();
			}.bind(this),
			complete: function(response) {
			}
		});
	}
	
});
