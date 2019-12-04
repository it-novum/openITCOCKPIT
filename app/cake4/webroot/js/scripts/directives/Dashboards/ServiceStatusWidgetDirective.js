angular.module('openITCOCKPIT').directive('servicesStatusWidget', function($http, $rootScope, $interval){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/servicesStatusListWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){
            $scope.interval = null;
            $scope.init = true;
            $scope.useScroll = true;
            $scope.scroll_interval = 30000;

            var $widget = $('#widget-' + $scope.widget.id);

            $widget.on('resize', function(event, items){
                hasResize();
            });

            $scope.serviceListTimeout = null;

            $scope.sort = 'Servicestatus.current_state';
            $scope.direction = 'desc';
            $scope.currentPage = 1;

            $scope.filter = {
                Servicestatus: {
                    current_state: {
                        ok: false,
                        warning: false,
                        critical: false,
                        unknown: false
                    },
                    acknowledged: false,
                    not_acknowledged: false,
                    in_downtime: false,
                    not_in_downtime: false,
                    output: ''
                },
                Host: {
                    name: ''
                },
                Service: {
                    name: ''
                }
            };

            var loadWidgetConfig = function(){
                $http.get("/dashboards/servicesStatusListWidget.json?angular=true&widgetId=" + $scope.widget.id, $scope.filter).then(function(result){
                    $scope.filter.Host = result.data.config.Host;
                    $scope.filter.Service = result.data.config.Service;
                    $scope.filter.Servicestatus = result.data.config.Servicestatus;
                    $scope.direction = result.data.config.direction;
                    $scope.sort = result.data.config.sort;
                    $scope.useScroll = result.data.config.useScroll;
                    var scrollInterval = parseInt(result.data.config.scroll_interval);
                    if(scrollInterval < 5000){
                        scrollInterval = 5000;
                    }
                    $scope.scroll_interval = scrollInterval;
                    if($scope.useScroll){
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

                var hasBeenAcknowledged = '';
                var inDowntime = '';
                if($scope.filter.Servicestatus.acknowledged ^ $scope.filter.Servicestatus.not_acknowledged){
                    hasBeenAcknowledged = $scope.filter.Servicestatus.acknowledged === true;
                }
                if($scope.filter.Servicestatus.in_downtime ^ $scope.filter.Servicestatus.not_in_downtime){
                    inDowntime = $scope.filter.Servicestatus.in_downtime === true;
                }

                var params = {
                    'angular': true,
                    'scroll': true,
                    'sort': $scope.sort,
                    'page': $scope.currentPage,
                    'direction': $scope.direction,
                    'filter[Hosts.name]': $scope.filter.Host.name,
                    'filter[servicename]': $scope.filter.Service.name,
                    'filter[Servicestatus.output]': $scope.filter.Servicestatus.output,
                    'filter[Servicestatus.current_state][]': $rootScope.currentStateForApi($scope.filter.Servicestatus.current_state),
                    'filter[Servicestatus.problem_has_been_acknowledged]': hasBeenAcknowledged,
                    'filter[Servicestatus.scheduled_downtime_depth]': inDowntime,
                    'limit': $scope.limit
                };

                $http.get("/services/index.json", {
                    params: params
                }).then(function(result){
                    $scope.services = result.data.all_services;
                    $scope.scroll = result.data.scroll;

                    if(options.save === true){
                        saveSettings(params);
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
                height = height - 34 - 180 - 61 - 10 - 37; //Unit: px
                //                ^ widget Header
                //                     ^ Widget filter
                //                           ^ Paginator
                //                                ^ Margin between header and table
                //                                     ^ Table header

                var limit = Math.floor(height / 36); // 36px = table row height;
                if(limit <= 0){
                    limit = 1;
                }
                return limit;
            };

            var saveSettings = function(){
                var settings = $scope.filter;
                settings['scroll_interval'] = $scope.scroll_interval;
                settings['useScroll'] = $scope.useScroll;
                $http.post("/dashboards/servicesStatusListWidget.json?angular=true&widgetId=" + $scope.widget.id, settings).then(function(result){
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

            $scope.limit = getLimit($widget.height());

            loadWidgetConfig();

            $scope.$watch('filter', function(){
                $scope.currentPage = 1;
                if($scope.init === true){
                    return true;
                }

                $scope.load({
                    save: true
                });
            }, true);

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
