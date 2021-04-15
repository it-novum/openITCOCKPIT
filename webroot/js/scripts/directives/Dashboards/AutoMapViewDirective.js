angular.module('openITCOCKPIT').directive('automapView', function($http, $state, $interval){
    return {
        restrict: 'A',
        templateUrl: '/automaps/viewDirective.html',
        scope: {
            'widget': '=',
            'automapId': '=',
            'useScroll': '=',
            'scrollInterval': '='
        },

        controller: function($scope){
            $scope.init = true;
            $scope.currentPage = 1;
            $scope.useScroll = true;
            $scope.interval = null;
            //$scope.scroll_interval = $scope.scrollInterval;
            if($scope.scrollInterval === 0){
                $scope.scroll_interval = 30000;
            }else{
                $scope.scroll_interval = $scope.scrollInterval;
            }

            $scope.$on('$destroy', function(){
                $scope.pauseScroll();
            });

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

            $scope.startScroll = function(){
                $scope.pauseScroll();
                $scope.useScroll = true;

                $scope.interval = $interval(function(){
                    var page = $scope.currentPage;
                    if($scope.scroll.hasNextPage){
                        page++;
                    }else{
                        page = 1;
                    }
                    $scope.changepage(page)
                }, $scope.scroll_interval);

            };

            $scope.pauseScroll = function(){
                if($scope.interval !== null){
                    $interval.cancel($scope.interval);
                    $scope.interval = null;
                }
                $scope.useScroll = false;
            };

            $scope.saveSettings = function(){
                $http.post("/automaps/automapWidget.json?angular=true", {
                    Widget: {
                        id: $scope.widget.id
                    },
                    automap_id: $scope.automap.id,
                    scroll_interval: $scope.scroll_interval,
                    useScroll: $scope.useScroll
                }).then(function(result){
                    return true;
                });

            };

            var getTimeString = function(){
                return (new Date($scope.scroll_interval * 60)).toUTCString().match(/(\d\d:\d\d)/)[0] + " minutes";
            };

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


            $scope.$watch('scrollInterval', function(){
                $scope.scroll_interval = parseInt($scope.scroll_interval, 10);
                $scope.load();

            });

            $scope.$watch('scroll_interval', function(){
                $scope.pagingTimeString = getTimeString();
                if($scope.init === true){
                    return true;
                }
                $scope.pauseScroll();
                $scope.startScroll();
                $scope.load({
                    save: true
                });
            });
        },

        link: function($scope, element, attr){

        }
    };
});
