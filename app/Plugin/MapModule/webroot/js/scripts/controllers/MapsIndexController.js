angular.module('openITCOCKPIT')
    .controller('MapsIndexController', function($scope, $http, SortService, MassChangeService){

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

        $scope.showFilter = false;
        $scope.init = true;
        $scope.load = function(){
            $http.get('/map_module/maps/index.json',{
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

        $scope.undoSelection = function(){
            MassChangeService.clearSelection();
            $scope.massChange = MassChangeService.getSelected();
            $scope.selectedElements = MassChangeService.getCount();
        };

        //Fire on page load
        defaultFilter();
        SortService.setCallback($scope.load);

        $scope.$watch('filter', function(){
            $scope.undoSelection();
            $scope.load();
        }, true);

        //$scope.load();
    });