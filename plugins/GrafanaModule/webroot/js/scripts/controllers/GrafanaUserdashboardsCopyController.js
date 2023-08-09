angular.module('openITCOCKPIT')
    .controller('Grafana_userdashboardsCopyController', function($scope, $http, $state, $stateParams, QueryStringService, NotyService, RedirectService){

        var ids = $stateParams.ids.split(',');

        if(ids.length === 0 || ids[0] === ''){
            //No ids to copy given - redirect
            RedirectService.redirectWithFallback('GrafanaUserdashboardsIndex');
            return;
        }


        $scope.load = function(){
            $http.get("/grafana_module/grafana_userdashboards/copy/" + ids.join('/') + ".json", {
                params: {
                    'angular': true,
                }
            }).then(function(result){
                $scope.sourceDashboards = [];
                for(var key in result.data.dashboards){
                    $scope.sourceDashboards.push({
                        Source: {
                            id: result.data.dashboards[key].id,
                            name: result.data.dashboards[key].name,
                        },
                        Dashboard: {
                            name: result.data.dashboards[key].name
                        }
                    });
                }

                $scope.init = false;

            });
        };

        $scope.copy = function(){
            $http.post("/grafana_module/grafana_userdashboards/copy/.json?angular=true",
                {
                    data: $scope.sourceDashboards
                }
            ).then(function(result){
                NotyService.genericSuccess();
                RedirectService.redirectWithFallback('GrafanaUserdashboardsIndex');
            }, function errorCallback(result){
                //Print errors
                NotyService.genericError();
                $scope.sourceDashboards = result.data.result;
            });
        };


        $scope.load();

    });
