angular.module('openITCOCKPIT').directive('colorpickerDirective', function(){
    return {
        restrict: 'E',
        templateUrl: '/angular/colorpicker.html',
        scope: {
            'post': '=',
            'key': '='
        },
        replace: true,

        controller: function($scope){
            jQuery(document).ready(function(){
                jQuery($scope.element).spectrum({
                    type: "color",
                    showPalette: "false",
                    showInput: "true"
                });

                jQuery($scope.element).spectrum("set", $scope.post[$scope.key]);
            });
        },

        link: function($scope, element, attr, ctrl){
            $scope.element = element;
        }
    };
});
