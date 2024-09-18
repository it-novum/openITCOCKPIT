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

angular.module('openITCOCKPIT')
    .controller('EventlogsIndexController', function($scope, $http, SortService, QueryStringService, $stateParams, $httpParamSerializer) {

        SortService.setSort(QueryStringService.getStateValue($stateParams, 'sort', 'Eventlogs.id'));
        SortService.setDirection(QueryStringService.getStateValue($stateParams, 'direction', 'desc'));
        $scope.useScroll = true;
        $scope.currentPage = 1;

        $scope.logTypes = [];
        $scope.typeTranslations = [];
        $scope.typeIconClasses = [];

        /*** Filter Settings ***/
        var defaultFilter = function() {
            var now = new Date();

            $scope.filter = {
                name: '',
                user_email: '',
                Types: {
                    login: 1,
                    user_delete: 1,
                    user_password_change: 1
                },
                from: date('d.m.Y H:i', now.getTime() / 1000 - ( 3600 * 24 * 30 * 4 )),
                to: date('d.m.Y H:i', now.getTime() / 1000 + ( 3600 * 24 * 5 ))
            };
            var from = new Date(now.getTime() - ( 3600 * 24 * 30 * 4 * 1000 ));
            from.setSeconds(0);
            var to = new Date(now.getTime() + ( 3600 * 24 * 5 * 1000 ));
            to.setSeconds(0);
            $scope.from_time = from;
            $scope.to_time = to;
        };

        $scope.showFilter = false;
        $scope.init = true;

        $scope.load = function() {
            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'types[]': getTypesFilter(),
                'direction': SortService.getDirection(),
                'filter[Eventlogs.type][]': getTypesFilter(),
                'filter[name]': $scope.filter.name,
                'filter[user_email]': $scope.filter.user_email,
                'filter[from]': $scope.filter.from,
                'filter[to]': $scope.filter.to
            };

            $http.get("/eventlogs/index.json", {
                params: params
            }).then(function(result) {
                $scope.events = result.data.all_events;
                $scope.logTypes = result.data.logTypes;
                $scope.typeTranslations = result.data.typeTranslations;
                $scope.typeIconClasses = result.data.typeIconClasses;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;

            });
        };

        var getTypesFilter = function() {
            var selectedTypes = [];
            for(var typeName in $scope.filter.Types) {
                if($scope.filter.Types[typeName] === 1) {
                    selectedTypes.push(typeName);
                }
            }

            if(selectedTypes.length === 0) {
                for(var typeName in $scope.filter.Types) {
                    selectedTypes.push(typeName);
                }
            }

            return selectedTypes;
        };

        $scope.linkFor = function(format) {
            var baseUrl = '/eventlogs/listToPdf.pdf?';
            if(format === 'csv') {
                baseUrl = '/eventlogs/listToCsv?';
            }

            var params = {
                'angular': true,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'types[]': getTypesFilter(),
                'filter[Eventlogs.type][]': getTypesFilter(),
                'filter[name]': $scope.filter.name,
                'filter[user_email]': $scope.filter.user_email,
                'filter[from]': $scope.filter.from,
                'filter[to]': $scope.filter.to
            };

            return baseUrl + $httpParamSerializer(params);
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

        //Watch on filter change
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
