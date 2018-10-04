angular.module('openITCOCKPIT')
    .controller('Grafana_userdashboardsEditController', function($scope, $http, QueryStringService){

        $scope.id =

        $scope.post = {
            GrafanaUserdashboard: {
                id: null,
                container_id: null,
                name: '',
                configuration_id: null
            }
        };

        $scope.id = QueryStringService.getCakeId();

        $scope.load = function(){
            $http.get("/grafana_module/grafana_userdashboards/edit/"+$scope.id+".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post.GrafanaUserdashboard.id = result.data.dashboard.GrafanaUserdashboard.id;
                $scope.post.GrafanaUserdashboard.container_id = result.data.dashboard.GrafanaUserdashboard.container_id;
                $scope.post.GrafanaUserdashboard.name = result.data.dashboard.GrafanaUserdashboard.name;
                $scope.post.GrafanaUserdashboard.configuration_id = result.data.dashboard.GrafanaUserdashboard.configuration_id;
            }, function errorCallback(result){
                if(result.status === 404){
                    window.location.href = '/angular/not_found';
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
                if(result.status === 404){
                    window.location.href = '/angular/not_found';
                }
            });
        };


        $scope.submit = function(){
            $http.post("/grafana_module/grafana_userdashboards/edit/"+$scope.id+".json?angular=true",
                $scope.post
            ).then(function(result){
                console.log('Data saved successfully');
                window.location.href = '/grafana_module/grafana_userdashboards/index';
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.loadContainers();
    });