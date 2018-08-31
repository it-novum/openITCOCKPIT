angular.module('openITCOCKPIT')
    .controller('MapeditorsViewController', function($scope, $http, QueryStringService, $timeout, $interval){

        $scope.init = true;
        $scope.id = QueryStringService.getCakeId();

        $scope.fullscreen = QueryStringService.getValue('fullscreen', false) === 'true';
        $scope.rotate = QueryStringService.getValue('rotation', null);
        $scope.rotationInterval = parseInt(QueryStringService.getValue('interval', 0), 10) * 1000;
        $scope.rotationPossition = 1;

        $scope.loadMapDetails = function(){
            $http.get("/map_module/mapeditors/mapDetails/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.map = result.data.map;
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