angular.module('openITCOCKPIT')
    .controller('ServicegroupsAddController', function($scope, $http, $state, NotyService){


        $scope.post = {
            Container: {
                name: '',
                parent_id: 0
            },
            Servicegroup: {
                description: '',
                servicegroup_url: '',
                Service: [],
                Servicetemplate: []
            }
        };

        $scope.init = true;
        $scope.load = function(){
            $http.get("/servicegroups/loadContainers.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };

        $scope.loadServices = function(searchString){
            if($scope.post.Container.parent_id){
                $http.get("/services/loadServicesByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Container.parent_id,
                        'filter[Host.name]': searchString,
                        'filter[Service.servicename]': searchString,
                        'selected[]': $scope.post.Servicegroup.Service
                    }
                }).then(function(result){
                    $scope.services = result.data.services;
                });
            }

        };

        $scope.loadServicetemplates = function(searchString){
            $http.get("/servicetemplates/loadServicetemplatesByContainerId.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.post.Container.parent_id,
                    'filter[Servicetemplates.name]': searchString,
                    'selected[]': $scope.post.Servicegroup.Servicetemplate
                }
            }).then(function(result){
                $scope.servicetemplates = result.data.servicetemplates;
            });
        };

        $scope.submit = function(){
            $http.post("/servicegroups/add.json?angular=true",
                $scope.post
            ).then(function(result){
                NotyService.genericSuccess();
                $state.go('ServicegroupsIndex');

                console.log('Data saved successfully');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };


        $scope.$watch('post.Container.parent_id', function(){
            if($scope.init){
                return;
            }
            $scope.loadServices('');
            $scope.loadServicetemplates('');
        }, true);

        $scope.load();
    });
