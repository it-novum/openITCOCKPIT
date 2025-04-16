/*
 * Copyright (C) <2015-present>  <it-novum GmbH>
 *
 * This file is dual licensed
 *
 * 1.
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, version 3 of the License.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * 2.
 *     If you purchased an openITCOCKPIT Enterprise Edition you can use this file
 *     under the terms of the openITCOCKPIT Enterprise Edition license agreement.
 *     License agreement and license key will be shipped with the order
 *     confirmation.
 */

angular.module('openITCOCKPIT').directive('popoverGraphDirective', function($http, UuidService) {
    return {
        restrict: 'E',
        templateUrl: '/angular/popover_graph.html', // This template is also used by PopoverPrometheusGraphDirective

        controller: function($scope) {
            var startTimestamp = new Date().getTime();

            $scope.popoverOffset = {
                relativeTop: 0,
                relativeLeft: 0,
                absoluteTop: 0,
                absoluteLeft: 0
            };

            $scope.popoverPerfdata = {};
            $scope.popoverTimer = null;
            $scope.graphPopoverId = UuidService.v4();

            $scope.doLoadGraph = function(hostUuid, serviceUuid) {
                var serverTime = new Date($scope.timezone.server_time_iso);
                var compareTimestamp = new Date().getTime();
                var diffFromStartToNow = parseInt(compareTimestamp - startTimestamp, 10);

                graphEnd = Math.floor(( serverTime.getTime() + diffFromStartToNow ) / 1000);
                graphStart = graphEnd - ( 3600 * 4 );

                $http.get('/Graphgenerators/getPerfdataByUuid.json', {
                    params: {
                        angular: true,
                        host_uuid: hostUuid,
                        service_uuid: serviceUuid,
                        start: graphStart,
                        end: graphEnd,
                        jsTimestamp: 0
                    }
                }).then(function(result) {
                    $scope.isLoadingGraph = false;
                    $scope.popoverPerfdata = result.data.performance_data;

                    // Give the browser a few ms to render the template
                    setTimeout(renderGraphs, 100);
                });
            };

            $scope.placePopoverGraph = function() {
                // Do we need this?
                //var currentScrollPosition = $(window).scrollTop();

                var margin = 15;
                var $popupGraphContainer = $('#serviceGraphContainer-' + $scope.graphPopoverId);
                var popupGraphContainerHeight = $popupGraphContainer.height();
                if(popupGraphContainerHeight < 272) {
                    // The popupGraphContainerHeight is at least 272px in height.
                    popupGraphContainerHeight = 272;
                }


                var absoluteBottomPositionOfPopoverGraphContainer = $scope.popoverOffset.absoluteTop + margin + popupGraphContainerHeight;

                //if(($scope.popoverOffset.relativeTop - currentScrollPosition + margin + popupGraphContainerHeight) > $(window).innerHeight()){
                if(absoluteBottomPositionOfPopoverGraphContainer > $(window).innerHeight()) {
                    //There is no space in the window for the popup, we need to place it above the mouse cursor
                    var marginTop = parseInt($scope.popoverOffset.relativeTop - popupGraphContainerHeight - margin + 10);
                    $popupGraphContainer.css({
                        'top': ( marginTop > 1 ) ? marginTop : 1,
                        'left': parseInt($scope.popoverOffset.relativeLeft + margin),
                        'padding': '6px'
                    });
                } else {
                    //Default Popup
                    $popupGraphContainer.css({
                        'top': parseInt($scope.popoverOffset.relativeTop + margin),
                        'left': parseInt($scope.popoverOffset.relativeLeft + margin),
                        'padding': '6px'
                    });
                }
            };

            var renderGraphs = function() {
                for(var index in $scope.popoverPerfdata) {
                    if(index > 3) {
                        //Only render 4 gauges in popover...
                        continue;
                    }

                    var uPlotGraphDefaultsObj = new uPlotGraphDefaults();

                    // https://github.com/leeoniya/uPlot/tree/master/docs#data-format
                    var data = [];
                    var xData = []
                    var yData = []
                    for(var timestamp in $scope.popoverPerfdata[index].data) {
                        xData.push(timestamp); // Timestamps
                        yData.push($scope.popoverPerfdata[index].data[timestamp]); // values
                    }
                    data.push(xData);
                    data.push(yData);

                    //Render Chart
                    var title = $scope.popoverPerfdata[index].datasource.name;
                    if(title.length > 80) {
                        title = title.substring(0, 80);
                        title += '...';
                    }

                    var $elm = $('#serviceGraphUPlot-' + $scope.graphPopoverId + '-' + index);

                    var colors = uPlotGraphDefaultsObj.getColorByIndex(index);
                    var options = uPlotGraphDefaultsObj.getDefaultOptions({
                        unit: $scope.popoverPerfdata[index].datasource.unit,
                        showLegend: false,
                        timezone: $scope.timezone.user_timezone,
                        lineWidth: 2,
                        thresholds: {
                            show: true,
                            warning: parseFloat($scope.popoverPerfdata[index].datasource.warn),
                            critical: parseFloat($scope.popoverPerfdata[index].datasource.crit)
                        },
                        // X-Axis min / max
                        start: graphStart,
                        end: graphEnd,
                        //Fallback if no thresholds exists
                        strokeColor: colors.stroke,
                        fillColor: colors.fill,
                        YAxisLabelLength: 100
                    });
                    options.height = $elm.height() - 25; // 27px for headline
                    options.width = $elm.width();
                    //options.title = title;
                    options.title = false;

                    if(document.getElementById('serviceGraphUPlot-' + $scope.graphPopoverId + '-' + index) && !$scope.mouseout) {
                        try {
                            var elm = document.getElementById('serviceGraphUPlot-' + $scope.graphPopoverId + '-' + index);

                            console.log(JSON.stringify(options));

                            self.plot = new uPlot(options, data, elm);
                        } catch(e) {
                            console.error(e);
                        }
                    }
                }
                $scope.placePopoverGraph();
            };

        },

        link: function($scope, element, attr) {
            $scope.mouseenter = function($event, hostUuid, serviceUuid) {
                if($scope.popoverTimer === null) {
                    $scope.popoverTimer = setTimeout(function() {
                        $scope.mouseout = false;
                        $scope.isLoadingGraph = true;

                        var position = $event.target.getBoundingClientRect();
                        var offset = {
                            relativeTop: $event.relatedTarget.offsetTop + 40,
                            relativeLeft: $event.relatedTarget.offsetLeft + 40,
                            absoluteTop: position.top,
                            absoluteLeft: position.left
                        };

                        if($event.relatedTarget.offsetParent && $event.relatedTarget.offsetParent.offsetTop) {
                            offset.relativeTop += $event.relatedTarget.offsetParent.offsetTop;
                        }
                        $scope.popoverOffset = offset;

                        $scope.placePopoverGraph();

                        var $popupGraphContainer = $('#serviceGraphContainer-' + $scope.graphPopoverId);
                        $popupGraphContainer.show();
                        $scope.doLoadGraph(hostUuid, serviceUuid);
                        $scope.popoverTimer = null;
                    }, 150);
                }


            };

            $scope.mouseleave = function() {
                $scope.mouseout = true;

                if($scope.popoverTimer !== null) {
                    clearTimeout($scope.popoverTimer);
                    $scope.popoverTimer = null;
                }

                $('#serviceGraphContainer-' + $scope.graphPopoverId).hide();
            };

        }
    };
});
