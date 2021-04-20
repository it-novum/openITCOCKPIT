angular.module('openITCOCKPIT').directive('automap', function($http, $state, $interval){
    return {
        restrict: 'E',
        templateUrl: '/automaps/automap.html',
        scope: {
            'automap': '=',
            'servicesByHost': '=',
            'scroll': '=?',
            'paging': '=?',
            'changepage': '=',
            'changeMode': '=',
            'useScroll': '=',
            'mode': '=?'
        },
        controller: function($scope){
        },
        link: function($scope, element, attr){
        }
    };
});
