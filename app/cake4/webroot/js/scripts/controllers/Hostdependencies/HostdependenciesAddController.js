angular.module('openITCOCKPIT')
    .controller('HostdependenciesAddController', function($scope, $http, $state, $stateParams, $location, NotyService, RedirectService){
        $scope.init = true;
        $scope.id = $stateParams.id;
        $scope.post = {
            Hostdependency: {
                container_id: null,
                inherits_parent: 0,
                timeperiod_id: null,
                execution_fail_on_up: 1,
                execution_fail_on_down: 1,
                execution_fail_on_unreachable: 1,
                execution_fail_on_pending: 1,
                execution_none: 0,
                notification_fail_on_up: 1,
                notification_fail_on_down: 1,
                notification_fail_on_unreachable: 1,
                notification_fail_on_pending: 1,
                notification_none: 0,
                hosts: {
                    _ids: []
                },
                hosts_dependent: {
                    _ids: []
                },
                hostgroups: {
                    _ids: []
                },
                hostgroups_dependent: {
                    _ids: []
                }
            }
        };
        $scope.containers = {};

        $('#infoButtonNotificationOptions').popover({
            boundary: 'window',
            trigger: 'hover',
            placement: 'left'
        });

        $('#infoButtonExecutionOptions').popover({
            boundary: 'window',
            trigger: 'hover',
            placement: 'left'
        });

        $scope.loadContainer = function(){
            var params = {
                'angular': true
            };
            $http.get("/hostdependencies/loadContainers.json", {
                params: params
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };

        $scope.loadElementsByContainerId = function(){
            $http.get("/hostdependencies/loadElementsByContainerId/" + $scope.post.Hostdependency.container_id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.hosts = result.data.hosts;
                $scope.hosts_dependent = result.data.hostsDependent;
                $scope.hostgroups = result.data.hostgroups;
                $scope.hostgroups_dependent = result.data.hostgroupsDependent;
                $scope.timeperiods = result.data.timeperiods;
                $scope.processChosenHosts();
                $scope.processChosenDependentHosts();
                $scope.processChosenHostgroups();
                $scope.processChosenDependentHostgroups();
            });
        };

        $scope.loadHosts = function(searchString){
            if($scope.post.Hostdependency.container_id != null){
                $http.get("/hosts/loadHostsByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Hostdependency.container_id,
                        'filter[Hosts.name]': searchString,
                        'selected[]': $scope.post.Hostdependency.hosts._ids
                    }
                }).then(function(result){
                    $scope.hosts = result.data.hosts;
                });
            }
        };

        $scope.loadDependentHosts = function(searchString){
            if($scope.post.Hostdependency.container_id != null){
                $http.get("/hosts/loadHostsByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Hostdependency.container_id,
                        'filter[Hosts.name]': searchString,
                        'selected[]': $scope.post.Hostdependency.hosts_dependent._ids
                    }
                }).then(function(result){
                    $scope.hosts_dependent = result.data.hosts;
                });
            }
        };

        $scope.submit = function(){
            console.log($scope.post);
            $http.post("/hostdependencies/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var hostdependencyEditUrl = $state.href('HostdependenciesEdit', {id: $scope.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + hostdependencyEditUrl + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('HostdependenciesIndex');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };


        $scope.processChosenHosts = function(){
            for(var key in $scope.hosts){
                if(in_array($scope.hosts[key].key, $scope.post.Hostdependency.hosts_dependent._ids)){
                    $scope.hosts[key].disabled = true;
                }else{
                    $scope.hosts[key].disabled = false;
                }
            }
        };

        $scope.processChosenDependentHosts = function(){
            for(var key in $scope.hosts_dependent){
                if(in_array($scope.hosts_dependent[key].key, $scope.post.Hostdependency.hosts._ids)){
                    $scope.hosts_dependent[key].disabled = true;
                }else{
                    $scope.hosts_dependent[key].disabled = false;
                }
            }
        };

        $scope.processChosenHostgroups = function(){
            for(var key in $scope.hostgroups){
                if(in_array($scope.hostgroups[key].key, $scope.post.Hostdependency.hostgroups_dependent._ids)){
                    $scope.hostgroups[key].disabled = true;
                }else{
                    $scope.hostgroups[key].disabled = false;
                }
            }
        };

        $scope.processChosenDependentHostgroups = function(){
            for(var key in $scope.hostgroups_dependent){
                if(in_array($scope.hostgroups_dependent[key].key, $scope.post.Hostdependency.hostgroups._ids)){
                    $scope.hostgroups_dependent[key].disabled = true;
                }else{
                    $scope.hostgroups_dependent[key].disabled = false;
                }
            }
        };


        $scope.$watch('post.Hostdependency.container_id', function(){
            if($scope.post.Hostdependency.container_id != null){
                $scope.loadElementsByContainerId();
            }
        }, true);

        $scope.$watch('post.Hostdependency.hosts._ids', function(){
            if($scope.init){
                return;
            }
            $scope.processChosenDependentHosts();
        }, true);

        $scope.$watch('post.Hostdependency.hosts_dependent._ids', function(){
            if($scope.init){
                return;
            }
            $scope.processChosenHosts();
        }, true);

        $scope.$watch('post.Hostdependency.hostgroups._ids', function(){
            if($scope.init){
                return;
            }
            $scope.processChosenDependentHostgroups();
        }, true);

        $scope.$watch('post.Hostdependency.hostgroups_dependent._ids', function(){
            if($scope.init){
                return;
            }
            $scope.processChosenHostgroups();
        }, true);


        $scope.$watchGroup([
            'post.Hostdependency.execution_fail_on_up',
            'post.Hostdependency.execution_fail_on_down',
            'post.Hostdependency.execution_fail_on_unreachable',
            'post.Hostdependency.execution_fail_on_pending'
        ], function(){
            if($scope.init){
                return;
            }
            if($scope.post.Hostdependency.execution_none === 0){
                return;
            }
            if($scope.post.Hostdependency.execution_fail_on_up |
                $scope.post.Hostdependency.execution_fail_on_down |
                $scope.post.Hostdependency.execution_fail_on_unreachable |
                $scope.post.Hostdependency.execution_fail_on_pending
            ){
                $scope.post.Hostdependency.execution_none = 0;
            }

        }, true);

        $scope.$watch('post.Hostdependency.execution_none', function(){
            if($scope.init){
                return;
            }
            if($scope.post.Hostdependency.execution_none === 0){
                return;
            }
            $scope.post.Hostdependency.execution_fail_on_up = 0;
            $scope.post.Hostdependency.execution_fail_on_down = 0;
            $scope.post.Hostdependency.execution_fail_on_unreachable = 0;
            $scope.post.Hostdependency.execution_fail_on_pending = 0;
        }, true);


        $scope.$watchGroup([
            'post.Hostdependency.notification_fail_on_up',
            'post.Hostdependency.notification_fail_on_down',
            'post.Hostdependency.notification_fail_on_unreachable',
            'post.Hostdependency.notification_fail_on_pending'
        ], function(){
            if($scope.init){
                return;
            }
            if($scope.post.Hostdependency.notification_none === 0){
                return;
            }
            if($scope.post.Hostdependency.notification_fail_on_up |
                $scope.post.Hostdependency.notification_fail_on_down |
                $scope.post.Hostdependency.notification_fail_on_unreachable |
                $scope.post.Hostdependency.notification_fail_on_pending
            ){
                $scope.post.Hostdependency.notification_none = 0;
            }

        }, true);

        $scope.$watch('post.Hostdependency.notification_none', function(){
            if($scope.init){
                return;
            }
            if($scope.post.Hostdependency.notification_none === 0){
                return;
            }
            $scope.post.Hostdependency.notification_fail_on_up = 0;
            $scope.post.Hostdependency.notification_fail_on_down = 0;
            $scope.post.Hostdependency.notification_fail_on_unreachable = 0;
            $scope.post.Hostdependency.notification_fail_on_pending = 0;
        }, true);

        //Fire on page load
        $scope.loadContainer();

    });
