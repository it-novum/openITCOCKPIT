angular.module('openITCOCKPIT').directive('hostBrowserMenu', function($http, $state, StatusHelperService, $interval){
    return {
        restrict: 'E',
        templateUrl: '/angular/hostBrowserMenu.html',
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
                hostId: 1337,
                hostUuid: 'aaaa-bbbbb-ccccc-ddddd',
                docuExists: true,
                hostUrl: "https://openitcockpit.io",
                allowEdit: true,
                hostName: "localhost",
                hostAddress: "127.0.0.1",
                includeHoststatus: false

                //Or enable autoload and the directive will load all required data by itself
                autoload: true
                hostId: 1337,
                includeHoststatus: false
            }
            */

            $scope.loadData = function(){
                var includeHoststatus = false;
                if($scope.config.hasOwnProperty('includeHoststatus')){
                    includeHoststatus = $scope.config.includeHoststatus;
                }

                $http.get("/angular/hostBrowserMenu/.json", {
                    params: {
                        'angular': true,
                        'hostId': $scope.config.hostId,
                        'includeHoststatus': includeHoststatus,
                    }
                }).then(function(result){
                    for(var key in result.data.config){
                        $scope.config[key] = result.data.config[key];
                    }

                    if(includeHoststatus){
                        $scope.hoststatus = result.data.config.Hoststatus;
                        $scope.hostStatusTextClass = StatusHelperService.getHoststatusTextColor($scope.hoststatus.currentState);
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
            if($scope.config.hasOwnProperty('autoload') && $scope.config.hasOwnProperty('hostId')){
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

            $scope.$watch('hoststatus.isFlapping', function(){
                if($scope.hoststatus){
                    if($scope.hoststatus.hasOwnProperty('isFlapping')){
                        if($scope.hoststatus.isFlapping === true){
                            $scope.startFlapping();
                        }

                        if($scope.hoststatus.isFlapping === false){
                            $scope.stopFlapping();
                        }

                    }
                }
            });

        }

    };
});
