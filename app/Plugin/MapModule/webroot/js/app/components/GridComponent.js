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

App.Components.GridComponent = Frontend.Component.extend({

    gridContainerGroup: 'mapeditorGrid',

    drawGrid: function (svgContainerId, opt) {
        var opt = opt || {};
        var sizeX = opt.sizeX || 20;
        var sizeY = opt.sizeY || 20;
        var gridTextSize = opt.fontSize || '10px';
        var gridColor = opt.gridColor || '#DDD';

        svgContainerId = '#' + svgContainerId;

        if (sizeX < 0) {
            sizeX = 20;
            sizeY = 20;
        }

        sizeX = parseInt(sizeX);
        sizeY = parseInt(sizeY);
        $(svgContainerId).svg();
        var svg = $(svgContainerId).svg('get');

        var grid = svg.group(this.gridContainerGroup);

        var containerHeight = $(svgContainerId).height();
        var containerWidth = $(svgContainerId).width();

        var loopIterationsX = containerWidth / sizeX;
        var loopIterationsY = containerHeight / sizeY;

        //vertical Grid lines and Text
        for (var i = 1; i < parseInt(loopIterationsX) + 1; i++) {
            svg.line(grid, sizeX * i, 0, sizeX * i, containerHeight, {
                stroke: gridColor, strokeWidth: 0.35
            });

            svg.text(grid, sizeX * i, 10, Math.round(sizeX * i).toString(), {
                fontFamily: 'monospace, Courier New',
                fontSize: gridTextSize
            });
        }
        ;
        //horizontal Grid lines and Text
        for (var i = 1; i < parseInt(loopIterationsY) + 1; i++) {
            svg.line(grid, 0, sizeY * i, containerWidth, sizeY * i, {
                stroke: gridColor, strokeWidth: 0.35
            });

            svg.text(grid, 0, sizeY * i, Math.round(sizeY * i).toString(), {
                fontFamily: 'monospace, Courier New',
                fontSize: gridTextSize
            })
        }
        ;
    },

    refreshGrid: function (svgContainerId, opt) {
        //remove old grid 
        this.removeGrid(svgContainerId);
        //draw new grid
        this.drawGrid(svgContainerId, opt);
    },

    removeGrid: function (svgContainerId) {
        $('#' + svgContainerId).children().remove();
        $('#' + svgContainerId).removeClass('hasSVG');
    }
});