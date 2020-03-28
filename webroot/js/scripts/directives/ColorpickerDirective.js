angular.module('openITCOCKPIT').directive('colorpickerDirective', function(){
    return {
        restrict: 'E',
        templateUrl: '/angular/colorpicker.html',
        replace: true,
        require:'ngModel',

        controller: function($scope){
        },

        link: function($scope, element, attr, ctrl){
            jQuery(element).colorpicker({
                color: ctrl.$modelValue,
                format: 'hex',
                adjustableNumbers: true,

            }).on('colorpickerChange colorpickerCreate', function (e){
                ctrl.$setViewValue(e.color.string());
            });
        }
    };
});
