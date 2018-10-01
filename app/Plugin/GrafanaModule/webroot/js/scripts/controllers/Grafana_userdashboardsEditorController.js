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

        $scope.addRow = function(){
            var data = {
                GrafanaUserdashboardPanel: {
                    row: parseInt($scope.rowId, 10), //int
                    userdashboard_id: $scope.id //int
                }
            };

            $http.post("/grafana_module/grafana_userdashboards/addRow.json?angular=true", data
            ).then(function(result){
                if(result.data.hasOwnProperty('panel')){
                    new Noty({
                        theme: 'metroui',
                        type: 'success',
                        text: 'Panel added successfully',
                        timeout: 3500
                    }).show();

                    //Add new created panel to local json
                    $scope.row.push(result.data.panel);
                    setPanelClass();
                }

            }, function errorCallback(result){
                new Noty({
                    theme: 'metroui',
                    type: 'error',
                    text: 'Error while adding panel',
                    timeout: 3500
                }).show();
            });
        };

        $scope.load();


    });
