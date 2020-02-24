angular.module('openITCOCKPIT')
    .controller('RotationsIndexController', function($scope, $http, SortService, MassChangeService){

        SortService.setSort('Rotations.name');
        SortService.setDirection('asc');
        $scope.currentPage = 1;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                rotation: {
                    name: '',
                    interval: '',
                },
            };
        };
        /*** Filter end ***/

        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/map_module/rotations/delete/';

        $scope.showFilter = false;
        $scope.load = function(){
            $http.get('/map_module/rotations/index.json', {
                params: {
                    'angular': true,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[Rotations.name]': $scope.filter.rotation.name,
                    'filter[Rotations.interval]': $scope.filter.rotation.interval
                }
            }).then(function(result){
                $scope.rotations = result.data.all_rotations;
                $scope.paging = result.data.paging;
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
            if($scope.rotations){
                for(var key in $scope.rotations){
                    if($scope.rotations[key].allowEdit){
                        var id = $scope.rotations[key].id;
                        $scope.massChange[id] = true;
                    }
                }
            }
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.rotations){
                for(var id in selectedObjects){
                    if(id == $scope.rotations[key].id){
                        objects[id] = $scope.rotations[key].name;
                    }

                }
            }
            return objects;
        };

        $scope.getObjectForDelete = function(rotation){
            var object = {};
            object[rotation.id] = rotation.name;
            return object;
        };

        $scope.changepage = function(page){
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        defaultFilter();
        SortService.setCallback($scope.load);
        $scope.load();

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
