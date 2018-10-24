angular.module('openITCOCKPIT').directive('grafanaRow', function($http){
    return {
        restrict: 'E',
        templateUrl: '/grafana_module/grafana_userdashboards/grafanaRow.html',
        scope: {
            'id': '=',
            'row': '=',
            'rowId': '=',
            'removeRowCallback': '=',
            'grafanaUnits': '=',
            'containerId': '='
        },
        controller: function($scope){
            $scope.addPanel = function(){
                var data = {
                    GrafanaUserdashboardPanel: {
                        row: parseInt($scope.rowId, 10), //int
                        userdashboard_id: $scope.id //int
                    }
                };

                $http.post("/grafana_module/grafana_userdashboards/addPanel.json?angular=true", data
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

            $scope.removePanel = function(panelId){
                $http.post("/grafana_module/grafana_userdashboards/removePanel.json?angular=true",
                    {
                        id: parseInt(panelId, 10)
                    }
                ).then(function(result){
                    if(result.data.success){
                        new Noty({
                            theme: 'metroui',
                            type: 'success',
                            text: 'Panel removed successfully',
                            timeout: 3500
                        }).show();

                        //Remove panel from local json
                        removePanelFromRow(panelId);
                        setPanelClass();
                    }else{
                        new Noty({
                            theme: 'metroui',
                            type: 'error',
                            text: 'Error while removing panel',
                            timeout: 3500
                        }).show();
                    }

                }, function errorCallback(result){
                    new Noty({
                        theme: 'metroui',
                        type: 'error',
                        text: 'Error while removing panel',
                        timeout: 3500
                    }).show();
                });
            };

            $scope.removeRow = function(){
                //Remove all panels and the row is gone as well
                var panelIds = [];
                for(var i in $scope.row){
                    var id = parseInt($scope.row[i].id);
                    if(isNaN(id) === false){
                        panelIds.push($scope.row[i].id);
                    }
                }

                $http.post("/grafana_module/grafana_userdashboards/removeRow.json?angular=true",
                    {
                        'ids': panelIds
                    }
                ).then(function(result){
                    if(result.data.success){
                        new Noty({
                            theme: 'metroui',
                            type: 'success',
                            text: 'Row removed successfully',
                            timeout: 3500
                        }).show();

                        //Call callback from parent scrope to reload data (Grafana_userdashboardsEditorController)
                        $scope.removeRowCallback();
                    }else{
                        new Noty({
                            theme: 'metroui',
                            type: 'error',
                            text: 'Error while removing row',
                            timeout: 3500
                        }).show();
                    }

                }, function errorCallback(result){
                    new Noty({
                        theme: 'metroui',
                        type: 'error',
                        text: 'Error while removing row',
                        timeout: 3500
                    }).show();
                });
            };

            var setPanelClass = function(){
                $scope.panelClass = 'col-lg-' + (12 / $scope.row.length);
            };

            var removePanelFromRow = function(panelId){
                panelId = parseInt(panelId, 10);
                for(var i in $scope.row){
                    var rowId = parseInt($scope.row[i].id, 10);
                    if(rowId === panelId){
                        $scope.row.splice(i, 1);

                    }
                }
            };

            //Dynamic panel layout
            $scope.panelClass = 'col-md-3';
            $scope.$watch('row', function(){
                setPanelClass();
            });

        },

        link: function($scope, element, attr){
        }
    };
});
