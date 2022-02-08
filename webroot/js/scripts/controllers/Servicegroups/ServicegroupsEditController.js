angular.module('openITCOCKPIT')
    .controller('ServicegroupsEditController', function($scope, $http, QueryStringService, $stateParams, $state, NotyService, RedirectService){


        $scope.post = {
            Servicegroup: {
                description: '',
                servicegroup_url: '',
                container: {
                    name: '',
                    parent_id: 0
                },
                services: {
                    _ids: []
                },
                servicetemplates: {
                    _ids: []
                }
            }
        };

        //$scope.id = QueryStringService.getCakeId();
        $scope.id = $stateParams.id;

        $scope.deleteUrl = "/servicegroups/delete/" + $scope.id + ".json?angular=true";
        $scope.successState = 'ServicegroupsIndex';

        $scope.init = true;
        $scope.load = function(){
            $http.get("/servicegroups/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post = result.data.servicegroup;


                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.loadContainers = function(){
            $http.get("/servicegroups/loadContainers.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.load();
            });
        };

        $scope.loadServices = function(searchString){
            if($scope.post.Servicegroup.container.parent_id == 0){
                return;
            }
            $scope.params = {
                'containerId': $scope.post.Servicegroup.container.parent_id,
                'filter': {
                    'servicename': searchString,
                },
                'selected': $scope.post.Servicegroup.services._ids
            };
            $http.post("/services/loadServicesByContainerIdCake4.json?angular=true",
                $scope.params
            ).then(function(result){
                $scope.services = result.data.services;
            });
        };

        $scope.loadServicetemplates = function(searchString){
            if($scope.post.Servicegroup.container.parent_id == 0){
                return;
            }
            $http.get("/servicegroups/loadServicetemplates.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.post.Servicegroup.container.parent_id,
                    'filter[Servicetemplates.name]': searchString,
                    'selected[]': $scope.post.Servicegroup.servicetemplates._ids
                }
            }).then(function(result){
                $scope.servicetemplates = result.data.servicetemplates;
            });
        };

        $scope.submit = function(){
            $http.post("/servicegroups/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('ServicegroupsEdit', {id: $scope.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('ServicegroupsIndex');

            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });

        };

        $scope.$watch('post.Servicegroup.container.parent_id', function(){
            if($scope.init){
                return;
            }
            $scope.loadServices('');
            $scope.loadServicetemplates('');
        }, true);

        //$scope.load();
        $scope.loadContainers();
    });
