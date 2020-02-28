angular.module('openITCOCKPIT').directive('serviceOutputItem', function($http, $interval){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors/serviceOutput.html',
        scope: {
            'item': '=',
            'refreshInterval': '='
        },
        controller: function($scope){
            $scope.init = true;
            $scope.statusUpdateInterval = null;

            $scope.width = 60;
            $scope.height = 150;

            $scope.item.size_x = parseInt($scope.item.size_x, 10);
            $scope.item.size_y = parseInt($scope.item.size_y, 10);


            if($scope.item.size_x > 0){
                $scope.width = $scope.item.size_x;
            }
            if($scope.item.size_y > 0){
                $scope.height = $scope.item.size_y;
            }

            var output = null;
            var longOutputHtml = null;


            $scope.load = function(){
                $http.get("/map_module/mapeditors/mapitem/.json", {
                    params: {
                        'angular': true,
                        'disableGlobalLoader': true,
                        'objectId': $scope.item.object_id,
                        'mapId': $scope.item.map_id,
                        'type': $scope.item.type,
                        'includeServiceOutput': true
                    }
                }).then(function(result){
                    $scope.current_state = result.data.data.Servicestatus.currentState;
                    $scope.is_flapping = result.data.data.Servicestatus.isFlapping;

                    $scope.Host = result.data.data.Host;
                    $scope.Service = result.data.data.Service;
                    $scope.allowView = result.data.allowView;
                    $scope.color = result.data.data.color;

                    output = result.data.data.Servicestatus.output;
                    longOutputHtml = result.data.data.Servicestatus.longOutputHtml;

                    renderOutput();

                    initRefreshTimer();

                    $scope.init = false;
                });
            };

            $scope.stop = function(){
                if($scope.statusUpdateInterval !== null){
                    $interval.cancel($scope.statusUpdateInterval);
                }
            };

            //Disable status update interval, if the object gets removed from DOM.
            //E.g in Map rotations
            $scope.$on('$destroy', function(){
                $scope.stop();
            });


            var renderOutput = function(){
                $scope.output = output;

                if($scope.item.output_type !== 'service_output'){
                    if(longOutputHtml !== null){
                        $scope.output = longOutputHtml;
                    }
                }

            };

            var initRefreshTimer = function(){
                if($scope.refreshInterval > 0 && $scope.statusUpdateInterval === null){
                    $scope.statusUpdateInterval = $interval(function(){
                        $scope.load();
                    }, $scope.refreshInterval);
                }
            };

            $scope.$watchGroup(['item.size_x', 'item.show_label', 'item.output_type'], function(){
                if($scope.init){
                    return;
                }

                $scope.width = $scope.item.size_x;
                $scope.height = $scope.item.size_y;
                renderOutput();
            });

            $scope.$watch('item.object_id', function(){
                if($scope.init || $scope.item.object_id === null){
                    //Avoid ajax error if user search a service in Gadget config modal
                    return;
                }

                $scope.load();
            });

            $scope.load();
        },

        link: function(scope, element, attr){

        }
    };
});
