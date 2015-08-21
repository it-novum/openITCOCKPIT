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

App.Components.WidgetBrowserComponent = Frontend.Component.extend({

	setup: function(options, allWidgetParameters){
		var self = this;
		var defaults = {
			ids:[]
		};
		self.intervalIds = {};
		self.options = $.extend({}, defaults, options);

		$(document).on('submit', '.widgetBrowserForm', function(event){
			event.preventDefault();

			var $form = $(this),
				widgetId = $form.parents('.grid-stack-item').attr('widget-id'),
				url = self.getData($form),
				$iframeContainer = $form.parents('.browser-body').find('.widget-browser'),
				regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

			if (!regexp.test(url) && url !== 'https://') {
				url = ('https://' + url);
			}
			url = encodeURI(url);

			self.options.loadWidgetData(widgetId, function(data){
				if(data.data.WidgetBrowser.widget_id){
					var dataToSave = {
						'Widget': {
							'id' : parseInt(widgetId),
						},
						'WidgetBrowser': {
							'id' : data.data.WidgetBrowser.id,
							'url': url,
						}
					};
				}
				else{
					var dataToSave = {
						'Widget': {
							'id' : parseInt(widgetId),
						},
						'WidgetBrowser': {
							'widget_id': parseInt(widgetId),
							'url': url,
						}
					};
				}
				self.options.saveWidgetData(dataToSave);
			});

			$form.css('display','none');
			$form.next().html('<i class="fa fa-cog "></i> '+url);
			$form.next().css('display','block');

			$iframeContainer.css('display','block');
			$iframeContainer.empty();

			$('<iframe>', {
				src: url,
				id: 'widgetIframe_'+widgetId,
				frameborder: 0,
				scrolling: 'yes',
				width: '100%',
				height:'100%',
			}).appendTo($('[widget-id="'+widgetId+'"]').find('.widget-browser'));
			$iframeContainer.css('display','block');

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

		$(document).on('click', '.widget-browser-title', function(event){
			var $div = $(this),
				widgetId = $div.parents('.grid-stack-item').attr('widget-id');

			$div.css('display','none');
			$div.parents('.browser-body').find('.widget-browser').css('display','none');
			$div.parents('.grid-stack-item').find('#browserUrl').val($div.text().trim());
			$div.prev().css('display','block');

		});

		var allBrowserIds = $('.browser-body').each(function(i, elem){
			var $widgetContainer = $(elem).parents('.grid-stack-item'),
				currentId = $widgetContainer.attr('widget-id'),
				$form = $widgetContainer.find('form');

			if(allWidgetParameters){
				if(typeof(allWidgetParameters[12]) !== "undefined"){
					$form.css('display','none');
					$form.next().html('<i class="fa fa-cog "></i> '+allWidgetParameters[12][currentId].url);
					$form.next().css('display','block');

					var $iframeContainer = $form.parents('.browser-body').find('.widget-browser');
					$iframeContainer.empty();
					$('<iframe>', {
						src: allWidgetParameters[12][currentId].url,
						id: 'widgetIframe_'+currentId,
						frameborder: 0,
						scrolling: 'yes',
						width: '100%',
						height:'100%',
					}).appendTo($('[widget-id="'+currentId+'"]').find('.widget-browser'));
					$iframeContainer.css('display','block');
				}
			}
		});
	},

	getData: function(f){
		var data = f.find('#browserUrl').val();
		return data;
	},

})
