angular.module('openITCOCKPIT').directive('mapIcon', function($http){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors_new/mapicon.html',
        scope: {
            'item': '='
        },
        controller: function($scope){

        },

        link: function(scope, element, attr){

        }
    };
});
