angular.module('openITCOCKPIT')
    .controller('StatuspagesAddController', function($scope, $http, SudoService, $state, NotyService, RedirectService){
        $scope.post = {
            Statuspages: {
                containers: {
                    _ids: []
                },
                name: '',
                description: '',
                public: false,
                hosts: {
                    _ids: []
                },
                services: {
                    _ids: []
                },
                hostgroups: {
                    _ids: []
                },
                servicegroups: {
                    _ids: []
                }
            },

        };

        $scope.init = true;
        $scope.hasError = null;
        $scope.errors = {};

        $scope.loadContainers = function(){
            var params = {
                'angular': true
            };

            $http.get("/contacts/loadContainers.json", {
                params: params
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };


        $scope.loadHosts = function(searchString){
            $http.get("/hosts/loadHostsByString/1.json", {
                params: {
                    'angular': true,
                    'filter[Hosts.name]': searchString,
                    'selected[]': $scope.post.Statuspages.hosts.ids
                }
            }).then(function(result){
                $scope.hosts = result.data.hosts;
            });
        };

        $scope.loadServices = function(searchString){
            $http.get("/services/loadServicesByStringCake4/1.json", {
                params: {
                    'angular': true,
                    'filter[servicename]': searchString,
                    'selected[]': $scope.post.Statuspages.services.ids
                }
            }).then(function(result){
                $scope.services = result.data.services;
            });
        };

        $scope.loadHostgroups = function(searchString){
            $http.get("/hostgroups/loadHostgroupsByString/1.json", {
                params: {
                    'angular': true,
                    'filter[Containers.name]': searchString,
                    'selected[]': $scope.post.Statuspages.hostgroups.ids
                }
            }).then(function(result){
                $scope.hostgroups = result.data.hostgroups;
            });
        };

        $scope.loadServicegroups = function(searchString){
            $http.get("/servicegroups/loadServicegroupsByString/1.json", {
                params: {
                    'angular': true,
                    'filter[Containers.name]': searchString,
                    'selected[]': $scope.post.Statuspages.servicegroups.ids
                }
            }).then(function(result){
                $scope.servicegroups = result.data.servicegroups;
            });
        };


        $scope.submit = function(){
            $http.post("/statuspages/add.json?angular=true",
                $scope.post
            ).then(function(result){
                if(result.data.id != null){
                    $state.go('StatuspagesStepTwo', {
                        id: result.data.id
                    }).then(function(){
                        NotyService.scrollTop();
                    });
                }
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    NotyService.genericError();
                    $scope.errors = result.data.error;
                }
            });
        };
        $scope.loadContainers();
        $scope.loadHosts('');
        $scope.loadServices('');
        $scope.loadHostgroups('');
        $scope.loadServicegroups('');

    });
