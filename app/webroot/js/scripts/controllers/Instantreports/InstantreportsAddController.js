angular.module('openITCOCKPIT')
    .controller('InstantreportsAddController', function($scope, $state, $http, NotyService, RedirectService){
        $scope.types = {
            TYPE_HOSTGROUPS: '1',
            TYPE_HOSTS: '2',
            TYPE_SERVICEGROUPS: '3',
            TYPE_SERVICES: '4'
        };
        $scope.post = {
            Instantreport: {
                container_id: null,
                name: '',
                type: $scope.types.TYPE_HOSTGROUPS, // select host groups as default value
                timeperiod_id: '0',
                reflection: '1',
                summary: false,
                downtimes: false,
                send_email: false,
                send_interval: '1',
                evaluation: '2', //hosts and services
                Hostgroup: [],
                Host: [],
                Servicegroup: [],
                Service: [],
                User: []
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
                        'containerId': $scope.post.Instantreport.container_id
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
                        'selected[]': $scope.post.Instantreport.Host
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
                        'containerId': $scope.post.Instantreport.container_id
                    }
                }).then(function(result){
                    $scope.servicegroups = result.data.servicegroups;
                });
            }
        };

        $scope.loadServices = function(searchString){
            if($scope.post.Instantreport.container_id){
                $http.get("/services/loadServicesByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Instantreport.container_id,
                        'filter[Host.name]': searchString,
                        'filter[Service.servicename]': searchString,
                        'selected[]': $scope.post.Instantreport.Service
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
                    'selected[]': $scope.post.Instantreport.User
                }
            }).then(function(result){
                $scope.users = result.data.users;
            });
        };

        $scope.submit = function(){
            $http.post("/instantreports/add.json?angular=true",
                $scope.post
            ).then(function(result){
                NotyService.genericSuccess();
                RedirectService.redirectWithFallback('InstantreportsIndex');
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                    NotyService.genericError();
                }
            });
        };

        $scope.resetOnTypeChange = function(){
            $scope.post.Instantreport.Hostgroup = [];
            $scope.post.Instantreport.Host = [];
            $scope.post.Instantreport.Servicegroup = [];
            $scope.post.Instantreport.Service = [];
        };

        $scope.$watch('post.Instantreport.container_id', function(){
            if($scope.init){
                return;
            }
            switch($scope.post.Instantreport.type){
                case $scope.types.TYPE_HOSTGROUPS:
                    $scope.loadHostgroups('');
                    break;
                case $scope.types.TYPE_HOSTS:
                    $scope.loadHosts('');
                    break;
                case $scope.types.TYPE_SERVICEGROUPS:
                    $scope.loadServicegroups('');
                    break;
                case $scope.types.TYPE_SERVICES:
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
            $scope.resetOnTypeChange();
            switch($scope.post.Instantreport.type){
                case $scope.types.TYPE_HOSTGROUPS:
                    $scope.loadHostgroups('');
                    break;
                case $scope.types.TYPE_HOSTS:
                    $scope.loadHosts('');
                    break;
                case $scope.types.TYPE_SERVICEGROUPS:
                    $scope.loadServicegroups('');
                    break;
                case $scope.types.TYPE_SERVICES:
                    $scope.loadServices('');
                    break;
            }

        }, true);

        $scope.$watch('post.Instantreport.send_email', function(){
            if($scope.init){
                return;
            }
            if(!$scope.post.Instantreport.send_email){
                $scope.post.Instantreport.send_interval = '0';
                $scope.post.Instantreport.User = [];
            }
        }, true);

        $scope.load();
    });
