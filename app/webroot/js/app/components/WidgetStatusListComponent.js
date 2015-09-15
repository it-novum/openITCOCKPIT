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
	
	lists: {},
	UtilsComponent: null,
	updateInterval: 500000, //Ajax update interval of the lists in seconds (5 mins by default)
	
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
	},
	
	
	initLists: function(){
		var $slider = $('input.slider');
		$slider.slider();
		$('.slider-horizontal').each(function(int, object){
			$(object).css('margin-bottom', '0px');
		});
		
		var self = this;
		$('.statusListHosts').each(function(key, object){
			var $list = $(object);
			var $widgetContainer = $list.parent().parent().parent().parent();
			var height = self.calculateHeight($widgetContainer);
			
			var recordsPerPage = self.calculateRowsByHeight(height);
			
			$list.dataTable({
				'iDisplayLength': recordsPerPage,
				'bLengthChange': false,
				'sScrollY': height+'px',
				'bAutoWidth': true,
				'pagingType': 'numbers',
				'fnInitComplete': function(){
					self.lists[$list.data('widget-id')] = {
						datatable: $list,
						timer: null,
						lastUpdate: new Date().getTime()
					};
					self.startRotate($list);
				}
			});
			
		});
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
					if((this.lists[widgetId].lastUpdate + this.updateInterval) < new Date().getTime()){
						console.log('refresh data');
					}
				}
				$list.fnPageChange(nextPage);
			}
			this.UtilsComponent.flapping();
		}.bind(this), 5000);
	},
	
	stopInterval: function(widgetId){
		if(this.lists[widgetId].timer !== null){
			clearInterval(this.lists[widgetId].timer);
		}
	}
	
});
