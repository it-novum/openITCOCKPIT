angular.module('openITCOCKPIT').directive('serverTime', function($http, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/user_timezone.html',

        controller: function($scope){
            $scope.initTime = true;
            $scope.showClientTime = false;

            var getCurrentTimeWithOffset = function(offset){
                return new Date(($scope.timezone.server_time_utc + (offset || 0)) * 1000 + (new Date().getTime() - $scope.pageLoadedTime));
            };

            var getServerTime = function(){
                return getCurrentTimeWithOffset($scope.timezone.server_timezone_offset);
            };

            var getClientTime = function(){
                return getCurrentTimeWithOffset($scope.timezone.user_offset);
            };

            var formatTimeAsString = function(time){
                return prependZero(time.getUTCHours()) + ':' + prependZero(time.getUTCMinutes());
            };

            var prependZero = function(number){
                if(number < 10){
                    return '0' + number;
                }
                return number.toString();
            };

            $scope.loadServerTime = function(){
                $http.get("/angular/user_timezone.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.timezone = result.data.timezone;
                    $scope.pageLoadedTime = new Date().getTime();
                    $scope.initTime = false;

                    if($scope.timezone.user_offset !== $scope.timezone.server_timezone_offset){
                        $scope.showClientTime = true;
                    }

                    $scope.runClocks();
                });
            };

            $scope.runClocks = function(){
                $scope.currentServerTime = formatTimeAsString(getServerTime());

                if($scope.showClientTime === true){
                    $scope.currentClientTime = formatTimeAsString(getClientTime());
                }
                $timeout($scope.runClocks, 10000);
            };

            $scope.loadServerTime();

        },

        link: function(scope, element, attr){
            jQuery(element).find("[rel=tooltip]").tooltip();
        }
    };
});