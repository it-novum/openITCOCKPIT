angular.module('openITCOCKPIT').directive('paginator', function($http, $filter, $rootScope){
    return {
        restrict: 'E',
        templateUrl: '/angular/paginator.html',
        scope: {
            'paging': '=',
            'clickAction': '='
        },
        controller: function($scope){
            var paginatorLimit = 5;
            var paginatorOffset = 2;

            $scope.changePage = function(page){
                $scope.clickAction(page);
            };

            $scope.prevPage = function(){
                var page = $scope.paging.page - 1;
                if(page < 1){
                    page = 1;
                }
                $scope.clickAction(page);
            };

            $scope.nextPage = function(){
                var page = $scope.paging.page + 1;
                if(page > $scope.paging.pageCount){
                    page = $scope.paging.pageCount;
                }
                $scope.clickAction(page);
            };

            $scope.pageNumbers = function(){
                if($scope.paging.hasOwnProperty('pageCount')){
                    if($scope.paging.pageCount <= paginatorLimit){
                        //Less pages than paginatorLimit

                        var pages = {};
                        for(i = 1; i <= $scope.paging.pageCount; i++){
                            pages[i] = i;
                        }
                        return pages;
                    }

                    if($scope.paging.pageCount > paginatorLimit){

                        //More than paginatorLimit and current page > 5
                        if(($scope.paging.page + paginatorLimit > $scope.paging.pageCount) || ($scope.paging.page >= paginatorLimit)){
                            var pages = {};
                            var start = $scope.paging.page - paginatorOffset;
                            var end = $scope.paging.page + paginatorOffset;

                            if(end > $scope.paging.pageCount){
                                start = $scope.paging.pageCount - paginatorLimit + 1;
                                end = $scope.paging.pageCount;
                            }

                            for(var i = start; i <= end; i++){
                                pages[i] = i;
                            }
                            return pages;
                        }


                        // More than paginatorLimit but current page <= 5
                        return {
                            1: 1,
                            2: 2,
                            3: 3,
                            4: 4,
                            5: 5
                        }

                    }
                }
                return {};
            };

        },

        link: function(scope, element, attr){

        }
    };
});