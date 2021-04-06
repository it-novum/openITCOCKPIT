angular.module('openITCOCKPIT').directive('automapView', function($http, $state){
    return {
        restrict: 'A',
        templateUrl: '/automaps/viewDirective.html',
        scope: {
            'automapId': '='
        },

        controller: function($scope){
            $scope.init = true;
            $scope.currentPage = 1;
            $scope.useScroll = true;

            $scope.load = function(){
                var params = {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'page': $scope.currentPage
                };
                $http.get("/automaps/view/" + $scope.id + ".json", {
                    params: params
                }).then(function(result){
                    $scope.automap = result.data.automap;
                    $scope.servicesByHost = result.data.servicesByHost;

                    if($scope.automap.use_paginator){
                        $scope.paging = result.data.paging;
                        $scope.scroll = result.data.scroll;
                    }
                    $scope.init = false;

                }, function errorCallback(result){
                    if(result.status === 403){
                        $state.go('403');
                    }

                    if(result.status === 404){
                        $state.go('404');
                    }
                });

            };//load function

            $scope.changepage = function(page){
                if(page !== $scope.currentPage){
                    $scope.currentPage = page;
                    $scope.load();
                }
            };

            $scope.changeMode = function(val){
                $scope.useScroll = val;
                $scope.load();
            };


            $scope.$watch('automapId', function(){
                $scope.id = parseInt($scope.automapId, 10);
                $scope.load();
            });
        },

        link: function($scope, element, attr){

        }
    };
});
