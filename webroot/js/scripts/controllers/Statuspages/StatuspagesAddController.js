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

            $http.get("/statuspages/loadContainers.json", {
                params: params
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };


        $scope.loadHosts = function(searchString){
            $http.get("/statuspages/loadHostsByContainerIds.json", {
                params: {
                    'angular': true,
                    'containerIds[]': $scope.post.Statuspages.containers._ids,
                    'filter[Hosts.name]': searchString,
                    'selected[]': $scope.post.Statuspages.hosts.ids
                }
            }).then(function(result){
                $scope.hosts = result.data.hosts;
            });
        };

        $scope.loadServices = function(searchString){
            $http.get("/statuspages/loadServicesByContainerIds.json", {
                params: {
                    'angular': true,
                    'containerIds[]': $scope.post.Statuspages.containers._ids,
                    'filter': {
                        'servicename': searchString,
                    },
                    'selected': $scope.post.Statuspages.services.ids,
                }
            }).then(function(result){
                $scope.services = result.data.services;
            });
        };

        $scope.loadHostgroups = function(searchString){
            $http.get("/statuspages/loadHostgroupsByContainerIds.json", {
                params: {
                    'angular': true,
                    'filter[Containers.name]': searchString,
                    'selected[]': $scope.post.Statuspages.hostgroups.ids,
                    'containerIds[]': $scope.post.Statuspages.containers._ids,
                }
            }).then(function(result){
                $scope.hostgroups = result.data.hostgroups;
            });
        };

        $scope.loadServicegroups = function(searchString){
            $http.get("/statuspages/loadServicegroupsByContainerIds.json", {
                params: {
                    'angular': true,
                    'filter[Containers.name]': searchString,
                    'selected[]': $scope.post.Statuspages.servicegroups.ids,
                    'containerIds[]': $scope.post.Statuspages.containers._ids
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
        $scope.$watch('post.Statuspages.containers._ids', function(){
            if($scope.post.Statuspages.containers._ids.length > 0){
                $scope.loadHosts('');
                $scope.loadServices('');
                $scope.loadHostgroups('');
                $scope.loadServicegroups('');
            }
        }, true);

    });
