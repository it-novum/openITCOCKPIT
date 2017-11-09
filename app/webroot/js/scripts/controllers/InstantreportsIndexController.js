angular.module('openITCOCKPIT')
    .controller('InstantreportsIndexController', function($scope, $http, SortService){

        SortService.setSort('Instantreport.name');
        SortService.setDirection('asc');
        $scope.currentPage = 1;

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                instantreport: {
                    name: '',
                    evaluation: {
                        hosts: false,
                        hostsandservices: false,
                        services: false
                    },
                    type: {
                        hostgroups: false,
                        hosts: false,
                        servicegroups: false,
                        services: false
                    }
                }
            };
        };
        /*** Filter end ***/
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/instantreports/delete/';

        $scope.init = true;
        $scope.showFilter = false;
        $scope.load = function(){
            var evaluationTypes = [];
            for(var key in $scope.filter.instantreport.evaluation){
                if($scope.filter.instantreport.evaluation[key] !== false){
                    evaluationTypes.push($scope.filter.instantreport.evaluation[key]);
                }
            }

            var objectTypes = [];
            for(var key in $scope.filter.instantreport.type){
                if($scope.filter.instantreport.type[key] !== false){
                    objectTypes.push($scope.filter.instantreport.type[key]);
                }
            }

            $http.get("/instantreports/index.json", {
                params: {
                    'angular': true,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    'direction': SortService.getDirection(),
                    'filter[Instantreport.name]': $scope.filter.instantreport.name,
                    'filter[Instantreport.evaluation][]': evaluationTypes,
                    'filter[Instantreport.type][]': objectTypes
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
