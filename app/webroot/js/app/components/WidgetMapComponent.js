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

	setup: function(options, allWidgetParameters){
		var self = this;
		var defaults = {
			ids:[]
		};
		self.intervalIds = {};
		self.options = $.extend({}, defaults, options);

		$(document).on('submit', '.widgetMapsForm', function(event){
			event.preventDefault();

			var $form = $(this),
				widgetId = $form.parents('.grid-stack-item').attr('widget-id'),
				mapId = self.getData($form),
				$mapContainer = $form.parents('.maps-body').find('.widget-map');

			data = {
				'Widget': {
					'id' : widgetId,
					'map_id' : mapId
				}
			};
			self.options.saveWidgetData(data);

			$form.css('display','none');
			$form.next().html('page will reload to show selected map');
			$form.next().css('display','block');

			$mapContainer.css('display','block');


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
			location.reload();
		});

		$(document).ready(function(){
			$('.maps-body').find('.elementHover').mouseenter(function(){

				var elementType = $(this).data('type'),
					elementUuid = $(this).data('uuid'),
					titleAndIconColor = 'rgb(90,90,90)';

				var $el = $('#divSmallBoxes').children();
					$el.hide(0,function(){
						$(this).remove();
					});

				$.ajax({
					url: "/map_module/mapeditors/popover"+elementType+"Status/" + encodeURIComponent(elementUuid),
					type: "POST",
					dataType: "html",
					error: function(){},
					success: function(){},
					complete: function(response){
						$.smallBox({
							title : elementType,
							content : response.responseText,
							color : 'rgba(249, 249, 249, 1)',
							icon : "fa fa-desktop"
						});
						$('.textoFoto').first('<span>').css({'color':titleAndIconColor});
						$('.textoFoto').css('color', '#000000');
						$('.foto').css({'color':titleAndIconColor});
					}.bind(self)
				});
			});
			$('.maps-body').find('.elementHover').mouseleave(function(){
				var $el = $('#divSmallBoxes').children();
				$el.hide(300,function(){
					$(this).remove();
				});
			});
		});

		var mapWidgetIds = $('.maps-body').each(function(i, elem){
			var currentId = self.getWidgetId($(elem)),
				$widgetContainer = $(elem),
				$mapViewContainer = $widgetContainer.find('.widget-map'),
				$form = $(elem).find('form');

			if(allWidgetParameters && !allWidgetParameters[11][currentId].missingModule){
				if(typeof(allWidgetParameters[11][currentId]) !== "undefined" && allWidgetParameters[11][currentId]['map'].length !== 0){
					$form.css('display','none');

					if(allWidgetParameters[11][currentId].map.Map.background !== null && allWidgetParameters[11][currentId].map.Map.background !== ''){
						var filepath = allWidgetParameters[11][currentId].backgroundThumbs.backgrounds.webPath+'/'+allWidgetParameters[11][currentId].map.Map.background;

						$mapViewContainer.css({
							'background-image': 'url("' + filepath + '")',
							'background-repeat': 'no-repeat'
						});

						$.fn.getBgImage = function(callback) {
							var height = 0;
							var path = $(this).css('background-image').replace('url', '').replace('(', '').replace(')', '').replace('"', '').replace('"', '');
							var tempImg = $('<img />');
							tempImg.hide();
							tempImg.bind('load', callback);
							$('body').append(tempImg);
							tempImg.attr('src', path);
							$('#tempImg').remove();
						};

						var bgDimension = {};

						$mapViewContainer.getBgImage(function() {
							bgDimension.height = $(this).height();
							bgDimension.width = $(this).width();
							$mapViewContainer.css({
								height:bgDimension.height,
								width: bgDimension.width
							});
						});
					}

					if(allWidgetParameters[11][currentId].map_gadgets){
						var mapGadgets = allWidgetParameters[11][currentId].map_gadgets;
						for (var i = 0; i < mapGadgets.length; i++) {

							var currentElementData = {},
								gadgetType = self.capitaliseFirstLetter(mapGadgets[i]['Mapgadget']['type']);

							for(var j = 0; j < allWidgetParameters[11][currentId]['servicestatus'].length; j++){
								if(allWidgetParameters[11][currentId]['servicestatus'][j]['Objects']['name2'] === mapGadgets[i][gadgetType]['uuid']){
									var gadgetServiceState = allWidgetParameters[11][currentId]['servicestatus'][j]['Servicestatus']['current_state'],
										gadgetServiceFlapping = allWidgetParameters[11][currentId]['servicestatus'][j]['Servicestatus']['is_flapping'];
								}
							}

							currentElementData = {
								currentUuid: mapGadgets[i][gadgetType]['uuid'],
								currentType: mapGadgets[i]['Mapgadget']['type'],
								currentLink: mapGadgets[i]['Mapgadget']['type']+'s/browser/'+mapGadgets[i][gadgetType]['id'],
								currentPerfdata: mapGadgets[i]['Mapgadget']['perfdata'],
								currentState: gadgetServiceState,
								currentFlapping: gadgetServiceFlapping,
								currentRRDGraphLink: mapGadgets[i]['Mapgadget']['rrdGraphLink'],
							}

							$mapViewContainer.append('<div id="svgContainer_'+currentElementData['currentUuid']+'"></div>');
							$('<div id="svgContainer_'+mapGadgets[i]['Mapgadget']['id']+'"></div>')
							.appendTo($mapViewContainer);

							var state = currentElementData['currentState'],
								flapping = currentElementData['currentFlapping'],
								containerData = {'uuid':currentElementData['currentUuid'],type:self.capitaliseFirstLetter(currentElementData['currentType'])};

							self.options.Gadget['draw'+mapGadgets[i]['Mapgadget']['gadget']]('svgContainer_'+mapGadgets[i]['Mapgadget']['id'],{
								id:currentElementData['currentUuid'],
								x:mapGadgets[i]['Mapgadget']['x'],
								y:mapGadgets[i]['Mapgadget']['y'],
								containerData:containerData,
								perfdata:currentElementData['currentPerfdata'],
								state:state.toString(),
								flapping:flapping,
								RRDGraphLink:currentElementData['currentRRDGraphLink']
							});
						};
					}

					start = [];
					end = [];
					wasClicked = false;

					$mapViewContainer.svg();
					svg = $mapViewContainer.svg('get');

					if(allWidgetParameters[11][currentId].map_lines){
						var mapLines = allWidgetParameters[11][currentId].map_lines;

						for (var i = 0; i < mapLines.length; i++) {
							start['x'] = parseInt(mapLines[i]['Mapline']['startX']);
							start['y'] = parseInt(mapLines[i]['Mapline']['startY']);
							end['x'] = parseInt(mapLines[i]['Mapline']['endX']);
							end['y'] = parseInt(mapLines[i]['Mapline']['endY']);

							var currentElementData = {},
								lineType = self.capitaliseFirstLetter(mapLines[i]['Mapline']['type']),
								tempUuid = self.options.Uuid.v4();

							for(var j = 0; j < allWidgetParameters[11][currentId]['servicestatus'].length; j++){
								if(allWidgetParameters[11][currentId]['servicestatus'][j]['Objects']['name2'] === mapLines[i][lineType]['uuid']){
									var lineServiceState = allWidgetParameters[11][currentId]['servicestatus'][j]['Servicestatus']['current_state'];
								}
							}

							for(var j = 0; j < allWidgetParameters[11][currentId]['hoststatus'].length; j++){
								if(allWidgetParameters[11][currentId]['hoststatus'][j]['Objects']['name1'] === mapLines[i][lineType]['uuid']){
									var lineHostState = allWidgetParameters[11][currentId]['hoststatus'][j]['Hoststatus']['current_state'];
								}

							}

							var color = '';
							if(lineServiceState){
								switch(parseInt(lineServiceState)) {
									case 0:
										color = '#5CB85C'
										break;
									case 1:
										color = '#f0ad4e'
										break;
									case 2:
										color = '#d9534f'
										break;
									case 3:
										color = '#4C4F53'
										break;
								}
							}
							if(lineHostState){
								switch(parseInt(lineHostState)) {
									case 0:
										color = '#5CB85C'
										break;
									case 1:
										color = '#d9534f'
										break;
									case 2:
										color = '#4C4F53'
										break;
								}
							}

							var currentElementData = {
								currentUuid: mapLines[i][lineType]['uuid'],
								currentType: mapLines[i]['Mapline']['type'],
								currentColor: color,
								currentLink: '/'+mapLines[i]['Mapline']['type']+'s/browser/'+mapLines[i][lineType]['id'],
							};

							$mapViewContainer.append('<div id="svgLineContainer_'+tempUuid+'"></div>');
							$('<div id="svgLineContainer_'+tempUuid+'"></div>')
							.appendTo($mapViewContainer);

							var tempObj = {
								id:tempUuid,
								svgContainer:'svgLineContainer_'+tempUuid,
								start:start,
								end:end,
								lineId:mapLines[i]['id'],
								link:true,
								linkData:'',
								objData: currentElementData,
							};
							self.options.Line.drawSVGLine(tempObj);
						};
					}

					if(allWidgetParameters[11][currentId].map_texts){
						var mapTexts = allWidgetParameters[11][currentId].map_texts;

						for (var i = 0; i < mapTexts.length; i++) {

							var tempUuid = self.options.Uuid.v4(),
								top = 'top:'+mapTexts[i]['Maptext']['y']+'px;',
								left = 'left:'+mapTexts[i]['Maptext']['x']+'px;',
								spanId = 'spanText_'+tempUuid,
								myFontSize = 'font-size:'+mapTexts[i]['Maptext']['font_size']+'px;';

							$('<div id="'+tempUuid+'" class="textContainer"style="position:absolute;'+top+''+left+'"><span id="'+spanId+'" class="textElement" style="'+myFontSize+'">'+mapTexts[i]['Maptext']['text']+'</span></div>').appendTo($mapViewContainer);
						}
					}

					if(allWidgetParameters[11][currentId].map_items){
						var mapItems = allWidgetParameters[11][currentId].map_items;

						for (var i = 0; i < mapItems.length; i++) {

							var tempUuid = self.options.Uuid.v4(),
								top = 'top:'+mapItems[i]['Mapitem']['y']+'px;',
								left = 'left:'+mapItems[i]['Mapitem']['x']+'px;',
								type = self.capitaliseFirstLetter(mapItems[i]['Mapitem']['type']),
								imgPath = '/map_module/img/items/'+mapItems[i]['Mapitem']['iconset'],
								stateObj = self.getDataForCurrentStateAndType(0,type),
								dataUuid = mapItems[i][type]['uuid'],
								mapItemLink = '/'+mapItems[i]['Mapitem']['type']+'s/browser/'+mapItems[i][type]['id'];

							if(type === 'Map'){
								mapItemLink = '/map_module/mapeditors/view/'+mapItems[i]['Mapitem']['object_id'];
								dataUuid = mapItems[i]['Map']['id'];
								stateObj.image = 'up.png';
							}

							$('<div id="'+tempUuid+'" class="elementHover"style="position:absolute;'+top+''+left+'" data-type="'+type+'" data-uuid="'+dataUuid+'"><a href="'+mapItemLink+'"><img src="'+imgPath+'/'+stateObj.image+'"></a></div>').appendTo($mapViewContainer);
						}
					}
				}else{
					$form.css('display','none');
					$('<div>No Map found or linked Map has been deleted!</div>').appendTo($mapViewContainer);
				}
			}
		});
	},

	getData: function(f){
		var data = f.find('select').val();
		return data;
	},

	/**
	 * @param {jQuery} $elem
	 * @return {number}
	 */
	getWidgetId: function($elem){
		var currentId = $elem.parents('.grid-stack-item').attr('widget-id');
		if(currentId === null){
			currentId = 0;
		}
		return currentId;
	},

	getDataForCurrentStateAndType: function(state, type){
		var color = '',
			image = '',
			data = {};

		if(type === 'Service' || type === 'Servicegroup'){
			switch(parseInt(state)) {
				case 0:
					color = '#5CB85C',
					image = 'up.png'
					break;
				case 1:
					color = '#f0ad4e',
					image = 'down.png'
					break;
				case 2:
					color = '#d9534f',
					image = 'critical.png'
					break;
				case 3:
					color = '#4C4F53',
					image = 'unreachable.png'
					break;
			}
		}
		if(type === 'Host' || type === 'Hostgroup'){
			switch(parseInt(state)) {
				case 0:
					color = '#5CB85C',
					image = 'up.png'
					break;
				case 1:
					color = '#d9534f',
					image = 'down.png'
					break;
				case 2:
					color = '#4C4F53',
					image = 'unreachable.png'
					break;
			}
		}
		data = {
			color: color,
			image: image,
		};
		return data;
	},

	popoverTitle:function(){
		var elementUuid = $(this).data('uuid');
		return '<h1>'+elementUuid+'</h1>';
	},

	capitaliseFirstLetter:function(string){
		return string.charAt(0).toUpperCase() + string.slice(1);
	},

})
