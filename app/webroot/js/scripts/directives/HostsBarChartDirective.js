angular.module('openITCOCKPIT').directive('hostsBarChart', function($http){
    return {
        restrict: 'E',
        templateUrl: '/angular/hostsBarChart.html',
        scope: {
            'id': '='
        },
        controller: function($scope){
        },

        link: function($scope, element, attr){
        }
    };
});
