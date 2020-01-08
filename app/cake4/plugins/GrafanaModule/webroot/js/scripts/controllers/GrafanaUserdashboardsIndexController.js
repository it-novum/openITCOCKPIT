angular.module('openITCOCKPIT')
    .controller('Grafana_userdashboardsIndexController', function($scope, $http, SortService, MassChangeService, $state){

        SortService.setSort('GrafanaUserdashboard.name');
        SortService.setDirection('asc');
        $scope.currentPage = 1;
        $scope.useScroll = true;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                GrafanaUserdashboard: {
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
                    'filter[GrafanaUserdashboard.name]': $scope.filter.GrafanaUserdashboard.name
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
            if($scope.showFilter === true){
                $scope.showFilter = false;
            }else{
                $scope.showFilter = true;
            }
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
                    if($scope.allUserdashboards[key].GrafanaUserdashboard.allowEdit){
                        var id = $scope.allUserdashboards[key].GrafanaUserdashboard.id;
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
                    if(id == $scope.allUserdashboards[key].GrafanaUserdashboard.id){
                        objects[id] = $scope.allUserdashboards[key].GrafanaUserdashboard.name;
                    }
                }
            }
            return objects;
        };

        $scope.getObjectForDelete = function(GrafanaUserdashboard){
            var object = {};
            object[GrafanaUserdashboard.GrafanaUserdashboard.id] = GrafanaUserdashboard.GrafanaUserdashboard.name;
            return object;
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