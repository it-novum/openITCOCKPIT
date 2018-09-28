angular.module('openITCOCKPIT')
    .controller('Grafana_userdashboardsEditorController', function($scope, $http, QueryStringService){
        $scope.id = QueryStringService.getCakeId();

        $scope.load = function(){
            $http.get("/grafana_module/grafana_userdashboards/editor/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                console.log(result.data);

                $scope.data = result.data.userdashboardData.rows;
            }, function errorCallback(result){
                if(result.status === 404){
                    window.location.href = '/angular/not_found';
                }
            });
        };

        $scope.load();


    });
