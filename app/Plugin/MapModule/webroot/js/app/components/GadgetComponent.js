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
    drawTacho: function (svgContainerId, opt) {
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

        $('#' + svgContainerId).css({
            'top': y + 'px',
            'left': x + 'px',
            'height': radius * 2 + 10 + 'px',
            'width': radius * 2 + 10 + 'px',
            'position': 'absolute',
            'z-index': zIndex
        }).svg();
        var svg = $('#' + svgContainerId).svg('get');

        if (perfdata[0] != undefined) {
            var label = perfdata[0].label;
            //cut the text if its too long
            if (label.length > 14) {
                label = label.substr(0, 14);
                label += '...';
            }
            displayText = label;
            var value = parseInt(perfdata[0].current_value).toString();
            if (perfdata[0].current_value.length > 6) {
                value = perfdata[0].current_value.substr(0, 6);
            }
            displayValue = value;
            if (perfdata[0].current_value && perfdata[0].max) {
                needleValue = parseInt(perfdata[0].current_value) / parseInt(perfdata[0].max) * 100;
            }
        }

        x = 3;
        y = 3;
        radius = parseInt(radius);

        var centerX = x + radius;
        var centerY = y + radius;

        var rectWidth = 80;
        var rectHeight = 50;
        var rectPosX = centerX - (rectWidth / 2);
        var rectPosY = centerY + 25;

        var defs = svg.defs();
        svg.linearGradient(defs, 'fadeGray_' + id, [[0.0, '#AFAFAF', 0.5], [0.2, '#FFFFFF', 0.3], [0.7, '#AFAFAF', 0.5], [1.0, '#A0A0A0', 0.5]], 0, 1, 0);
        svg.linearGradient(defs, 'gradientWhite_' + id, [[0.0, '#FFFFFF', 0.0], [1.0, '#FFFFFF', 0.8]], 0, 1, 0);


        //container group
        //the id schema must be like this "cyliner_"+id
        var tacho = svg.group('tacho_' + id);


        //outer Circle (the border)
        var tachoBackground = svg.group(tacho, 'tachoBg_' + id);
        svg.circle(tachoBackground, centerX, centerY, radius + 5, {
            fill: '#FFFFFF'
        });
        svg.circle(tachoBackground, centerX, centerY, radius + 3, {
            fill: '#000', 'fill-opacity': 0.6
        });

        //cx,cy,r, settings
        svg.circle(tachoBackground, centerX, centerY, radius, {
            stroke: '#7C7C7C', strokeWidth: 5, fill: '#FFF'
        });
        //inner circle (the background)
        svg.circle(tachoBackground, centerX, centerY, radius - 3, {
            fill: 'url(#fadeGray_' + id + ')'
        });
        //the gray circle in the center
        svg.circle(tachoBackground, centerX, centerY, 60, {
            fill: '#7C7C7C'
        });


        //inner circle (the background)
        svg.ellipse(tachoBackground, centerX, centerY + 2, radius - 10, radius - 10, {
            fill: 'url(#gradientWhite_' + id + ')'
        });


        //the value display
        var display = svg.group(tacho, 'display_' + id);
        //the border rect
        svg.rect(display, rectPosX - 1, rectPosY - 1, rectWidth + 1, rectHeight + 1, {
            stroke: '#444', strokeWidth: 1
        });
        //the display rect
        svg.rect(display, rectPosX, rectPosY, rectWidth, rectHeight, {
            fill: '#A7D64A'
        });

        //text for the display
        svg.text(display, centerX - 38, centerY + 40, displayText, {
            fontFamily: 'sans-serif',
            fontSize: '13px'
        });
        //value text for the display
        svg.text(display, centerX - 38, centerY + 70, displayValue, {
            fontFamily: 'sans-serif',
            fontSize: '16px'
        });


        //calculate the needle position
        //250° -> initial value -> max value +220° = 470°
        var minValue = 250;
        var maxValue = 470;
        //value which can be rotated by the needle
        var valueScale = maxValue - minValue;
        var rotatingValue = (valueScale * (needleValue / 100) + minValue);

        //needle
        var path = svg.createPath();
        var needle = svg.group(tacho, 'needle_' + id);
        //rotate(angle,cx,cy)
        svg.path(needle, path.move(centerX, centerY).line(-5, 0, true).line(2, -80, true).line(3, -10, true).line(3, 10, true).line(2, 80, true).close(), {
            stroke: '#000',
            strokeWidth: 1,
            fill: '#3D3C3D',
            transform: 'rotate(' + rotatingValue + ', ' + centerX + ', ' + centerY + ')'
        });
        //center circle
        svg.circle(needle, centerX, centerY, 10, {
            stroke: '#888', fill: '#1c1c1c'
        });


        //scale
        var scale = svg.group(tacho, 'scale_' + id);

        for (var i = 0; i < 101; i++) {
            var scaleRotatingValue = valueScale * (i / 100) + minValue;
            if (i % 10 == 0) {
                svg.line(scale, centerX, centerY - (radius - 3), centerX, centerY - (radius - 15), {
                    stroke: '#000',
                    strokeWidth: 1,
                    transform: 'rotate(' + scaleRotatingValue + ', ' + centerX + ', ' + centerY + ')'
                });
                if (perfdata[0]) {
                    if (showPercentScale) {
                        svg.text(scale, centerX, centerY - (radius - 25), i.toString(), {
                            fontFamily: 'monospace, Courier New',
                            fontSize: '10px',
                            color: '#f00',
                            transform: 'rotate(' + scaleRotatingValue + ', ' + centerX + ', ' + centerY + ')'
                        })
                    } else {
                        if (perfdata[0].max) {
                            svg.text(scale, centerX, centerY - (radius - 25), (parseInt(perfdata[0].max) / 10 * i).toString(), {
                                fontFamily: 'monospace, Courier New',
                                fontSize: '10px',
                                color: '#f00',
                                transform: 'rotate(' + scaleRotatingValue + ', ' + centerX + ', ' + centerY + ')'
                            })
                        }
                    }
                }
            } else {
                svg.line(scale, centerX, centerY - (radius - 3), centerX, centerY - (radius - 10), {
                    stroke: '#000',
                    strokeWidth: 1,
                    transform: 'rotate(' + scaleRotatingValue + ', ' + centerX + ', ' + centerY + ')'
                });
            }
        }
        ;


        //LED
        if (showLED) {
            //the LED case
            var led = svg.group(tacho, 'led_' + id);
            svg.circle(led, centerX - 60, centerY + 45, 15, {
                fill: '#C5C5C5'
            });
            svg.circle(led, centerX - 60, centerY + 45, 13, {
                fill: '#8A8A8A'
            });
            //the LED
            svg.circle(led, centerX - 60, centerY + 45, 11, {
                fill: ledColor
            });
            svg.ellipse(led, centerX - 60, centerY + 43, 9, 8, {
                fill: '#AAA', fillOpacity: 0.5
            });
        }

        //container Div
        if (containSVG) {
            //append an div container into the Tacho (eg. for mouseover events)
            var containerDiv = document.createElementNS("http://www.w3.org/1999/xhtml", "div");
            var foreignObject = document.createElementNS('http://www.w3.org/2000/svg', 'foreignObject');
            //build the data object if there is data
            if (containerData) {
                var data = {};
                for (var key in containerData) {
                    data['data-' + key] = containerData[key];
                }
                $(containerDiv).attr(data).css({
                    'width': radius * 2 + 'px',
                    'height': radius * 2 + 'px'
                }).addClass('elementHover');
            } else {
                //there is no data given so create the div without hover information
                $(containerDiv).css({'width': radius * 2 + 'px', 'height': radius * 2 + 'px'}).addClass('elementHover');
            }
            $(foreignObject).attr({'x': x, 'y': y, 'width': radius * 2, 'height': radius * 2}).append(containerDiv);
            $(tacho).append(foreignObject);
        }
    },

    //Cylinder Gadget
    drawCylinder: function (svgContainerId, opt) {
        var opt = opt || {};
        var id = (opt.id == false || opt.id != null ? opt.id : '');
        var x = opt.x || 0;
        var y = opt.y || 0;
        var color = opt.color || '#5CB85C'
        var width = opt.width || 80;
        var height = opt.height || 100;
        var value = opt.value || 1; // percentage!!!!
        var containSVG = (opt.contain == null ? true : opt.contain);
        var containerData = opt.containerData || false;
        var perfdata = opt.perfdata || false;
        var zIndex = opt.z_index || 0;

        $('#' + svgContainerId).css({
            'top': y + 'px',
            'left': x + 'px',
            'height': height + 25 + 'px',
            'width': width + 'px',
            'position': 'absolute',
            'z-index': zIndex
        }).svg();
        var svg = $('#' + svgContainerId).svg('get');

        //max min current_value
        if (perfdata[0] != undefined) {
            if (perfdata[0].max != '') {
                value = (parseInt(perfdata[0].current_value) / parseInt(perfdata[0].max))*100;
                //todo fix me
                if(value > 90){
                    value = 90;
                }
            } else {
                value = 0;
            }
        }
        x = 0;
        y = 10;
        //radii for the ellipse
        var rx = width / 2;
        var ry = 10;
        //calculate positions for the Cylinder
        var ellipseCx = x + rx;
        var ellipseBottomCy = height;
        var rectX = x;
        var rectY = y;
        var ellipseTopCy = y;
        var pxValue = height * value/100;
        var newRectY = (height - pxValue);
        var newTopEllipseY = newRectY;

        //the id schema must be like this "cyliner_"+id
        var cylinder = svg.group('cylinder_' + id);
        var cylinerGroup = svg.group(cylinder, 'cylinder_' + id)
        var defs = svg.defs();
        var stateColor = 'Gray';

        if (opt.state) {
            switch (opt.state) {
                case '0':
                    stateColor = 'Green';
                    break;
                case '1':
                    stateColor = 'Yellow';
                    break;
                case '2':
                    stateColor = 'Red';
                    break;
            }
        }

        svg.linearGradient(defs, 'fadeGreen_' + id, [[0, '#00cc00'], [0.2, '#5BFF5B'], [0.7, '#006600']]);
        svg.linearGradient(defs, 'fadeDarkGreen_' + id, [[0, '#00AD00'], [0.6, '#006600'], [0.7, '#005600']]);

        svg.linearGradient(defs, 'fadeYellow_' + id, [[0, '#FFCC00'], [0.2, '#FFFF5B'], [0.7, '#E5BB00']]);
        svg.linearGradient(defs, 'fadeDarkYellow_' + id, [[0, '#FFAD00'], [0.6, '#E5BB00'], [0.7, '#E2B100']]);

        svg.linearGradient(defs, 'fadeRed_' + id, [[0, '#CE0D00'], [0.2, '#FF0000'], [0.7, '#BF1600']]);
        svg.linearGradient(defs, 'fadeDarkRed_' + id, [[0, '#c91400'], [0.6, '#BF1600'], [0.7, '#BF0600']]);

        svg.linearGradient(defs, 'fadeGray_' + id, [[0.0, '#AFAFAF'], [0.2, '#FFFFFF'], [0.7, '#AFAFAF'], [1.0, '#A0A0A0']], 0, 0, 1);
        svg.linearGradient(defs, 'fadeDarkGray_' + id, [[0.0, '#757575'], [0.2, '#939393'], [1.0, '#757575']]);


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
        svg.ellipse(cylinerGroup, ellipseCx, ellipseBottomCy - 10 , rx, ry, {
            fill: 'url(#fadeDark'+stateColor+'_' + id + ')',
            fillOpacity: 0.8

        });
        //center rect
        if(value > 1){
            svg.rect(cylinerGroup, rectX, newRectY - 10 , width, pxValue + 10, rx, ry, {
                fill: 'url(#fade'+stateColor+'_' + id + ')',
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
        svg.rect(cylinerGroup, rectX, rectY - 10, width, height , rx, ry, {
                fill: 'url(#fadeGray_' + id + ')',
                fillOpacity: 0.5,
                id: 'background_' + id,
                strokeWidth: 2,
                stroke: '#CECECE',
                strokeOpacity: 0.3
            }
        );

        //container Div
        if (containSVG) {
            //append an div container into the Cylinder (eg. for mouseover events)
            var containerDiv = document.createElementNS("http://www.w3.org/1999/xhtml", "div");
            var foreignObject = document.createElementNS('http://www.w3.org/2000/svg', 'foreignObject');
            //build the data object if there is data
            if (containerData) {
                var data = {};
                for (var key in containerData) {
                    data['data-' + key] = containerData[key];
                }
                $(containerDiv).attr(data).css({
                    'width': width + 'px',
                    'height': height + 'px'
                }).addClass('elementHover');
            } else {
                //there is no data given so create the div without hover information
                $(containerDiv).css({'width': width + 'px', 'height': height + 'px'}).addClass('elementHover');
            }
            $(foreignObject).attr({'x': x, 'y': y, 'width': width, 'height': height}).append(containerDiv);
            $(cylinder).append(foreignObject);
        }
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
    },

    drawTrafficLight: function (svgContainerId, opt) {
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
        if (state != undefined) {
            switch (state) {
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

        if (flapping != undefined && flapping == true) {
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
        if (showRed) {
            //red background
            var redLight = svg.circle(redLightGroup, 50, 30, lightRadius, {
                fill: '#f00'
            });
            if (blinkLight) {
                this.blinking(redLight);
            }
        }
        //red
        svg.circle(redLightGroup, 50, 30, lightRadius, {
            fill: 'url(#redLightPattern_' + id + ')', stroke: '#444', strokeWidth: 2
        });

        var yellowLightGroup = svg.group(lights, 'yellowLightGroup_' + id);
        if (showYellow) {
            //yellow background
            var yellowLight = svg.circle(yellowLightGroup, 50, 71, lightRadius, {
                fill: '#FFF000'
            });
            if (blinkLight) {
                this.blinking(yellowLight);
            }
        }
        //yellow
        svg.circle(yellowLightGroup, 50, 71, lightRadius, {
            fill: 'url(#yellowLightPattern_' + id + ')', stroke: '#444', strokeWidth: 2
        });

        var greenLightGroup = svg.group(lights, 'greenLightGroup_' + id);
        if (showGreen) {
            //green background
            var greenLight = svg.circle(greenLightGroup, 50, 112, lightRadius, {
                fill: '#0F0'
            });
            if (blinkLight) {
                this.blinking(greenLight);
            }
        }
        //green
        svg.circle(greenLightGroup, 50, 112, lightRadius, {
            fill: 'url(#greenLightPattern_' + id + ')', stroke: '#444', strokeWidth: 2
        });
        //container Div
        if (containSVG) {
            //append an div container into the traffic light (eg. for mouseover events)
            var containerDiv = document.createElementNS("http://www.w3.org/1999/xhtml", "div");
            var foreignObject = document.createElementNS('http://www.w3.org/2000/svg', 'foreignObject');
            //build the data object if there is data
            if (containerData) {
                var data = {};
                for (var key in containerData) {
                    data['data-' + key] = containerData[key];
                }
                $(containerDiv).attr(data).css({
                    'width': sizeX + 'px',
                    'height': sizeY + 'px'
                }).addClass('elementHover');
            } else {
                //there is no data given so create the div without hover information
                $(containerDiv).css({'width': sizeX + 'px', 'height': sizeY + 'px'}).addClass('elementHover');
            }
            $(foreignObject).attr({'x': 20, 'y': 0, 'width': sizeX, 'height': sizeY}).append(containerDiv);
            $(trafficLight).append(foreignObject);
        }
    },

    blinking: function (el) {
        //set the animation interval high to prevent high CPU usage
        //the animation isnt that smooth anymore but the browser need ~70% less CPU!
        $.fx.interval = 100;
        setInterval(function () {
            $(el).fadeOut(2000, function () {
                $(el).fadeIn(2000);
            });
        }, 6000);

    },

    drawRRDGraph: function (svgContainerId, opt) {
        var initSizeX = 300;
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

        if (!demo) {
            var imageLink = '';
            if (RRDLink != null) {
                imageLink = RRDLink;
                //determine new svg size when img is loaded
                $('<img/>').attr('src', imageLink).load(function () {
                    sizeX = this.width;
                    sizeY = this.height;
                    //set new height to container div
                    $('#' + svgContainerId).css({'width': sizeX, 'height': sizeY});
                    //set new height to svg tag
                    $('#' + svgContainerId).find('svg').attr({'width': sizeX, 'height': sizeY});
                    //set new height to svg elements
                    $('#mapRRDGraph_' + id).children().each(function () {
                        $(this).attr({'width': sizeX, 'height': sizeY});
                        if ($(this).prop('tagName') == 'foreignObject') {
                            $(this).children().css({'width': sizeX + 'px', 'height': sizeY + 'px'});
                        }
                    });
                });
            }
        }

        //SVG Container
        $('#' + svgContainerId).css({
            'top': y + 'px',
            'left': x + 'px',
            'position': 'absolute',
            'height': sizeY,
            'width': sizeX,
            'z-index': zIndex
        }).svg();
        var svg = $('#' + svgContainerId).svg('get');

        if (!demo) {
            var RRDGroup = svg.group('mapRRDGraph_' + id);
            svg.image(RRDGroup, 0, 0, sizeX, sizeY, imageLink, {});
        } else {
            var defs = svg.defs();

            var scaleX = sizeX/initSizeX;
            var scaleY = sizeY/initSizeY;

            svg.linearGradient(defs, 'fadeGreen', [[0, '#00cc00'], [0.2, '#5BFF5B'], [0.9, '#006600']]);
            svg.linearGradient(defs, 'fadeDarkGreen', [[0, '#00AD00'], [0.6, '#006600'], [0.7, '#005600']]);

            var path = svg.createPath();
            var arrow_path = svg.createPath();

            var startX = 10; // Start fuer Tacho

            var element_group = svg.group(null, 'elementGroup');
            var markerVert = svg.marker(defs, 'myMarker', 0, 5, 10, 10, 0, {
                fill: 'black',
                markerUnits: 'strokeWidth',
                viewBox: '0 0 20 20',
                fillOpacity: 0.3,
                transform: 'scale('+scaleX+' '+scaleY+')'
            });
            svg.path(markerVert, arrow_path.move(0, 0).line(20, 5).line(0, 10).close());
            svg.line(element_group, startX, 170, startX, 10, {
                strokeWidth: 1,
                stroke: 'black',
                markerEnd: 'url(#myMarker)',
                fillOpacity: 0.3,
                transform: 'scale('+scaleX+' '+scaleY+')'
            });
            svg.path(markerVert, arrow_path.move(0, 0).line(20, 5).line(0, 10).close());
            svg.line(element_group, startX, 170, 280, 170, {
                strokeWidth: 1,
                stroke: 'black',
                markerEnd: 'url(#myMarker)',
                fillOpacity: 0.3,
                transform: 'scale('+scaleX+' '+scaleY+')'
            });


            svg.polygon([[startX, 170], [startX, 40], [30, 75], [50, 70], [80, 110], [110, 85], [140, 90], [170, 100], [200, 130], [230, 100], [260, 95], [280, 60], [280, 170]],
                {fill: '#b2d6a4', stroke: '#a1c593', strokeWidth: 0.5, fillOpacity: 0.5,
                    transform: 'scale('+scaleX+' '+scaleY+')'
                });

        }

        //container Div
        if (containSVG) {
            //append an div container into the traffic light (eg. for mouseover events)
            var containerDiv = document.createElementNS("http://www.w3.org/1999/xhtml", "div");
            var foreignObject = document.createElementNS('http://www.w3.org/2000/svg', 'foreignObject');
            //build the data object if there is data
            if (containerData) {
                var data = {};
                for (var key in containerData) {
                    data['data-' + key] = containerData[key];
                }
                $(containerDiv).attr(data).css({
                    'width': sizeX + 'px',
                    'height': sizeY + 'px'
                }).addClass('elementHover');
            } else {
                //there is no data given so create the div without hover information
                $(containerDiv).css({'width': sizeX + 'px', 'height': sizeY + 'px'}).addClass('elementHover');
            }
            $(foreignObject).attr({'x': 0, 'y': 0, 'width': sizeX, 'height': sizeY}).append(containerDiv);
            $(RRDGroup).append(foreignObject);
        }
    },
});
