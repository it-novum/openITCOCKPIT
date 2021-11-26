angular.module('openITCOCKPIT').directive('regexHelperTooltip', function($interval){
    return {
        restrict: 'E',
        templateUrl: '/angular/regexHelperTooltip.html',
        scope: {
        },
        controller: function($scope){

            $('.infoButton').popover({
                boundary: 'window',
                trigger: 'hover',
                placement: 'left'
            });
        },

        link: function(scope, element, attr){

        }
    };
});
