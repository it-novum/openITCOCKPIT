angular.module('openITCOCKPIT').directive('humanTimeDirective', function($http){
    return {
        restrict: 'E',
        templateUrl: '/angular/humanTime.html',
        scope: {
            'seconds': '='
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

            $scope.$watch('seconds', function(){
                calcValues($scope.seconds);
            });

        },

        link: function($scope, element, attr){

        }
    };
});
