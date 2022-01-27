angular.module('openITCOCKPIT')
    .controller('InstantreportsAddController', function($scope, $state, $http, NotyService, RedirectService){
        $scope.post = {
            Instantreport: {
                container_id: null,
                name: null,
                type: 1, // 1 - host groups, 2 - hosts, 3 - service groups, 4 - services
                timeperiod_id: null,
                reflection: 1,// 1 - soft and hard states, 2 - only hard states
                summary: 0,
                downtimes: 0,
                send_email: 0,
                send_interval: 0, // 0 - NEVER
                evaluation: 2, //hosts and services
                hostgroups: {
                    _ids: []
                },
                hosts: {
                    _ids: []
                },
                servicegroups: {
                    _ids: []
                },
                services: {
                    _ids: []
                },
                users: {
                    _ids: []
                }
            }
        };

        $scope.init = true;

        $scope.load = function(){
            $http.get("/instantreports/loadContainers.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };

        $scope.loadTimeperiods = function(){
            $http.get("/timeperiods/loadTimeperiodsByContainerId.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.post.Instantreport.container_id
                }
            }).then(function(result){
                $scope.timeperiods = result.data.timeperiods;
            });
        };

        $scope.loadHostgroups = function(){
            if($scope.init){
                return;
            }
            if($scope.post.Instantreport.container_id){
                $http.get("/hostgroups/loadHosgroupsByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Instantreport.container_id,
                        'selected[]': $scope.post.Instantreport.hostgroups._ids,
                        'resolveContainerIds': true
                    }
                }).then(function(result){
                    $scope.hostgroups = result.data.hostgroups;
                });
            }
        };

        $scope.loadHosts = function(searchString){
            if($scope.post.Instantreport.container_id){
                $http.get("/hosts/loadHostsByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Instantreport.container_id,
                        'filter[Hosts.name]': searchString,
                        'selected[]': $scope.post.Instantreport.hosts._ids,
                        'resolveContainerIds': true
                    }
                }).then(function(result){
                    $scope.hosts = result.data.hosts;
                });
            }
        };

        $scope.loadServicegroups = function(){
            if($scope.init){
                return;
            }
            if($scope.post.Instantreport.container_id){
                $http.get("/servicegroups/loadServicegroupsByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Instantreport.container_id,
                        'selected[]': $scope.post.Instantreport.servicegroups._ids,
                        'resolveContainerIds': true

                    }
                }).then(function(result){
                    $scope.servicegroups = result.data.servicegroups;
                });
            }
        };

        $scope.loadServices = function(searchString){
            if($scope.post.Instantreport.container_id){
                $http.get("/services/loadServicesByStringCake4.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Instantreport.container_id,
                        'filter[servicename]': searchString,
                        'selected[]': $scope.post.Instantreport.services._ids,
                        'resolveContainerIds': true
                    }
                }).then(function(result){
                    $scope.services = result.data.services;
                });
            }
        };

        $scope.loadUsers = function(){
            $http.get("/users/loadUsersByContainerId.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.post.Instantreport.container_id,
                    'selected[]': $scope.post.Instantreport.users._ids
                }
            }).then(function(result){
                $scope.users = result.data.users;
            });
        };

        $scope.submit = function(){
            $http.post("/instantreports/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('InstantreportsEdit', {id: result.data.instantreport.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });
                RedirectService.redirectWithFallback('InstantreportsIndex');
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                    NotyService.genericError();
                }
            });
        };

        $scope.resetOnTypeChange = function(){
            $scope.post.Instantreport.hostgroups._ids = [];
            $scope.post.Instantreport.hosts._ids = [];
            $scope.post.Instantreport.servicegroups._ids = [];
            $scope.post.Instantreport.services._ids = [];
        };

        $scope.$watch('post.Instantreport.container_id', function(){
            if($scope.init){
                return;
            }
            switch($scope.post.Instantreport.type){
                case 1:
                    $scope.loadHostgroups('');
                    break;
                case 2:
                    $scope.loadHosts('');
                    break;
                case 3:
                    $scope.loadServicegroups('');
                    break;
                case 4:
                    $scope.loadServices('');
                    break;
            }
            $scope.loadTimeperiods('');
            $scope.loadUsers('');
        }, true);

        $scope.$watch('post.Instantreport.type', function(){
            if($scope.init){
                return;
            }
            if(!$scope.post.Instantreport.container_id){
                return;
            }
            $scope.resetOnTypeChange();
            switch($scope.post.Instantreport.type){
                case 1:
                    $scope.loadHostgroups('');
                    break;
                case 2:
                    $scope.loadHosts('');
                    break;
                case 3:
                    $scope.loadServicegroups('');
                    break;
                case 4:
                    $scope.loadServices('');
                    break;
            }
        }, true);

        $scope.$watch('post.Instantreport.send_email', function(){
            if($scope.init){
                return;
            }
            if(!$scope.post.Instantreport.send_email){
                $scope.post.Instantreport.send_interval = 0;
                $scope.post.Instantreport.users._ids = [];
            }else{
                $scope.post.Instantreport.send_interval = 1;
            }
        }, true);

        $scope.load();
    });
