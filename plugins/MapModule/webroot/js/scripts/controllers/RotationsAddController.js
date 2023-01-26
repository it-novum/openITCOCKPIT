angular.module('openITCOCKPIT')
    .controller('RotationsAddController', function($scope, $http, $state, NotyService){

        $scope.post = {
            Rotation: {
                name: '',
                interval: 90,
                container_id: [],
                Map: []
            }
        };

        $scope.loadMaps = function(){
            $http.get("/map_module/rotations/loadMaps.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.maps = result.data.maps;
            });
        };

        $scope.loadContainers = function(){
            $http.get("/map_module/rotations/loadContainers.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
            });
        };

        $scope.submit = function(){
            $http.post("/map_module/rotations/add.json?angular=true",
                $scope.post
            ).then(function(result){
                NotyService.genericSuccess();
                $state.go('RotationsIndex');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });

        };

        $scope.loadMaps();
        $scope.loadContainers();

    });
