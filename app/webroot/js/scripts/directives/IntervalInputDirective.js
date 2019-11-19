angular.module('openITCOCKPIT').directive('intervalInputDirective', function($http){
    return {
        restrict: 'E',
        templateUrl: '/angular/intervalInput.html',
        scope: {
            'interval': '='
        },

        controller: function($scope){

            $scope.changeInterval = function(valueInSeconds){
                $scope.interval = valueInSeconds;
            };

        },

        link: function($scope, element, attr){

        }
    };
});
