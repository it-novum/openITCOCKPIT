angular.module('openITCOCKPIT').directive('hostsStatusExtendedWidget', function($http, $rootScope, $interval, NotyService){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/hostsStatusListExtendedWidget.html',
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

            $scope.hostListTimeout = null;

            $scope.sort = 'Hoststatus.current_state';
            $scope.direction = 'desc';
            $scope.currentPage = 1;

            $scope.filter = {
                Hoststatus: {
                    current_state: {
                        up: 0,
                        down: 0,
                        unreachable: 0
                    },
                    acknowledged: false,
                    not_acknowledged: false,
                    in_downtime: false,
                    not_in_downtime: false,
                    output: ''
                },
                Host: {
                    name: '',
                    keywords: '',
                    not_keywords: ''
                }
            };

            $scope.loadWidgetConfig = function(){
                $http.get("/dashboards/hostsStatusListExtendedWidget.json?angular=true&widgetId=" + $scope.widget.id, $scope.filter).then(function(result){
                    $scope.filter.Host = result.data.config.Host;
                    $('#HostTags').tagsinput('add', $scope.filter.Host.keywords);
                    $('#HostExcludedTags').tagsinput('add', $scope.filter.Host.not_keywords);
                    $scope.filter.Hoststatus = result.data.config.Hoststatus;
                    $scope.filter.Hoststatus.current_state.up = result.data.config.Hoststatus.current_state.up ? 1 : 0;
                    $scope.filter.Hoststatus.current_state.down = result.data.config.Hoststatus.current_state.down ? 1 : 0;
                    $scope.filter.Hoststatus.current_state.unreachable = result.data.config.Hoststatus.current_state.unreachable ? 1 : 0;
                    $scope.filter.Hoststatus.acknowledged = result.data.config.Hoststatus.acknowledged;
                    $scope.filter.Hoststatus.not_acknowledged = result.data.config.Hoststatus.not_acknowledged;
                    $scope.filter.Hoststatus.in_downtime = result.data.config.Hoststatus.in_downtime;
                    $scope.filter.Hoststatus.not_in_downtime = result.data.config.Hoststatus.not_in_downtime;
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
                if($scope.filter.Hoststatus.acknowledged ^ $scope.filter.Hoststatus.not_acknowledged){
                    hasBeenAcknowledged = $scope.filter.Hoststatus.acknowledged === true;
                }
                if($scope.filter.Hoststatus.in_downtime ^ $scope.filter.Hoststatus.not_in_downtime){
                    inDowntime = $scope.filter.Hoststatus.in_downtime === true;
                }

                var params = {
                    'angular': true,
                    'scroll': true,
                    'sort': $scope.sort,
                    'page': $scope.currentPage,
                    'includes[]': ['downtimes', 'acknowledgements'],
                    'direction': $scope.direction,
                    'filter[Hosts.name]': $scope.filter.Host.name,
                    'filter[Hosts.keywords][]': $scope.filter.Host.keywords.split(','),
                    'filter[Hosts.not_keywords][]': $scope.filter.Host.not_keywords.split(','),
                    'filter[Hoststatus.output]': $scope.filter.Hoststatus.output,
                    'filter[Hoststatus.current_state][]': $rootScope.currentStateForApi($scope.filter.Hoststatus.current_state),
                    'filter[Hoststatus.problem_has_been_acknowledged]': hasBeenAcknowledged,
                    'filter[Hoststatus.scheduled_downtime_depth]': inDowntime,
                    'limit': $scope.limit
                };

                $http.get("/hosts/index.json", {
                    params: params
                }).then(function(result){
                    $scope.hosts = result.data.all_hosts;
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
                if($scope.hostListTimeout){
                    clearTimeout($scope.hostListTimeout);
                }
                $scope.hostListTimeout = setTimeout(function(){
                    $scope.hostListTimeout = null;
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
                $http.post("/dashboards/hostsStatusListExtendedWidget.json?angular=true&widgetId=" + $scope.widget.id, settings).then(function(result){
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

            $scope.loadHostBrowserDetails = function(hostId){
                $http.get("/hosts/browser/" + hostId + ".json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.hostBrowser = result.data;
                    $('#hostBrowserModal' + $scope.widget.id).modal('show');
                }, function errorCallback(result){
                    NotyService.genericError();
                });
            }


            // Fire on page load
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
                $('#hostBrowserModal' + $scope.widget.id).appendTo("body");
            }, 250);

            // Remove modal HTML from DOM when scope changes
            // https://weblog.west-wind.com/posts/2016/sep/14/bootstrap-modal-dialog-showing-under-modal-background
            $scope.$on('$destroy', function(){
                //console.log('Remove modal: #hostBrowserModal' + $scope.widget.id);
                $('#hostBrowserModal' + $scope.widget.id).remove();
            });
        },


        link: function($scope, element, attr){

        }
    };
});
