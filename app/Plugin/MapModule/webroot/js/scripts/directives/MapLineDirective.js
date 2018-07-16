angular.module('openITCOCKPIT').directive('mapLine', function($http){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors_new/mapline.html',
        scope: {
            'item': '='
        },
        controller: function($scope){

            $scope.init = function(){
                $scope.item.startX = parseInt($scope.item.startX, 10);
                $scope.item.startY = parseInt($scope.item.startY, 10);
                $scope.item.endX = parseInt($scope.item.endX, 10);
                $scope.item.endY = parseInt($scope.item.endY, 10);

                $scope.z_index = parseInt((1 + $scope.item.z_index), 10); //Always 1 to be over background iamge

                var distance = Math.sqrt(
                    Math.pow(($scope.item.endX - $scope.item.startX), 2) + Math.pow(($scope.item.endY - $scope.item.startY), 2)
                );

                $scope.width = parseInt(distance, 10);

                $scope.top = $scope.item.startY;
                if($scope.item.startX > $scope.item.endX){
                    $scope.left = $scope.item.startX;
                    $scope.origin = 'top right';
                }

                if($scope.item.endX > $scope.item.startX){
                    $scope.left = $scope.item.startX;
                    $scope.origin = 'top left';
                }

                var tan = ($scope.item.endY - $scope.item.startY) / ($scope.item.endX - $scope.item.startX);
                var atan = Math.atan(($scope.item.endY - $scope.item.startY) / ($scope.item.endX - $scope.item.startX)); //tan / Math.PI * 180;
                $scope.arctan = atan * 180 / Math.PI;
            };

            $scope.load = function(){
                $http.get("/map_module/mapeditors_new/mapitem/.json", {
                    params: {
                        'angular': true,
                        'objectId': $scope.item.object_id,
                        'type': $scope.item.type
                    }
                }).then(function(result){
                    $scope.background = result.data.background;
                    $scope.allowView = result.data.allowView;
                    $scope.init = false;
                });
            };

            $scope.init();
            $scope.load();
        },

        link: function(scope, element, attr){

        }
    };
});
