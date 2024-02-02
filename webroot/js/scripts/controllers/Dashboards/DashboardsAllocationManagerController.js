angular.module('openITCOCKPIT')
    .controller('DashboardsAllocationManagerController', function($scope, $http, $rootScope, SortService, MassChangeService, QueryStringService, NotyService){
        //
        SortService.setSort(QueryStringService.getValue('sort', 'name'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));

        // I am the array of available dashboardTabs.
        $scope.dashboardTabs = [];

        // I am ...
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/dashboards/deallocate/';
        $scope.useScroll = true;

        // I am the filter transport object.
        $scope.filter = {
            DashboardTab: {
                name: ''
            },
            full_name: ''
        };

        var defaultFilter = function(){
            $scope.filter = {
                DashboardTab: {
                    name: ''
                },
                full_name: ''
            };
        };

        // I will prepeare the view.
        $scope.load = function(){
            $http.get("/dashboards/allocationManager.json?angular=true", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[DashboardTabs.name]': $scope.filter.DashboardTab.name,
                    'filter[full_name]': $scope.filter.full_name
                }
            }).then(function(result){
                $scope.dashboardTabs = result.data.dashboardTabs;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
            });
        }

        //
        $scope.changepage = function(page){
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        //
        $scope.changeMode = function(val){
            $scope.useScroll = val;
            $scope.load();
        };

        $scope.getObjectForDelete = function(dashboardTab){
            var object = {};
            object[dashboardTab.id] = dashboardTab.name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.users){
                for(var id in selectedObjects){
                    if(id == $scope.users[key].id){
                        if($scope.users[key].allow_edit === true){
                            objects[id] = $scope.users[key].full_name;
                        }
                    }
                }
            }
            return objects;
        };

        SortService.setCallback($scope.load);
        $scope.load();

        // Trigger filter show / Hide.
        $scope.triggerFilter = function(){
            $scope.showFilter = !$scope.showFilter === true;
        };

        // Duh...
        $scope.resetFilter = function(){
            defaultFilter();
        };


        $scope.undoSelection = function(){
            MassChangeService.clearSelection();
            $scope.massChange = MassChangeService.getSelected();
            $scope.selectedElements = MassChangeService.getCount();
        };

        $scope.selectAll = function(){
            if($scope.dashboardTabs){
                for(var key in $scope.dashboardTabs){
                    var id = $scope.dashboardTabs[key].id;
                    $scope.massChange[id] = true;
                }
            }
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.dashboardTabs){
                for(var id in selectedObjects){
                    if(id == $scope.dashboardTabs[key].id){
                        objects[id] = $scope.dashboardTabs[key].name;
                    }
                }
            }
            return objects;
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
    });
