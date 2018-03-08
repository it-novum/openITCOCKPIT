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

App.Components.LineComponent = Frontend.Component.extend({

    drawSVGLine: function (opt) {
        opt = opt || {};
        var id = opt.id || ''; // mandatory
        var svgContainer = opt.svgContainer || ''; //mandatory
        var start = opt.start || []; //mandatory
        var end = opt.end || []; //mandatory
        opt.rectWidth = opt.rectWidth || 30;
        opt.rectHeight = opt.rectHeight || 30;
        var lineId = opt.lineId || ''; //optional
        var el2append = opt.el2append || null; //optional
        var link = (opt.link == null ? false : opt.link);
        var objData = opt.objData || ''; //optional but mandatory if link == true
        var lineStatusColor = objData.currentColor || '#5CB85C';
        var drawRect = (opt.drawRect == null) ? true : opt.drawRect;
        var zIndex = opt.z_index || 0;

        var boxes = this.calculateLineBoxCenterPosition(opt);

        var svgContainerTop = boxes.containerTop - 2;
        var svgContainerLeft = boxes.containerLeft - 2;
        var svgContainerHeight = boxes.containerHeight + 4;
        var svgContainerWidth = boxes.containerWidth + 4;
        //$('#'+svgContainer).css({'top':svgContainerTop+'px', 'left':svgContainerLeft+'px','height':svgContainerHeight+'px', 'width':svgContainerWidth+'px','position':'absolute'}).svg();
        $('#' + svgContainer).css({
            'top': svgContainerTop + 'px',
            'left': svgContainerLeft + 'px',
            'width': svgContainerWidth + 'px',
            'position': 'absolute',
            'z-index': zIndex
        }).svg();

        var svg = $('#' + svgContainer).svg('get');
        var lineGroup = svg.group('line_' + id);
        svg.line(boxes.newLineStartX, boxes.newLineStartY, boxes.newLineEndX, boxes.newLineEndY, {
            stroke: lineStatusColor, strokeWidth: 5, class: 'itemElement_line', id: id + '_line'
        });

        //check if the rect should be drawn -> this is not neccessary on stateless lines for example
        if (drawRect) {
            //style for the inner rect div
            var rectStyle = {
                'position': 'absolute',
                'width': opt.rectWidth,
                'height': opt.rectHeight,
                'left': boxes.centerX,
                'top': boxes.centerY,
                'border': '1px solid #FFF',
                'border-radius': '5px'
            }

            var innerRectClass = '';
            if (link) {
                //link is true -> so it must be the View mode
                innerRectClass = 'elementHover';
            } else {
                //link is false -> so it must be the Edit mode
                innerRectClass = 'itemElement';
            }


            //inner rect (cannot be an SVG obj. -> jQuery mouseover doesnt work on this)
            $('#' + svgContainer).append($('<div>', {
                id: id + '_rect',
                class: innerRectClass
            })
                .data({'lineId': lineId})
                .addClass('lineHoverElement lineSVGContainer')
                .css(rectStyle));

            if (link) {
                $('#' + id + '_rect')
                    .attr({
                        'data-uuid': objData['currentUuid'],
                        'data-type': this.capitaliseFirstLetter(objData['currentType'])
                    })
            }

            //outer rect style
            var outerRectStyle = {
                'position': 'absolute',
                'width': opt.rectWidth + 2,
                'height': opt.rectHeight + 2,
                'left': boxes.centerX - 1,
                'top': boxes.centerY - 1,
                'border': '1px solid #000',
                'border-radius': '3px',
            };

            //outer rect
            $('#' + svgContainer).append($('<div>', {
                id: id + '_rectOuter',
                class: 'itemElement_rectOuter'
            }).css(outerRectStyle));

            if (link) {
                if (objData['currentLink'] != null) {
                    //link to the browser
                    $('<a>').attr({id: id + '_link', href: objData['currentLink'], target: '_parent'})
                        .css({'height': opt.rectHeight + 'px', 'width': opt.rectWidth + 'px'})
                        .appendTo($('#' + id + '_rect'));

                    $('<div>').css({
                        'height': opt.rectHeight + 'px',
                        'width': opt.rectWidth + 'px'
                    }).appendTo('#' + id + '_link');
                }
            }

            if (el2append != undefined) {
                $('#' + id + '_rect').append(el2append);
            }
        }
        //workaround for centering horizontal lines
        $('#' + svgContainer).children('svg').attr({'height': svgContainerHeight + 7 + 'px'});
    },


    calculateLineBoxCenterPosition: function (obj) {
        var returnObj = {}
        //calculate the center of the line for the rectangle
        if (obj.start['x'] > obj.end['x']) {
            //X-Axis from right to left
            var containerWidth = obj.start['x'] - obj.end['x'];
            var containerLeft = obj.end['x'];

            var centerX = ((containerWidth / 2) - obj.rectWidth / 2);
            var newLineStartX = containerWidth;
            //2px more otherwise the corners of line get cut off
            var newLineEndX = 2;
        } else {
            //X-Axis from left to right
            var containerWidth = obj.end['x'] - obj.start['x'];
            var containerLeft = obj.start['x'];

            var centerX = ((containerWidth / 2) - obj.rectWidth / 2);
            //2px more otherwise the corners of line get cut off
            var newLineStartX = 2;
            var newLineEndX = containerWidth;
        }

        if (obj.start['y'] > obj.end['y']) {
            //Y-Axis from bottom to top
            var containerHeight = obj.start['y'] - obj.end['y'];
            var containerTop = obj.end['y'];

            var centerY = ((containerHeight / 2) - obj.rectHeight / 2);
            var newLineStartY = containerHeight;
            //2px more otherwise the corners of line get cut off
            var newLineEndY = 2;
        } else {
            //Y-Axis from top to bottom
            var containerHeight = obj.end['y'] - obj.start['y'];
            var containerTop = obj.start['y'];

            var centerY = ((containerHeight / 2) - obj.rectHeight / 2);
            //2px more otherwise the corners of line get cut off
            var newLineStartY = 2;
            var newLineEndY = containerHeight;
        }

        returnObj = {
            containerHeight: containerHeight,
            containerWidth: containerWidth,
            containerTop: containerTop,
            containerLeft: containerLeft,
            newLineStartX: newLineStartX,
            newLineStartY: newLineStartY,
            newLineEndX: newLineEndX,
            newLineEndY: newLineEndY,
            centerX: centerX,
            centerY: centerY
        };
        return returnObj;
    },

    redrawLine: function (obj) {
        //called to redraw the line i.e. when the coordinates has been changed through the wizard
        this.deleteLine(obj.svgContainer);
        this.drawSVGLine(obj);
    },

    deleteLine: function (svgContainer) {
        //deletes the line including its SVG tag and the 2 div rectangles
        $('#' + svgContainer).children().remove();
        $('#' + svgContainer).removeClass('hasSVG');
    },

    capitaliseFirstLetter: function (string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    },
});