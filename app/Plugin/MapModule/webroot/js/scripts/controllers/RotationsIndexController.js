angular.module('openITCOCKPIT')
    .controller('RotationsIndexController', function($scope, $http, SortService, MassChangeService){

        SortService.setSort('Rotation.name');
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
            $http.get('/map_module/rotations/index.json',{
                params: {
                    'angular': true,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[Rotation.name]': $scope.filter.rotation.name,
                    'filter[Rotation.interval]': $scope.filter.rotation.interval
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
                    if($scope.rotations[key].Rotation.allowEdit){
                        var id = $scope.rotations[key].Rotation.id;
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
                    if(id == $scope.rotations[key].Rotation.id){
                        objects[id] = $scope.rotations[key].Rotation.name;
                    }

                }
            }
            return objects;
        };

        $scope.getObjectForDelete = function(rotation){
            var object = {};
            object[rotation.Rotation.id] = rotation.Rotation.name;
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