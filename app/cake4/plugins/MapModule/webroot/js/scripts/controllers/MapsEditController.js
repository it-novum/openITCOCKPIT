angular.module('openITCOCKPIT')
    .controller('MapsEditController', function($scope, $http, $stateParams, $state, NotyService, RedirectService){

        $scope.id = $stateParams.id;

        $scope.deleteUrl = "/map_module/maps/delete/" + $scope.id + ".json?angular=true";
        $scope.sucessUrl = '/map_module/maps/index';

        $scope.load = function(){
            $http.get("/map_module/maps/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post = result.data.map;
                $scope.post.Map.refresh_interval = (parseInt($scope.post.Map.refresh_interval, 10) / 1000);
                $scope.init = false;
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
            $http.post("/map_module/maps/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('MapsEdit', {id: $scope.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('MapsIndex');

                console.log('Data saved successfully');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.loadContainers();
        $scope.load();
    });
