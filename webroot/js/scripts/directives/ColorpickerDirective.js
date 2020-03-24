angular.module('openITCOCKPIT').directive('colorpickerDirective', function(){
    return {
        restrict: 'E',
        templateUrl: '/angular/colorpicker.html',
        scope: {
            'label': '=',
            'model': '='
        },

        controller: function($scope){

        },

        link: function($scope, element, attr){
            jQuery(element).colorpicker({
                format: 'auto'
            });
        }
    };
});
