angular.module('openITCOCKPIT')
    .controller('RotationsEditController', function($scope, $http, $stateParams, $state, NotyService){

        $scope.post = {
            Rotation: {
                name: '',
                interval: 90,
                container_id: [],
                Map: []
            }
        };
        $scope.id = $stateParams.id;

        $scope.deleteUrl = "/map_module/rotations/delete/" + $scope.id + ".json?angular=true";
        $scope.sucessUrl = '/map_module/rotations/index';

        $scope.load = function(){
            $http.get("/map_module/rotations/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.rotation = result.data.rotation;
                var selectedContainer = [];
                var selectedMaps = [];

                for(var key in $scope.rotation.containers){
                    selectedContainer.push(parseInt($scope.rotation.containers[key].id, 10));
                }

                for(var key in $scope.rotation.maps){
                    selectedMaps.push(parseInt($scope.rotation.maps[key].id, 10));
                }

                $scope.post.Rotation.container_id = selectedContainer;
                $scope.post.Rotation.Map = selectedMaps;
                $scope.post.Rotation.name = $scope.rotation.name;
                $scope.post.Rotation.interval = parseInt($scope.rotation.interval, 10);
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
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

        $scope.loadMaps = function(){
            $http.get("/map_module/rotations/loadMaps.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.maps = result.data.maps;
            });
        };

        $scope.submit = function(){
            $http.post("/map_module/rotations/edit/" + $scope.id + ".json?angular=true",
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

        $scope.loadContainers();
        $scope.loadMaps();
        $scope.load();
    });
