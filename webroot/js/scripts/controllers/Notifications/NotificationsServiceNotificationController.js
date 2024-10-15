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

angular.module('openITCOCKPIT')
    .controller('NotificationsServiceNotificationController', function($scope, $http, $rootScope, $httpParamSerializer, SortService, QueryStringService, $stateParams) {

        SortService.setSort(QueryStringService.getValue('sort', 'NotificationServices.start_time'));
        SortService.setDirection(QueryStringService.getValue('direction', 'desc'));
        $scope.currentPage = 1;
        let state = QueryStringService.getValue('state', null);
        console.log($stateParams.state);

        $scope.id = $stateParams.id;
        $scope.useScroll = true;

        var now = new Date();

        /*** Filter Settings ***/
        var defaultFilter = function() {
            $scope.filter = {
                NotificationServices: {
                    state: {
                        ok: false,
                        warning: false,
                        critical: false,
                        unknown: false
                    },
                    output: ''
                },
                from: date('d.m.Y H:i', now.getTime() / 1000 - ( 3600 * 24 * 30 )),
                to: date('d.m.Y H:i', now.getTime() / 1000 + ( 3600 * 24 * 30 * 2 ))
            };
            var from = new Date(now.getTime() - ( 3600 * 24 * 30 * 1000 ));
            from.setSeconds(0);
            var to = new Date(now.getTime() + ( 3600 * 24 * 30 * 2 * 1000 ));
            to.setSeconds(0);
            $scope.from_time = from;
            $scope.to_time = to;
        };
        /*** Filter end ***/

        $scope.init = true;
        $scope.showFilter = false;

        $scope.serviceBrowserMenuConfig = {
            autoload: true,
            serviceId: $scope.id,
            includeServicestatus: true
        };

        $scope.load = function() {

            $http.get("/notifications/serviceNotification/" + $scope.id + ".json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[NotificationServices.output]': $scope.filter.NotificationServices.output,
                    'filter[NotificationServices.state][]': $rootScope.currentStateForApi($scope.filter.NotificationServices.state),
                    'filter[from]': $scope.filter.from,
                    'filter[to]': $scope.filter.to
                }
            }).then(function(result) {
                $scope.notifications = result.data.all_notifications;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;
            });
        };

        $scope.triggerFilter = function() {
            $scope.showFilter = !$scope.showFilter === true;
        };

        $scope.resetFilter = function() {
            defaultFilter();
        };


        $scope.changepage = function(page) {
            if(page !== $scope.currentPage) {
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.changeMode = function(val) {
            $scope.useScroll = val;
            $scope.load();
        };

        //Fire on page load
        defaultFilter();
        SortService.setCallback($scope.load);

        $scope.$watch('filter', function() {
            $scope.currentPage = 1;
            $scope.load();
        }, true);

        $scope.$watch('from_time', function(dateObject) {
            if(dateObject !== undefined && dateObject instanceof Date) {
                var dateString = date('d.m.Y H:i', dateObject.getTime() / 1000);
                $scope.filter.from = dateString;
            }
        });
        $scope.$watch('to_time', function(dateObject) {
            if(dateObject !== undefined && dateObject instanceof Date) {
                var dateString = date('d.m.Y H:i', dateObject.getTime() / 1000);
                $scope.filter.to = dateString;
            }
        });

    });
