angular.module('openITCOCKPIT').directive('iframeHeightDirective', function($sce){
    return {
        restrict: 'E',
        template: '<iframe src="{{trustedUrl}}" height="{{height}}" width="100%" frameborder="0"></iframe>',
        scope: {
            'url': '=',
            'height': '='
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
