angular.module('openITCOCKPIT')
    .controller('LogentriesIndexController', function($scope, $http, $httpParamSerializer, SortService, QueryStringService){
        SortService.setSort(QueryStringService.getValue('sort', 'Logentry.entry_time'));
        SortService.setDirection(QueryStringService.getValue('direction', 'desc'));
        $scope.currentPage = 1;

        $scope.useScroll = true;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Logentry: {
                    logentry_data: '',
                    logentry_type: ''
                },
                Host: {
                    id: []
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
                'filter[Logentry.logentry_data]': $scope.filter.Logentry.logentry_data,
                'filter[Host.id][]': $scope.filter.Host.id
            };

            if($scope.filter.Logentry.logentry_type.length > 0){
                params['filter[Logentry.logentry_type][]'] = $scope.filter.Logentry.logentry_type;
            }

            $http.get("/logentries/index.json", {
                params: params
            }).then(function(result){
                $scope.logentries = result.data.all_logentries;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;
                $scope.init = false;
            });
        };

        $scope.loadHosts = function(searchString){
            $http.get("/hosts/loadHostsByString.json", {
                params: {
                    'angular': true,
                    'filter[Host.name]': searchString,
                    'selected[]': $scope.filter.Host.id
                }
            }).then(function(result){
                $scope.hosts = result.data.hosts;
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
        $scope.loadHosts('');

        $scope.$watch('filter', function(){
            $scope.currentPage = 1;
            $scope.load();
        }, true);

    });
