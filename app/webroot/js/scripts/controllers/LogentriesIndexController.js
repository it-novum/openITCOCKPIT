angular.module('openITCOCKPIT')
    .controller('LogentriesIndexController', function($scope, $http, $httpParamSerializer, SortService, QueryStringService){
        SortService.setSort(QueryStringService.getValue('sort', 'Logentry.logentry_time'));
        SortService.setDirection(QueryStringService.getValue('direction', 'desc'));
        $scope.currentPage = 1;


        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Logentry: {
                    logentry_data: '',
                    logentry_type: ''
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
                'filter[Logentry.logentry_data]': $scope.filter.Logentry.logentry_data
            };

            if($scope.filter.Logentry.logentry_type.length > 0){
                params['filter[Logentry.logentry_type][]'] = $scope.filter.Logentry.logentry_type;
            }

            $http.get("/logentries/index.json", {
                params: params
            }).then(function(result){
                $scope.logentries = result.data.all_logentries;
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
