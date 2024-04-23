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

angular.module('openITCOCKPIT').directive('hostsStatusWidget', function($http, $rootScope, $interval) {
    return {
        restrict: 'E',
        templateUrl: '/dashboards/hostsStatusListWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope) {
            $scope.interval = null;
            $scope.init = true;
            $scope.useScroll = true;
            $scope.scroll_interval = 30000;
            $scope.min_scroll_intervall = 5000;

            // ITC-3037
            $scope.readOnly = $scope.widget.isReadonly;

            var $widget = $('#widget-' + $scope.widget.id);

            $widget.on('resize', function(event, items) {
                hasResize();
            });

            $scope.hostListTimeout = null;

            $scope.sort = 'Hoststatus.current_state';
            $scope.direction = 'desc';
            $scope.currentPage = 1;

            $scope.filter = {
                Hoststatus: {
                    current_state: {
                        up: 0,
                        down: 0,
                        unreachable: 0
                    },
                    acknowledged: false,
                    not_acknowledged: false,
                    in_downtime: false,
                    not_in_downtime: false,
                    output: ''
                },
                Host: {
                    name: '',
                    name_regex: false,
                    keywords: '',
                    not_keywords: ''
                }
            };

            $scope.loadWidgetConfig = function() {
                $http.get("/dashboards/hostsStatusListWidget.json?angular=true&widgetId=" + $scope.widget.id, $scope.filter).then(function(result) {
                    $scope.filter.Host = result.data.config.Host;
                    $('#HostTags-' + $scope.widget.id).tagsinput('add', $scope.filter.Host.keywords);
                    $('#HostExcludedTags-' + $scope.widget.id).tagsinput('add', $scope.filter.Host.not_keywords);
                    $scope.filter.Hoststatus = result.data.config.Hoststatus;
                    $scope.filter.Hoststatus.current_state.up = result.data.config.Hoststatus.current_state.up ? 1 : 0;
                    $scope.filter.Hoststatus.current_state.down = result.data.config.Hoststatus.current_state.down ? 1 : 0;
                    $scope.filter.Hoststatus.current_state.unreachable = result.data.config.Hoststatus.current_state.unreachable ? 1 : 0;
                    $scope.filter.Hoststatus.acknowledged = result.data.config.Hoststatus.acknowledged;
                    $scope.filter.Hoststatus.not_acknowledged = result.data.config.Hoststatus.not_acknowledged;
                    $scope.filter.Hoststatus.in_downtime = result.data.config.Hoststatus.in_downtime;
                    $scope.filter.Hoststatus.not_in_downtime = result.data.config.Hoststatus.not_in_downtime;
                    $scope.filter.Hoststatus.state_older_than = result.data.config.Hoststatus.state_older_than ? parseInt(result.data.config.Hoststatus.state_older_than, 10) : null;
                    $scope.filter.Host.name_regex = result.data.config.Host.name_regex;
                    $scope.direction = result.data.config.direction;
                    $scope.sort = result.data.config.sort;
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

                var hasBeenAcknowledged = '';
                var inDowntime = '';
                if($scope.filter.Hoststatus.acknowledged ^ $scope.filter.Hoststatus.not_acknowledged) {
                    hasBeenAcknowledged = $scope.filter.Hoststatus.acknowledged === true;
                }
                if($scope.filter.Hoststatus.in_downtime ^ $scope.filter.Hoststatus.not_in_downtime) {
                    inDowntime = $scope.filter.Hoststatus.in_downtime === true;
                }

                var params = {
                    'angular': true,
                    'scroll': true,
                    'sort': $scope.sort,
                    'page': $scope.currentPage,
                    'direction': $scope.direction,
                    'filter[Hosts.name]': $scope.filter.Host.name,
                    'filter[Hosts.name_regex]': $scope.filter.Host.name_regex,
                    'filter[Hosts.keywords][]': $scope.filter.Host.keywords.split(','),
                    'filter[Hosts.not_keywords][]': $scope.filter.Host.not_keywords.split(','),
                    'filter[Hoststatus.output]': $scope.filter.Hoststatus.output,
                    'filter[Hoststatus.current_state][]': $rootScope.currentStateForApi($scope.filter.Hoststatus.current_state),
                    'filter[Hoststatus.problem_has_been_acknowledged]': hasBeenAcknowledged,
                    'filter[Hoststatus.scheduled_downtime_depth]': inDowntime,
                    'filter[Hoststatus.last_state_change][]': [
                        $scope.filter.Hoststatus.state_older_than || null,
                        $scope.filter.Hoststatus.state_older_than_unit || null
                    ],
                    'limit': $scope.limit
                };

                $http.get("/hosts/index.json", {
                    params: params
                }).then(function(result) {
                    $scope.hosts = result.data.all_hosts;
                    $scope.scroll = result.data.scroll;

                    if(options.save === true) {
                        $scope.saveSettings(params);
                    }

                    $scope.init = false;
                });
            };

            $scope.getSortClass = function(field) {
                if(field === $scope.sort) {
                    if($scope.direction === 'asc') {
                        return 'fa-sort-asc';
                    }
                    return 'fa-sort-desc';
                }
                return 'fa-sort';
            };

            $scope.orderBy = function(field) {
                if(field !== $scope.sort) {
                    $scope.direction = 'asc';
                    $scope.sort = field;
                    $scope.load();
                    return;
                }

                if($scope.direction === 'asc') {
                    $scope.direction = 'desc';
                } else {
                    $scope.direction = 'asc';
                }
                $scope.load();
            };

            var hasResize = function() {
                if($scope.hostListTimeout) {
                    clearTimeout($scope.hostListTimeout);
                }
                $scope.hostListTimeout = setTimeout(function() {
                    $scope.hostListTimeout = null;
                    $scope.limit = getLimit($widget.height());
                    if($scope.limit <= 0) {
                        $scope.limit = 1;
                    }
                    $scope.load();
                }, 500);
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

            var getLimit = function(height) {
                height = height - 42 - 61 - 10 - 37; //Unit: px
                //                ^ Widget play/pause div
                //                     ^ Paginator
                //                          ^ Margin between header and table
                //                                ^ Table header

                var limit = Math.floor(height / 36); // 36px = table row height;
                if(limit <= 0) {
                    limit = 1;
                }
                return limit;
            };

            $scope.saveSettings = function() {
                var settings = $scope.filter;
                settings['scroll_interval'] = $scope.scroll_interval;
                settings['useScroll'] = $scope.useScroll;
                settings['sort'] = $scope.sort;
                settings['direction'] = $scope.direction;
                $http.post("/dashboards/hostsStatusListWidget.json?angular=true&widgetId=" + $scope.widget.id, settings).then(function(result) {
                    $scope.currentPage = 1;
                    $scope.loadWidgetConfig();
                    $scope.hideConfig();
                    if($scope.init === true) {
                        return true;
                    }
                    return true;
                });
            };

            var getTimeString = function() {
                return ( new Date($scope.scroll_interval * 60) ).toUTCString().match(/(\d\d:\d\d)/)[0] + " minutes";
            };

            $scope.changepage = function(page) {
                if(page !== $scope.currentPage) {
                    $scope.currentPage = page;
                    $scope.load();
                }
            };

            $scope.hideConfig = function() {
                $scope.$broadcast('FLIP_EVENT_IN');
            };
            $scope.showConfig = function() {
                $scope.$broadcast('FLIP_EVENT_OUT');
                $scope.loadWidgetConfig();
            };

            $scope.limit = getLimit($widget.height());

            $scope.loadWidgetConfig();

            jQuery(function() {
                $("input[data-role=tagsinput]").tagsinput();
            });

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

            $scope.$watch('[sort, direction]', function() {
                if($scope.init === true) {
                    return true;
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
