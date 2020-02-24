angular.module('openITCOCKPIT').directive('mapText', function($http, BBParserService){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors/maptext.html',
        scope: {
            'item': '='
        },
        controller: function($scope){

            $scope.$watch('item.text', function(){
                if($scope.item.text !== null && typeof $scope.item.text !== 'undefined'){
                    $scope.bbhtml = BBParserService.parse($scope.item.text);
                }
            });

        },

        link: function(scope, element, attr){

        }
    };
});
