angular.module('openITCOCKPIT')
    .controller('Grafana_userdashboardsViewController', function($scope, $http, $stateParams){

        $scope.id = $stateParams.id;
        $scope.selectedTimerange = 'now-3h';
        $scope.selectedAutorefresh = '60s';

        $scope.dashboardFoundInGrafana = false;

        $scope.load = function(){
            $http.get("/grafana_module/grafana_userdashboards/view/" + $scope.id + ".json", {
                params: {
                    'angular': true,
                }
            }).then(function(result){
                $scope.dashboard = result.data.dashboard;
                $scope.allowEdit = result.data.allowEdit;
                $scope.dashboardFoundInGrafana = result.data.dashboardFoundInGrafana;

                $scope.loadIframeUrl();
            });
        };

        $scope.loadIframeUrl = function(){
            $http.get("/grafana_module/grafana_userdashboards/getViewIframeUrl/" + $scope.id + ".json", {
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

        $scope.load();

    });