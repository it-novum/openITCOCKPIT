angular.module('openITCOCKPIT')
    .controller('InstantreportsEditController', function($scope, $http, QueryStringService){
        $scope.types = {
            TYPE_HOSTGROUPS:    '1',
            TYPE_HOSTS:         '2',
            TYPE_SERVICEGROUPS: '3',
            TYPE_SERVICES:      '4'
        };

        $scope.post = {
            Instantreport: {
                container_id: null,
                name: '',
                type: $scope.types.TYPE_HOSTGROUPS, // select host groups as default value
                timeperiod_id: '0',
                reflection: '1',
                summary:false,
                downtimes: false,
                send_email:false,
                send_interval:'1',
                evaluation: '2', //hosts and services
                Hostgroup: [],
                Host: [],
                Servicegroup: [],
                Service: [],
                User: []
            }
        };

        $scope.id = QueryStringService.getCakeId();

        $scope.init = true;
        $scope.sucessUrl = '/instantreports/index';

        $scope.load = function() {
            $http.get("/instantreports/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function (result) {
                var instantreport = result.data.instantreport;

                var selectedHostgroups = [];
                var selectedServicegroups = [];
                var selectedHosts = [];
                var selectedServices = [];
                var selectedUsers = [];

                var key;
                for (key in instantreport.Hostgroup) {
                    selectedHostgroups.push(parseInt(instantreport.Hostgroup[key].id, 10));
                }
                for (key in instantreport.Servicegroup) {
                    selectedServicegroups.push(parseInt(instantreport.Servicegroup[key].id, 10));
                }
                for (key in instantreport.Host) {
                    selectedHosts.push(parseInt(instantreport.Host[key].id, 10));
                }
                for (key in instantreport.Service) {
                    selectedServices.push(instantreport.Service[key].id);
                }
                for (key in instantreport.User) {
                    selectedUsers.push(parseInt(instantreport.User[key].id, 10));
                }
                $scope.post.Instantreport.Hostgroup = selectedHostgroups;
                $scope.post.Instantreport.Servicegroup = selectedServicegroups;
                $scope.post.Instantreport.Host = selectedHosts;
                $scope.post.Instantreport.Service = selectedServices;
                $scope.post.Instantreport.User = selectedUsers;
                $scope.post.Instantreport.container_id = parseInt(instantreport.Instantreport.container_id, 10);
                $scope.post.Instantreport.name = instantreport.Instantreport.name;
                $scope.post.Instantreport.type = instantreport.Instantreport.type;
                $scope.post.Instantreport.timeperiod_id = parseInt(instantreport.Instantreport.timeperiod_id, 10);
                $scope.post.Instantreport.reflection = instantreport.Instantreport.reflection;
                $scope.post.Instantreport.summary = parseInt(instantreport.Instantreport.summary, 10) === 1;
                $scope.post.Instantreport.downtimes = parseInt(instantreport.Instantreport.downtimes, 10) === 1;
                $scope.post.Instantreport.send_email = parseInt(instantreport.Instantreport.send_email, 10) === 1;
                $scope.post.Instantreport.send_interval = instantreport.Instantreport.send_interval;
                $scope.post.Instantreport.evaluation = instantreport.Instantreport.evaluation;
                $scope.init = false;
            }, function errorCallback(result) {
                if (result.status === 404) {
                    window.location.href = '/angular/not_found';
                }
            });
        };

        $scope.loadContainers = function(){
            $http.get("/instantreports/loadContainers.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.load();
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
            if($scope.post.Instantreport.container_id) {
                $http.get("/hostgroups/loadHosgroupsByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Instantreport.container_id,
                        'selected[]': $scope.post.Instantreport.Hostgroup
                    }
                }).then(function (result) {
                    $scope.hostgroups = result.data.hostgroups;
                });
            }
        };

        $scope.loadHosts = function(searchString){
            if($scope.post.Instantreport.container_id) {
                $http.get("/hosts/loadHostsByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Instantreport.container_id,
                        'filter[Host.name]': searchString,
                        'selected[]': $scope.post.Instantreport.Host
                    }
                }).then(function (result) {
                    $scope.hosts = result.data.hosts;
                });
            }
        };

        $scope.loadServicegroups = function(){
            if($scope.init){
                return;
            }
            if($scope.post.Instantreport.container_id) {
                $http.get("/servicegroups/loadServicegroupsByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Instantreport.container_id,
                        'selected[]': $scope.post.Instantreport.Servicegroup
                    }
                }).then(function (result) {
                    $scope.servicegroups = result.data.servicegroups;
                });
            }
        };

        $scope.loadServices = function(searchString){
            if($scope.post.Instantreport.container_id) {
                $http.get("/services/loadServicesByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Instantreport.container_id,
                        'filter[Host.name]': searchString,
                        'filter[Service.servicename]': searchString,
                        'selected[]': $scope.post.Instantreport.Service
                    }
                }).then(function (result) {
                    $scope.services = result.data.services;
                });
            }

        };

        $scope.loadUsers = function(){
            $http.get("/users/loadUsersByContainerId.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.post.Instantreport.container_id
                }
            }).then(function(result){
                $scope.users = result.data.users;
            });
        };

        $scope.submit = function(){
            console.log($scope.post);
            $http.post("/instantreports/edit/"+$scope.id+".json?angular=true",
                $scope.post
            ).then(function(result){
                window.location.href = '/instantreports/index';
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
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

        $scope.changeType = function(){
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

        };

        $scope.$watch('post.Instantreport.send_email', function(){
            if($scope.init){
                return;
            }
            if(!$scope.post.Instantreport.send_email){
                $scope.post.Instantreport.send_interval = '0';
                $scope.post.Instantreport.User = [];
            }
        }, true);

        $scope.loadContainers();
    });
