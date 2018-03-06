angular.module('openITCOCKPIT')
    .controller('SystemdowntimesServiceController', function($scope, $http, $rootScope, $httpParamSerializer, SortService, QueryStringService, MassChangeService){

        SortService.setSort(QueryStringService.getValue('sort', 'Systemdowntime.from_time'));
        SortService.setDirection(QueryStringService.getValue('direction', 'desc'));
        $scope.currentPage = 1;



        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Host: {
                    name: ''
                },
                Systemdowntime: {
                    author: '',
                    comment: ''
                },
                servicename: ''
            };
        };
        /*** Filter end ***/

        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/systemdowntimes/delete/';

        $scope.init = true;
        $scope.showFilter = false;


        $scope.load = function(){
            $http.get("/systemdowntimes/service.json", {
                params: {
                    'angular': true,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[Host.name]': $scope.filter.Host.name,
                    'filter[servicename]': $scope.filter.servicename,
                    'filter[Systemdowntime.author]': $scope.filter.Systemdowntime.author,
                    'filter[Systemdowntime.comment]': $scope.filter.Systemdowntime.comment
                }
            }).then(function(result){
                $scope.systemdowntimes = result.data.all_service_recurring_downtimes;
                $scope.paging = result.data.paging;
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
                    if($scope.systemdowntimes[key].Host.allow_edit){
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
            object[downtime.Systemdowntime.id] = downtime.Host.hostname;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.systemdowntimes){
                for(var id in selectedObjects){
                    if(id == $scope.systemdowntimes[key].Systemdowntime.id){
                        objects[id] = $scope.systemdowntimes[key].Host.hostname;
                    }
                }
            }
            return objects;
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