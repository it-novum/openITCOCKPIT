angular.module('openITCOCKPIT')
    .controller('StatuspagesIndexController', function($scope, $q, $rootScope, $stateParams, $http, $sce, $timeout, SortService, QueryStringService){
        $rootScope.lastObjectName = null;

        SortService.setSort(QueryStringService.getValue('sort', 'Statuspages.name'));
        SortService.setDirection(QueryStringService.getValue('direction', 'asc'));
        $scope.currentPage = 1;

        $scope.useScroll = true;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Statuspages: {
                    id: QueryStringService.getStateValue($stateParams, 'id', []),
                    name: '',
                    description: ''
                }
            };
        };
        /*** Filter end ***/
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/statuspages/delete/';

        $scope.init = true;
        $scope.showFilter = false;

        $scope.load = function(){

            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Statuspages.id][]': $scope.filter.Statuspages.id,
                'filter[Statuspages.name]': $scope.filter.Statuspages.name,
                'filter[Statuspages.description]': $scope.filter.Statuspages.description,
            };

            $http.get("/statuspages/index.json", {
                params: params
            }).then(function(result){
                $scope.statuspages = result.data.all_statuspages;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;
            });
        };


        //Fire on page load
        defaultFilter();
        $scope.load();
        SortService.setCallback($scope.load);

    });
