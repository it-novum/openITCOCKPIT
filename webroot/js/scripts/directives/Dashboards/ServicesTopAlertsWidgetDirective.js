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

angular.module('openITCOCKPIT').directive('servicesTopAlertsWidget', function($http, $state, $interval) {
    return {
        restrict: 'E',
        templateUrl: '/dashboards/servicesTopAlertsWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope) {
            $scope.error = false;
            $scope.interval = null;
            $scope.init = true;
            $scope.useScroll = true;
            $scope.scroll_interval = 30000;
            $scope.min_scroll_intervall = 5000;
            $scope.scroll = {};

            // ITC-3037
            $scope.readOnly = $scope.widget.isReadonly;

            var $widget = $('#widget-' + $scope.widget.id);
            $scope.all_notifications = [];

            $widget.on('resize', function(event, items) {
                hasResize();
            });
            $scope.listTimeout = null;

            $scope.currentPage = 1;
            $scope.bgClass = 'bg-danger';

            $scope.filter = {
                state: 'down',
                not_older_than: 24,
                not_older_than_unit: 'HOUR'
            };

            $scope.all_notifications = [];

            $scope.loadWidgetConfig = function() {
                $http.get("/dashboards/servicesTopAlertsWidget.json?angular=true&widgetId=" + $scope.widget.id, $scope.filter).then(function(result) {
                    $scope.filter.state = result.data.config.state;
                    $scope.filter.not_older_than = result.data.config.not_older_than;
                    $scope.filter.not_older_than_unit = result.data.config.not_older_than_unit;
                    $scope.useScroll = result.data.config.useScroll;
                    let scrollInterval = parseInt(result.data.config.scroll_interval);
                    $scope.scroll_interval = scrollInterval;

                    $scope.load();
                });
            };

            $scope.load = function(options) {
                $scope.getBgClass();
                options = options || {};
                options.save = options.save || false;


                var params = {
                    'angular': true,
                    'scroll': true,
                    'page': $scope.currentPage,
                    'limit': $scope.limit,
                    'filter[NotificationServices.state][]': [$scope.filter.state],
                    'filter[not_older_than]': $scope.getMinutes()
                };

                $http.get("/notifications/serviceTopNotifications.json", {
                    params: params
                }).then(function(result) {
                    $scope.all_notifications = result.data.all_notifications;
                    $scope.scroll = result.data.scroll;

                    if(options.save === true) {
                        $scope.saveServiceTopAlertWidget();
                    }
                    if($scope.init === true && $scope.scroll.hasNextPage) {
                        if($scope.scroll_interval < 5000) {
                            $scope.pauseScroll();
                        } else {
                            $scope.startScroll();
                        }
                    }

                    $scope.init = false;

                });
            };

            $scope.getBgClass = function() {
                switch($scope.filter.state) {
                    case 'ok':
                        $scope.bgClass = 'bg-success';
                        break;
                    case 'warning':
                        $scope.bgClass = 'bg-warning';
                        break;
                    case 'critical':
                        $scope.bgClass = 'bg-danger';
                        break;
                    case 'unknown':
                        $scope.bgClass = 'bg-secondary';
                        break;
                    default:
                        $scope.bgClass = 'bg-primary';
                        break;
                }
            };

            $scope.getMinutes = function() {
                switch($scope.filter.not_older_than_unit) {
                    case 'MINUTE':
                        return $scope.filter.not_older_than;

                    case 'HOUR':
                        return $scope.filter.not_older_than * 60;
                    case 'DAY':
                        return $scope.filter.not_older_than * 60 * 24;
                    default:
                        return $scope.filter.not_older_than;
                }

            };

            $scope.changepage = function(page) {
                if(page !== $scope.currentPage) {
                    $scope.currentPage = page;
                    $scope.load();
                }
            };
            $scope.loadServiceNotificationDetails = function(serviceId) {
                $state.go("NotificationsServiceNotification", {
                    id: serviceId,
                    state: $scope.filter.state
                });
            }


            var hasResize = function() {
                if($scope.listTimeout) {
                    clearTimeout($scope.listTimeout);
                }
                $scope.listTimeout = setTimeout(function() {
                    $scope.listTimeout = null;
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
                height = height - 45 - 25 - 10 - 47; //Unit: px
                var limit = Math.floor(height / 36); // 36px = table row height;
                if(limit <= 0) {
                    limit = 1;
                }
                return limit;
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
            };


            // Fire on page load
            $scope.limit = getLimit($widget.height());

            $scope.loadWidgetConfig();


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

            $scope.saveServiceTopAlertWidget = function() {
                if(typeof ( $scope.filter.not_older_than ) === 'undefined') {
                    $scope.error = true;
                    return;
                }
                $scope.error = false;
                $http.post("/dashboards/servicesTopAlertsWidget.json?angular=true",
                    {
                        Widget: {
                            id: $scope.widget.id
                        },
                        state: $scope.filter.state,
                        not_older_than: $scope.filter.not_older_than,
                        not_older_than_unit: $scope.filter.not_older_than_unit,
                        scroll_interval: $scope.scroll_interval,
                        useScroll: $scope.useScroll
                    }
                ).then(function(result) {
                    $scope.filter = result.data.config;
                    $scope.currentPage = 1;
                    $scope.load();
                    $scope.hideConfig();
                    if($scope.init === true) {
                        return true;
                    }
                    return true;
                });
            };

            $scope.$on('$destroy', function() {
                $scope.pauseScroll();
            });

        },


        link: function($scope, element, attr) {

        }
    };
});
