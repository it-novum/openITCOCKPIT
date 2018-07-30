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
       // var radius = opt.radius || 100;

        var radius = opt.sizeX || 200;
        radius = parseInt(radius, 10);
        if(radius < 10){
            radius = 200;
        }
        radius = radius / 2;

        var zIndex = opt.z_index || 0;

        var $svgContainer =  $('#' + svgContainerId);

        if(radius < 10){
            radius = 200;
        }

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

        var width = opt.sizeX || 60;
        width = parseInt(width, 10);
        var height = opt.sizeY || 150;
        height = parseInt(height, 10);
        if(width < 10){
            width = 60;
        }

        if(height < 10){
            height = 150;
        }

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


        $('#' + svgContainerId).css({
            'top': y + 'px',
            'left': x + 'px',
            'position': 'absolute',
            'height': height,
            'width': width + 40,
            'z-index': zIndex
        }).svg();
        var svg = $('#' + svgContainerId).svg('get');

        // 17px was the old radius of the static traffic light.
        // We calucate this calue on the fly to be able to resize the traffic light
        var lightRadius = parseInt((width * (17/60)));
        var lightDiameter = (lightRadius*2) + 2; //2 is the stroke width
        var lightPadding = Math.ceil((height - lightDiameter*3)/4);
        var x = parseInt((width / 2), 10);

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


        //the main background for the traffic light where the lights are placed
        svg.rect(tLBackground, 0, 0, width, height, 10, 10, {
            fill: 'url(#tlBg_' + id + ')', stroke: '#444', strokeWidth: 2
        });

        //pattern which are the small green, red and yellow "Dots" within a light
        //red pattern
        var redPattern;
        redPattern = svg.pattern(defs, 'redLightPattern_' + id, 0, 0, 3, 3, {
            patternUnits: 'userSpaceOnUse'
        });
        //pattern circle
        svg.circle(redPattern, 1, 1, 3, {
            fill: 'url(#redLight_' + id + ')'
        });

        //yellow pattern
        redPattern = svg.pattern(defs, 'yellowLightPattern_' + id, 0, 0, 3, 3, {
            patternUnits: 'userSpaceOnUse'
        });
        //pattern circle
        svg.circle(redPattern, 1, 1, 3, {
            fill: 'url(#yellowLight_' + id + ')'
        });

        //green pattern
        redPattern = svg.pattern(defs, 'greenLightPattern_' + id, 0, 0, 3, 3, {
            patternUnits: 'userSpaceOnUse'
        });
        //pattern circle
        svg.circle(redPattern, 1, 1, 3, {
            fill: 'url(#greenLight_' + id + ')'
        });

        //main group for the lights
        var lights = svg.group(trafficLight, 'lights_' + id);

        var redLightGroup = svg.group(lights, 'redLightGroup_' + id);

        svg.circle(redLightGroup, x, lightPadding+lightRadius, lightRadius, {
            fill: 'url(#redLightPattern_' + id + ')', stroke: '#444', strokeWidth: 2
        });
        var yellowLightGroup = svg.group(lights, 'yellowLightGroup_' + id);

        //yellow
        svg.circle(yellowLightGroup, x, lightDiameter+lightPadding*2+lightRadius, lightRadius, {
            fill: 'url(#yellowLightPattern_' + id + ')', stroke: '#444', strokeWidth: 2
        });

        var blueLightGroup = svg.group(lights, 'blueLightGroup_' + id);

            //blue background
            var blueLight = svg.circle(blueLightGroup, x, lightDiameter+lightPadding*2+lightRadius, lightRadius, {
                fill: '#6e99ff'
            });

        //blue
        svg.circle(yellowLightGroup, x, lightDiameter+lightPadding*2+lightRadius, lightRadius, {
            fill: 'url(#yellowLightPattern_' + id + ')', stroke: '#444', strokeWidth: 2
        });

        var greenLightGroup = svg.group(lights, 'greenLightGroup_' + id);

        //green
        svg.circle(greenLightGroup, x, lightDiameter*2+lightPadding*3+lightRadius, lightRadius, {
            fill: 'url(#greenLightPattern_' + id + ')', stroke: '#444', strokeWidth: 2
        });
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
    },

    //Cylinder Gadget
    drawCylinder: function (svgContainerId, opt) {
        var opt = opt || {};
        var id = (opt.id == false || opt.id != null ? opt.id : '');
        var x = opt.x || 0;
        var y = opt.y || 0;
        var width = opt.sizeX || 80;
        var height = opt.sizeY || 100;
        var value = opt.value || 75; // percentage!!!!
        var containSVG = (opt.contain == null ? true : opt.contain);
        var containerData = opt.containerData || false;
        var perfdata = opt.perfdata || false;
        var zIndex = opt.z_index || 0;

        if(width < 10){
            width = 80;
        }

        if(height < 10){
            height = 100;
        }



        $('#' + svgContainerId).css({
            'top': y + 'px',
            'left': x + 'px',
            'height': height + 25 + 'px',
            'width': width + 'px',
            'position': 'absolute',
            'z-index': zIndex
        }).svg();
        var svg = $('#' + svgContainerId).svg('get');



        var x = 0;
        var y = 10;
        //radii for the ellipse
        var rx = width / 2;
        var ry = 10;
        //calculate positions for the Cylinder
        var ellipseCx = x + rx;
        var ellipseBottomCy = height;
        var rectX = x;
        var rectY = y;
        var ellipseTopCy = y;
        var pxValue = height * value / 100;
        var newRectY = (height - pxValue);
        var newTopEllipseY = newRectY;

        //the id schema must be like this "cyliner_"+id
        var cylinder = svg.group('cylinder_' + id);
        var cylinerGroup = svg.group(cylinder, 'cylinder_' + id);
        var defs = svg.defs();
        var stateColor = 'Blue';


        svg.linearGradient(defs, 'fadeBlue_' + id, [[0.0, '#0006D5'], [0.2, '#1248D5'], [0.7, '#0006D5']]);
        svg.linearGradient(defs, 'fadeDarkBlue_' + id, [[0.0, '#000674'], [0.2, '#0006B8'], [1.0, '#000674']]);


        //outer Cylinder
        //bottom ellipse
        svg.ellipse(cylinerGroup, ellipseCx, ellipseBottomCy - 10, rx, ry, {
            fill: 'url(#fadeDarkGray_' + id + ')',
            fillOpacity: 0.1,
            id: 'background_' + id,
            strokeWidth: 2,
            stroke: '#CECECE',
            strokeOpacity: 0.5
        });

        //inner Cylinder (the value)
        //bottom ellipse
        svg.ellipse(cylinerGroup, ellipseCx, ellipseBottomCy - 10, rx, ry, {
            fill: 'url(#fadeDark' + stateColor + '_' + id + ')',
            fillOpacity: 0.8

        });
        //center rect
        if(value > 1){
            svg.rect(cylinerGroup, rectX, newRectY - 10, width, pxValue + 10, rx, ry, {
                fill: 'url(#fade' + stateColor + '_' + id + ')',
                fillOpacity: 0.9
            });
            //top ellipse
            svg.ellipse(cylinerGroup, ellipseCx, newTopEllipseY, rx, ry, {
                fill: 'url(#fadeDark' + stateColor + '_' + id + ')',
                fillOpacity: 0.8
            });
        }
        //outer Cylinder
        //top ellipse
        svg.ellipse(cylinerGroup, ellipseCx, ellipseTopCy, rx, ry, {
            fill: 'url(#fadeDarkGray_' + id + ')',
            fillOpacity: 0.0,
            strokeWidth: 2,
            stroke: '#CECECE',
            strokeOpacity: 0.4
        });

        //center rect
        svg.rect(cylinerGroup, rectX, rectY - 10, width, height, rx, ry, {
                fill: 'url(#fadeGray_' + id + ')',
                fillOpacity: 0.5,
                id: 'background_' + id,
                strokeWidth: 2,
                stroke: '#CECECE',
                strokeOpacity: 0.3
            }
        );
    },

    //Text Gadget
    drawText: function (svgContainerId, opt) {
        var opt = opt || {};
        var id = (opt.id == false || opt.id != null ? opt.id : '');
        var textX = opt.x || 0;
        var textY = opt.y || 0;
        var color = opt.color || '#5CB85C';
        var text = opt.text || 'Perfdata:';
        var value = opt.value || 'to';
        var unit = opt.unit || 'Text';
        var containSVG = (opt.contain == null ? true : opt.contain);
        var containerData = opt.containerData || false;
        var perfdata = opt.perfdata || false;
        var showLabel = (opt.showLabel == null ? true : opt.showLabel);
        var fontSize = opt.fontSize || 13;//px
        var zIndex = opt.z_index || 0;

        $('#' + svgContainerId).css({
            'top': textY + 'px',
            'left': textX + 'px',
            'position': 'absolute',
            'z-index': zIndex
        }).svg();
        var svg = $('#' + svgContainerId).svg('get');

        textY = fontSize;
        textX = 0;

        if (perfdata) {
            if (perfdata[0] != undefined) {
                text = perfdata[0].label;
                value = perfdata[0].current_value;
                unit = perfdata[0].unit;
            }else{
                text = 'Perfdata';
                value = 'not';
                unit = 'found!';
            }
        }

        //build up the text
        var textString = '';
        if(showLabel == false && value.length > 0){
            textString = value;
            if (unit.length > 0) {
                textString = value + ' ' + unit;
            }
        }else if (text.length > 0) {
            textString = text;
            if (value.length > 0) {
                textString = text + ' ' + value;
                if (unit.length > 0) {
                    textString = text + ' ' + value + ' ' + unit;
                }
            }
        } else {
            return false;
        }

        //the id schema must be like this "text_"+id
        var textGroup = svg.group('text_' + id);
        var perfdataText = svg.group(textGroup, 'textGroup');

        //draw the string
        svg.text(perfdataText, textX, textY, textString, {
            fontFamily: 'monospace, Courier New',
            fontSize: fontSize+'px',
            fill: color,
        });

        if (containSVG) {
            //append an div container into the Text (eg. for mouseover events)
            var containerDiv = document.createElementNS("http://www.w3.org/1999/xhtml", "div");
            var foreignObject = document.createElementNS('http://www.w3.org/2000/svg', 'foreignObject');
            //build the data object if there is data
            var textSize = textGroup.getBBox();

            $('#' + svgContainerId).css({
                'height': parseInt(textSize.height) + 'px',
                'width': parseInt(textSize.width) + 'px'
            });
            $('#' + svgContainerId).children('svg').attr({
                'width': parseInt(textSize.width),
                'height': parseInt(textSize.height)
            });

            if (containerData) {
                var data = {};
                for (var key in containerData) {
                    data['data-' + key] = containerData[key];
                }
                $(containerDiv).attr(data).css({
                    'width': textSize.width + 'px',
                    'height': textSize.height + 'px'
                }).addClass('elementHover');
            } else {
                //there is no data given so create the div without hover information
                $(containerDiv).css({
                    'width': textSize.width + 'px',
                    'height': textSize.height + 'px'
                }).addClass('elementHover');
            }
            $(foreignObject).attr({
                'x': textX,
                'y': textY - 10,
                'width': textSize.width,
                'height': textSize.height
            }).append(containerDiv);
            $(textGroup).append(foreignObject);
        }
    }
});
