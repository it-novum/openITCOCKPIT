angular.module('openITCOCKPIT').directive('nestedList', function($http){
    return {
        restrict: 'E',
        templateUrl: '/angular/nested_list.html',
        scope: {
            'container': '=',
            'loadContainersCallback': '='
        },
        controller: function($scope){
        },
        link: function($scope, element, attr){
        }
    };
});
