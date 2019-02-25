angular.module('openITCOCKPIT').directive('intervalInputDirective', function($http){
    return {
        restrict: 'E',
        templateUrl: '/angular/intervalInput.html',
        scope: {
            'interval': '='
        },

        controller: function($scope){

            $scope.humanInterval = {
                hours: 0,
                minutes: 0,
                seconds: 0
            };

            var calcValues = function(valueInSeconds){
                var hours = Math.floor(valueInSeconds / 3600);
                var minutes = Math.floor((valueInSeconds % 3600) / 60);
                var seconds = valueInSeconds % 60;

                $scope.humanInterval = {
                    hours: hours,
                    minutes: minutes,
                    seconds: seconds
                };
            };

            $scope.changeInterval = function(valueInSeconds){
                $scope.interval = valueInSeconds;
            };

            $scope.$watch('interval', function(){
                calcValues($scope.interval);
            });

        },

        link: function($scope, element, attr){

        }
    };
});
