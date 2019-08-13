angular.module('openITCOCKPIT')
    .controller('DowntimereportsIndexController', function($rootScope, $scope, $http, $timeout, NotyService, QueryStringService, $httpParamSerializer){
        $scope.init = true;
        $scope.errors = null;
        $scope.hasEntries = null;

        $scope.post = {
            evaluation_type: 0,
            report_format: 2,
            reflection_state: 2,
            timeperiod_id: null
        };
        $scope.timeperiods = {};

        $scope.loadTimeperiods = function(searchString){
            $http.get("/timeperiods/index.json", {
                params: {
                    'angular': true,
                    'filter[Timeperiod.name]': searchString
                }
            }).then(function(result){
                $scope.timeperiods = result.data.all_timeperiods;
                console.log($scope.timeperiods);
            });

        };
        $scope.loadTimeperiods();
    });
