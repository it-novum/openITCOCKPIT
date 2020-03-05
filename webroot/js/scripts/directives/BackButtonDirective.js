angular.module('openITCOCKPIT').directive('backButton', function($http, $state){
    return {
        restrict: 'A',
        scope: {
            'fallbackState': '@',
        },

        link: function($scope, element, attr){
            element.click(function(){
                if($state.previous != null && $state.previous.name !== "" && $state.previous.url !== "^"){
                    $state.go($state.previous.name, $state.previous.params);
                }else if($state.previousUrl != null && $state.previousUrl !== "" && $state.previousUrl !== $state.currentUrl){
                    location.href = $state.previousUrl;
                }else if($scope.fallbackState != null){
                    $state.go($scope.fallbackState);
                }else{
                    window.history.back();
                }
            });
        }
    };
});
