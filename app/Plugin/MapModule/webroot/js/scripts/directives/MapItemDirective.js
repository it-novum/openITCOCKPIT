angular.module('openITCOCKPIT').directive('mapItem', function($http, $interval, UuidService, BlinkService, MapItemReloadService){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors/mapitem.html',
        scope: {
            'item': '=',
            'refreshInterval': '='
        },
        controller: function($scope){
            $scope.init = true;


            var uuidForServices = null;
            var interval = null;

            var updateCallback = function(result){
                $scope.icon = result.data.data.icon;
                $scope.icon_property = result.data.data.icon_property;
                $scope.allowView = result.data.allowView;
                $scope.init = false;

                getLable(result.data.data);

                $scope.currentIcon = $scope.icon;

                if(result.data.data.isAcknowledged === true || result.data.data.isInDowntime === true){
                    BlinkService.registerNewObject(uuidForServices, $scope.blinkServiceCallback);
                }else{
                    BlinkService.unregisterObject(uuidForServices);
                }
            };

            $scope.load = function(){
                if(uuidForServices === null){
                    uuidForServices = UuidService.v4();
                }

                $http.get("/map_module/mapeditors/mapitem/.json", {
                    params: {
                        'angular': true,
                        'disableGlobalLoader': true,
                        'objectId': $scope.item.object_id,
                        'mapId': $scope.item.map_id,
                        'type': $scope.item.type
                    }
                }).then(function(result){
                    updateCallback(result);
                });
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

            $scope.blinkServiceCallback = function(){
                if($scope.currentIcon === $scope.icon){
                    $scope.currentIcon = $scope.icon_property;
                }else{
                    $scope.currentIcon = $scope.icon;
                }
            };

            $scope.stop = function(){
                BlinkService.unregisterObject(uuidForServices);
                MapItemReloadService.unregisterItem(uuidForServices);
            };

            $scope.load();


            if($scope.refreshInterval > 0){
                MapItemReloadService.setRefreshInterval($scope.refreshInterval);
                MapItemReloadService.registerNewItem(uuidForServices, $scope.item, updateCallback);
            }

            //Disable status update interval, if the object gets removed from DOM.
            //E.g in Map rotations
            $scope.$on('$destroy', function(){
                $scope.stop();
            });


            $scope.$watch('item.object_id', function(){
                if($scope.init || $scope.item.object_id === null){
                    //Avoid ajax error if user search a object in item config modal
                    return;
                }

                $scope.load();
            });


        },

        link: function(scope, element, attr){

        }
    };
});
