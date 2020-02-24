angular.module('openITCOCKPIT')
    .controller('LocationsEditController', function($scope, $http, $state, NotyService, $stateParams, RedirectService){

        $scope.id = $stateParams.id;
        $scope.containers = {};

        $scope.post = {
            description: '',
            latitude: null,
            longitude: null,
            timezone: null,
            container: {
                name: '',
                parent_id: null
            }
        };

        $scope.mapDiv = $('#mapDiv');
        $scope.mapDiv.vectorMap({
            map: 'world_mill_en',
            backgroundColor: '#fff',
            regionStyle: {
                initial: {
                    fill: '#c4c4c4'
                },
                hover: {
                    'hoverColor': '#4C4C4C'
                }
            },

            markerStyle: {
                initial: {
                    fill: '#800000',
                    stroke: '#383f47'
                }
            },
        });
        $scope.$map = $scope.mapDiv.vectorMap('get', 'mapObject');

        $scope.load = function(){
            $http.get("/locations/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post = result.data.location;

                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
            $scope.loadContainer();
        };

        $scope.submit = function(){
            $http.post("/locations/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){

                var url = $state.href('LocationsEdit', {id: $scope.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('LocationsIndex');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.loadContainer = function(){
            var params = {
                'angular': true
            };

            $http.get("/locations/loadContainers.json", {
                params: params
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };

        $scope.$watchGroup(['post.latitude', 'post.longitude'], function(){
            if($scope.init){
                return;
            }
            if(!isBlank($scope.post.latitude) && !isBlank($scope.post.longitude)){
                $scope.$map.removeAllMarkers();
                $scope.$map.reset();
                $scope.$map.addMarker('markerIndex', {latLng: [$scope.post.latitude, $scope.post.longitude]});
                $scope.$map.latLngToPoint($scope.post.latitude, $scope.post.longitude);
            }
        });

        var isBlank = function(str){
            return (!str || /^\s*$/.test(str));
        };

        $scope.load();

    });
