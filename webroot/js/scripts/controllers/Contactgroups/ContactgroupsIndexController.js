angular.module('openITCOCKPIT')
    .controller('ContactgroupsIndexController', function($scope, $http, $rootScope, $stateParams, SortService, MassChangeService, QueryStringService){
        $rootScope.lastObjectName = null;

        SortService.setSort(QueryStringService.getValue('sort', 'Containers.name'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));
        $scope.currentPage = 1;

        $scope.useScroll = true;


        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Containers: {
                    name: ''
                },
                Contactgroups: {
                    id: QueryStringService.getStateValue($stateParams, 'id', []),
                    description: ''
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/contactgroups/delete/';

        $scope.init = true;
        $scope.showFilter = false;


        var buildUrl = function(baseUrl){
            var ids = Object.keys(MassChangeService.getSelected());
            return baseUrl + ids.join('/');
        };


        $scope.load = function(){

            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Contactgroups.id][]': $scope.filter.Contactgroups.id,
                'filter[Containers.name]': $scope.filter.Containers.name,
                'filter[Contactgroups.description]': $scope.filter.Contactgroups.description
            };

            $http.get("/contactgroups/index.json", {
                params: params
            }).then(function(result){
                $scope.contactgroups = result.data.all_contactgroups;
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
            if($scope.contactgroups){
                for(var key in $scope.contactgroups){
                    if($scope.contactgroups[key].Contactgroup.allow_edit === true){
                        var id = $scope.contactgroups[key].Contactgroup.id;
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

        $scope.getObjectForDelete = function(contactgroup){
            var object = {};
            object[contactgroup.Contactgroup.id] = contactgroup.Container.name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.contactgroups){
                for(var id in selectedObjects){
                    if(id == $scope.contactgroups[key].Contactgroup.id){
                        if($scope.contactgroups[key].Contactgroup.allow_edit === true){
                            objects[id] = $scope.contactgroups[key].Container.name;
                        }
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
