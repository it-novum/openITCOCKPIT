angular.module('openITCOCKPIT').directive('mapSummaryItem', function($http, $interval){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors_new/mapsummaryitem.html',
        scope: {
            'item': '='
        },
        controller: function($scope){

            var interval = null;

            if($scope.item.size_x <= 0){
                $scope.item.size_x = 100;
            }


            $scope.load = function(){
                $http.get("/map_module/mapeditors_new/mapsummaryitem/.json", {
                    params: {
                        'angular': true,
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
                $interval.cancel($scope.statusUpdateInterval);
            };

            $scope.load();

            /*
            //All objects on the map gets rerenderd by MapEditorsController.
            //May be we need this in a later version?
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
            */

        },

        link: function(scope, element, attr){

        }
    };
});
