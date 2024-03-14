angular.module('openITCOCKPIT')
    .controller('Grafana_userdashboardsViewController', function($scope, $http, $stateParams){

        $scope.id = $stateParams.id;

        $scope.dashboardFoundInGrafana = false;

        $scope.dashboard = {
            range : 'now-3h',
            refresh : '1m'
        }

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
                    'from': $scope.dashboard.range,
                    'refresh': $scope.dashboard.refresh
                }
            }).then(function(result){
                $scope.dashboardFoundInGrafana = result.data.dashboardFoundInGrafana;
                $scope.iframeUrl = result.data.iframeUrl;
            });
        };

        $scope.grafanaTimepickerCallback = function(selectedTimerange, selectedAutorefresh){
            $scope.dashboard.range   = selectedTimerange;
            $scope.dashboard.refresh = selectedAutorefresh;
            $scope.loadIframeUrl();
        };

        $scope.load();

    });