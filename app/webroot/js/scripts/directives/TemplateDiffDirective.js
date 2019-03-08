angular.module('openITCOCKPIT').directive('templateDiff', function($http, $interval){
    return {
        restrict: 'E',
        templateUrl: '/angular/template_diff.html',
        replace: true,
        scope: {
            'value': '=',
            'templateValue': '='
        },

        controller: function($scope){

            $scope.hasDiff = false;

            $scope.restoreDefault = function(){
                $scope.value = $scope.templateValue;
            };

            $scope.$watch('value', function(){
                if(typeof $scope.templateValue !== "undefined"){
                    $scope.hasDiff = $scope.value != $scope.templateValue;
                }
            });

        },

        link: function(scope, element, attr){
        }
    };
});