angular.module('openITCOCKPIT')
    .controller('MapeditorsViewController', function($scope, $http, QueryStringService, $timeout, $interval, $stateParams){

        $scope.init = true;
        $scope.id = $stateParams.id;
        $scope.rotate = null;

        $scope.fullscreen = ($stateParams.fullscreen === 'true');
        if($stateParams.rotation != null) $scope.rotate = $stateParams.rotation;
        $scope.rotationInterval = parseInt($stateParams.interval, 10) * 1000;
        $scope.rotationPossition = 1;


        $scope.loadMapDetails = function(){
            $http.get("/map_module/mapeditors/mapDetails/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.map = result.data.map;
                $scope.refreshInterval = $scope.map.Map.refresh_interval;
                if($scope.refreshInterval !== 0 && $scope.refreshInterval < 5000){
                    $scope.refreshInterval = 5000;
                }
                $scope.init = false;
            });
        };


        $scope.loadMapDetails();

        if($scope.rotate !== null && $scope.rotationInterval > 0){
            $scope.rotate = $scope.rotate.split(',');

            $interval(function(){
                $scope.rotationPossition++;
                if($scope.rotationPossition > $scope.rotate.length){
                    $scope.rotationPossition = 1;
                }

                $scope.id = $scope.rotate[$scope.rotationPossition - 1];
                $scope.loadMapDetails();

            }, $scope.rotationInterval);
        }


    });