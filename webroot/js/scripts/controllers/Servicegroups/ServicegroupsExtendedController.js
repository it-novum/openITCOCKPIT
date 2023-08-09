angular.module('openITCOCKPIT')
    .controller('ServicegroupsExtendedController', function($rootScope, $scope, $http, $interval, $stateParams){

        $scope.init = true;
        $scope.servicegroupsStateFilter = {};

        $scope.deleteUrl = '/services/delete/';
        $scope.deactivateUrl = '/services/deactivate/';
        $scope.activateUrl = '/services/enable/';
        $scope.mouseout = true;
        $scope.interval = null;

        $scope.currentPage = 1;
        $scope.useScroll = true;

        $scope.filter = {
            servicename: '',
            Servicestatus: {
                current_state: {
                    ok: false,
                    warning: false,
                    critical: false,
                    unknown: false
                }
            }
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
                        'scroll': $scope.useScroll,
                        'page': $scope.currentPage,
                        'filter[servicename]': $scope.filter.servicename,
                        'filter[Servicestatus.current_state][]': $rootScope.currentStateForApi($scope.filter.Servicestatus.current_state)
                    }
                }).then(function(result){
                    $scope.servicegroup = result.data.servicegroup;
                    $scope.paging = result.data.paging;
                    $scope.scroll = result.data.scroll;
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
            $scope.interval = $interval(function(){
                $scope.autoRefreshCounter--;
                if($scope.autoRefreshCounter === 0){
                    $scope.loadServicesWithStatus('');
                    $interval.cancel(interval);
                    $scope.showFlashSuccess = false;
                }
            }, 1000);
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

        $scope.$watch('post.Servicegroup.id', function(){
            if($scope.init){
                return;
            }

            $scope.currentPage = 1;
            $scope.loadServicesWithStatus('');
        }, true);

        $scope.$watch('filter', function(){
            if($scope.init){
                return;
            }
            if($scope.post.Servicegroup.id > 0){
                $scope.currentPage = 1;
                $scope.loadServicesWithStatus('');
            }
        }, true);

    });
