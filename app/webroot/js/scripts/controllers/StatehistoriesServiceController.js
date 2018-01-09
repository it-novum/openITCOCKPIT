angular.module('openITCOCKPIT')
    .controller('StatehistoriesServiceController', function($scope, $http, $rootScope, $httpParamSerializer, SortService, QueryStringService){

        SortService.setSort(QueryStringService.getValue('sort', 'Statehistory.state_time'));
        SortService.setDirection(QueryStringService.getValue('direction', 'desc'));
        $scope.currentPage = 1;

        $scope.id = QueryStringService.getCakeId();

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                StatehistoryService: {
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
                    output: ""
                }
            };
        };
        /*** Filter end ***/

        $scope.init = true;
        $scope.showFilter = false;


        $scope.load = function(){

            var state_type = '';
            if($scope.filter.StatehistoryService.state_types.soft ^ $scope.filter.StatehistoryService.state_types.hard){
                state_type = 0;
                if($scope.filter.StatehistoryService.state_types.hard === true){
                    state_type = 1;
                }
            }

            $http.get("/statehistories/service/"+$scope.id+".json", {
                params: {
                    'angular': true,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[StatehistoryService.output]': $scope.filter.StatehistoryService.output,
                    'filter[StatehistoryService.state][]': $rootScope.currentStateForApi($scope.filter.StatehistoryService.state),
                    'filter[StatehistoryService.state_type]': state_type
                }
            }).then(function(result){
                //console.log(result.data.all_statehistories[0]["StatehistoryService"]);
                $scope.statehistories = result.data.all_statehistories;
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

        //Fire on page load
        defaultFilter();
        SortService.setCallback($scope.load);

        $scope.$watch('filter', function(){
            $scope.currentPage = 1;
            $scope.load();
        }, true);

    });