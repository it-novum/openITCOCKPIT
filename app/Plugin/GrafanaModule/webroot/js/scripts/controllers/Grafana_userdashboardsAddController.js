angular.module('openITCOCKPIT')
    .controller('Grafana_userdashboardsAddController', function($scope, $http){
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
    });