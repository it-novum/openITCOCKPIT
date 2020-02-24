angular.module('openITCOCKPIT')
    .controller('Grafana_userdashboardsEditorController', function($scope, $http, $stateParams, $state){
        $scope.id = $stateParams.id;
        $scope.name = '';

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
                $scope.containerId = parseInt(result.data.userdashboardData.container_id, 10);

                $scope.name = result.data.userdashboardData.name;
                $scope.grafanaUnits = result.data.grafanaUnits;

            }, function errorCallback(result){
                if(result.status === 404){
                    if(result.status === 403){
                        $state.go('403');
                    }

                    if(result.status === 404){
                        $state.go('404');
                    }
                }
            });
        };


        $scope.addRow = function(){
            var data = {
                id: $scope.id
            };

            $http.post("/grafana_module/grafana_userdashboards/addRow.json?angular=true", data).then(function(result){
                if(result.data.hasOwnProperty('success')){
                    new Noty({
                        theme: 'metroui',
                        type: 'success',
                        text: 'Row added successfully',
                        timeout: 3500
                    }).show();

                    //Add new created panel to local json
                    $scope.load();
                }

            }, function errorCallback(result){
                new Noty({
                    theme: 'metroui',
                    type: 'error',
                    text: 'Error while adding row',
                    timeout: 3500
                }).show();
            });
        };

        $scope.synchronizeWithGrafana = function(){
            $('#synchronizeWithGrafanaModal').modal('show');

            $scope.syncError = false;
            var data = {
                id: $scope.id
            };

            $http.post("/grafana_module/grafana_userdashboards/synchronizeWithGrafana.json?angular=true", data).then(function(result){


                if(result.data.success){
                    new Noty({
                        theme: 'metroui',
                        type: 'success',
                        text: 'Synchronization successfully',
                        timeout: 3500
                    }).show();
                    $('#synchronizeWithGrafanaModal').modal('hide');
                    return;
                }

                $scope.syncError = result.data.message;
            }, function errorCallback(result){
                $scope.syncError = result.data.message;
            });

        };

        /**
         * Gets called from child scops, if a row was deleted.
         */
        $scope.removeRowCallback = function(){
            $scope.load();
        };


        $scope.load();


    });
