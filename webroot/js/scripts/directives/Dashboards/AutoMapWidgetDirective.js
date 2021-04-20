angular.module('openITCOCKPIT').directive('automapWidget', function($http, $rootScope, $interval){
    return {
        restrict: 'E',
        templateUrl: '/automaps/automapWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){
            $scope.interval = null;
            $scope.init = true;
            $scope.currentPage = 1;
            $scope.useScroll = true;
            $scope.scroll_interval = 30000;
            $scope.limit = 25;
            $scope.mode = 'widget';

            $scope.automap_id = null;

            $scope.automapTimeout = null;
            $scope.currentPage = 1;


            var loadWidgetConfig = function(){
                $http.get("/automaps/automapWidget.json?angular=true&widgetId=" + $scope.widget.id).then(function(result){
                    $scope.useScroll = result.data.config.useScroll || true;
                    $scope.automap_id = parseInt(result.data.config.automap_id, 10);
                    $scope.limit = parseInt(result.data.config.limit, 10);
                    var scrollInterval = parseInt(result.data.config.scroll_interval) || 30000;
                    if(scrollInterval < 5000){
                        scrollInterval = 5000;
                    }
                    $scope.scroll_interval = scrollInterval;
                    $scope.load();
                });
            };

            $scope.$on('$destroy', function(){
                $scope.pauseScroll();
            });


            $scope.load = function(options){
                options = options || {};
                options.save = options.save || false;

                var params = {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'page': $scope.currentPage,
                    'limit': $scope.limit
                };
                if($scope.automap_id){
                    $http.get("/automaps/view/" + $scope.automap_id + ".json", {
                        params: params
                    }).then(function(result){
                        $scope.automap = result.data.automap;
                        $scope.servicesByHost = result.data.servicesByHost;

                        if($scope.automap.use_paginator){
                            $scope.paging = result.data.paging;
                            $scope.scroll = result.data.scroll;
                        }

                        if(options.save === true){
                            $scope.saveSettings(params);
                        }

                        if($scope.useScroll){
                            $scope.startScroll();
                        }
                        $scope.init = false;
                    });
                }
            };

            $scope.loadAutomaps = function(searchString){
                $http.get("/automaps/loadAutomapsByString.json", {
                    params: {
                        'angular': true,
                        'filter[Automap.name]': searchString,
                        'selected[]': $scope.automap_id
                    }
                }).then(function(result){
                    $scope.automaps = result.data.automaps;
                });
            };

            $scope.changepage = function(page){
                if(page !== $scope.currentPage){
                    $scope.currentPage = page;
                    $scope.load();
                }
            };


            $scope.startScroll = function(){
                $scope.pauseScroll();
                $scope.useScroll = true;
                if($scope.scroll){
                    $scope.interval = $interval(function(){
                        var page = $scope.currentPage;
                        if($scope.scroll.hasNextPage){
                            page++;
                        }else{
                            page = 1;
                        }
                        $scope.changepage(page)
                    }, $scope.scroll_interval);
                }
            };

            $scope.pauseScroll = function(){
                if($scope.interval !== null){
                    $interval.cancel($scope.interval);
                    $scope.interval = null;
                }
                $scope.useScroll = false;
            };

            var getTimeString = function(){
                return (new Date($scope.scroll_interval * 60)).toUTCString().match(/(\d\d:\d\d)/)[0] + " minutes";
            };


            $scope.hideConfig = function(){
                $scope.$broadcast('FLIP_EVENT_IN');
            };
            $scope.showConfig = function(){
                $scope.$broadcast('FLIP_EVENT_OUT');
                $scope.loadAutomaps('');
            };

            $scope.saveSettings = function(){
                var settings = {
                    'automap_id': $scope.automap_id,
                    'scroll_interval': $scope.scroll_interval,
                    'useScroll': $scope.useScroll,
                    'limit': $scope.limit
                };

                $http.post("/automaps/automapWidget.json?angular=true&widgetId=" + $scope.widget.id, settings).then(function(result){
                    $scope.currentPage = 1;
                    loadWidgetConfig();
                    $scope.hideConfig();
                    if($scope.init === true){
                        return true;
                    }
                    return true;
                });
            };


            /** Page load / widget get loaded **/
            loadWidgetConfig();

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

            $scope.$watch('automap_id', function(){
                if($scope.init === true){
                    return true;
                }
                $scope.$apply();
            });
        },

        link: function($scope, element, attr){

        }
    };
});
