angular.module('openITCOCKPIT')
    .controller('Grafana_userdashboardsAddController', function($scope, $http){

        $scope.post = {
            GrafanaUserdashboard:{
                container_id: null,
                name:''
            }
        };


        $scope.loadContainers = function(){
            $http.get("/grafana_module/grafana_userdashboards/loadContainers.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
            }, function errorCallback(result){
                if(result.status === 404){
                    window.location.href = '/angular/not_found';
                }
            });
        };


        $scope.submit = function(){
            $http.post("/grafana_module/grafana_userdashboards/add.json?angular=true",
                $scope.post
            ).then(function(result){
                console.log('Data saved successfully');
                window.location.href = '/grafana_module/grafana_userdashboards/editor/' + result.data.id;
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.loadContainers();
    });