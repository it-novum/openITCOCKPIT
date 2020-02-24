angular.module('openITCOCKPIT')
    .controller('HostescalationsAddController', function($scope, $http, $state, NotyService, RedirectService){

        $scope.post = {
            Hostescalation: {
                container_id: null,
                first_notification: 1,
                last_notification: 5,
                notification_interval: 7200,
                timeperiod_id: null,
                escalate_on_recovery: 0,
                escalate_on_down: 0,
                escalate_on_unreachable: 0,
                contacts: {
                    _ids: []
                },
                contactgroups: {
                    _ids: []
                },
                hosts: {
                    _ids: []
                },
                hosts_excluded: {
                    _ids: []
                },
                hostgroups: {
                    _ids: []
                },
                hostgroups_excluded: {
                    _ids: []
                }
            }
        };
        $scope.containers = {};

        $scope.load = function(){
            var params = {
                'angular': true
            };

            $http.get("/hostescalations/loadContainers.json", {
                params: params
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };

        $scope.loadElementsByContainerId = function(){
            $http.get("/hostescalations/loadElementsByContainerId/" + $scope.post.Hostescalation.container_id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.hosts = result.data.hosts;
                $scope.hosts_excluded = result.data.hostsExcluded;
                $scope.hostgroups = result.data.hostgroups;
                $scope.hostgroups_excluded = result.data.hostgroupsExcluded;
                $scope.timeperiods = result.data.timeperiods;
                $scope.contacts = result.data.contacts;
                $scope.contactgroups = result.data.contactgroups;
            });
        };

        $scope.loadHosts = function(searchString){
            if($scope.post.Hostescalation.container_id != null){
                $http.get("/hosts/loadHostsByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Hostescalation.container_id,
                        'filter[Hosts.name]': searchString,
                        'selected[]': $scope.post.Hostescalation.hosts._ids
                    }
                }).then(function(result){
                    $scope.hosts = result.data.hosts;
                });
            }
        };

        $scope.loadExcludedHosts = function(searchString){
            if($scope.post.Hostescalation.container_id != null){
                $http.get("/hosts/loadHostsByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.post.Hostescalation.container_id,
                        'filter[Hosts.name]': searchString,
                        'selected[]': $scope.post.Hostescalation.hosts_excluded._ids
                    }
                }).then(function(result){
                    $scope.hosts_excluded = result.data.hosts;
                });
            }
        };

        $scope.submit = function(){
            $http.post("/hostescalations/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var hostescalatingEditUrl = $state.href('HostescalationsEdit', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + hostescalatingEditUrl + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('HostescalationsIndex');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };


        $scope.processChosenHosts = function(){
            for(var key in $scope.hosts){
                if(in_array($scope.hosts[key].key, $scope.post.Hostescalation.hosts_excluded._ids)){
                    $scope.hosts[key].disabled = true;
                }else{
                    $scope.hosts[key].disabled = false;
                }
            }
        };

        $scope.processChosenExcludedHosts = function(){
            for(var key in $scope.hosts_excluded){
                if(in_array($scope.hosts_excluded[key].key, $scope.post.Hostescalation.hosts._ids)){
                    $scope.hosts_excluded[key].disabled = true;
                }else{
                    $scope.hosts_excluded[key].disabled = false;
                }
            }
        };

        $scope.processChosenHostgroups = function(){
            for(var key in $scope.hostgroups){
                if(in_array($scope.hostgroups[key].key, $scope.post.Hostescalation.hostgroups_excluded._ids)){
                    $scope.hostgroups[key].disabled = true;
                }else{
                    $scope.hostgroups[key].disabled = false;
                }
            }
        };

        $scope.processChosenExcludedHostgroups = function(){
            for(var key in $scope.hostgroups_excluded){
                if(in_array($scope.hostgroups_excluded[key].key, $scope.post.Hostescalation.hostgroups._ids)){
                    $scope.hostgroups_excluded[key].disabled = true;
                }else{
                    $scope.hostgroups_excluded[key].disabled = false;
                }
            }
        };


        $scope.$watch('post.Hostescalation.container_id', function(){
            if($scope.post.Hostescalation.container_id != null){
                $scope.loadElementsByContainerId();
            }
        }, true);

        $scope.$watch('post.Hostescalation.hosts._ids', function(){
            $scope.processChosenExcludedHosts();
        }, true);

        $scope.$watch('post.Hostescalation.hosts_excluded._ids', function(){
            $scope.processChosenHosts();
        }, true);

        $scope.$watch('post.Hostescalation.hostgroups._ids', function(){
            $scope.processChosenExcludedHostgroups();
        }, true);

        $scope.$watch('post.Hostescalation.hostgroups_excluded._ids', function(){
            $scope.processChosenHostgroups();
        }, true);

        //Fire on page load
        $scope.load();

    });