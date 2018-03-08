angular.module('openITCOCKPIT').directive('downtimeicon', function($interval){
    return {
        restrict: 'E',
        templateUrl: '/downtimes/icon.html',
        scope: {
            'downtime': '='
        },
        controller: function($scope){
            //Empty
        },

        link: function(scope, element, attr){
            //Empty
        }
    };
});
