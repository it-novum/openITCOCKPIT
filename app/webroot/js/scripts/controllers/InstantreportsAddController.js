angular.module('openITCOCKPIT')
    .controller('InstantreportsAddController', function($scope, $http){
        $scope.post = {
            Instantreport: {
                name: '',
                Hostgroups: [],
                Hosts: [],
                Servicegroups: [],
                Services: []
            }
        };

        $scope.types = {
            TYPE_HOSTGROUPS:    '1',
            TYPE_HOSTS:         '2',
            TYPE_SERVICEGROUPS: '3',
            TYPE_SERVICES:      '4'
        }

        $scope.init = true;
        $scope.post.Instantreport.type = $scope.types.TYPE_HOSTGROUPS; //select host groups as default value

        $scope.post.Instantreport.send_email = false;
        $scope.post.Instantreport.send_interval = '0';
        $scope.post.Instantreport.user_id = [];
        $scope.post.Instantreport.container_id = null;

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

        $scope.loadTimeperiods = function(searchString){
            $http.get("/timeperiods/loadTimeperiodsByContainerId.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.post.Instantreport.container_id,
                    //'filter[Timeperiod.name]': searchString,
                    //'selected[]': $scope.post.Instantreport.Timeperiod
                }
            }).then(function(result){
                $scope.timeperiods = result.data.timeperiods;
            });
        };

        $scope.loadHostgroups = function(searchString){
            if($scope.init){
                return;
            }
            if($scope.post.Instantreport.container_id) {
                $http.get("/hostgroups/loadHosgroupsByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Instantreport.container_id,
                        'filter[Container.name]': searchString,
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

        $scope.loadServicegroups = function(searchString){
            if($scope.init){
                return;
            }
            if($scope.post.Instantreport.container_id) {
                $http.get("/servicegroups/loadServicegroupsByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Instantreport.container_id,
                        'filter[Container.name]': searchString,
                        'selected[]': $scope.post.Instantreport.Servicegroup
                    }
                }).then(function (result) {
                    $scope.servicegroups = result.data.servicegroups;
                });
            }
        };

        $scope.loadUsers = function(searchString){
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
                window.location.href = '/instanreports/index';
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
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
                    break;
            }
    //      $scope.loadServices('');
            $scope.loadTimeperiods('');
            $scope.loadUsers('');
        }, true);

        $scope.$watch('post.Instantreport.type', function(){
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
                    break;
            }

        }, true);

        $scope.$watch('post.Instantreport.send_email', function(){
            if($scope.init){
                return;
            }
            if(!$scope.post.Instantreport.send_email){
                $scope.post.Instantreport.send_interval = '0';
                $scope.post.Instantreport.user_id = [];
            }
        }, true);

        $scope.load();
    });
