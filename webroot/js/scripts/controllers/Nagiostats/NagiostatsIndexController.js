/**
 * @link https://fullcalendar.io/docs/upgrading-from-v3
 */
angular.module('openITCOCKPIT')
    .controller('NagiostatsIndexController', function($scope, $http, $interval){

        $scope.init = true;

        $scope.interval = null;

        $scope.sparklines = {
            NUMSVCACTCHK1M: {
                values: []
            },
            NUMSVCACTCHK5M: {
                values: []
            },
            NUMSVCACTCHK15M: {
                values: []
            },
            NUMSVCACTCHK60M: {
                values: []
            },

            NUMSVCPSVCHK1M: {
                values: []
            },
            NUMSVCPSVCHK5M: {
                values: []
            },
            NUMSVCPSVCHK15M: {
                values: []
            },
            NUMSVCPSVCHK60M: {
                values: []
            },

            NUMHSTACTCHK1M: {
                values: []
            },
            NUMHSTACTCHK5M: {
                values: []
            },
            NUMHSTACTCHK15M: {
                values: []
            },
            NUMHSTACTCHK60M: {
                values: []
            },

            NUMHSTPSVCHK1M: {
                values: []
            },
            NUMHSTPSVCHK5M: {
                values: []
            },
            NUMHSTPSVCHK15M: {
                values: []
            },
            NUMHSTPSVCHK60M: {
                values: []
            }
        };

        $scope.load = function(){
            $http.get("/nagiostats/index.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.stats = result.data.stats;

                for(var key in $scope.sparklines){
                    //var lastValue = $scope.sparklines[key].values[$scope.sparklines[key].values.length - 1];

                    if($scope.sparklines[key].values.length > 50){
                        $scope.sparklines[key].values.shift(1, 1);
                    }

                    //if($scope.stats[key] !== lastValue) {
                    // Only update chart if new value is different
                    $scope.sparklines[key].values.push($scope.stats[key]);
                    //}
                }
                $scope.updateSparklines();

                $scope.init = false;
            });
        };

        $scope.updateSparklines = function(){
            for(var key in $scope.sparklines){
                var sparkline = $('#' + key + '_sparkline').sparkline($scope.sparklines[key].values, {type: 'line'});
            }
        };

        $scope.startRefresh = function(){
            $scope.stopRefresh();
            $scope.interval = $interval(function(){
                $scope.load();
            }, 10000);
        };

        $scope.stopRefresh = function(){
            if($scope.interval !== null){
                $interval.cancel($scope.interval);
            }
        };

        $scope.$on('$destroy', function(){
            $scope.stopRefresh();
        });

        //Fire on page load
        $scope.load();
        $scope.startRefresh();

    });
