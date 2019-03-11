angular.module('openITCOCKPIT').directive('intervalInputWithDifferDirective', function($http){
    return {
        restrict: 'E',
        templateUrl: '/angular/intervalInputWithDiffer.html',
        scope: {
            'interval': '=',
            'templateValue': '=',
            'templateId': '='
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
