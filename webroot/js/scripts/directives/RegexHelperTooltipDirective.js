angular.module('openITCOCKPIT').directive('regexHelperTooltip', function($interval){
    return {
        restrict: 'E',
        templateUrl: '/angular/regexHelperTooltip.html',
        scope: {},
        controller: function($scope){

            $('.regexHelper').popover({
                boundary: 'window',
                trigger: 'hover',
                placement: 'left',
                container: 'body',
                template: '<div class="popover" style="min-width: 500px;" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
            });
        },

        link: function(scope, element, attr){

        }
    };
});
