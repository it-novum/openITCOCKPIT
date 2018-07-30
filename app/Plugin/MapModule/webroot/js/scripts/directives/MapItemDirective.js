angular.module('openITCOCKPIT').directive('mapItem', function($http, $interval){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors_new/mapitem.html',
        scope: {
            'item': '=',
            'refreshInterval': '='
        },
        controller: function($scope){

            var interval = null;

            $scope.load = function(){
                $http.get("/map_module/mapeditors_new/mapitem/.json", {
                    params: {
                        'angular': true,
                        'objectId': $scope.item.object_id,
                        'type': $scope.item.type
                    }
                }).then(function(result){
                    $scope.icon = result.data.data.icon;
                    $scope.icon_property = result.data.data.icon_property;
                    $scope.allowView = result.data.allowView;
                    $scope.init = false;


                    $scope.currentIcon = $scope.icon;

                    if(result.data.data.isAcknowledged === true || result.data.data.isInDowntime === true){
                        startBlink();
                    }

                });
            };

            var startBlink = function(){
                interval = $interval(function(){
                    if($scope.currentIcon === $scope.icon){
                        $scope.currentIcon = $scope.icon_property;
                    }else{
                        $scope.currentIcon = $scope.icon;
                    }
                }, 5000);
            };

            var stopBlink = function(){
                if(interval !== null){
                    $interval.cancel(interval);
                }
                interval = null;
            };

            $scope.stop = function(){
                $interval.cancel($scope.statusUpdateInterval);
            };

            $scope.load();

            if($scope.refreshInterval > 0){
                $scope.statusUpdateInterval = $interval(function(){
                    $scope.load();
                }, $scope.refreshInterval);
            }

            //Disable status update interval, if the object gets removed from DOM.
            //E.g in Map rotations
            $scope.$on('$destroy', function() {
                $scope.stop();
            });

        },

        link: function(scope, element, attr){

        }
    };
});
