angular.module('openITCOCKPIT')
    .controller('ServicegroupsExtendedController', function($scope, $http, $interval, $stateParams){

        $scope.init = true;
        $scope.servicegroupsStateFilter = {};

        $scope.deleteUrl = '/services/delete/';
        $scope.deactivateUrl = '/services/deactivate/';
        $scope.activateUrl = '/services/enable/';
        $scope.mouseout = true;

        $scope.filter = {
            servicename: ''
        };

        $scope.post = {
            Servicegroup: {
                id: null
            }
        };

        $scope.post.Servicegroup.id = $stateParams.id;
        if($scope.post.Servicegroup.id !== null){
            $scope.post.Servicegroup.id = parseInt($scope.post.Servicegroup.id, 10);
        }

        $scope.load = function(){
            $http.get("/servicegroups/loadServicegroupsByString.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.servicegroups = result.data.servicegroups;

                if($scope.post.Servicegroup.id === null){
                    if($scope.servicegroups.length > 0){
                        $scope.post.Servicegroup.id = $scope.servicegroups[0].key;
                    }
                }else{
                    //HostgroupId was passed in URL
                    $scope.loadServicesWithStatus();
                }

                $scope.init = false;
            });
        };

        $scope.loadServicegroupsCallback = function(searchString){
            $http.get("/servicegroups/loadServicegroupsByString.json", {
                params: {
                    'angular': true,
                    'filter[Containers.name]': searchString,
                    'selected[]': $scope.post.Servicegroup.id
                }
            }).then(function(result){
                $scope.servicegroups = result.data.servicegroups;
            });
        };

        $scope.loadServicesWithStatus = function(){
            if($scope.post.Servicegroup.id){
                $http.get("/servicegroups/loadServicegroupWithServicesById/" + $scope.post.Servicegroup.id + ".json", {
                    params: {
                        'angular': true,
                        'filter[servicename]': $scope.filter.servicename,
                    }
                }).then(function(result){
                    $scope.servicegroup = result.data.servicegroup;
                    $scope.servicegroupsStateFilter = {
                        0: true,
                        1: true,
                        2: true,
                        3: true
                    };
                });
            }

        };

        $scope.getObjectForDelete = function(host, service){
            var object = {};
            object[service.Service.id] = host.hostname + '/' + service.Service.servicename;
            return object;
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

        $scope.getObjectsForExternalCommand = function(){
            var objects = {};
            if($scope.post.Servicegroup.id){
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
            if($scope.post.Servicegroup.id){
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
            if($scope.post.Servicegroup.id){
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
                    $scope.loadServicesWithStatus('');
                    $interval.cancel(interval);
                    $scope.showFlashSuccess = false;
                }
            }, 1000);
        };

        //Fire on page load
        $scope.loadTimezone();
        $scope.load();

        $scope.$watch('post.Servicegroup.id', function(){
            if($scope.init){
                return;
            }
            $scope.loadServicesWithStatus('');
        }, true);

        $scope.$watch('filter', function(){
            if($scope.init){
                return;
            }

            if($scope.post.Servicegroup.id > 0){
                $scope.loadServicesWithStatus('');
            }
        }, true);

    });
