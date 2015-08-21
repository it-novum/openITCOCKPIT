"use strict";
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

App.Components.WidgetNoticeComponent = Frontend.Component.extend({

	setup: function(options, allWidgetParameters){
		var self = this;
		var defaults = {
			ids:[]
		};
		self.intervalIds = {};
		self.options = $.extend({}, defaults, options);

		$(document).on('submit', '.notice_form', function(event){
			event.preventDefault();

			var $form = $(this),
				widgetId = $form.parents('.grid-stack-item').attr('widget-id'),
				notice = self.getData($form),
				$noticeContainer = $form.parents('.notice-body').find('.widget-notice');

			self.options.loadWidgetData(widgetId, function(data){
				if(data.data.WidgetNotice.widget_id){
					var dataToSave = {
						'Widget': {
							'id' : parseInt(widgetId),
						},
						'WidgetNotice': {
							'id' : data.data.WidgetNotice.id,
							'note': notice,
						}
					};
				}
				else{
					var dataToSave = {
						'Widget': {
							'id' : parseInt(widgetId),
						},
						'WidgetNotice': {
							'widget_id': parseInt(widgetId),
							'note': notice,
						}
					};
				}
				self.options.saveWidgetData(dataToSave);
			});

			$form.css('display','none');
			$form.next().html('<i class="fa fa-cog "></i>');
			$form.next().css('display','block');

			$.ajax({
				url: "/admin/dashboard/parseMarkdown/.json",
				type: "POST",
				dataType: "json",
				data: {notice: notice},
				error: function(){},
				success: function(){},
				complete: function(response){
					$noticeContainer.html(response.responseText);
					$noticeContainer.css('display','block');
				}
			});

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

		$(document).on('click', '.widget-notice-title', function(event){
			var $div = $(this),
				$form = $div.prev(),
				widgetId = $div.parents('.grid-stack-item').attr('widget-id'),
				notice = self.getData($form);

			$div.css('display','none');
			$div.next().css('display','none');
			$('[widget-id="'+widgetId+'"]').find('.notice_textarea').val(notice);
			$div.prev().css('display','block');

		});

		var allNoticeIds = $('.notice-body').each(function(i, elem){
			var $widgetContainer = $(elem).parents('.grid-stack-item'),
				currentId = $widgetContainer.attr('widget-id'),
				$form = $widgetContainer.find('form');

			if(allWidgetParameters){
				if(typeof(allWidgetParameters[13]) !== "undefined"){
					$form.css('display','none');

					$form.next().html(' <i class="fa fa-cog "></i>');
					$form.next().css('display','block');

					var $noticeContainer = $form.parents('.notice-body').find('.widget-notice');

					$form.find('.notice_textarea').val(allWidgetParameters[13][currentId].note);
					$noticeContainer.html(allWidgetParameters[13][currentId].noteMarkdown);
					$noticeContainer.css('display','block');
				}
			}

		});
	},

	getData: function(f){
		var data = f.find('.notice_textarea').val();
		data = data.replace(/(<([^>]+)>)/ig,"");
		return data;
	},

})
