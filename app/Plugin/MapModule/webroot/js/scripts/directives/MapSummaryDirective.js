angular.module('openITCOCKPIT').directive('mapSummary', function ($http, $interval) {
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors_new/mapsummary.html',

        controller: function ($scope) {
            $scope.loadSumaryState = function (item) {
                $http.get("/map_module/mapeditors_new/mapsummary/.json", {
                    params: {
                        'angular': true,
                        'objectId': item.object_id,
                        'type': item.type
                    }
                }).then(function (result) {
                    $('.map-summary-state-popover').switchClass('slideOutRight', 'slideInRight');
                    $scope.summaryState = result.data.summary;
                    $scope.iconType = item.type;
                    $scope.startInterval();
                });
            };
            $scope.hideTooltip = function ($event) {
                $($event.currentTarget).switchClass('slideInRight', 'slideOutRight');
                $scope.stopInterval();
            };

            $scope.startInterval = function (){
                var showFor = 5000;
                var intervalSpeed = 10;
                $scope.percentValue = 100;

                $scope.stopInterval();

                $scope.intervalRef = $interval(function () {
                    showFor = showFor - intervalSpeed;
                    if (showFor === 0) {
                        $scope.stopInterval();
                        $('.map-summary-state-popover').switchClass('slideInRight', 'slideOutRight');
                    }

                    $scope.percentValue = showFor / 5000 * 100;
                }, intervalSpeed);
            };

            $scope.stopInterval = function (){
                if(typeof $scope.intervalRef !== "undefined"){
                    $interval.cancel($scope.intervalRef);
                }

            };
        },

        link: function (scope, element, attr) {
            scope.showSummaryState = function (item) {
                scope.loadSumaryState(item);
            };
        }
    };
});
