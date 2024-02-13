angular.module('openITCOCKPIT')
    .controller('DashboardAllocationsIndexController', function($scope, $http, $rootScope, $stateParams, SortService, MassChangeService, QueryStringService){

        SortService.setSort(QueryStringService.getValue('sort', 'DashboardTabAllocations.name'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));
        $scope.currentPage = 1;

        $scope.useScroll = true;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                DashboardTabAllocations: {
                    name: '',
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/DashboardAllocations/delete/';

        $scope.init = true;
        $scope.showFilter = false;

        $scope.load = function(){
            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[DashboardTabAllocations.name]': $scope.filter.DashboardTabAllocations.name,
            };

            $http.get("/DashboardAllocations/index.json", {
                params: params
            }).then(function(result){
                $scope.all_dashboardtab_allocations = result.data.all_dashboardtab_allocations;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;
            });
        };

        $scope.triggerFilter = function(){
            $scope.showFilter = !$scope.showFilter === true;
        };

        $scope.resetFilter = function(){
            defaultFilter();
            $scope.undoSelection();
        };

        $scope.selectAll = function(){
            if($scope.all_dashboardtab_allocations){
                for(var key in $scope.all_dashboardtab_allocations){
                    var id = $scope.all_dashboardtab_allocations[key].id;
                    $scope.massChange[id] = true;
                }
            }
        };

        $scope.undoSelection = function(){
            MassChangeService.clearSelection();
            $scope.massChange = MassChangeService.getSelected();
            $scope.selectedElements = MassChangeService.getCount();
        };

        $scope.getObjectForDelete = function(allocation){
            var object = {};
            object[allocation.id] = allocation.name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.all_dashboardtab_allocations){
                for(var id in selectedObjects){
                    if(id == $scope.all_dashboardtab_allocations[key].id){
                        objects[id] = $scope.all_dashboardtab_allocations[key].name;
                    }
                }
            }
            return objects;
        };


        $scope.linkForCopy = function(){
            var ids = Object.keys(MassChangeService.getSelected());
            return ids.join(',');
        };


        $scope.changepage = function(page){
            $scope.undoSelection();
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.changeMode = function(val){
            $scope.useScroll = val;
            $scope.load();
        };

        //Fire on page load
        defaultFilter();
        SortService.setCallback($scope.load);

        $scope.$watch('filter', function(){
            $scope.currentPage = 1;
            $scope.undoSelection();
            $scope.load();
        }, true);


        $scope.$watch('massChange', function(){
            MassChangeService.setSelected($scope.massChange);
            $scope.selectedElements = MassChangeService.getCount();
        }, true);

    });
