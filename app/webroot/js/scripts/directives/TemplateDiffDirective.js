angular.module('openITCOCKPIT').directive('templateDiff', function($http, $interval){
    return {
        restrict: 'E',
        templateUrl: '/angular/template_diff.html',
        scope: {
            'value': '=',
            'templateValue': '=',
            'callback': '=',
            'field': '=',
        },

        controller: function($scope){

        },

        link: function(scope, element, attr){
        }
    };
});