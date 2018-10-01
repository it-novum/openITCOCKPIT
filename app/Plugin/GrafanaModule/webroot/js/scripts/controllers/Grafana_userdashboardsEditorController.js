angular.module('openITCOCKPIT')
    .controller('Grafana_userdashboardsEditorController', function($scope, $http, QueryStringService){
        $scope.id = QueryStringService.getCakeId();

        $scope.load = function(){
            $http.get("/grafana_module/grafana_userdashboards/editor/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                //Convert non row arrays from objects to arrays
                //PHP API give use objects some times
                //[0,1,2] is encoded as array in php
                //[5,10,20] is encoded as object
                var data = [];
                for(var i in result.data.userdashboardData.rows){
                    if(!Array.isArray(result.data.userdashboardData.rows[i])){
                        data.push(Object.values(result.data.userdashboardData.rows[i]));
                    }else{
                        //data is an array, just push it
                        data.push(result.data.userdashboardData.rows[i]);
                    }
                }

                $scope.data = data;
            }, function errorCallback(result){
                if(result.status === 404){
                    window.location.href = '/angular/not_found';
                }
            });
        };

        $scope.load();


    });
