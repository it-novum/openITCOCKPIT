angular.module('openITCOCKPIT')
    .controller('RotationsEditController', function($scope, $http, QueryStringService){

        $scope.post = {
            Rotation: {
                name: '',
                interval: 90,
                container_id: [],
                Map: []
            }
        };
        $scope.id = QueryStringService.getCakeId();

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

                for(var key in $scope.rotation.Container){
                    selectedContainer.push(parseInt($scope.rotation.Container[key].id, 10));
                }

                for(var key in $scope.rotation.Map){
                    selectedMaps.push(parseInt($scope.rotation.Map[key].id, 10));
                }

                $scope.post.Rotation.container_id = selectedContainer;
                $scope.post.Rotation.Map = selectedMaps;
                $scope.post.Rotation.name = $scope.rotation.Rotation.name;
                $scope.post.Rotation.interval = parseInt($scope.rotation.Rotation.interval, 10);
            }, function errorCallback(result){
                if(result.status === 404){
                    window.location.href = '/angular/not_found';
                }
            });
        };

        $scope.loadContainers = function(){
            $http.get("/map_module/rotations/loadContainers.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                console.log(result.data);
                $scope.containers = result.data.containers;
            });
        };

        $scope.loadMaps = function(){
            $http.get("/map_module/rotations/loadMaps.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                console.log(result.data);
                $scope.maps = result.data.maps;
            });
        };

        $scope.submit = function(){
            $http.post("/map_module/rotations/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                console.log('Data saved successfully');
                window.location.href = '/map_module/rotations/index';
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });

        };

        $scope.loadContainers();
        $scope.loadMaps();
        $scope.load();
    });