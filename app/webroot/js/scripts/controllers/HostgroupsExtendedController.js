angular.module('openITCOCKPIT')
    .controller('HostgroupsExtendedController', function($scope, $http, $interval, QueryStringService){

        $scope.init = true;
        $scope.servicegroupsStateFilter = {};

        $scope.deleteUrl = '/hosts/delete/';
        $scope.deactivateUrl = '/hosts/deactivate/';

        $scope.post = {
            Hostgroup: {
                id: null
            }
        };

        $scope.post.Hostgroup.id = QueryStringService.getCakeId();

        $scope.showServices = {};

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Host: {
                    name: ''
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

                if(isNaN($scope.post.Hostgroup.id)){
                    if($scope.hostgroups.length > 0){
                        $scope.post.Hostgroup.id = $scope.hostgroups[0].key;
                    }
                }else{
                    //ServicegroupId was passed in URL
                    $scope.loadHostsWithStatus();
                }

                $scope.init = false;
            });
        };

        $scope.loadHostsWithStatus = function(){
            if($scope.post.Hostgroup.id) {
                $http.get("/hostgroups/loadHostgroupWithHostsById/" + $scope.post.Hostgroup.id +".json", {
                    params: {
                        'angular': true,
                        'selected': $scope.post.Hostgroup.id,
                        'filter[Host.name]': $scope.filter.Host.name
                    }
                }).then(function (result) {
                    $scope.hostgroup = result.data.hostgroup;

                    for(var host in $scope.hostgroup.Hosts){
                        $scope.showServices[$scope.hostgroup.Hosts[host].Host.id] = false;
                    }

                    $scope.hostgroupsStateFilter = {
                        0 : true,
                        1 : true,
                        2 : true
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
            var interval = $interval(function(){
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

        //Fire on page load
        $scope.loadTimezone();
        $scope.load();
        defaultFilter();

        $scope.$watch('post.Hostgroup.id', function(){
            if($scope.init){
                return;
            }
            defaultFilter();
            $scope.loadHostsWithStatus('');
        }, true);

        $scope.$watch('filter', function(){
            if($scope.init){
                return;
            }
            $scope.loadHostsWithStatus();
        }, true);
    });
