angular.module('openITCOCKPIT').directive('backButton', function($http, $state) {
    return {
        restrict: 'E',
        templateUrl: '/angular/back_button.html',
        scope: {
            'fallbackState': '@',
            'customText': '@'
        },

        controller: function($scope) {
            $scope.text = '';

            $scope.goBack = function() {
                if (history.length > 2 || $scope.fallbackState == null) {
                    window.history.back();
                } else if ($scope.fallbackState != null) {
                    $state.go($scope.fallbackState);
                }
            };
            $scope.defaultText = function(text){
                if ($scope.customText != null) {
                    $scope.text = $scope.customText;
                } else {
                    $scope.text = text;
                }
            };
        },

        link: function($scope, element, attr) {
            element.addClass('btn');
            element.addClass('btn-default');
        }
    };
});