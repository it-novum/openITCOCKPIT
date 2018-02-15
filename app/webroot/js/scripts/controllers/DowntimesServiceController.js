angular.module('openITCOCKPIT')
    .controller('DowntimesServiceController', function($scope, $http, $rootScope, $httpParamSerializer, SortService, QueryStringService, MassChangeService){

        SortService.setSort(QueryStringService.getValue('sort', 'DowntimeService.scheduled_start_time'));
        SortService.setDirection(QueryStringService.getValue('direction', 'desc'));
        $scope.currentPage = 1;


        var now = new Date();

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                DowntimeService: {
                    author_name: '',
                    comment_data: '',
                    was_cancelled: false,
                    was_not_cancelled: false
                },
                Host: {
                    name: ''
                },
                Service: {
                    name: ''
                },
                from: date('d.m.Y H:i', now.getTime()/1000 - (3600 * 24 * 30)),
                to: date('d.m.Y H:i', now.getTime()/1000 + (3600 * 24 * 30 * 2)),
                isRunning: false,
                hideExpired: true
            };
        };
        /*** Filter end ***/

        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/downtimes/delete/';

        $scope.init = true;
        $scope.showFilter = false;


        $scope.load = function(){
            var wasCancelled = '';
            if($scope.filter.DowntimeService.was_cancelled ^ $scope.filter.DowntimeService.was_not_cancelled){
                wasCancelled = $scope.filter.DowntimeService.was_cancelled === true;
            }
            $http.get("/downtimes/service.json", {
                params: {
                    'angular': true,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[DowntimeService.author_name]': $scope.filter.DowntimeService.author_name,
                    'filter[DowntimeService.comment_data]': $scope.filter.DowntimeService.comment_data,
                    'filter[DowntimeService.was_cancelled]': wasCancelled,
                    'filter[Host.name]': $scope.filter.Host.name,
                    'filter[Service.name]': $scope.filter.Service.name,
                    'filter[from]': $scope.filter.from,
                    'filter[to]': $scope.filter.to,
                    'filter[hideExpired]': $scope.filter.hideExpired,
                    'filter[isRunning]': $scope.filter.isRunning
                }
            }).then(function(result){
                $scope.downtimes = result.data.all_service_downtimes;
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
            if($scope.downtimes){
                for(var key in $scope.downtimes){
                    if($scope.downtimes[key].DowntimeService.allowEdit && $scope.downtimes[key].DowntimeService.isCancellable){
                        var id = $scope.downtimes[key].DowntimeService.internalDowntimeId;
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
            object[downtime.DowntimeService.internalDowntimeId] = downtime.Service.name;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.downtimes){
                for(var id in selectedObjects){
                    if(id == $scope.downtimes[key].DowntimeService.internalDowntimeId){
                        objects[id] = $scope.downtimes[key].Service.name;
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