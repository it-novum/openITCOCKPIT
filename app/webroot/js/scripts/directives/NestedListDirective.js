angular.module('openITCOCKPIT').directive('nestedList', function($http, $interval){
    return {
        restrict: 'A',
        templateUrl: '/angular/nested_list.html',
        scope: {
            'containers': '='
        },

        controller: function($scope){
            console.log($scope.containers);

        },

        link: function(scope, element, attr){

        }
    };
});