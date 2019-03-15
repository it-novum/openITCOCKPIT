angular.module('openITCOCKPIT')
    .controller('Grafana_userdashboardsAddController', function($scope, $http, $state, NotyService){

        $scope.post = {
            GrafanaUserdashboard: {
                container_id: null,
                name: ''
            }
        };
        $scope.hasGrafanaConfig = true;


        $scope.loadContainers = function(){
            $http.get("/grafana_module/grafana_userdashboards/loadContainers.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.hasGrafanaConfig = result.data.hasGrafanaConfig;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };


        $scope.submit = function(){
            $http.post("/grafana_module/grafana_userdashboards/add.json?angular=true",
                $scope.post
            ).then(function(result){
                NotyService.genericSuccess();
                $state.go('GrafanaUserdashboardsEditor', {id: result.data.id});
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.loadContainers();
    });