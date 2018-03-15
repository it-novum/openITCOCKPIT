angular.module('openITCOCKPIT')
    .controller('MapsCopyController', function($scope, $http, QueryStringService){

        $scope.post = {
            Map: {
                name: '',
                title: '',
                refresh_interval: 90,
                container_id: []
            }
        };
        $scope.id = QueryStringService.getCakeId();

        $scope.sucessUrl = '/map_module/maps/index';

        $scope.load = function(){
            $http.get("/map_module/maps/copy/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.sourceMap = result.data.sourceMap;
                var selectedContainer = [];

                for(var key in $scope.sourceMap.Container){
                    selectedContainer.push(parseInt($scope.sourceMap.Container[key].id, 10));
                }

                $scope.post.Map.container_id = selectedContainer;
                $scope.post.Map.name = $scope.sourceMap.Map.name;
                $scope.post.Map.title = $scope.sourceMap.Map.title;
                $scope.post.Map.refresh_interval = (parseInt($scope.sourceMap.Map.refresh_interval, 10)/1000);
                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 404){
                    window.location.href = '/angular/not_found';
                }
            });
        };

        $scope.submit = function(){
            $http.post("/map_module/maps/copy/"+$scope.id+".json?angular=true",
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

        $scope.load();
    });