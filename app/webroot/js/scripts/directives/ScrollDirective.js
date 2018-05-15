angular.module('openITCOCKPIT').directive('scroll', function($http, $filter, $rootScope){
    return {
        restrict: 'E',
        templateUrl: '/angular/scroll.html',
        scope: {
            'scroll': '=',
            'clickAction': '='
        },
        controller: function($scope){
            var paginatorLimit = 5;
            var paginatorOffset = 2;

            $scope.changePage = function(page){
                $scope.clickAction(page);
            };

            $scope.prevPage = function(){
                var page = $scope.scroll.prevPage;
                if(page < 1){
                    page = 1;
                }
                $scope.clickAction(page);
            };

            $scope.nextPage = function(){
                if($scope.scroll.hasNextPage){
                    var page = $scope.scroll.nextPage;
                    $scope.clickAction(page);
                }
            };

        },

        link: function(scope, element, attr){

        }
    };
});