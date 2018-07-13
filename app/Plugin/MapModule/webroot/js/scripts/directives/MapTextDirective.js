angular.module('openITCOCKPIT').directive('mapText', function($http){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors_new/maptext.html',
        scope: {
            'item': '='
        },
        controller: function($scope){

        },

        link: function(scope, element, attr){

        }
    };
});
