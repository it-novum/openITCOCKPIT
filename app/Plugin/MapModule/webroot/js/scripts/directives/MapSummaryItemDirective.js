angular.module('openITCOCKPIT').directive('mapSummaryItem', function($http, $interval){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors/mapsummaryitem.html',
        scope: {
            'item': '=',
            'refreshInterval': '='
        },
        controller: function($scope){
            $scope.statusUpdateInterval = null;

            var interval = null;

            if($scope.item.size_x <= 0){
                $scope.item.size_x = 100;
            }


            $scope.load = function(){
                $http.get("/map_module/mapeditors/mapsummaryitem/.json", {
                    params: {
                        'angular': true,
                        'disableGlobalLoader': true,
                        'objectId': $scope.item.object_id,
                        'mapId': $scope.item.map_id,
                        'type': $scope.item.type
                    }
                }).then(function(result){
                    $scope.bitMaskHostState = result.data.data.BitMaskHostState;
                    $scope.bitMaskServiceState = result.data.data.BitMaskServiceState;
                    $scope.allowView = result.data.allowView;

                    $scope.init = false;
                    getLable(result.data.data);
                    initRefreshTimer();
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

            var initRefreshTimer = function(){
                if($scope.refreshInterval > 0 && $scope.statusUpdateInterval === null){
                    $scope.statusUpdateInterval = $interval(function(){
                        $scope.load();
                    }, $scope.refreshInterval);
                }
            };

            $scope.load();

        },

        link: function(scope, element, attr){

        }
    };
});
