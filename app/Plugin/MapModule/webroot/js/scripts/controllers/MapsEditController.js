angular.module('openITCOCKPIT')
    .controller('MapsEditController', function($scope, $http, QueryStringService){

        $scope.post = {
            Map: {
                name: '',
                title: '',
                refresh_interval: 90,
                container_id: []
            }
        };
        $scope.id = QueryStringService.getCakeId();

        $scope.deleteUrl = "/map_module/maps/delete/"+$scope.id+".json?angular=true";
        $scope.sucessUrl = '/map_module/maps/index';

        $scope.load = function(){
            $http.get("/map_module/maps/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.map = result.data.map;
                var selectedContainer = [];

                for(var key in $scope.map.Container){
                    selectedContainer.push(parseInt($scope.map.Container[key].id, 10));
                }

                $scope.post.Map.container_id = selectedContainer;
                $scope.post.Map.name = $scope.map.Map.name;
                $scope.post.Map.title = $scope.map.Map.title;
                $scope.post.Map.refresh_interval = (parseInt($scope.map.Map.refresh_interval, 10)/1000);
                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 404){
                    window.location.href = '/angular/not_found';
                }
            });
        };

        $scope.loadContainers = function(){
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
            $http.post("/map_module/maps/edit/"+$scope.id+".json?angular=true",
                $scope.post
            ).then(function(result){
                console.log('Data saved successfully');
                window.location.href = '/map_module/maps/index';
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.$watch('post', function(){
            console.log($scope.post);
        },true);

        $scope.loadContainers();
        $scope.load();
    });