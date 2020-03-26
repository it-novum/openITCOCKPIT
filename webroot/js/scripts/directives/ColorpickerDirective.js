angular.module('openITCOCKPIT').directive('colorpickerDirective', function(){
    return {
        restrict: 'E',
        templateUrl: '/angular/colorpicker.html',
        replace: true,
        scope: {
            'model': '='
        },

        controller: function($scope){
        },

        link: function($scope, element, attr){
            jQuery(element).colorpicker({
                color: $scope.model,
                format: 'auto'
            });
        }
    };
});
