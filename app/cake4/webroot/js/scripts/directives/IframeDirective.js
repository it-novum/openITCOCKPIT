angular.module('openITCOCKPIT').directive('iframeDirective', function($sce){
    return {
        restrict: 'E',
        template: '<iframe src="{{trustedUrl}}" onload="this.height=(screen.height+15);" width="100%" frameborder="0"></iframe>',
        scope: {
            'url': '='
        },

        controller: function($scope){

        },

        link: function($scope, element, attr){
            $scope.$watch('url', function(){
                $scope.trustedUrl = $sce.trustAsResourceUrl($scope.url);
            });
        }
    };
});
