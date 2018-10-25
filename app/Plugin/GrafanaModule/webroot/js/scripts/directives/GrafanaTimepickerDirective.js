angular.module('openITCOCKPIT').directive('grafanaTimepicker', function($http){
    return {
        restrict: 'E',
        templateUrl: '/grafana_module/grafana_userdashboards/grafanaTimepicker.html',
        scope: {
            'callback': '='
        },
        controller: function($scope){
            var defaultTimerange = 'now-3h';
            var defaultAutoRefresh = '1m';

            $scope.selectedTimerange = defaultTimerange;
            $scope.selectedAutoRefresh = defaultAutoRefresh;

            $scope.humanTimerange = 'Last 3 hours';
            $scope.humanAutoRefresh = 'Refresh every 1m';

            $scope.init = true;

            $scope.load = function(){
                $http.get("/grafana_module/grafana_userdashboards/grafanaTimepicker.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.timeranges = result.data.timeranges;
                    $scope.init = false;
                });
            };

            $scope.changeAutoRefresh = function(urlKey, name){
                $scope.selectedAutoRefresh = urlKey;
                if(urlKey === 0 || urlKey === '0'){
                    $scope.humanAutoRefresh = false;
                }else{
                    $scope.humanAutoRefresh = name;
                }

                $scope.callback($scope.selectedTimerange, $scope.selectedAutoRefresh);
            };

            $scope.changeTimerange = function(urlKey, name){
                $scope.selectedTimerange = urlKey;
                $scope.humanTimerange = name;
                $scope.callback($scope.selectedTimerange, $scope.selectedAutoRefresh);
            };

            $scope.load();

        },

        link: function($scope, element, attr){
        }
    };
});
