angular.module('openITCOCKPIT').directive('mapSummary', function($http){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors_new/mapsummary.html',

        controller: function($scope){
            $scope.loadSumaryState = function(item){
                $http.get("/map_module/mapeditors_new/mapsummary/.json", {
                    params: {
                        'angular': true,
                        'objectId': item.object_id,
                        'type': item.type
                    }
                }).then(function(result){
                    $scope.summaryState = result.data.summary;
                });

            };

        },

        link: function(scope, element, attr){
            scope.showSummaryState = function(item){
                scope.loadSumaryState(item);
            };
        }
    };
});
