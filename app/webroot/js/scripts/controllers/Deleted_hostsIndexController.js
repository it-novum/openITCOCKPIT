angular.module('openITCOCKPIT')
    .controller('Deleted_hostsIndexController', function($scope, $http, $httpParamSerializer, SortService, QueryStringService){
        SortService.setSort(QueryStringService.getValue('sort', 'DeletedHost.created'));
        SortService.setDirection(QueryStringService.getValue('direction', 'desc'));
        $scope.currentPage = 1;


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
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[DeletedHost.name]': $scope.filter.DeletedHost.name
            };

            $http.get("/deleted_hosts/index.json", {
                params: params
            }).then(function(result){
                $scope.hosts = result.data.deletedHosts;
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
