angular.module('openITCOCKPIT')
    .controller('HostchecksIndexController', function($scope, $http, $rootScope, $httpParamSerializer, SortService, QueryStringService, $stateParams, StatusHelperService, $interval){

        SortService.setSort(QueryStringService.getValue('sort', 'Hostcheck.start_time'));
        SortService.setDirection(QueryStringService.getValue('direction', 'desc'));
        $scope.currentPage = 1;

        $scope.id = $stateParams.id;

        $scope.useScroll = true;

        var now = new Date();
        var flappingInterval;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Hostcheck: {
                    state: {
                        recovery: false,
                        down: false,
                        unreachable: false
                    },
                    state_types: {
                        soft: false,
                        hard: false
                    },
                    output: ''
                },
                from: date('d.m.Y H:i', now.getTime() / 1000 - (3600 * 24 * 30)),
                to: date('d.m.Y H:i', now.getTime() / 1000 + (3600 * 24 * 30 * 2))
            };
        };
        /*** Filter end ***/

        $scope.init = true;
        $scope.showFilter = false;


        $scope.load = function(){

            var state_type = '';
            if($scope.filter.Hostcheck.state_types.soft ^ $scope.filter.Hostcheck.state_types.hard){
                state_type = 0;
                if($scope.filter.Hostcheck.state_types.hard === true){
                    state_type = 1;
                }
            }

            $http.get("/hostchecks/index/" + $scope.id + ".json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[Hostcheck.output]': $scope.filter.Hostcheck.output,
                    'filter[Hostcheck.state][]': $rootScope.currentStateForApi($scope.filter.Hostcheck.state),
                    'filter[Hostcheck.state_type]': state_type,
                    'filter[from]': $scope.filter.from,
                    'filter[to]': $scope.filter.to
                }
            }).then(function(result){
                $scope.hostchecks = result.data.all_hostchecks;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;

                $scope.init = false;
            });

            $http.get("/hosts/hostBrowserMenu/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result) {
                $scope.host = result.data.host;
                $scope.hoststatus = result.data.hoststatus;
                $scope.hostStatusTextClass = StatusHelperService.getHoststatusTextColor($scope.hoststatus.currentState);

                $scope.hostBrowserMenu = {
                    hostId: $scope.host.Host.id,
                    hostUuid: $scope.host.Host.uuid,
                    allowEdit: $scope.host.Host.allowEdit,
                    hostUrl: $scope.host.Host.host_url_replaced,
                    docuExists: result.data.docuExists,
                    isHostBrowser: false
                };
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

        $scope.changeMode = function(val){
            $scope.useScroll = val;
            $scope.load();
        };

        $scope.startFlapping = function() {
            $scope.stopFlapping();
            flappingInterval = $interval(function() {
                if ($scope.flappingState === 0) {
                    $scope.flappingState = 1;
                } else {
                    $scope.flappingState = 0;
                }
            }, 750);
        };

        $scope.stopFlapping = function() {
            if (flappingInterval) {
                $interval.cancel(flappingInterval);
            }
            flappingInterval = null;
        };

        //Fire on page load
        defaultFilter();
        SortService.setCallback($scope.load);

        $scope.$watch('filter', function(){
            $scope.currentPage = 1;
            $scope.load();
        }, true);

        $scope.$watch('hoststatus.isFlapping', function() {
            if ($scope.hoststatus) {
                if ($scope.hoststatus.hasOwnProperty('isFlapping')) {
                    if ($scope.hoststatus.isFlapping === true) {
                        $scope.startFlapping();
                    }

                    if ($scope.hoststatus.isFlapping === false) {
                        $scope.stopFlapping();
                    }

                }
            }
        });

    });