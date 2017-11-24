angular.module('openITCOCKPIT').directive('nestedList', function($http, $interval){
    return {
        restrict: 'E',
        templateUrl: '/angular/nested_list.html',
        scope: {
            'container': '='
        },

        controller: function($scope){

        },

        link: function($scope, element, attr){

        }
    };
});
