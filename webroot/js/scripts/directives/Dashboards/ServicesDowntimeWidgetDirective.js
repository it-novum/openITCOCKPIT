angular.module('openITCOCKPIT').directive('servicesDowntimeWidget', function($http, $interval){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/servicesDowntimeWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){
            $scope.interval = null;
            $scope.init = true;
            $scope.useScroll = true;
            $scope.scroll_interval = 30000;
            $scope.min_scroll_intervall = 5000;

            // ITC-3037
            $scope.readOnly    = $scope.widget.isReadonly;

            var $widget = $('#widget-' + $scope.widget.id);

            $widget.on('resize', function(event, items){
                hasResize();
            });

            $scope.serviceListTimeout = null;

            $scope.sort = 'DowntimeServices.scheduled_start_time';
            $scope.direction = 'desc';
            $scope.currentPage = 1;

            /*** Filter Settings ***/
            $scope.filter = {
                DowntimeService: {
                    comment_data: '',
                    was_cancelled: false,
                    was_not_cancelled: false
                },
                Host: {
                    name: ''
                },
                Service: {
                    name: ''
                },
                isRunning: 0,
                hideExpired: 1
            };
            /*** Filter end ***/

            var loadWidgetConfig = function(){
                $http.get("/dashboards/servicesDowntimeWidget.json?angular=true&widgetId=" + $scope.widget.id, $scope.filter).then(function(result){
                    $scope.filter.Host = result.data.config.Host;
                    $scope.filter.Service = result.data.config.Service;
                    $scope.filter.DowntimeService = result.data.config.DowntimeService;
                    $scope.filter.DowntimeService.was_cancelled = result.data.config.DowntimeService.was_cancelled ? true : false;
                    $scope.filter.DowntimeService.was_not_cancelled = result.data.config.DowntimeService.was_not_cancelled ? true : false;
                    $scope.filter.DowntimeService.comment_data = result.data.config.DowntimeService.comment_data;
                    $scope.filter.isRunning = result.data.config.isRunning ? 1 : 0;
                    $scope.filter.hideExpired = result.data.config.hideExpired ? 1 : 0;
                    $scope.direction = result.data.config.direction;
                    $scope.sort = result.data.config.sort;
                    $scope.useScroll = result.data.config.useScroll;
                    var scrollInterval = parseInt(result.data.config.scroll_interval);
                    $scope.scroll_interval = scrollInterval;
                    if(scrollInterval < 5000){
                        $scope.pauseScroll();
                    }else{
                        $scope.startScroll();
                    }

                    $scope.load();
                });
            };

            $scope.$on('$destroy', function(){
                $scope.pauseScroll();
            });

            $scope.load = function(options){

                options = options || {};
                options.save = options.save || false;

                var wasCancelled = '';
                if($scope.filter.DowntimeService.was_cancelled ^ $scope.filter.DowntimeService.was_not_cancelled){
                    wasCancelled = $scope.filter.DowntimeService.was_cancelled === true;
                }
                var params = {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'sort': $scope.sort,
                    'page': $scope.currentPage,
                    'direction': $scope.direction,
                    'filter[DowntimeServices.comment_data]': $scope.filter.DowntimeService.comment_data,
                    'filter[DowntimeServices.was_cancelled]': wasCancelled,
                    'filter[Hosts.name]': $scope.filter.Host.name,
                    'filter[servicename]': $scope.filter.Service.name,
                    'filter[hideExpired]': $scope.filter.hideExpired,
                    'filter[isRunning]': $scope.filter.isRunning,
                    'limit': $scope.limit
                };

                $http.get("/downtimes/service.json", {
                    params: params
                }).then(function(result){
                    $scope.downtimes = result.data.all_service_downtimes;
                    $scope.paging = result.data.paging;
                    $scope.scroll = result.data.scroll;
                    if(options.save === true){
                        $scope.saveSettings(params);
                    }
                    if($scope.useScroll){
                        $scope.startScroll();
                    }

                    $scope.init = false;
                });
            };

            $scope.getSortClass = function(field){
                if(field === $scope.sort){
                    if($scope.direction === 'asc'){
                        return 'fa-sort-asc';
                    }
                    return 'fa-sort-desc';
                }
                return 'fa-sort';
            };

            $scope.orderBy = function(field){
                if(field !== $scope.sort){
                    $scope.direction = 'asc';
                    $scope.sort = field;
                    $scope.load();
                    return;
                }

                if($scope.direction === 'asc'){
                    $scope.direction = 'desc';
                }else{
                    $scope.direction = 'asc';
                }
                $scope.load();
            };

            var hasResize = function(){
                if($scope.serviceListTimeout){
                    clearTimeout($scope.serviceListTimeout);
                }
                $scope.serviceListTimeout = setTimeout(function(){
                    $scope.serviceListTimeout = null;
                    $scope.limit = getLimit($widget.height());
                    if($scope.limit <= 0){
                        $scope.limit = 1;
                    }
                    $scope.load();
                }, 500);
            };

            $scope.startScroll = function(){
                $scope.pauseScroll();
                if(!$scope.useScroll && $scope.scroll_interval === 0){
                    $scope.scroll_interval = $scope.min_scroll_intervall;
                }
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

            var getLimit = function(height){
                height = height - 42 - 61 - 10 - 37; //Unit: px
                //                ^ Widget play/pause div
                //                     ^ Paginator
                //                          ^ Margin between header and table
                //                                ^ Table header

                var limit = Math.floor(height / 36); // 36px = table row height;
                if(limit <= 0){
                    limit = 1;
                }
                return limit;
            };

            $scope.saveSettings = function(){
                var settings = $scope.filter;
                settings['scroll_interval'] = $scope.scroll_interval;
                settings['useScroll'] = $scope.useScroll;
                settings['sort'] = $scope.sort;
                settings['direction'] = $scope.direction;
                $http.post("/dashboards/servicesDowntimeWidget.json?angular=true&widgetId=" + $scope.widget.id, settings).then(function(result){
                    $scope.currentPage = 1;
                    loadWidgetConfig();
                    $scope.hideConfig();
                    if($scope.init === true){
                        return true;
                    }
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

            $scope.hideConfig = function(){
                $scope.$broadcast('FLIP_EVENT_IN');
            };
            $scope.showConfig = function(){
                $scope.$broadcast('FLIP_EVENT_OUT');
                $scope.load();
            };

            $scope.limit = getLimit($widget.height());

            loadWidgetConfig();

            $scope.$watch('scroll_interval', function(scrollInterval){
                $scope.pagingTimeString = getTimeString();
                if($scope.init === true){
                    return true;
                }
                $scope.pauseScroll();
                if(scrollInterval > 0){
                    $scope.startScroll();
                }
                $scope.load({
                    save: true
                });
            });

            $scope.$watch('sort', function(){
                if($scope.init === true){
                    return true;
                }
                $scope.load({
                    save: true
                });
            });
        },

        link: function($scope, element, attr){

        }
    };
});
