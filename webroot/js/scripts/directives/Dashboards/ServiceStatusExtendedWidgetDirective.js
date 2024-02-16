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
            $scope.min_scroll_intervall = 5000;

            // ITC-3037
            $scope.readOnly    = $scope.widget.isReadonly;

            var $widget = $('#widget-' + $scope.widget.id);

            $widget.on('resize', function(event, items){
                hasResize();
            });

            $scope.serviceListTimeout = null;

            $scope.sort = 'Servicestatus.current_state';
            $scope.direction = 'desc';
            $scope.currentPage = 1;

            $scope.configPageOpen = false;

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
                    output: '',
                    state_older_than: null,
                    state_older_than_unit: 'minutes',
                },
                Host: {
                    name: '',
                    name_regex: false,
                    keywords: '',
                    not_keywords: ''
                },
                Service: {
                    name: '',
                    name_regex: false,
                    keywords: '',
                    not_keywords: ''
                }
            };

            $scope.loadWidgetConfig = function(){
                $http.get("/dashboards/servicesStatusListWidget.json?angular=true&widgetId=" + $scope.widget.id, $scope.filter).then(function(result){
                    $scope.filter.Host = result.data.config.Host;
                    $scope.filter.Service = result.data.config.Service;
                    $('#HostTags-' + $scope.widget.id).tagsinput('add', $scope.filter.Host.keywords);
                    $('#HostExcludedTags-' + $scope.widget.id).tagsinput('add', $scope.filter.Host.not_keywords);
                    $('#ServiceTags-' + $scope.widget.id).tagsinput('add', $scope.filter.Service.keywords);
                    $('#ServiceExcludedTags-' + $scope.widget.id).tagsinput('add', $scope.filter.Service.not_keywords);

                    $scope.filter.Servicestatus = result.data.config.Servicestatus;
                    $scope.filter.Servicestatus.current_state.ok = result.data.config.Servicestatus.current_state.ok ? 1 : 0;
                    $scope.filter.Servicestatus.current_state.warning = result.data.config.Servicestatus.current_state.warning ? 1 : 0;
                    $scope.filter.Servicestatus.current_state.critical = result.data.config.Servicestatus.current_state.critical ? 1 : 0;
                    $scope.filter.Servicestatus.current_state.unknown = result.data.config.Servicestatus.current_state.unknown ? 1 : 0;
                    $scope.filter.Servicestatus.acknowledged = result.data.config.Servicestatus.acknowledged;
                    $scope.filter.Servicestatus.not_acknowledged = result.data.config.Servicestatus.not_acknowledged;
                    $scope.filter.Servicestatus.in_downtime = result.data.config.Servicestatus.in_downtime;
                    $scope.filter.Servicestatus.not_in_downtime = result.data.config.Servicestatus.not_in_downtime;
                    $scope.filter.Servicestatus.state_older_than = result.data.config.Servicestatus.state_older_than ? parseInt(result.data.config.Servicestatus.state_older_than, 10) : null;
                    $scope.direction = result.data.config.direction;
                    $scope.sort = result.data.config.sort;
                    $scope.useScroll = result.data.config.useScroll;
                    $scope.filter.Host.name_regex = result.data.config.Host.name_regex;
                    $scope.filter.Service.name_regex = result.data.config.Service.name_regex;
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
                    'filter[Hosts.name_regex]': $scope.filter.Host.name_regex,
                    'filter[Hosts.keywords][]': $scope.filter.Host.keywords.split(','),
                    'filter[Hosts.not_keywords][]': $scope.filter.Host.not_keywords.split(','),
                    'filter[servicename]': $scope.filter.Service.name,
                    'filter[servicename_regex]': $scope.filter.Service.name_regex,
                    'filter[keywords][]': $scope.filter.Service.keywords.split(','),
                    'filter[not_keywords][]': $scope.filter.Service.not_keywords.split(','),
                    'filter[Servicestatus.output]': $scope.filter.Servicestatus.output,
                    'filter[Servicestatus.current_state][]': $rootScope.currentStateForApi($scope.filter.Servicestatus.current_state),
                    'filter[Servicestatus.problem_has_been_acknowledged]': hasBeenAcknowledged,
                    'filter[Servicestatus.scheduled_downtime_depth]': inDowntime,
                    'noConditionFilter[Servicestatus.state_older_than]': $scope.filter.Servicestatus.state_older_than,
                    'noConditionFilter[Servicestatus.state_older_than_unit]': $scope.filter.Servicestatus.state_older_than_unit,
                    'limit': $scope.limit,
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
                $scope.configPageOpen = false;
            };
            $scope.showConfig = function(){
                $scope.$broadcast('FLIP_EVENT_OUT');
                $scope.configPageOpen = true;
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
                if($scope.init === true || $scope.configPageOpen === true){
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
