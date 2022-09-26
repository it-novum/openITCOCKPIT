angular.module('openITCOCKPIT')
    .controller('DowntimesHostController', function($scope, $http, $rootScope, $httpParamSerializer, SortService, QueryStringService, MassChangeService, $interval){

        SortService.setSort(QueryStringService.getValue('sort', 'DowntimeHosts.scheduled_start_time'));
        SortService.setDirection(QueryStringService.getValue('direction', 'desc'));
        $scope.currentPage = 1;
        $scope.interval = null;

        var now = new Date();

        $scope.useScroll = true;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                DowntimeHosts: {
                    author_name: '',
                    comment_data: '',
                    was_cancelled: false,
                    was_not_cancelled: false
                },
                Hosts: {
                    name: ''
                },
                from: date('d.m.Y H:i', now.getTime() / 1000 - (3600 * 24 * 30)),
                to: date('d.m.Y H:i', now.getTime() / 1000 + (3600 * 24 * 30 * 2)),
                isRunning: false,
                hideExpired: true
            };
            var from = new Date(now.getTime() - (3600 * 24 * 30 * 1000));
            from.setSeconds(0);
            var to = new Date(now.getTime() + (3600 * 24 * 30 * 2 * 1000));
            to.setSeconds(0);
            $scope.from_time = from;
            $scope.to_time = to;
        };
        /*** Filter end ***/

        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/downtimes/delete/';

        $scope.init = true;
        $scope.showFilter = false;


        $scope.load = function(){
            var wasCancelled = '';
            if($scope.filter.DowntimeHosts.was_cancelled ^ $scope.filter.DowntimeHosts.was_not_cancelled){
                wasCancelled = $scope.filter.DowntimeHosts.was_cancelled === true;
            }
            $http.get("/downtimes/host.json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[DowntimeHosts.author_name]': $scope.filter.DowntimeHosts.author_name,
                    'filter[DowntimeHosts.comment_data]': $scope.filter.DowntimeHosts.comment_data,
                    'filter[DowntimeHosts.was_cancelled]': wasCancelled,
                    'filter[Hosts.name]': $scope.filter.Hosts.name,
                    'filter[from]': $scope.filter.from,
                    'filter[to]': $scope.filter.to,
                    'filter[hideExpired]': $scope.filter.hideExpired,
                    'filter[isRunning]': $scope.filter.isRunning
                }
            }).then(function(result){
                $scope.downtimes = result.data.all_host_downtimes;
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
            if($scope.downtimes){
                for(var key in $scope.downtimes){
                    var id = $scope.downtimes[key].DowntimeHost.internalDowntimeId;
                    $scope.massChange[id] = true;
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
            object[downtime.DowntimeHost.internalDowntimeId] = downtime.Host.hostname;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.downtimes){
                for(var id in selectedObjects){
                    if($scope.downtimes[key].DowntimeHost.allowEdit && $scope.downtimes[key].DowntimeHost.isCancellable){
                        if(id == $scope.downtimes[key].DowntimeHost.internalDowntimeId){
                            objects[id] = $scope.downtimes[key].Host.hostname;
                        }
                    }
                }
            }
            return objects;
        };

        $scope.showHostDowntimeFlashMsg = function(){
            $scope.showFlashSuccess = true;
            $scope.autoRefreshCounter = 5;
            $scope.interval = $interval(function(){
                $scope.autoRefreshCounter--;
                if($scope.autoRefreshCounter === 0){
                    $scope.load();
                    $interval.cancel($scope.interval);
                    $scope.showFlashSuccess = false;
                }
            }, 1000);
        };

        $scope.changeMode = function(val){
            $scope.useScroll = val;
            $scope.load();
        };

        //Disable interval if object gets removed from DOM.
        $scope.$on('$destroy', function(){
            if($scope.interval !== null){
                $interval.cancel($scope.interval);
            }
        });

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

        $scope.$watch('from_time', function(dateObject){
            if(dateObject !== undefined && dateObject instanceof Date){
                var dateString = date('d.m.Y H:i', dateObject.getTime() / 1000);
                $scope.filter.from = dateString;
            }
        });
        $scope.$watch('to_time', function(dateObject){
            if(dateObject !== undefined && dateObject instanceof Date){
                var dateString = date('d.m.Y H:i', dateObject.getTime() / 1000);
                $scope.filter.to = dateString;
            }
        });

    });
