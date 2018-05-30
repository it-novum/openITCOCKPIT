angular.module('openITCOCKPIT')
    .controller('MapsIndexController', ['$scope', '$http', 'SortService', 'MassChangeService', function($scope, $http, SortService, MassChangeService){

        SortService.setSort('Map.name');
        SortService.setDirection('asc');
        $scope.currentPage = 1;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                map: {
                    name: '',
                    title: '',
                },
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/map_module/maps/delete/';

        $scope.showFilter = false;
        $scope.load = function(){
            $http.get('/map_module/maps/index.json', {
                params: {
                    'angular': true,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[Map.name]': $scope.filter.map.name,
                    'filter[Map.title]': $scope.filter.map.title
                }
            }).then(function(result){
                $scope.maps = result.data.all_maps;
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
            if($scope.maps){
                for(var key in $scope.maps){
                    if($scope.maps[key].Map.allowEdit){
                        var id = $scope.maps[key].Map.id;
                        $scope.massChange[id] = true;
                        $scope.selectedElements = MassChangeService.getCount();
                    }
                }
            }
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.maps){
                for(var id in selectedObjects){
                    if(id == $scope.maps[key].Map.id){
                        objects[id] = $scope.maps[key].Map.name;
                    }

                }
            }
            return objects;
        };

        $scope.getObjectForDelete = function(map){
            var object = {};
            object[map.Map.id] = map.Map.name + '/' + map.Map.title;
            return object;
        };

        $scope.changepage = function(page){
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.linkForCopy = function(){
            var baseUrl = '/map_module/maps/copy/';
            return buildUrl(baseUrl);

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

    }]);
