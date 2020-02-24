angular.module('openITCOCKPIT')
    .controller('Grafana_userdashboardsEditController', function($scope, $http, $stateParams, $state, NotyService){

        $scope.hasGrafanaConfig = true;
        $scope.post = {};

        $scope.id = $stateParams.id;

        $scope.load = function(){
            $http.get("/grafana_module/grafana_userdashboards/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post = result.data.dashboard;

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

        $scope.loadContainers = function(){
            $http.get("/grafana_module/grafana_userdashboards/loadContainers.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;

                //Load Grafana User Dashboard itself
                $scope.load();

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
            $http.post("/grafana_module/grafana_userdashboards/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                NotyService.genericSuccess();
                $state.go('GrafanaUserdashboardsIndex');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.loadContainers();
    });
