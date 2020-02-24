angular.module('openITCOCKPIT')
    .controller('SystemdowntimesNodeController', function($scope, $http, $rootScope, $httpParamSerializer, SortService, QueryStringService, MassChangeService){

        SortService.setSort(QueryStringService.getValue('sort', 'Systemdowntimes.from_time'));
        SortService.setDirection(QueryStringService.getValue('direction', 'desc'));
        $scope.currentPage = 1;
        $scope.useScroll = true;


        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Containers: {
                    name: ''
                },
                Systemdowntimes: {
                    author: '',
                    comment: ''
                }
            };
        };
        /*** Filter end ***/

        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/systemdowntimes/delete/';

        $scope.init = true;
        $scope.showFilter = false;


        $scope.load = function(){
            $http.get("/systemdowntimes/node.json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[Containers.name]': $scope.filter.Containers.name,
                    'filter[Systemdowntimes.author]': $scope.filter.Systemdowntimes.author,
                    'filter[Systemdowntimes.comment]': $scope.filter.Systemdowntimes.comment
                }
            }).then(function(result){
                $scope.systemdowntimes = result.data.all_node_recurring_downtimes;
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
        };

        $scope.changepage = function(page){
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };


        $scope.selectAll = function(){
            if($scope.systemdowntimes){
                for(var key in $scope.systemdowntimes){
                    if($scope.systemdowntimes[key].Container.allow_edit){
                        var id = $scope.systemdowntimes[key].Systemdowntime.id;
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

        $scope.getObjectForDelete = function(downtime){
            var object = {};
            object[downtime.Systemdowntime.id] = downtime.Container.name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.systemdowntimes){
                for(var id in selectedObjects){
                    if(id == $scope.systemdowntimes[key].Systemdowntime.id){
                        objects[id] = $scope.systemdowntimes[key].Container.name;
                    }
                }
            }
            return objects;
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
            $scope.load();
        }, true);

        $scope.$watch('massChange', function(){
            MassChangeService.setSelected($scope.massChange);
            $scope.selectedElements = MassChangeService.getCount();
        }, true);

    });