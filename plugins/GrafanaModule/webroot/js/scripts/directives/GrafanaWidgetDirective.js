angular.module('openITCOCKPIT').directive('grafanaWidget', function($http, $sce){
    return {
        restrict: 'E',
        templateUrl: '/grafana_module/grafana_configuration/grafanaWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){
            /** public vars **/
            $scope.init = true;
            $scope.grafana = {
                host_id: null,
                iframe_url: ''
            };


            $scope.load = function(){
                $http.get("/grafana_module/grafana_configuration/grafanaWidget.json", {
                    params: {
                        'angular': true,
                        'widgetId': $scope.widget.id
                    }
                }).then(function(result){
                    $sce.trustAsResourceUrl(result.data.iframe_url);
                    $scope.grafana.host_id = result.data.host_id;
                    $scope.grafana.iframe_url = result.data.iframe_url;

                    //Do not trigger watch on page load
                    setTimeout(function(){
                        $scope.init = false;
                    }, 250);
                });
            };

            $scope.loadGrafanaDashboards = function(searchString){
                $http.get("/grafana_module/grafana_configuration/getGrafanaDashboards.json", {
                    params: {
                        'angular': true,
                        'filter[Host.name]': searchString
                    }
                }).then(function(result){
                    $scope.availableGrafanaDashboards = result.data.grafana_dashboards;
                });
            };

            $scope.hideConfig = function(){
                $scope.$broadcast('FLIP_EVENT_IN');
                $scope.load();
            };
            $scope.showConfig = function(){
                $scope.$broadcast('FLIP_EVENT_OUT');
                $scope.loadGrafanaDashboards();
            };

            $scope.saveGrafana = function(){
                $http.post("/grafana_module/grafana_configuration/grafanaWidget.json?angular=true",
                    {
                        Widget: {
                            id: $scope.widget.id
                        },
                        host_id: $scope.grafana.host_id
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
