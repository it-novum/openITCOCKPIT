angular.module('openITCOCKPIT')
    .controller('ServicegroupsEditController', function($scope, $http, QueryStringService){


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

        $scope.id = QueryStringService.getCakeId();

        $scope.deleteUrl = "/servicegroups/delete/" + $scope.id + ".json?angular=true";
        $scope.sucessUrl = '/servicegroups/index';

        $scope.init = true;
        $scope.load = function(){
            $http.get("/servicegroups/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.servicegroup = result.data.servicegroup;

                var selectedServices = [];
                var selectedServicetemplates = [];
                var key;
                for(key in $scope.servicegroup.Service){
                    selectedServices.push(parseInt($scope.servicegroup.Service[key].id, 10));
                }
                for(key in $scope.servicegroup.Servicetemplate){
                    selectedServicetemplates.push(parseInt($scope.servicegroup.Servicetemplate[key].id, 10));
                }

                $scope.post.Servicegroup.Service = selectedServices;
                $scope.post.Servicegroup.Servicetemplate = selectedServicetemplates;
                $scope.post.Container.name = $scope.servicegroup.Container.name;
                $scope.post.Container.parent_id = parseInt($scope.servicegroup.Container.parent_id, 10);
                $scope.post.Servicegroup.description = $scope.servicegroup.Servicegroup.description;
                $scope.post.Servicegroup.servicegroup_url = $scope.servicegroup.Servicegroup.servicegroup_url;
                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 404){
                    window.location.href = '/angular/not_found';
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
                    'filter[Servicetemplate.name]': searchString,
                    'selected[]': $scope.post.Servicegroup.Servicetemplate
                }
            }).then(function(result){
                $scope.servicetemplates = result.data.servicetemplates;
            });
        };


        $scope.submit = function(){
            $http.post("/servicegroups/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                console.log('Data saved successfully');
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
        $scope.loadContainers();
    });
