/*
 * Copyright (C) <2015>  <it-novum GmbH>
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

angular.module('openITCOCKPIT').directive('automapWidget', function($http, $rootScope, $interval) {
    return {
        restrict: 'E',
        templateUrl: '/automaps/automapWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope) {
            $scope.interval = null;
            $scope.init = true;
            $scope.currentPage = 1;
            $scope.useScroll = true;
            $scope.scroll_interval = 30000;
            $scope.min_scroll_intervall = 5000;
            $scope.limit = 25;
            $scope.onlyButtons = true;

            $scope.automap_id = null;


            $scope.automapTimeout = null;
            $scope.currentPage = 1;

            // ITC-3037
            $scope.readOnly = $scope.widget.isReadonly;
            var loadWidgetConfig = function() {
                $http.get("/automaps/automapWidget.json?angular=true&widgetId=" + $scope.widget.id).then(function(result) {
                    $scope.automap_id = result.data.config.automap_id;
                    $scope.limit = parseInt(result.data.config.limit, 10);
                    $scope.useScroll = result.data.config.useScroll;

                    var scrollInterval = parseInt(result.data.config.scroll_interval);
                    $scope.scroll_interval = scrollInterval;
                    if(scrollInterval < 5000) {
                        $scope.pauseScroll();
                    } else {
                        $scope.startScroll();
                    }
                    $scope.load();
                });
            };

            $scope.$on('$destroy', function() {
                $scope.pauseScroll();
            });


            $scope.load = function(options) {
                options = options || {};
                options.save = options.save || false;

                var params = {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'page': $scope.currentPage,
                    'limit': $scope.limit
                };
                if($scope.automap_id) {
                    $http.get("/automaps/view/" + $scope.automap_id + ".json", {
                        params: params
                    }).then(function(result) {
                        $scope.automap = result.data.automap;
                        $scope.servicesByHost = result.data.servicesByHost;

                        if($scope.automap.use_paginator) {
                            $scope.paging = result.data.paging;
                            $scope.scroll = result.data.scroll;
                        }

                        if(options.save === true) {
                            $scope.saveSettings(params);
                        }
                        $scope.init = false;
                    });
                }
            };

            $scope.loadAutomaps = function(searchString) {
                $http.get("/automaps/loadAutomapsByString.json", {
                    params: {
                        'angular': true,
                        'filter[Automaps.name]': searchString,
                        'selected[]': $scope.automap_id
                    }
                }).then(function(result) {
                    $scope.automaps = result.data.automaps;
                });
            };

            $scope.changepage = function(page) {
                if(page !== $scope.currentPage) {
                    $scope.currentPage = page;
                    $scope.load();
                }
            };


            $scope.startScroll = function() {
                $scope.pauseScroll();
                if(!$scope.useScroll && $scope.scroll_interval === 0) {
                    $scope.scroll_interval = $scope.min_scroll_intervall;
                }
                $scope.useScroll = true;

                $scope.interval = $interval(function() {
                    var page = $scope.currentPage;
                    if($scope.scroll.hasNextPage) {
                        page++;
                    } else {
                        page = 1;
                    }
                    $scope.changepage(page)
                }, $scope.scroll_interval);

            };

            $scope.pauseScroll = function() {
                if($scope.interval !== null) {
                    $interval.cancel($scope.interval);
                    $scope.interval = null;
                }
                $scope.useScroll = false;
            };

            var getTimeString = function() {
                return ( new Date($scope.scroll_interval * 60) ).toUTCString().match(/(\d\d:\d\d)/)[0] + " minutes";
            };


            $scope.hideConfig = function() {
                $scope.$broadcast('FLIP_EVENT_IN');
            };
            $scope.showConfig = function() {
                $scope.$broadcast('FLIP_EVENT_OUT');
                $scope.loadAutomaps('');
            };

            $scope.saveSettings = function() {
                var settings = {
                    'automap_id': $scope.automap_id,
                    'scroll_interval': $scope.scroll_interval,
                    'useScroll': $scope.useScroll,
                    'limit': $scope.limit
                };

                $http.post("/automaps/automapWidget.json?angular=true&widgetId=" + $scope.widget.id, settings).then(function(result) {
                    $scope.currentPage = 1;
                    loadWidgetConfig();
                    $scope.hideConfig();
                    if($scope.init === true) {
                        return true;
                    }
                    return true;
                });
            };


            /** Page load / widget get loaded **/
            loadWidgetConfig();

            $scope.$watch('scroll_interval', function(scrollInterval) {
                $scope.pagingTimeString = getTimeString();
                if($scope.init === true) {
                    return true;
                }
                $scope.pauseScroll();
                if(scrollInterval > 0) {
                    $scope.startScroll();
                }
                $scope.load({
                    save: true
                });
            }, true);
        },

        link: function($scope, element, attr) {

        }
    };
});
