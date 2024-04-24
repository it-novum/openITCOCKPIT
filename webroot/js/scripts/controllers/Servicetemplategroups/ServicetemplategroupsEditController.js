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
    .controller('ServicetemplategroupsEditController', function($scope, $http, SudoService, $state, $stateParams, NotyService, RedirectService) {

        $scope.id = $stateParams.id;

        $scope.init = true;


        $scope.loadContainers = function() {
            var params = {
                'angular': true
            };

            $http.get("/servicetemplategroups/loadContainers.json", {
                params: params
            }).then(function(result) {
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };

        $scope.loadServicetemplategroup = function() {
            var params = {
                'angular': true
            };

            $http.get("/servicetemplategroups/edit/" + $scope.id + ".json", {
                params: params
            }).then(function(result) {
                $scope.post = result.data.servicetemplategroup;
                $scope.init = false;
            }, function errorCallback(result) {
                if(result.status === 403) {
                    $state.go('403');
                }

                if(result.status === 404) {
                    $state.go('404');
                }
            });
        };

        $scope.loadServicetemplates = function(searchString) {
            var containerId = $scope.post.Servicetemplategroup.container.parent_id;

            //May be triggered by watch from "Create another"
            if(containerId === 0) {
                return;
            }

            $http.get("/servicetemplategroups/loadServicetemplatesByContainerId.json", {
                params: {
                    'angular': true,
                    'containerId': containerId,
                    'filter[Servicetemplates.template_name]': searchString,
                    'selected[]': $scope.post.Servicetemplategroup.servicetemplates._ids
                }
            }).then(function(result) {
                $scope.servicetemplates = result.data.servicetemplates;
            });
        };

        $scope.submit = function() {
            $http.post("/servicetemplategroups/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result) {
                var url = $state.href('ServicetemplategroupsEdit', {id: $scope.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('ServicetemplategroupsIndex');

                console.log('Data saved successfully');
            }, function errorCallback(result) {

                NotyService.genericError();

                if(result.data.hasOwnProperty('error')) {
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.loadContainers();
        $scope.loadServicetemplategroup();

        $scope.$watch('post.Servicetemplategroup.container.parent_id', function() {
            if($scope.init) {
                return;
            }
            $scope.loadServicetemplates('');
        }, true);

    });
