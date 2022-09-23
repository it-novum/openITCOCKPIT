angular.module('openITCOCKPIT').directive('servicesStatusExtendedWidget', function($http, $rootScope, $interval, NotyService){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/servicesStatusListExtendedWidget.html',
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
                        ok: 0,
                        warning: 0,
                        critical: 0,
                        unknown: 0
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
                    name: '',
                    keywords: '',
                    not_keywords: ''
                }
            };

            $scope.loadWidgetConfig = function(){
                $http.get("/dashboards/servicesStatusListWidget.json?angular=true&widgetId=" + $scope.widget.id, $scope.filter).then(function(result){
                    $scope.filter.Host = result.data.config.Host;
                    $scope.filter.Service = result.data.config.Service;
                    $('#ServiceTags').tagsinput('add', $scope.filter.Service.keywords);
                    $('#ServiceExcludedTags').tagsinput('add', $scope.filter.Service.not_keywords);

                    $scope.filter.Servicestatus = result.data.config.Servicestatus;
                    $scope.filter.Servicestatus.current_state.ok = result.data.config.Servicestatus.current_state.ok ? 1 : 0;
                    $scope.filter.Servicestatus.current_state.warning = result.data.config.Servicestatus.current_state.warning ? 1 : 0;
                    $scope.filter.Servicestatus.current_state.critical = result.data.config.Servicestatus.current_state.critical ? 1 : 0;
                    $scope.filter.Servicestatus.current_state.unknown = result.data.config.Servicestatus.current_state.unknown ? 1 : 0;
                    $scope.filter.Servicestatus.acknowledged = result.data.config.Servicestatus.acknowledged;
                    $scope.filter.Servicestatus.not_acknowledged = result.data.config.Servicestatus.not_acknowledged;
                    $scope.filter.Servicestatus.in_downtime = result.data.config.Servicestatus.in_downtime;
                    $scope.filter.Servicestatus.not_in_downtime = result.data.config.Servicestatus.not_in_downtime;
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
                    'includes[]': ['downtimes', 'acknowledgements'],
                    'filter[Hosts.name]': $scope.filter.Host.name,
                    'filter[servicename]': $scope.filter.Service.name,
                    'filter[keywords][]': $scope.filter.Service.keywords.split(','),
                    'filter[not_keywords][]': $scope.filter.Service.not_keywords.split(','),
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
                        $scope.saveSettings(params);
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
                $http.post("/dashboards/servicesStatusListWidget.json?angular=true&widgetId=" + $scope.widget.id, settings).then(function(result){
                    $scope.currentPage = 1;
                    $scope.loadWidgetConfig();
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
                $scope.loadWidgetConfig();
            };

            $scope.loadServiceBrowserDetails = function(serviceId){
                $http.get("/services/browser/" + serviceId + ".json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.serviceBrowser = result.data;
                    $('#serviceBrowserModal' + $scope.widget.id).modal('show');
                }, function errorCallback(result){
                    NotyService.genericError();
                });
            }

            $scope.limit = getLimit($widget.height());

            $scope.loadWidgetConfig();

            jQuery(function(){
                $("input[data-role=tagsinput]").tagsinput();
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

            $scope.$watch('sort', function(){
                if($scope.init === true){
                    return true;
                }
                $scope.load({
                    save: true
                });
            });

            // Fix modal appearing under background / backdrop shadow
            //Issue: If possible have no position fixed, absolute or relative elements above the .modal
            setTimeout(function(){
                $('#serviceBrowserModal' + $scope.widget.id).appendTo("body");
            }, 250);

            // Remove modal HTML from DOM when scope changes
            // https://weblog.west-wind.com/posts/2016/sep/14/bootstrap-modal-dialog-showing-under-modal-background
            $scope.$on('$destroy', function(){
                //console.log('Remove modal: #serviceBrowserModal' + $scope.widget.id);
                $('#serviceBrowserModal' + $scope.widget.id).remove();
            });
        },
        link: function($scope, element, attr){

        }
    };
});
