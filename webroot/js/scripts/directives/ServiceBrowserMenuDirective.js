angular.module('openITCOCKPIT').directive('serviceBrowserMenu', function($http, $state, StatusHelperService, $interval){
    return {
        restrict: 'E',
        templateUrl: '/angular/serviceBrowserMenu.html',
        scope: {
            'config': '=',
            'lastLoadDate': '=',
            'rootCopyToClipboard': '=' // Passed from $rootScope
        },

        controller: function($scope){
            var flappingInterval;
            $scope.init = true;

            //Example of $scope.config
            /*
            config = {
                //Pass all options manually
                showReschedulingButton: true,
                rescheduleCallback: function(){},
                showBackButton: true,
                serviceId: 1337,
                serviceUuid: 'aaaa-bbbbb-ccccc-ddddd',
                docuExists: true,
                serviceUrl: "https://openitcockpit.io",
                allowEdit: true,
                hostId: 1,
                hostName: "localhost",
                hostAddress: "127.0.0.1",
                serviceName: "Ping",
                includeServicestatus: false

                //Or enable autoload and the directive will load all required data by itself
                autoload: true
                serviceId: 1337,
                includeServicestatus: false
            }
            */

            $scope.loadData = function(){
                var includeServicestatus = false;
                if($scope.config.hasOwnProperty('includeServicestatus')){
                    includeServicestatus = $scope.config.includeServicestatus;
                }

                $http.get("/angular/serviceBrowserMenu/.json", {
                    params: {
                        'angular': true,
                        'serviceId': $scope.config.serviceId,
                        'includeServicestatus': includeServicestatus,
                    }
                }).then(function(result){
                    for(var key in result.data.config){
                        $scope.config[key] = result.data.config[key];
                    }

                    if(includeServicestatus){
                        $scope.servicestatus = result.data.config.Servicestatus;
                        $scope.serviceStatusTextClass = StatusHelperService.getServicestatusTextColor($scope.servicestatus.currentState);
                    }
                    $scope.init = false;
                }, function errorCallback(result){
                    if(result.status === 403){
                        $state.go('403');
                    }

                    if(result.status === 404){
                        $state.go('404');
                    }
                });
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

            //Stop interval on page change
            $scope.$on('$destroy', function(){
                $scope.stopFlapping();
            });

            if(!$scope.config.hasOwnProperty('showReschedulingButton')){
                $scope.config.showReschedulingButton = false;
            }

            if(!$scope.config.hasOwnProperty('showBackButton')){
                $scope.config.showBackButton = true;
            }

            // Fire on page load
            if($scope.config.hasOwnProperty('autoload') && $scope.config.hasOwnProperty('serviceId')){
                if($scope.config.autoload === true){
                    $scope.loadData();
                }
            }

            $scope.$watch('lastLoadDate', function(){
                if($scope.init){
                    return;
                }
                $scope.loadData();
            }, true);

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

        }

    };
});
