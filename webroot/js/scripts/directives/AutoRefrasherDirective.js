angular.module('openITCOCKPIT').directive('autoRefrasher', function($http, $interval, LocalStorageService){
    return {
        restrict: 'E',
        templateUrl: '/angular/autoRefresher.html',
        scope: {
            'callback': '='
        },
        controller: function($scope){
            $scope.refreshInterval = null;

            $scope.selectedAutoRefresh = 0
            $scope.humanAutoRefresh = '';

            $scope.init = true;

            var restoreFromLocalStorage = function(){
                if($scope.init){
                    console.error('$scope.load has to be called before this function can work!');
                    return;
                }


                var seconds = LocalStorageService.getItemWithDefault('autoRefrasherIntervalValue', 0);
                seconds = parseInt(seconds, 10);

                var disabledStr = $scope.timeranges.refresh_interval[0];
                $scope.selectedAutoRefresh = 0;
                $scope.humanAutoRefresh = disabledStr;

                if($scope.timeranges.refresh_interval.hasOwnProperty(seconds)){
                    $scope.changeAutoRefresh(seconds, $scope.timeranges.refresh_interval[seconds])
                }

            };

            $scope.load = function(){
                $http.get("/angular/autoRefresher.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.timeranges = result.data.timeranges;
                    $scope.init = false;

                    restoreFromLocalStorage()
                });
            };

            $scope.changeAutoRefresh = function(seconds, name){
                $interval.cancel($scope.refreshInterval);


                seconds = parseInt(seconds, 10);
                LocalStorageService.setItem('autoRefrasherIntervalValue', seconds);

                if(seconds <= 0){
                    //Disable reload interval

                    $scope.selectedAutoRefresh = seconds;
                    $scope.humanAutoRefresh = name;
                    return;
                }

                if(seconds <= 5){
                    seconds = 5;
                }
                $scope.selectedAutoRefresh = seconds;
                $scope.humanAutoRefresh = name;

                $scope.refreshInterval = $interval(function(){
                    $scope.callback();
                }, seconds * 1000);
            };

            //Disable interval if object gets removed from DOM.
            $scope.$on('$destroy', function(){
                $interval.cancel($scope.refreshInterval);
            });

            // Fire on page load
            $scope.load();

        },

        link: function($scope, element, attr){
        }
    };
});
