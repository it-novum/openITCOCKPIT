angular.module('openITCOCKPIT').directive('grafanaWidgetUserdefined', function($http, $sce){
    return {
        restrict: 'E',
        templateUrl: '/grafana_module/grafana_userdashboards/grafanaWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){
            /** public vars **/
            $scope.init = true;
            $scope.grafana = {
                dashboard_id: null,
                iframe_url: ''
            };


            $scope.load = function(){
                $http.get("/grafana_module/grafana_userdashboards/grafanaWidget.json", {
                    params: {
                        'angular': true,
                        'widgetId': $scope.widget.id
                    }
                }).then(function(result){
                    $sce.trustAsResourceUrl(result.data.iframe_url);
                    $scope.grafana.dashboard_id = result.data.grafana_userdashboard_id;
                    if($scope.grafana.dashboard_id !== null){
                        $scope.grafana.dashboard_id = parseInt($scope.grafana.dashboard_id, 10);
                    }
                    $scope.grafana.iframe_url = result.data.iframe_url;

                    //Do not trigger watch on page load
                    setTimeout(function(){
                        $scope.init = false;
                    }, 250);
                });
            };

            $scope.loadGrafanaUserDashboards = function(){
                $http.get("/grafana_module/grafana_userdashboards/index.json", {
                    params: {
                        'angular': true,
                        'skipUnsyncDashboards': true
                    }
                }).then(function(result){
                    var availableGrafanaUserdefeinedDashboards = [];
                    for(var i in result.data.all_userdashboards){
                        availableGrafanaUserdefeinedDashboards.push({
                            id: parseInt(result.data.all_userdashboards[i].id, 10),
                            name: result.data.all_userdashboards[i].name
                        });
                    }

                    $scope.availableGrafanaUserdefeinedDashboards = availableGrafanaUserdefeinedDashboards;
                });
            };

            $scope.hideConfig = function(){
                $scope.$broadcast('FLIP_EVENT_IN');
                $scope.load();
            };
            $scope.showConfig = function(){
                $scope.$broadcast('FLIP_EVENT_OUT');
                $scope.loadGrafanaUserDashboards();
            };

            $scope.saveGrafana = function(){
                $http.post("/grafana_module/grafana_userdashboards/grafanaWidget.json?angular=true",
                    {
                        Widget: {
                            id: $scope.widget.id
                        },
                        dashboard_id: $scope.grafana.dashboard_id
                    }
                ).then(function(result){
                    //Update status
                    $scope.hideConfig();
                });
            };


            /** Page load / widget get loaded **/
            $scope.load();


        },

        link: function($scope, element, attr){

        }
    };
});
