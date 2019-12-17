angular.module('openITCOCKPIT')
    .controller('MapsCopyController', function($scope, $http, $stateParams, $state, NotyService, RedirectService){

        var ids = $stateParams.ids.split(',');

        if(ids.length === 0 || ids[0] === ''){
            //No ids to copy given - redirect
            RedirectService.redirectWithFallback('MapsIndex');
            return;
        }

        $scope.load = function(){
            $http.get("/map_module/maps/copy/" + ids.join('/') + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.sourceMaps = [];
                for(var key in result.data.maps){
                    $scope.sourceMaps.push({
                        Source: {
                            id: result.data.maps[key].id,
                            name: result.data.maps[key].name,
                            title: result.data.maps[key].title,
                            refresh_interval: result.data.maps[key].refresh_interval / 1000
                        },
                        Map: {
                            name: result.data.maps[key].name,
                            title: result.data.maps[key].title,
                            refresh_interval: result.data.maps[key].refresh_interval / 1000
                        }
                    });
                }
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.copy = function(){
            $http.post("/map_module/maps/copy/.json?angular=true",
                {
                    data: $scope.sourceMaps
                }
            ).then(function(result){
                NotyService.genericSuccess();
                RedirectService.redirectWithFallback('MapsIndex');
            }, function errorCallback(result){
                //Print errors
                NotyService.genericError();
                $scope.sourceMaps = result.data.result;
            });
        };

        //Fire on page load
        $scope.load();
    });
