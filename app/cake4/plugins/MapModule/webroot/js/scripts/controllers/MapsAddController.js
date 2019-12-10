angular.module('openITCOCKPIT')
    .controller('MapsAddController', function($scope, $http, $state, NotyService){

        $scope.post = {
            Map: {
                name: '',
                title: '',
                refresh_interval: 90,
                container_id: []
            }
        };

        $scope.init = true;
        $scope.load = function(){
            $http.get("/map_module/maps/loadContainers.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };

        $scope.submit = function(){
            $http.post("/map_module/maps/add.json?angular=true",
                $scope.post
            ).then(function(result){
                NotyService.genericSuccess();
                $state.go('MapsIndex');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });

        };

        $scope.load();
    });