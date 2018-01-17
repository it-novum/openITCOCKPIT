angular.module('openITCOCKPIT')
    .controller('ServicegroupsAddController', function($scope, $http){


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
            if($scope.post.Container.parent_id) {
                $http.get("/services/loadServicesByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Container.parent_id,
                        'filter[Host.name]': searchString,
                        'filter[Service.servicename]': searchString,
                        'selected[]': $scope.post.Servicegroup.Service
                    }
                }).then(function (result) {
                    $scope.services = result.data.services;
                });
            }

        };

        $scope.loadServicetemplates = function(searchString){
            $http.get("/servicetemplates/loadServicetemplatesByContainerId.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.post.Container.parent_id,
                    'filter[Servicetemplate.name]': searchString,
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
                window.location.href = '/servicegroups/index';
            }, function errorCallback(result){
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
