angular.module('openITCOCKPIT')
    .controller('TimeperiodsIndexController', function($scope, $http, $rootScope, SortService, MassChangeService, QueryStringService){
        $rootScope.lastObjectName = null;

        SortService.setSort(QueryStringService.getValue('sort', 'Timeperiods.name'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));
        $scope.currentPage = 1;

        $scope.useScroll = true;


        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Timeperiods: {
                    name: '',
                    description: ''
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/timeperiods/delete/';

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
                'filter[Timeperiods.name]': $scope.filter.Timeperiods.name,
                'filter[Timeperiods.description]': $scope.filter.Timeperiods.description
            };

            $http.get("/timeperiods/index.json", {
                params: params
            }).then(function(result){
                $scope.timeperiods = result.data.all_timeperiods;
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
            if($scope.timeperiods){
                for(var key in $scope.timeperiods){
                    if($scope.timeperiods[key].Timeperiod.allow_edit === true){
                        var id = $scope.timeperiods[key].Timeperiod.id;
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

        $scope.getObjectForDelete = function(timeperiod){
            var object = {};
            object[timeperiod.Timeperiod.id] = timeperiod.Timeperiod.name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.timeperiods){
                for(var id in selectedObjects){
                    if(id == $scope.timeperiods[key].Timeperiod.id){
                        if($scope.timeperiods[key].Timeperiod.allow_edit === true){
                            objects[id] = $scope.timeperiods[key].Timeperiod.name;
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