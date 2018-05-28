angular.module('openITCOCKPIT')
    .controller('TenantsIndexController', function($scope, $http, SortService, MassChangeService){
        SortService.setSort('Container.name');
        SortService.setDirection('asc');
        $scope.currentPage = 1;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                container: {
                    name: ''
                },
                tenant: {
                    description: '',
                    is_active:''
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
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[Container.name]': $scope.filter.container.name,
                    'filter[Tenant.description]': $scope.filter.tenant.description
                }
            }).then(function(result){
                $scope.tenants = result.data.all_tenants;
                $scope.paging = result.data.paging;
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

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.tenants){
                for(var id in selectedObjects){
                    if(id == $scope.tenants[key].Tenant.id){
                        objects[id] = $scope.tenants[key].Container.name;
                    }
                }
            }
            return objects;
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