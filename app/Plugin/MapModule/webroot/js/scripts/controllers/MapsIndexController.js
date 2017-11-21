angular.module('openITCOCKPIT')
    .controller('MapsIndexController', function($scope, $http, SortService, MassChangeService){


        $scope.init = true;
        $scope.load = function(){
            $http.get('/map_module/maps/index.json',{
                params: {
                    'angular': true,
                    'sort': SortService.getSort(),
                    'page': $scope.currentPage,
                    //'direction': SortService.getDirection(),
                    //'filter[Container.name]': $scope.filter.container.name,
                    //'filter[Hostgroup.description]': $scope.filter.hostgroup.description
                }
            }).then(function(result){
                $scope.maps = result.data.all_maps;
                $scope.paging = result.data.paging;
                $scope.init = false;
            });
        }

        $scope.$watch('maps', function(){
            console.log($scope.maps);
        })

        $scope.load();
    });