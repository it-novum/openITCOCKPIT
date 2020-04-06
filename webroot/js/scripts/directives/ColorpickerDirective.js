angular.module('openITCOCKPIT').directive('colorpickerDirective', function(){
    return {
        restrict: 'E',
        templateUrl: '/angular/colorpicker.html',
        scope: {
            'post': '=',
            'key': '=',
            'highlightclass': '=',
            'highlighttype': '='
        },
        replace: true,

        controller: function($scope){
            jQuery(document).ready(function(){
                jQuery($scope.element).spectrum({
                    type: "color",
                    showPalette: false,
                    showInput: true,
                    showButtons: false,
                    move: function(color){
                        $scope.post[$scope.key] = color.toHexString();
                        if($scope.highlightclass != null && $scope.highlighttype != null){
                            $scope.preview($scope.highlightclass, $scope.highlighttype, color.toHexString());
                        }

                        $scope.$apply();
                    }
                });

                jQuery($scope.element).spectrum("set", $scope.post[$scope.key]);
            });

            $scope.preview = function(highlightclass, highlighttype, color){

                switch(highlighttype){
                    case'color':
                        //font color
                        var type = 'color';
                        break;

                    case 'background':
                        //background-color
                        var type = 'background-color';
                        break;

                    default:
                        //both
                        //var type = 'color';
                        break;
                }
                jQuery(highlightclass).css(type, color);


            };

        },

        link: function($scope, element, attr, ctrl){
            $scope.element = element;
        }
    };
});
