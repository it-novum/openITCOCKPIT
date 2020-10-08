angular.module('openITCOCKPIT').directive('thresholds', function(){
    return {
        restrict: 'E',
        templateUrl: '/angular/thresholds.html',
        scope: {
            'inclusive': '=?',
            'type': '=?',
            'min': '=?',
            'max': '=?'
        },
        controller: function(){
        },

        link: function(){
        }
    };
});
