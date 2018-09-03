angular.module('openITCOCKPIT')
    .controller('Grafana_userdashboardsIndexController', function($scope, $http, QueryStringService){

        $scope.load = function(){
            $http.get("/grafana_module/grafana_configuration/index.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.config = result.data.grafanaConfiguration;
            }, function errorCallback(result){
                if(result.status === 404){
                    window.location.href = '/angular/not_found';
                }
            });
        };

        $scope.load();

    });