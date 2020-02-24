angular.module('openITCOCKPIT').directive('macrosDirective', function($http){
    return {
        restrict: 'E',
        templateUrl: '/angular/macros.html',
        scope: {
            'macro': '=',
            'macroName': '=',
            'callback': '=',
            'index': '=',
            'errors': '='
        },

        controller: function($scope){

            $scope.$watch('macro', function(){
                var name = $scope.macro.name.toUpperCase();
                name = name.replace(/[^\d\w\_]/g, '');
                $scope.macro.name = name;
            }, true)

        },

        link: function($scope, element, attr){

        }
    };
});
