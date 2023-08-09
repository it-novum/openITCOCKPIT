angular.module('openITCOCKPIT')
    .controller('HostgroupsExtendedController', function($rootScope, $scope, $http, $interval, $stateParams){

        $scope.init = true;
        $scope.servicegroupsStateFilter = {};

        $scope.deleteUrl = '/hosts/delete/';
        $scope.deactivateUrl = '/hosts/deactivate/';
        $scope.interval = null;

        $scope.currentPage = 1;
        $scope.useScroll = true;

        $scope.post = {
            Hostgroup: {
                id: null
            }
        };

        $scope.post.Hostgroup.id = $stateParams.id;
        if($scope.post.Hostgroup.id !== null){
            $scope.post.Hostgroup.id = parseInt($scope.post.Hostgroup.id, 10);
        }

        $scope.showServices = {};

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Host: {
                    name: ''
                },
                Hoststatus: {
                    current_state: {
                        up: false,
                        down: false,
                        unreachable: false
                    }
                }
            };
        };

        $scope.load = function(){
            $http.get("/hostgroups/loadHostgroupsByString.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.hostgroups = result.data.hostgroups;

                if($scope.post.Hostgroup.id === null){
                    if($scope.hostgroups.length > 0){
                        $scope.post.Hostgroup.id = $scope.hostgroups[0].key;
                    }
                }else{
                    //HostgroupId was passed in URL
                    $scope.loadHostsWithStatus();
                }

                $scope.init = false;
            });
        };

        $scope.loadHostgroupsCallback = function(searchString){
            $http.get("/hostgroups/loadHostgroupsByString.json", {
                params: {
                    'angular': true,
                    'filter[Containers.name]': searchString,
                    'selected[]': $scope.post.Hostgroup.id
                }
            }).then(function(result){
                $scope.hostgroups = result.data.hostgroups;
            });
        };


        $scope.loadHostsWithStatus = function(){
            if($scope.post.Hostgroup.id){
                $http.get("/hostgroups/loadHostgroupWithHostsById/" + $scope.post.Hostgroup.id + ".json", {
                    params: {
                        'angular': true,
                        'scroll': $scope.useScroll,
                        'page': $scope.currentPage,
                        'selected': $scope.post.Hostgroup.id,
                        'filter[Hosts.name]': $scope.filter.Host.name,
                        'filter[Hoststatus.current_state][]': $rootScope.currentStateForApi($scope.filter.Hoststatus.current_state)

                    }
                }).then(function(result){
                    $scope.hostgroup = result.data.hostgroup;
                    $scope.paging = result.data.paging;
                    $scope.scroll = result.data.scroll;

                    for(var host in $scope.hostgroup.Hosts){
                        $scope.showServices[$scope.hostgroup.Hosts[host].Host.id] = false;
                    }

                    $scope.hostgroupsStateFilter = {
                        0: true,
                        1: true,
                        2: true
                    };
                });
            }
        };

        $scope.loadTimezone = function(){
            $http.get("/angular/user_timezone.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.timezone = result.data.timezone;
            });
        };

        $scope.getObjectForDelete = function(host){
            var object = {};
            object[host.Host.id] = host.Host.hostname;
            return object;
        };

        $scope.getObjectsForExternalCommand = function(){
            var object = {};
            for(var host in $scope.hostgroup.Hosts){
                object[$scope.hostgroup.Hosts[host].Host.id] = $scope.hostgroup.Hosts[host];
            }
            return object;
        };


        $scope.showFlashMsg = function(){
            $scope.showFlashSuccess = true;
            $scope.autoRefreshCounter = 5;
            $scope.interval = $interval(function(){
                $scope.autoRefreshCounter--;
                if($scope.autoRefreshCounter === 0){
                    $interval.cancel(interval);
                    $scope.showFlashSuccess = false;
                }
            }, 1000);
        };


        $scope.showServicesCallback = function(hostId){
            if($scope.showServices[hostId] === false){
                $scope.showServices[hostId] = true;
            }else{
                $scope.showServices[hostId] = false;
            }
        };

        $scope.changepage = function(page){
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.changeMode = function(val){
            $scope.useScroll = val;
            $scope.load();
        };


        //Disable interval if object gets removed from DOM.
        $scope.$on('$destroy', function(){
            if($scope.interval !== null){
                $interval.cancel($scope.interval);
            }
        });

        //Fire on page load
        $scope.loadTimezone();
        $scope.load();
        defaultFilter();

        $scope.$watch('post.Hostgroup.id', function(){
            if($scope.init){
                return;
            }
            defaultFilter();
            $scope.currentPage = 1;
            $scope.loadHostsWithStatus('');
        }, true);

        $scope.$watch('filter', function(){
            if($scope.init){
                return;
            }
            $scope.currentPage = 1;
            $scope.loadHostsWithStatus();
        }, true);
    });
