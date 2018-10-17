angular.module('openITCOCKPIT')
    .controller('Grafana_userdashboardsViewController', function($scope, $http, QueryStringService){

        $scope.id = QueryStringService.getCakeId();
        $scope.selectedTimerange = 'now-3h';
        $scope.selectedAutorefresh = '60s';

        $scope.dashboardFoundInGrafana = false;

        $scope.loadIframeUrl = function(){
            $http.get("/grafana_module/grafana_userdashboards/getViewIframeUrl/"+$scope.id+".json", {
                params: {
                    'angular': true,
                    'from': $scope.selectedTimerange,
                    'refresh': $scope.selectedAutorefresh
                }
            }).then(function(result){
                $scope.dashboardFoundInGrafana = result.data.dashboardFoundInGrafana;
                $scope.iframeUrl = result.data.iframeUrl;
            });
        };

        $scope.grafanaTimepickerCallback = function(selectedTimerange, selectedAutorefresh){
            $scope.selectedTimerange = selectedTimerange;
            $scope.selectedAutorefresh = selectedAutorefresh;
            $scope.loadIframeUrl();
        };

        $scope.loadIframeUrl();

    });