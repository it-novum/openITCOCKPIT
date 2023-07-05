angular.module('openITCOCKPIT')
    .controller('ChangecalendarsIndexController', function($scope, $http, SortService, MassChangeService){

        SortService.setSort('Changecalendars.name');
        SortService.setDirection('asc');
        $scope.currentPage = 1;
        $scope.useScroll = true;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                changecalendars: {
                    name: '',
                    description: ''
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/changecalendar_module/changecalendars/delete/';

        $scope.init = true;
        $scope.showFilter = false;
        $scope.load = function(){
            $http.get("/changecalendar_module/changecalendars/index.json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[Changecalendars.name]': $scope.filter.changecalendars.name,
                    'filter[Changecalendars.description]': $scope.filter.changecalendars.description
                }
            }).then(function(result){
                console.log(result);
                $scope.changecalendars = result.data.all_changecalendars;
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
            if($scope.changecalendars){
                for(var key in $scope.changecalendars){
                    if($scope.changecalendars[key].allowEdit){
                        var id = $scope.changecalendars[key].id;
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

        $scope.getObjectForDelete = function(calendar){
            var object = {};
            object[calendar.id] = calendar.name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.changecalendars){
                for(var id in selectedObjects){
                    if(id == $scope.changecalendars[key].id){
                        objects[id] = $scope.changecalendars[key].name;
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
