angular.module('openITCOCKPIT')
    .controller('SystemfailuresIndexController', function($scope, $http, SortService, QueryStringService, MassChangeService){
        SortService.setSort(QueryStringService.getValue('sort', 'Systemfailures.start_time'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));
        $scope.currentPage = 1;

        $scope.useScroll = true;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Systemfailures: {
                    comment: '',
                },
                full_name: ''
            };
        };
        /*** Filter end ***/

        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/systemfailures/delete/';

        $scope.init = true;
        $scope.showFilter = false;


        $scope.load = function(){

            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Systemfailures.comment]': $scope.filter.Systemfailures.comment,
                'filter[full_name]': $scope.filter.full_name
            };


            $http.get("/systemfailures/index.json", {
                params: params
            }).then(function(result){
                $scope.systemfailures = result.data.all_systemfailures;
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
            if($scope.systemfailures){
                for(var key in $scope.systemfailures){
                    var id = $scope.systemfailures[key].id;
                    $scope.massChange[id] = true;
                }
            }
        };

        $scope.undoSelection = function(){
            MassChangeService.clearSelection();
            $scope.massChange = MassChangeService.getSelected();
            $scope.selectedElements = MassChangeService.getCount();
        };

        $scope.getObjectForDelete = function(systemfailure){
            var object = {};
            object[systemfailure.id] = '[ ' + systemfailure.start_time + ' ] ' + systemfailure.comment;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.systemfailures){
                for(var id in selectedObjects){
                    if(id == $scope.systemfailures[key].id){
                        objects[id] = '[ ' + $scope.systemfailures[key].start_time + ' ] ' + $scope.systemfailures[key].comment;
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