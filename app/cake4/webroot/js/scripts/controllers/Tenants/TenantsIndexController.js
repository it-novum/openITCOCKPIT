angular.module('openITCOCKPIT')
    .controller('TenantsIndexController', function($scope, $http, SortService, MassChangeService){
        SortService.setSort('Containers.name');
        SortService.setDirection('asc');
        $scope.currentPage = 1;
        $scope.useScroll = true;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                containers: {
                    name: ''
                },
                tenants: {
                    description: '',
                    is_active: ''
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/tenants/delete/';

        $scope.init = true;
        $scope.showFilter = false;
        $scope.load = function(){
            $http.get("/tenants/index.json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[Containers.name]': $scope.filter.containers.name,
                    'filter[Tenants.description]': $scope.filter.tenants.description
                }
            }).then(function(result){
                $scope.tenants = result.data.all_tenants;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;
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

        $scope.selectAll = function(){
            if($scope.tenants){
                for(var key in $scope.tenants){
                    if($scope.tenants[key].Tenant.allowEdit){
                        var id = $scope.tenants[key].Tenant.id;
                        $scope.massChange[id] = true;
                    }
                }
            }
        };

        $scope.undoSelection = function(){
            MassChangeService.clearSelection();
            $scope.massChange = MassChangeService.getSelected();
            $scope.selectedElements = MassChangeService.getCount();
        };

        $scope.getObjectForDelete = function(tenant){
            var object = {};
            object[tenant.Tenant.id] = tenant.Tenant.container.name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.tenants){
                for(var id in selectedObjects){
                    if(id == $scope.tenants[key].Tenant.id){
                        objects[id] = $scope.tenants[key].Tenant.container.name;
                    }
                }
            }
            return objects;
        };

        $scope.changeMode = function(val){
            $scope.useScroll = val;
            $scope.load();
        };

        $scope.changepage = function(page){
            $scope.undoSelection();
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
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