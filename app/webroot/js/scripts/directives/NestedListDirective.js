angular.module('openITCOCKPIT').directive('nestedList', function($http, $interval){
    return {
        restrict: 'A',
        templateUrl: '/angular/nested_list.html',
        scope: {
            'container': '='
        },

        controller: function($scope){
            console.log($scope.container);

        },

        link: function(scope, element, attr){

        }
    };
});