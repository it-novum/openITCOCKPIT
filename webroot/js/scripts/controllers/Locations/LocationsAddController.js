angular.module('openITCOCKPIT')
    .controller('LocationsAddController', function($scope, $http, $state, NotyService, RedirectService){

        $scope.data = {
            createAnother: false
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

        var clearForm = function(){
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
        };
        clearForm();

        $scope.load = function(){
            $http.get("/locations/loadContainers.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };

        $scope.submit = function(){
            $http.post("/locations/add.json?angular=true",
                $scope.post
            ).then(function(result){

                var url = $state.href('LocationsEdit', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                if($scope.data.createAnother === false){
                    RedirectService.redirectWithFallback('LocationsIndex');
                }else{
                    clearForm();
                    NotyService.scrollTop();
                }

            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
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
