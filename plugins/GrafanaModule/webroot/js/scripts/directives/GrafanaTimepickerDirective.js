angular.module('openITCOCKPIT').directive('grafanaTimepicker', function($http){
    return {
        restrict: 'E',
        templateUrl: '/grafana_module/grafana_userdashboards/grafanaTimepicker.html',
        scope: {
            'callback': '=',
            'selectedTimerange': '=',
            'selectedAutoRefresh': '=',
        },
        controller: function($scope){
            // Either fetch values from parameters, or use default.
            $scope.selectedTimerange   =  $scope.selectedTimerange || 'now-3h';
            $scope.selectedAutoRefresh =   $scope.selectedAutoRefresh || '1m';
            $scope.init                = true;


            $scope.load = function(){
                $http.get("/grafana_module/grafana_userdashboards/grafanaTimepicker.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.timeranges = result.data.timeranges;
                    $scope.init = false;

                    $scope.updateNames();
                });
            };

            $scope.changeAutoRefresh = function(urlKey){
                $scope.selectedAutoRefresh = urlKey;
                $scope.callback($scope.selectedTimerange, $scope.selectedAutoRefresh);
            };

            $scope.changeTimerange = function(urlKey){
                $scope.selectedTimerange = urlKey;
                $scope.callback($scope.selectedTimerange, $scope.selectedAutoRefresh);
            };

            $scope.updateNames = function() {
                for (let index in $scope.timeranges) {
                    let list = $scope.timeranges[index];
                    for (let value in list) {
                        let name = $scope.timeranges[index][value];
                        if (value === $scope.selectedTimerange) {
                            $scope.humanTimerange = name;
                        }
                        if (value === $scope.selectedAutoRefresh) {
                            $scope.humanAutoRefresh = name;
                        }
                    }
                }
            }

            $scope.$watchGroup(['selectedTimerange', 'selectedAutoRefresh'], function(){
                $scope.updateNames();
            });
            $scope.load();
        },

        link: function($scope, element, attr){
        }
    };
});
