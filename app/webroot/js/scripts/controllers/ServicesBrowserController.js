angular.module('openITCOCKPIT')
    .controller('ServicesBrowserController', function($scope, $http, QueryStringService, $interval){

        $scope.id = QueryStringService.getCakeId();

        $scope.showFlashSuccess = false;

        $scope.canSubmitExternalCommands = false;

        $scope.tags = [];

        $scope.init = true;

        $scope.serviceStatusTextClass = 'txt-primary';

        var flappingInterval;

        $scope.showFlashMsg = function(){
            $scope.showFlashSuccess = true;
            $scope.autoRefreshCounter = 5;
            var interval = $interval(function(){
                $scope.autoRefreshCounter--;
                if($scope.autoRefreshCounter === 0){
                    $scope.load();
                    $interval.cancel(interval);
                    $scope.showFlashSuccess = false;
                }
            }, 1000);
        };

        $scope.load = function(){
            $http.get("/services/browser/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.mergedService = result.data.mergedService;
                $scope.contacts = result.data.contacts;
                $scope.contactgroups = result.data.contactgroups;
                $scope.host = result.data.host;
                $scope.tags = $scope.mergedService.Service.tags.split(',');
                $scope.hoststatus = result.data.hoststatus;
                $scope.servicestatus = result.data.servicestatus;
                $scope.servicestatusForIcon = {
                    Servicestatus: $scope.servicestatus
                };
                $scope.serviceStatusTextClass = getServicestatusTextColor();


                $scope.acknowledgement = result.data.acknowledgement;
                $scope.downtime = result.data.downtime;

                $scope.canSubmitExternalCommands = result.data.canSubmitExternalCommands;

                $scope.priorities = {
                    1: false,
                    2: false,
                    3: false,
                    4: false,
                    5: false
                };
                var priority = parseInt($scope.mergedService.Service.priority, 10);
                for(var i = 1; i <= priority; i++){
                    $scope.priorities[i] = true;
                }

                $scope.init = false;
            });
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


        $scope.getObjectForDowntimeDelete = function(){
            var object = {};
            object[$scope.downtime.internalDowntimeId] = $scope.host.Host.name + ' / ' + $scope.mergedService.Service.name;
            return object;
        };

        $scope.getObjectsForExternalCommand = function(){
            return [{
                Service: {
                    id: $scope.mergedService.Service.id,
                    uuid: $scope.mergedService.Service.uuid,
                    name: $scope.mergedService.Service.name
                },
                Host: {
                    id: $scope.host.Host.id,
                    uuid: $scope.host.Host.uuid,
                    name: $scope.host.Host.name,
                    satelliteId: $scope.host.Host.satellite_id
                }
            }];
        };


        $scope.stateIsOk = function(){
            return parseInt($scope.servicestatus.currentState, 10) === 0;
        };

        $scope.stateIsWarning = function(){
            return parseInt($scope.servicestatus.currentState, 10) === 1;
        };

        $scope.stateIsCritical = function(){
            return parseInt($scope.servicestatus.currentState, 10) === 2;
        };

        $scope.stateIsUnknown = function(){
            return parseInt($scope.servicestatus.currentState, 10) === 3;
        };

        $scope.stateIsNotInMonitoring = function(){
            return !$scope.servicestatus.isInMonitoring;
        };

        $scope.startFlapping = function(){
            $scope.stopFlapping();
            flappingInterval = $interval(function(){
                if($scope.flappingState === 0){
                    $scope.flappingState = 1;
                }else{
                    $scope.flappingState = 0;
                }
            }, 750);
        };

        $scope.stopFlapping = function(){
            if(flappingInterval){
                $interval.cancel(flappingInterval);
            }
            flappingInterval = null;
        };


        var getServicestatusTextColor = function(){
            switch($scope.servicestatus.currentState){
                case 0:
                case '0':
                    return 'txt-color-green';

                case 1:
                case '1':
                    return 'warning';

                case 2:
                case '2':
                    return 'txt-color-red';

                case 3:
                case '3':
                    return 'txt-color-blueLight';
            }
            return 'txt-primary';
        };


        $scope.load();
        $scope.loadTimezone();


        $scope.$watch('servicestatus.isFlapping', function(){
            if($scope.servicestatus){
                if($scope.servicestatus.hasOwnProperty('isFlapping')){
                    if($scope.servicestatus.isFlapping === true){
                        $scope.startFlapping();
                    }

                    if($scope.servicestatus.isFlapping === false){
                        $scope.stopFlapping();
                    }

                }
            }
        });

    });