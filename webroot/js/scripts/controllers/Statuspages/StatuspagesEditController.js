angular.module('openITCOCKPIT')
    .controller('StatuspagesEditController', function($scope, $http, SudoService, $state, $stateParams, NotyService){
        $scope.id = $stateParams.id;

        $scope.post = {
            Statuspages: {
                containers: {
                    _ids: []
                },
                name: '',
                description: '',
                public: false,
                show_comments: false,
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

        $scope.loadStatuspage = function(){
            var params = {
                'angular': true
            };

            $http.get("/statuspages/edit/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.post.Statuspages = result.data.Statuspage;
                $scope.post.Statuspages.public = +result.data.Statuspage.public;
                $scope.post.Statuspages.show_comments = +result.data.Statuspage.show_comments;
                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
            $scope.loadContainers();
        };

        $scope.loadContainers = function(){
            var params = {
                'angular': true
            };

            $http.get("/containers/loadContainersForAngular.json", {
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
                    'selected[]': $scope.post.Statuspages.services.ids
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
                    'containerIds[]': $scope.post.Statuspages.containers._ids
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
            $http.post("/statuspages/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                $state.go('StatuspagesStepTwo', {
                    id: $scope.id
                }).then(function(){
                    NotyService.scrollTop();
                });
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    NotyService.genericError();
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.loadStatuspage();

        $scope.$watch('post.Statuspages.containers._ids', function(){
            if($scope.init){
                return;
            }
            $scope.loadHosts('');
            $scope.loadServices('');
            $scope.loadHostgroups('');
            $scope.loadServicegroups('');
        }, true);
    });
