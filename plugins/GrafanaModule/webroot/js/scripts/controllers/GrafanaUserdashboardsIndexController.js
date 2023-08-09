angular.module('openITCOCKPIT')
    .controller('Grafana_userdashboardsIndexController', function($scope, $http, SortService, MassChangeService, $state){

        SortService.setSort('GrafanaUserdashboards.name');
        SortService.setDirection('asc');
        $scope.currentPage = 1;
        $scope.useScroll = true;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                GrafanaUserdashboards: {
                    name: ''
                }
            };
        };
        /*** Filter end ***/

        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/grafana_module/grafana_userdashboards/delete/';

        $scope.showFilter = false;

        $scope.load = function(){
            $http.get("/grafana_module/grafana_userdashboards/index.json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[GrafanaUserdashboards.name]': $scope.filter.GrafanaUserdashboards.name
                }
            }).then(function(result){
                $scope.allUserdashboards = result.data.all_userdashboards;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;

            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };


        $scope.triggerFilter = function(){
            $scope.showFilter = !$scope.showFilter === true;
        };

        $scope.resetFilter = function(){
            defaultFilter();
            $scope.undoSelection();
        };

        $scope.undoSelection = function(){
            MassChangeService.clearSelection();
            $scope.massChange = MassChangeService.getSelected();
            $scope.selectedElements = MassChangeService.getCount();
        };

        $scope.selectAll = function(){
            if($scope.allUserdashboards){
                for(var key in $scope.allUserdashboards){
                    if($scope.allUserdashboards[key].allowEdit){
                        var id = $scope.allUserdashboards[key].id;
                        $scope.massChange[id] = true;
                    }
                }
            }
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.allUserdashboards){
                for(var id in selectedObjects){
                    if(id == $scope.allUserdashboards[key].id){
                        objects[id] = $scope.allUserdashboards[key].name;
                    }
                }
            }
            return objects;
        };

        $scope.getObjectForDelete = function(GrafanaUserdashboard){
            var object = {};
            object[GrafanaUserdashboard.id] = GrafanaUserdashboard.name;
            return object;
        };

        $scope.linkForCopy = function(){
            var ids = Object.keys(MassChangeService.getSelected());
            return ids.join(',');
        };

        $scope.changepage = function(page){
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.changeMode = function(val){
            $scope.useScroll = val;
            $scope.load();
        };

        // This method can now be called with a singel id, or an object with multiple ids
        // where the key of the object has to be the ID of the dashboard you want to sync
        $scope.synchronizeWithGrafana = function(_ids){
            // Convert singel ID into an object
            // For example when synchronize is selected from the dropdown menu
            var ids = {};
            if(typeof _ids !== "object"){
                ids[_ids] = 'Dashboard';
            }else{
                ids = _ids;
            }

            var count = Object.keys(ids).length;
            var i = 0;
            var issueCount = 0;

            if(count === 0){
                return;
            }

            $('#synchronizeWithGrafanaModal').modal('show');
            for(var id in ids){
                $scope.syncError = false;
                var data = {
                    id: id
                };

                $http.post("/grafana_module/grafana_userdashboards/synchronizeWithGrafana.json?angular=true", data).then(function(result){
                    i++;
                    if(result.data.success){
                        if(i === count && issueCount === 0){
                            new Noty({
                                theme: 'metroui',
                                type: 'success',
                                text: 'Synchronization successfully',
                                timeout: 3500
                            }).show();
                            $('#synchronizeWithGrafanaModal').modal('hide');
                            $scope.load();
                            return;
                        }
                    }else{
                        $scope.syncError = result.data.message;
                    }
                }, function errorCallback(result){
                    i++;
                    issueCount++;
                    $scope.syncError = result.data.message;
                });
            }

        };

        $scope.$watch('filter', function(){
            $scope.currentPage = 1;
            $scope.undoSelection();
            $scope.load();
        }, true);

        $scope.$watch('massChange', function(){
            MassChangeService.setSelected($scope.massChange);
            $scope.selectedElements = MassChangeService.getCount();
        }, true);

        defaultFilter();
        SortService.setCallback($scope.load);

    });
