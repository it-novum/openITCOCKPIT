angular.module('openITCOCKPIT')
    .controller('InstantreportsIndexController', function($scope, $http, SortService){

        SortService.setSort('Instantreport.name');
        SortService.setDirection('asc');
        $scope.currentPage = 1;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                instantreport: {
                    name: ''
                }
            };
        };
        /*** Filter end ***/
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/instantreports/delete/';

        $scope.init = true;
        $scope.showFilter = false;
        $scope.load = function(){
            $http.get("/instantreports/index.json", {
                params: {
                    'angular': true,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[Instantreport.name]': $scope.filter.instantreport.name
                }
            }).then(function(result){
                $scope.instantreports = result.data.instantreports;
                $scope.paging = result.data.paging;
                $scope.init = false;
            });
        };

        $scope.triggerFilter = function(){
            if($scope.showFilter === true){
                $scope.showFilter = false;
            }else{
                $scope.showFilter = true;
            }
        };

        $scope.resetFilter = function(){
            defaultFilter();
        };

        $scope.changepage = function(page){
            console.log('CurrentPage : '+$scope.currentPage);
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };


        //Fire on page load
        defaultFilter();
        SortService.setCallback($scope.load);

        $scope.$watch('filter', function(){
            $scope.load();
        }, true);
    });
