angular.module('openITCOCKPIT')
    .controller('StatehistoriesServiceController', function($scope, $http, $rootScope, $httpParamSerializer, SortService, QueryStringService, $stateParams){

        SortService.setSort(QueryStringService.getValue('sort', 'StatehistoryServices.state_time'));
        SortService.setDirection(QueryStringService.getValue('direction', 'desc'));
        $scope.currentPage = 1;

        $scope.id = $stateParams.id;
        $scope.useScroll = true;

        var now = new Date();

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                StatehistoryServices: {
                    state: {
                        ok: false,
                        warning: false,
                        critical: false,
                        unknown: false
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
            var from = new Date(now.getTime() - (3600 * 24 * 30 * 1000));
            from.setSeconds(0);
            var to = new Date(now.getTime() + (3600 * 24 * 30 * 2 * 1000));
            to.setSeconds(0);
            $scope.from_time = from;
            $scope.to_time = to;
        };
        /*** Filter end ***/

        $scope.serviceBrowserMenuConfig = {
            autoload: true,
            serviceId: $scope.id,
            includeServicestatus: true
        };

        $scope.init = true;
        $scope.showFilter = false;


        $scope.load = function(){

            var state_type = '';
            if($scope.filter.StatehistoryServices.state_types.soft ^ $scope.filter.StatehistoryServices.state_types.hard){
                state_type = 0;
                if($scope.filter.StatehistoryServices.state_types.hard === true){
                    state_type = 1;
                }
            }

            $http.get("/statehistories/service/" + $scope.id + ".json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[StatehistoryServices.output]': $scope.filter.StatehistoryServices.output,
                    'filter[StatehistoryServices.state][]': $rootScope.currentStateForApi($scope.filter.StatehistoryServices.state),
                    'filter[StatehistoryServices.state_type]': state_type,
                    'filter[from]': $scope.filter.from,
                    'filter[to]': $scope.filter.to
                }
            }).then(function(result){
                $scope.statehistories = result.data.all_statehistories;
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
