angular.module('openITCOCKPIT')
    .controller('DeletedHostsIndexController', function($scope, $http, $httpParamSerializer, SortService, QueryStringService){
        SortService.setSort(QueryStringService.getValue('sort', 'DeletedHosts.created'));
        SortService.setDirection(QueryStringService.getValue('direction', 'desc'));
        $scope.currentPage = 1;
        $scope.useScroll = true;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                DeletedHost: {
                    name: QueryStringService.getValue('filter[DeletedHost.name]', '')
                }
            };
        };
        /*** Filter end ***/


        $scope.init = true;
        $scope.showFilter = false;


        $scope.load = function(){
            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[DeletedHosts.name]': $scope.filter.DeletedHost.name
            };

            $http.get("/deletedHosts/index.json", {
                params: params
            }).then(function(result){
                $scope.hosts = result.data.deletedHosts;
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

    });
