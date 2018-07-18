angular.module('openITCOCKPIT').directive('mapItem', function($http){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors_new/mapitem.html',
        scope: {
            'item': '='
        },
        controller: function($scope){
            $scope.load = function(){
                $http.get("/map_module/mapeditors_new/mapitem/.json", {
                    params: {
                        'angular': true,
                        'objectId': $scope.item.object_id,
                        'type': $scope.item.type
                    }
                }).then(function(result){
                    $scope.icon = result.data.data.icon;
                    $scope.allowView = result.data.allowView;
                    $scope.init = false;
                });
            };

            $scope.load();
        },

        link: function(scope, element, attr){

        }
    };
});
