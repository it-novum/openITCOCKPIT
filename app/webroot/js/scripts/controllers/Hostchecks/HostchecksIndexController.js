angular.module('openITCOCKPIT')
    .controller('HostchecksIndexController', function($scope, $http, $rootScope, $httpParamSerializer, SortService, QueryStringService, $stateParams){

        SortService.setSort(QueryStringService.getValue('sort', 'Hostchecks.start_time'));
        SortService.setDirection(QueryStringService.getValue('direction', 'desc'));
        $scope.currentPage = 1;

        $scope.id = $stateParams.id;

        $scope.useScroll = true;

        var now = new Date();

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Hostchecks: {
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

        $scope.hostBrowserMenuConfig = {
            autoload: true,
            hostId: $scope.id,
            includeHoststatus: true
        };

        $scope.init = true;
        $scope.showFilter = false;


        $scope.load = function(){

            var state_type = '';
            if($scope.filter.Hostchecks.state_types.soft ^ $scope.filter.Hostchecks.state_types.hard){
                state_type = 0;
                if($scope.filter.Hostchecks.state_types.hard === true){
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
                    'filter[Hostchecks.output]': $scope.filter.Hostchecks.output,
                    'filter[Hostchecks.state][]': $rootScope.currentStateForApi($scope.filter.Hostchecks.state),
                    'filter[Hostchecks.state_type]': state_type,
                    'filter[from]': $scope.filter.from,
                    'filter[to]': $scope.filter.to
                }
            }).then(function(result){
                $scope.hostchecks = result.data.all_hostchecks;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;

                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
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

        //Fire on page load
        defaultFilter();
        SortService.setCallback($scope.load);

        $scope.$watch('filter', function(){
            $scope.currentPage = 1;
            $scope.load();
        }, true);

    });