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

App.Components.GadgetComponent = Frontend.Component.extend({

    availableGadgets: ['Tacho', 'Cylinder', 'Text', 'TrafficLight', 'RRDGraph'],

    //tacho Gadget
    drawTacho: function(svgContainerId, opt){
        var opt = opt || {};
        var id = (opt.id == false || opt.id != null ? opt.id : '');
        var x = opt.x || 0;
        var y = opt.y || 0;
        var radius = opt.radius || 100;
        var showLED = opt.showLED || false;
        var ledColor = opt.statusColor || '#00FF26';
        var displayText = opt.displayText || '- - - - - - - - - -';
        var displayValue = opt.displayValue || '- - - - - -';
        var needleValue = opt.value || 0; // percentage
        var containSVG = (opt.contain == null ? true : opt.contain);
        var containerData = opt.containerData || false;
        var perfdata = opt.perfdata || false;
        var showPercentScale = opt.showPercentScale || true;
        var zIndex = opt.z_index || 0;

        var $svgContainer =  $('#' + svgContainerId);

        $svgContainer.css({
            'top': y + 'px',
            'left': x + 'px',
            'height': radius * 2 + 10 + 'px',
            'width': radius * 2 + 10 + 'px',
            'position': 'absolute',
            'z-index': zIndex
        });

        $svgContainer.html('<canvas id="map-tacho-'+id+'"></canvas>');

        var gauge = new RadialGauge({
            renderTo: 'map-tacho-'+id,
            height: (radius * 2),
            width: (radius * 2),
            value: 30,
            minValue: 0,
            maxValue: 100,
            units: '%',
            strokeTicks: true,
            title: 'Example data',
            animationDuration: 700,
            animationRule: 'elastic',
            majorTicks: [0, 10, 20, 30, 40, 50, 60, 80, 90, 100]
        });

        gauge.draw();

    },

    drawTrafficLight: function(svgContainerId, opt){
        var opt = opt || {};
        var id = (opt.id == false || opt.id != null ? opt.id : '');
        var x = opt.x || 0;
        var y = opt.y || 0;
        var sizeX = 60;
        var sizeY = 150;
        var state = opt.state || '';
        var flapping = (opt.flapping == null ? false : opt.flapping);
        var containSVG = (opt.contain == null ? true : opt.contain);
        var containerData = opt.containerData || false;
        var zIndex = opt.z_index || 0;
        var lightRadius = 17;
        var showGreen = false;
        var showYellow = false;
        var showRed = false;
        var blinkLight = false;
        state = parseInt(state);
        if(state != undefined){
            switch(state){
                case 0:
                    //ok
                    showGreen = true;
                    break;
                case 1:
                    //warning
                    showYellow = true;
                    break;
                case 2:
                    //critical
                    showRed = true;
                    break;
                case 3:
                    //unkown
                    showGreen = false;
                    showYellow = false;
                    showRed = false;
                    break;
            }
        }

        if(flapping != undefined && flapping == true){
            showGreen = true;
            showYellow = true;
            showRed = true;
            blinkLight = true;
        }

        //SVG Container
        $('#' + svgContainerId).css({
            'top': y + 'px',
            'left': x + 'px',
            'position': 'absolute',
            'height': sizeY,
            'width': sizeX + 40,
            'z-index': zIndex
        }).svg();
        var svg = $('#' + svgContainerId).svg('get');

        //main group
        var trafficLight = svg.group('trafficLight_' + id);

        //Traffic Light background group
        var tLBackground = svg.group(trafficLight, 'trafficLightBackground_' + id);

        //style definitions for the Traffic light
        var defs = svg.defs();
        //background gradient
        svg.linearGradient(defs, 'tlBg_' + id, [[0.02, '#323232'], [0.02, '#323232'], [0.03, '#333'], [0.3, '#323232']], 0, 0, 0, 1);

        svg.linearGradient(defs, 'protectorGradient_' + id, [[0, '#555'], [0.03, '#444'], [0.07, '#333'], [0.12, '#222']], 0, 0, 0, 1);

        //red light gradient
        svg.radialGradient(defs, 'redLight_' + id, [['0%', 'brown'], ['25%', 'transparent']], 1, 1, 4, 0, 0, {
            gradientUnits: 'userSpaceOnUse'
        });
        //yellow light gradient
        svg.radialGradient(defs, 'yellowLight_' + id, [['0%', 'orange'], ['25%', 'transparent']], 1, 1, 4, 0, 0, {
            gradientUnits: 'userSpaceOnUse'
        });
        //green light gradient
        svg.radialGradient(defs, 'greenLight_' + id, [['0%', 'lime'], ['25%', 'transparent']], 1, 1, 4, 0, 0, {
            gradientUnits: 'userSpaceOnUse'
        });


        //Traffic light "protector"
        /*	var protector1 = svg.createPath();
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
         */
        //the main background for the traffic light where the lights are placed
        svg.rect(tLBackground, 20, 0, sizeX, sizeY, 10, 10, {
            fill: 'url(#tlBg_' + id + ')', stroke: '#444', strokeWidth: 2
        });

        //pattern which are the small green, red and yellow "Dots" within a light
        //red pattern
        var redPattern = svg.pattern(defs, 'redLightPattern_' + id, 0, 0, 3, 3, {
            patternUnits: 'userSpaceOnUse'
        });
        //pattern circle
        svg.circle(redPattern, 1, 1, 3, {
            fill: 'url(#redLight_' + id + ')'
        });

        //yellow pattern
        var redPattern = svg.pattern(defs, 'yellowLightPattern_' + id, 0, 0, 3, 3, {
            patternUnits: 'userSpaceOnUse'
        });
        //pattern circle
        svg.circle(redPattern, 1, 1, 3, {
            fill: 'url(#yellowLight_' + id + ')'
        });

        //green pattern
        var redPattern = svg.pattern(defs, 'greenLightPattern_' + id, 0, 0, 3, 3, {
            patternUnits: 'userSpaceOnUse'
        });
        //pattern circle
        svg.circle(redPattern, 1, 1, 3, {
            fill: 'url(#greenLight_' + id + ')'
        });

        //main group for the lights
        var lights = svg.group(trafficLight, 'lights_' + id);

        var redLightGroup = svg.group(lights, 'redLightGroup_' + id);
        if(showRed){
            //red background
            var redLight = svg.circle(redLightGroup, 50, 30, lightRadius, {
                fill: '#f00'
            });
            if(blinkLight){
                this.blinking(redLight);
            }
        }
        //red
        svg.circle(redLightGroup, 50, 30, lightRadius, {
            fill: 'url(#redLightPattern_' + id + ')', stroke: '#444', strokeWidth: 2
        });

        var yellowLightGroup = svg.group(lights, 'yellowLightGroup_' + id);
        if(showYellow){
            //yellow background
            var yellowLight = svg.circle(yellowLightGroup, 50, 71, lightRadius, {
                fill: '#FFF000'
            });
            if(blinkLight){
                this.blinking(yellowLight);
            }
        }
        //yellow
        svg.circle(yellowLightGroup, 50, 71, lightRadius, {
            fill: 'url(#yellowLightPattern_' + id + ')', stroke: '#444', strokeWidth: 2
        });

        var greenLightGroup = svg.group(lights, 'greenLightGroup_' + id);
        if(showGreen){
            //green background
            var greenLight = svg.circle(greenLightGroup, 50, 112, lightRadius, {
                fill: '#0F0'
            });
            if(blinkLight){
                this.blinking(greenLight);
            }
        }
        //green
        svg.circle(greenLightGroup, 50, 112, lightRadius, {
            fill: 'url(#greenLightPattern_' + id + ')', stroke: '#444', strokeWidth: 2
        });
        //container Div
        if(containSVG){
            //append an div container into the traffic light (eg. for mouseover events)
            var containerDiv = document.createElementNS("http://www.w3.org/1999/xhtml", "div");
            var foreignObject = document.createElementNS('http://www.w3.org/2000/svg', 'foreignObject');
            //build the data object if there is data
            if(containerData){
                var data = {};
                for(var key in containerData){
                    data['data-' + key] = containerData[key];
                }
                $(containerDiv).attr(data).css({
                    'width': sizeX + 'px',
                    'height': sizeY + 'px'
                }).addClass('elementHover');
            }else{
                //there is no data given so create the div without hover information
                $(containerDiv).css({'width': sizeX + 'px', 'height': sizeY + 'px'}).addClass('elementHover');
            }
            $(foreignObject).attr({'x': 20, 'y': 0, 'width': sizeX, 'height': sizeY}).append(containerDiv);
            $(trafficLight).append(foreignObject);
        }
    },

    blinking: function(el){
        //set the animation interval high to prevent high CPU usage
        //the animation isnt that smooth anymore but the browser need ~70% less CPU!
        $.fx.interval = 100;
        setInterval(function(){
            $(el).fadeOut(2000, function(){
                $(el).fadeIn(2000);
            });
        }, 6000);

    },

    drawRRDGraph: function(svgContainerId, opt){
        var initSizeX = 388;
        var initSizeY = 173;

        var opt = opt || {};
        var id = (opt.id == false || opt.id != null ? opt.id : '');
        var x = opt.x || 0;
        var y = opt.y || 0;
        var sizeX = parseInt(opt.sizeX, 10) || initSizeX;
        var sizeY = parseInt(opt.sizeY, 10) || initSizeY;
        var state = opt.state || '';
        var containSVG = (opt.contain == null ? true : opt.contain);
        var containerData = opt.containerData || false;
        var RRDLink = opt.RRDGraphLink || null;
        var demo = opt.demo || false;
        var zIndex = opt.z_index || 0;

        var isThumbnail = svgContainerId.match('Thumbnail');

        if(isThumbnail){
            sizeX = 300;
            sizeY = 173;
        }

        var $container = $('#' + svgContainerId);
        $container.css({
            'top': y + 'px',
            'left': x + 'px',
            'position': 'absolute',
            'width': sizeX+'px',
            'z-index': zIndex
        });

        if(isThumbnail){
            $container.html('<div class="well padding-0">' +
                '    <div id="mapgraph-'+id+'" style="height:'+sizeY+'px; width: '+sizeX+'px;">' +
                '    </div>' +
                '</div>');
        }else{
            $container.html('<div class="well padding-5">' +
                '    <div class="text-center" style="width: 100%">' +
                '        Example data' +
                '    </div>' +
                '    <div id="graph_legend-'+id+'"><table style="font-size:smaller;color:#545454"><tbody><tr><td class="legendColorBox"><div><div style="border:2px solid rgb(25,255,25);overflow:hidden;margin:0px 2px 5px 0px;"></div></div></td><td class="legendLabel">rta</td></tr></tbody></table></div>' +
                '    <div id="mapgraph-'+id+'" style="height:'+sizeY+'px; width: '+sizeX+'px;">' +
                '    </div>' +
                '</div>');
        }

        var options = {
            colors: ['#e67e17', '#e67e17'],
            lines: {
                show: true,
                lineWidth: 1,
                fill: true,
                steps: 0,
                fillColor: {
                    colors: [{
                        opacity: 0.5
                    },
                        {
                            opacity: 0.3
                        }]
                }
            }
        };



        var d1 = [];
        for(var i = 0; i < 50; i++){
            d1.push([i, Math.random()]);
        }


        $.plot('#mapgraph-'+id, [d1], options);
    }
});
