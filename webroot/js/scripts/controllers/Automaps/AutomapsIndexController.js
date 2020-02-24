angular.module('openITCOCKPIT')
    .controller('AutomapsIndexController', function($scope, $http, SortService, MassChangeService, QueryStringService){
        SortService.setSort(QueryStringService.getValue('sort', 'Automaps.name'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));
        $scope.currentPage = 1;

        $scope.useScroll = true;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Automaps: {
                    name: '',
                    description: ''
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/automaps/delete/';

        $scope.init = true;
        $scope.showFilter = false;


        $scope.load = function(){

            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Automaps.name]': $scope.filter.Automaps.name,
                'filter[Automaps.description]': $scope.filter.Automaps.description
            };

            $http.get("/automaps/index.json", {
                params: params
            }).then(function(result){
                $scope.automaps = result.data.all_automaps;
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
            if($scope.automaps){
                for(var key in $scope.automaps){
                    if($scope.automaps[key].allow_edit === true){
                        var id = $scope.automaps[key].id;
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

        $scope.getObjectForDelete = function(automap){
            var object = {};
            object[automap.id] = automap.name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.automaps){
                for(var id in selectedObjects){
                    if(id == $scope.automaps[key].id){
                        if($scope.automaps[key].allow_edit === true){
                            objects[id] = $scope.automaps[key].name;
                        }
                    }
                }
            }
            return objects;
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