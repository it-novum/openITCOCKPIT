angular.module('openITCOCKPIT').directive('mapLine', function($http, $interval){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors/mapline.html',
        scope: {
            'item': '=',
            'refreshInterval': '='
        },
        controller: function($scope){
            $scope.init = true;
            $scope.statusUpdateInterval = null;

            $scope.initLine = function(){
                $scope.item.startX = parseInt($scope.item.startX, 10);
                $scope.item.startY = parseInt($scope.item.startY, 10);
                $scope.item.endX = parseInt($scope.item.endX, 10);
                $scope.item.endY = parseInt($scope.item.endY, 10);

                $scope.z_index = parseInt($scope.item.z_index, 10);
                console.log($scope.z_index);

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

            var getLable = function(data){
                $scope.lable = '';
                switch($scope.item.type){
                    case 'host':
                        $scope.lable = data.Host.hostname;
                        break;

                    case 'service':
                        $scope.lable = data.Host.hostname + '/' + data.Service.servicename;
                        break;

                    case 'hostgroup':
                        $scope.lable = data.Hostgroup.name;
                        break;

                    case 'servicegroup':
                        $scope.lable = data.Servicegroup.name;
                        break;

                    case 'map':
                        $scope.lable = data.Map.name;
                        break;
                }
            };

            var initRefreshTimer = function(){
                if($scope.item.type !== 'stateless'){
                    if($scope.refreshInterval > 0 && $scope.statusUpdateInterval === null){
                        $scope.statusUpdateInterval = $interval(function(){
                            $scope.load();
                        }, $scope.refreshInterval);
                    }
                }
            };

            $scope.load = function(){
                $http.get("/map_module/mapeditors/mapitem/.json", {
                    params: {
                        'angular': true,
                        'disableGlobalLoader': true,
                        'objectId': $scope.item.object_id,
                        'mapId': $scope.item.map_id,
                        'type': $scope.item.type
                    }
                }).then(function(result){
                    $scope.background = result.data.data.background;
                    $scope.allowView = result.data.allowView;
                    $scope.init = false;
                    getLable(result.data.data);
                    initRefreshTimer();
                });
            };

            $scope.initLine();

            if($scope.item.type === 'stateless'){
                $scope.background = 'bg-color-black';
                $scope.allowView = true;
                $scope.init = false;
            }else{
                $scope.load();
            }


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


            $scope.$watch('item', function(){
                if($scope.init || $scope.item.object_id === null){
                    if($scope.item.type !== 'stateless'){
                        //Avoid ajax error if user search a object in line config modal
                        return;
                    }
                }

                $scope.initLine();
                if($scope.item.type !== 'stateless'){
                    $scope.load();

                }
            }, true);
        },

        link: function(scope, element, attr){

        }
    };
});
