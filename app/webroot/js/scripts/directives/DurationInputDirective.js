angular.module('openITCOCKPIT').directive('durationInputDirective', function($http){
    return {
        restrict: 'E',
        templateUrl: '/angular/durationInput.html',
        scope: {
            'duration': '='
        },

        controller: function($scope){

            $scope.changeDuration = function(valueInMinutes){
                $scope.duration = valueInMinutes;
            };

        },

        link: function($scope, element, attr){

        }
    };
});
