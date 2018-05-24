angular.module('openITCOCKPIT')
    .controller('HostgroupsExtendedController', function($scope, $http, $interval, QueryStringService){

        $scope.init = true;
        $scope.servicegroupsStateFilter = {};

        $scope.deleteUrl = '/services/delete/';
        $scope.deactivateUrl = '/services/deactivate/';
        $scope.activateUrl = '/services/enable/';

        $scope.post = {
            Hostgroup: {
                id: null
            }
        };

        $scope.showServices = {};

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Host: {
                    name: QueryStringService.getValue('filter[Host.name]', '')
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

        $scope.getObjectForDelete = function(host, service){
            var object = {};
            object[service.Service.id] = host.hostname + '/' + service.Service.servicename;
            return object;
        };


        $scope.getObjectsForExternalCommand = function(){
            var objects = {};
            if($scope.post.Servicegroup.id) {
                for(var key in $scope.servicegroup.Services){
                    if($scope.servicegroup.Services[key].Service.allow_edit){
                        objects[$scope.servicegroup.Services[key].Service.id] = $scope.servicegroup.Services[key];
                    }
                }
            }
            return objects;
        };

        $scope.getNotOkObjectsForExternalCommand = function(){
            var objects = {};
            if($scope.post.Servicegroup.id) {
                for(var key in $scope.servicegroup.Services){
                    if($scope.servicegroup.Services[key].Service.allow_edit &&
                        $scope.servicegroup.Services[key].Servicestatus.currentState > 0){
                        objects[$scope.servicegroup.Services[key].Service.id] = $scope.servicegroup.Services[key];
                    }
                }
            }
            return objects;
        };

        $scope.getObjectsForNotificationsExternalCommand = function(notificationsEnabled){
            var objects = {};
            if($scope.post.Servicegroup.id) {
                for(var key in $scope.servicegroup.Services){
                    if($scope.servicegroup.Services[key].Service.allow_edit &&
                        $scope.servicegroup.Services[key].Servicestatus.notifications_enabled === notificationsEnabled){

                        objects[$scope.servicegroup.Services[key].Service.id] = $scope.servicegroup.Services[key];
                    }
                }
            }
            return objects;
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
            $scope.loadHostsWithStatus();
        }, true);
    });
