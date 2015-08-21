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

App.Components.WidgetTrafficLightComponent = Frontend.Component.extend({

	setup: function(options, allWidgetParameters){
		var self = this;
		var defaults = {
			ids:[]
		};
		self.intervalIds = {};
		self.options = $.extend({}, defaults, options);

		$(document).on('submit', '.trafficlight_form', function(event){
			event.preventDefault();

			var $form = $(this),
				widget_id = $form.parents('.grid-stack-item').attr('widget-id'),
				service_id = self.getData($form),
				$traffLightSvg = $form.parents('.trafficLight-body').find('.trafficlight');


			data = {
				'Widget': {
					'id' : widget_id,
					'service_id' : service_id
				}
			};

			self.options.saveWidgetData(data);

			$form.css('display','none');
			$form.next().html($form.find('select option:selected').html()+' <i class="fa fa-cog "></i>');
			$form.next().css('display','block');

			$.ajax({
				url: "/admin/dashboard/getServiceCurrentState/" + service_id+".json",
				type: "POST",
				dataType: "json",
				error: function(){},
				success: function(){},
				complete: function(response){
					var checkInterval = response.responseJSON.Service.check_interval;
					//if there is no checkInterval defined get standard checkInterval for service
					if(!checkInterval){
						checkInterval = response.responseJSON.Servicetemplate.check_interval;
					}
					checkInterval = checkInterval*1000;
					self.drawTrafficLight($traffLightSvg,{
						state : response.responseJSON.Servicestatus.current_state,
						blinkLight : response.responseJSON.Servicestatus.is_flapping,
						id : widget_id
					});
					var intervalId = setInterval(function() {
						$.ajax({
							url: "/admin/dashboard/getServiceCurrentState/" + service_id+".json",
							type: "POST",
							dataType: "json",
							error: function(){},
							success: function(){},
							complete: function(response){
								var checkInterval = response.responseJSON.Service.check_interval;
								//if there is no checkInterval defined get standard checkInterval for service
								if(!checkInterval){
									checkInterval = response.responseJSON.Servicetemplate.check_interval;
								}
								checkInterval = checkInterval*1000;
								self.drawTrafficLight($traffLightSvg,{
									state : response.responseJSON.Servicestatus.current_state,
									blinkLight : response.responseJSON.Servicestatus.is_flapping,
									id : widget_id
								});
							}
						});
					}, checkInterval);
				}
			});



			self.intervalIds[widget_id] = intervalId;
			$traffLightSvg.css('display','block');

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

		$(document).on('change', '.selectTrafficlight', function(event){
			var $widgetContainer = $(this).parents('.grid-stack-item');
			$widgetContainer.find('.trafficlightWarning').css('display','none');

		});

		$(document).on('click', '.service-title', function(event){
			var $div = $(this),
				widgetId = $div.parents('.grid-stack-item').attr('widget-id');

			$div.css('display','none');
			$div.parents('.trafficLight-body').find('.trafficlight').css('display','none');
			$div.prev().css('display','flex');

			if(allWidgetParameters['tabRotation'].tabIntervalId){
				clearInterval(allWidgetParameters['tabRotation'].tabIntervalId);
				allWidgetParameters['tabRotation'].tabIntervalId = null;
			}

			if(self.intervalIds[widgetId]){
				clearInterval(self.intervalIds[widgetId]);
				self.intervalIds[widgetId] = 0;
			}
		});

		var trafficLightIds = $('.trafficlight').each(function(i, elem){

			var $widgetContainer = $(elem).parents('.grid-stack-item'),
				current_id = $widgetContainer.attr('widget-id'),
				$form = $widgetContainer.find('form');

			if(allWidgetParameters){
				if(typeof(allWidgetParameters[6]) !== "undefined"){
					if(typeof(allWidgetParameters[6][current_id].serviceGone) === 'undefined'){
						$form.css('display','none');

						$form.next().html(allWidgetParameters[6][current_id].host_name+' | '+allWidgetParameters[6][current_id].service_name+' <i class="fa fa-cog "></i>');
						$form.next().css('display','block');
						self.drawTrafficLight($(elem),{
							state : allWidgetParameters[6][current_id].current_state,
							blinkLight : allWidgetParameters[6][current_id].is_flapping,
							id : current_id
						});

						var checkInterval = allWidgetParameters[6][current_id].check_interval*1000;

						var intervalId = setInterval(function() {
							$.ajax({
								url: "/admin/dashboard/getServiceCurrentState/" + encodeURIComponent(allWidgetParameters[6][current_id].service_id)+".json",
								type: "POST",
								dataType: "json",
								error: function(){},
								success: function(){},
								complete: function(response){
									self.drawTrafficLight($(elem),{
										state : response.responseJSON.Servicestatus.current_state,
										blinkLight : response.responseJSON.Servicestatus.is_flapping,
										id : current_id
									});
								}
							});
						}, checkInterval);
						self.intervalIds[current_id] = intervalId;
					}else{
						$widgetContainer.find('.selectTrafficlight').append($('<div/>',{
							class : 'trafficlightWarning',
							style : 'color:#FF0000; padding-top: 10px;',
							text : allWidgetParameters[6][current_id].serviceGone
						}));
					}
				}

			}

		});
	},

	getData: function(f){
		var data = f.find('select').val();
		return data;
	},

	drawTrafficLight:function($svgContainer, opt){
		var opt = opt || {};
		var id = (opt.id == false || opt.id != null?opt.id:'');
		var x = opt.x || 0;
		var y = opt.y || 0;
		var sizeX = opt.sizeX || 60;
		var sizeY = opt.sizeY || 150;
		var state = opt || '';
		var containSVG = (opt.contain == null?true:opt.contain);
		var containerData = opt.containerData || false;
		var lightRadius = 17;
		var showGreen = false;
		var showYellow = false;
		var showRed = false;
		var blinkLight = false;

		if(opt.blinkLight == 1){
			blinkLight = true;
		}

		state = parseInt(state.state);

		if(state != undefined){
			switch(state){
				case 0:
				showGreen = true;
				break;
				case 1:
				showYellow = true;
				break;
				case 2:
				showRed = true;
				break;
				case 3:
				showGreen = false;
				showYellow = false;
				showRed = false;
				break;
			}
		}
		if(state == 3 && blinkLight){
			showGreen = true;
			showYellow = true;
			showRed = true;
		}

		//SVG Container
		$svgContainer.css({'top':y+'px', 'left':x+'px','position':'absolute', 'height':sizeY, 'width': sizeX+40}).svg();
		var svg = $svgContainer.svg('get');

		//main group
		var trafficLight = svg.group('trafficLight_'+id);

		//Traffic Light background group
		var tLBackground = svg.group(trafficLight,'trafficLightBackground_'+id);

		//style definitions for the Traffic light
		var defs = svg.defs();
		//background gradient
		svg.linearGradient(defs, 'tlBg_'+id, [[0.02, '#111'], [0.02, '#111'], [0.03, '#222'], [0.3, '#111']],0,0,0,1);

		svg.linearGradient(defs, 'protectorGradient_'+id, [[0, '#444'], [0.03, '#333'],[0.07, '#222'],[0.12, '#111']], 0,0,0,1);

		//red light gradient
		svg.radialGradient(defs, 'redLight_'+id, [['0%', 'brown'], ['25%', 'transparent']], 1, 1, 4, 0, 0,{
			gradientUnits:'userSpaceOnUse'
		});
		//yellow light gradient
		svg.radialGradient(defs, 'yellowLight_'+id, [['0%', 'orange'], ['25%', 'transparent']], 1, 1, 4, 0, 0,{
			gradientUnits:'userSpaceOnUse'
		});
		//green light gradient
		svg.radialGradient(defs, 'greenLight_'+id, [['0%', 'lime'], ['25%', 'transparent']], 1, 1, 4, 0, 0,{
			gradientUnits:'userSpaceOnUse'
		});

		//Traffic light "protector"
		var protector1 = svg.createPath();
		svg.path(tLBackground, protector1.move(5,15).line(90,0,true).line(-15,35,true).line(-60,0,true).close(),{
			fill:'url(#protectorGradient_'+id+')'
		});

		//Traffic light "protector"
		var protector2 = svg.createPath();
		svg.path(tLBackground, protector2.move(5,55).line(90,0,true).line(-15,35,true).line(-60,0,true).close(),{
			fill:'url(#protectorGradient_'+id+')'
		});

		//Traffic light "protector"
		var protector3 = svg.createPath();
		svg.path(tLBackground, protector3.move(5,95).line(90,0,true).line(-15,35,true).line(-60,0,true).close(),{
			fill:'url(#protectorGradient_'+id+')'
		});

		//the main background for the traffic light where the lights are placed
		svg.rect(tLBackground, 20, 0, sizeX, sizeY, 10, 10, {
			fill:'url(#tlBg_'+id+')', stroke: '#333', strokeWidth:2
		});

		//pattern which are the small green, red and yellow "Dots" within a light
		//red pattern
		var redPattern = svg.pattern(defs, 'redLightPattern_'+id, 0,0,3,3,{
			patternUnits:'userSpaceOnUse'
		});
		//pattern circle
		svg.circle(redPattern, 1,1,3,{
			fill:'url(#redLight_'+id+')'
		});

		//yellow pattern
		var redPattern = svg.pattern(defs, 'yellowLightPattern_'+id, 0,0,3,3,{
			patternUnits:'userSpaceOnUse'
		});
		//pattern circle
		svg.circle(redPattern, 1,1,3,{
			fill:'url(#yellowLight_'+id+')'
		});

		//green pattern
		var redPattern = svg.pattern(defs, 'greenLightPattern_'+id, 0,0,3,3,{
			patternUnits:'userSpaceOnUse'
		});
		//pattern circle
		svg.circle(redPattern, 1,1,3,{
			fill:'url(#greenLight_'+id+')'
		});

		//main group for the lights
		var lights = svg.group(trafficLight, 'lights_'+id);

		var redLightGroup = svg.group(lights, 'redLightGroup_'+id);
		if(showRed){
			//red background
			var redLight = svg.circle(redLightGroup, 50, 30, lightRadius,{
				fill:'#f00'
			});
			if(blinkLight){
				this.blinking(redLight);
			}
		}
		//red
		svg.circle(redLightGroup, 50, 30, lightRadius,{
			fill:'url(#redLightPattern_'+id+')', stroke:'#333', strokeWidth:2
		});

		var yellowLightGroup = svg.group(lights, 'yellowLightGroup_'+id);
		if(showYellow){
			//yellow background
			var yellowLight = svg.circle(yellowLightGroup, 50, 71, lightRadius,{
				fill:'#FFF000'
			});
			if(blinkLight){
				this.blinking(yellowLight);
			}
		}
		//yellow
		svg.circle(yellowLightGroup, 50, 71, lightRadius,{
			fill:'url(#yellowLightPattern_'+id+')', stroke:'#333', strokeWidth:2
		});

		var greenLightGroup = svg.group(lights, 'greenLightGroup_'+id);
		if(showGreen){
			//green background
			var greenLight = svg.circle(greenLightGroup, 50, 112, lightRadius,{
				fill:'#0F0'
			});
			if(blinkLight){
				this.blinking(greenLight);
			}
		}
		//green
		svg.circle(greenLightGroup, 50, 112, lightRadius,{
			fill:'url(#greenLightPattern_'+id+')', stroke:'#333', strokeWidth:2
		});
		//container Div
		if(containSVG){
			//append an div container into the traffic light (eg. for mouseover events)
			var containerDiv = document.createElementNS("http://www.w3.org/1999/xhtml","div");
			var foreignObject = document.createElementNS('http://www.w3.org/2000/svg', 'foreignObject');
			//build the data object if there is data
			if(containerData){
				var data = {};
				for(var key in containerData){
					data['data-'+key] = containerData[key];
				}
				$(containerDiv).attr(data).css({'width':sizeX+'px','height':sizeY+'px'}).addClass('elementHover');
			}else{
				//there is no data given so create the div without hover information
				$(containerDiv).css({'width':sizeX+'px','height':sizeY+'px'}).addClass('elementHover');
			}
			$(foreignObject).attr({'x':20,'y':0,'width':sizeX, 'height':sizeY}).append(containerDiv);
			$(trafficLight).append(foreignObject);
		}
	},

	blinking:function(el){
		//set the animation interval high to prevent high CPU usage
		//the animation isnt that smooth anymore but the browser need ~70% less CPU!
		$.fx.interval = 100;
		setInterval(function(){
			$(el).fadeOut(2000,function(){
				$(el).fadeIn(2000);
			});
		},6000);

	}

})
