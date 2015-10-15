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

	Ajaxloader: null,

	trafficlights: {},

	setAjaxloader: function(Ajaxloader){
		this.Ajaxloader = Ajaxloader;
	},

	initTrafficlights: function(){
		var self = this;
		$(document).on('change', '.trafficLightSelectService', function(){
			var $object = $(this);
			var widgetId = parseInt($object.data('widget-id'), 10);
			var serviceId = parseInt($object.val(), 10);
			if(!isNaN(serviceId)){
				self.saveService(widgetId, serviceId);
			}
		});
		
		var gridstack = $('.grid-stack');
		gridstack.on('resizestop', function(event, ui){
			var $element = $(ui.element);
			var widgetId = parseInt($element.data('widget-id'), 10);
			var widgetTypeId = parseInt($element.data('widget-type-id'), 10);
			if(widgetTypeId == 11){
				this.drawTrafficLight(widgetId, this.trafficlights[widgetId].container);
			}
		}.bind(this));
		
		$('.trafficlightContainer').each(function(key, object){
			this.initTrafficlight(object);
		}.bind(this));
	},
	
	initTrafficlight: function(object){
		var $object = $(object);
		widgetId = parseInt($object.parents('.grid-stack-item').data('widget-id'), 10);
		var $container = $object;
		var $wrapper = $object.parents('.trafficLight-body').find('.trafficLightWrapper');
		var $widgetContainer = $object.parents('.grid-stack-item');
		this.trafficlights[widgetId] = {
			container: $container,
			wrapper: $wrapper,
			svg: null,
			blinkTimer: null,
			refreshTimer: null,
			current_state: parseInt($container.data('current-state'), 10),
			is_flapping: parseInt($container.data('is-flapping'), 10),
			check_interval: parseInt($container.data('check-interval'), 10),
			idService: parseInt($container.data('id-service'), 10),
			widgetContainer: $widgetContainer
		};
		if(this.trafficlights[widgetId].idService > 0){
			this.drawTrafficLight(widgetId, $container);
		}
	},

	calculateHeight: function(widgetId){
		var height = this.trafficlights[widgetId].widgetContainer.innerHeight();
		return parseInt((height - 70 - 35 - 30), 10)
	},

	drawTrafficLight:function(widgetId, $container){
		$svgContainer = $container;

		var id = widgetId;
		var sizeX = 60;
		var sizeY = 150;

		var lightRadius = 17;
		
		var blinkLight = false;
		if(this.trafficlights[widgetId].is_flapping == 1 || this.trafficlights[widgetId].current_state == 3){
			blinkLight = true;
		}

		//SVG Container
		//$svgContainer.css({width: '150px'}).svg();
		
		var height = this.calculateHeight(widgetId);
		
		$svgContainer.svg();
		var svg = $svgContainer.svg('get');

		svg._svg.setAttribute('viewBox', '0 0 105 150');
		svg._svg.setAttribute('width', '100%');
		svg._svg.setAttribute('height', height+'px');

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
		var backgrounds = svg.group(trafficLight, 'backgrounds_'+id);

		//var redLightGroup = svg.group(lights, 'redLightGroup_'+id);
		if(this.trafficlights[widgetId].current_state == 2 || this.trafficlights[widgetId].current_state == 3){
			//red background
			var redLight = svg.circle(lights, 50, 30, lightRadius,{
				fill:'#f00'
			});
		}
		//red
		svg.circle(backgrounds, 50, 30, lightRadius,{
			fill:'url(#redLightPattern_'+id+')', stroke:'#333', strokeWidth:2
		});

		//var yellowLightGroup = svg.group(lights, 'yellowLightGroup_'+id);
		if(this.trafficlights[widgetId].current_state == 1 || this.trafficlights[widgetId].current_state == 3){
			//yellow background
			var yellowLight = svg.circle(lights, 50, 71, lightRadius,{
				fill:'#FFF000'
			});
		}
		//yellow
		svg.circle(backgrounds, 50, 71, lightRadius,{
			fill:'url(#yellowLightPattern_'+id+')', stroke:'#333', strokeWidth:2
		});

		//var greenLightGroup = svg.group(lights, 'greenLightGroup_'+id);
		if(this.trafficlights[widgetId].current_state == 0 || this.trafficlights[widgetId].current_state == 3){
			//green background
			var greenLight = svg.circle(lights, 50, 112, lightRadius,{
				fill:'#0F0'
			});
		}
		//green
		svg.circle(backgrounds, 50, 112, lightRadius,{
			fill:'url(#greenLightPattern_'+id+')', stroke:'#333', strokeWidth:2
		});
		
		this.trafficlights[widgetId].svg = svg;
		
		if(this.trafficlights[widgetId].current_state == 3 || this.trafficlights[widgetId].is_flapping == 1){
			this.blinking(widgetId);
		}
		this.startRefreshInterval(widgetId);
	},

	blinking: function(widgetId){
		var $green = $('#lights_'+widgetId);
		//the animation isnt that smooth anymore but the browser need ~70% less CPU!
		// Do not increase animation speed, this will burn your CPU!
		$.fx.interval = 50;
		this.trafficlights[widgetId].blinkTimer = setInterval(function(){
			$green.fadeOut(2000);
			$green.fadeIn(2000);
		}, 3000);
	},
	
	startRefreshInterval: function(widgetId){
		if(this.trafficlights[widgetId].refreshTimer != null){
			clearTimeout(this.trafficlights[widgetId].refreshTimer);
		}
		if(this.trafficlights[widgetId].check_interval > 0){
			this.trafficlights[widgetId].refreshTimer = setTimeout(function(){
				this.refresh(widgetId);
			}.bind(this), (this.trafficlights[widgetId].check_interval * 1000));
		}
	},
	
	saveService: function(widgetId, serviceId){
		this.Ajaxloader.show();
		$.ajax({
			url: "/dashboards/saveTrafficLightService",
			type: "POST",
			data: {widgetId: widgetId, serviceId: serviceId},
			error: function(){},
			success: function(response){
				this.Ajaxloader.hide();
				this.refresh(widgetId);
			}.bind(this),
			complete: function(response) {
			}
		});
	},
	
	refresh: function(widgetId){
		var $wrapper = this.trafficlights[widgetId].wrapper;
		var $trafficLightBody = $wrapper.parents('.trafficLight-body').parent();
		$wrapper.html('<div class="text-center padding-top-50"><h1><i class="fa fa-cog fa-lg fa-spin"></i></h1></div>');
		this.Ajaxloader.show();
		$.ajax({
			url: "/dashboards/refresh",
			type: "POST",
			data: {widgetId: widgetId},
			error: function(){},
			success: function(response){
				if(response != ''){
					$trafficLightBody.html(response);
					if(this.trafficlights[widgetId].blinkTimer != null){
						clearInterval(this.trafficlights[widgetId].blinkTimer);
					}
					this.initTrafficlight($trafficLightBody.find('.trafficlightContainer'));
					$('.chosen').chosen();
				}
				this.Ajaxloader.hide();
			}.bind(this),
			complete: function(response) {
			}
		});
	}

});

