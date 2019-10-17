angular.module('openITCOCKPIT')
    .controller('HostsBrowserController', function($scope, $rootScope, $http, QueryStringService, SortService, $interval){

        $scope.id = QueryStringService.getCakeId();

        $scope.activeTab = 'active';
        SortService.setSort('Servicestatus.current_state');
        SortService.setDirection('desc');
        $scope.currentPage = 1;

        $scope.deleteUrl = '/services/delete/';
        $scope.deactivateUrl = '/services/deactivate/';
        $scope.activateUrl = '/services/enable/';

        $scope.parentHostProblems = {};
        $scope.hasParentHostProblems = false;

        $scope.showFlashSuccess = false;

        $scope.canSubmitExternalCommands = false;

        $scope.tags = [];

        $scope.pingResult = [];

        //There is no service status for not monitored services :)
        $scope.fakeServicestatus = {
            Servicestatus: {
                currentState: 5
            }
        };

        $scope.activeServiceFilter = {
            Servicestatus: {
                current_state: {
                    ok: false,
                    warning: false,
                    critical: false,
                    unknown: false
                },
                output: ''
            },
            Service: {
                name: QueryStringService.getValue('filter[Service.servicename]', '')
            }
        };

        $scope.init = true;

        $scope.hostStatusTextClass = 'txt-primary';

        $scope.visTimeline = null;
        $scope.visTimelineInit = true;
        $scope.visTimelineStart = -1;
        $scope.visTimelineEnd = -1;
        $scope.visTimeout = null;
        $scope.visChangeTimeout = null;
        $scope.showTimelineTab = false;
        $scope.timelineIsLoading = false;
        $scope.failureDurationInPercent = null;
        $scope.lastLoadDate = Date.now();

        $scope.selectedGrafanaTimerange = 'now-3h';
        $scope.selectedGrafanaAutorefresh = '60s';

        var flappingInterval;

        var graphStart = 0;
        var graphEnd = 0;

        $scope.showFlashMsg = function(){
            $scope.showFlashSuccess = true;
            $scope.autoRefreshCounter = 5;
            var interval = $interval(function(){
                $scope.autoRefreshCounter--;
                if($scope.autoRefreshCounter === 0){
                    $scope.loadHost();
                    $interval.cancel(interval);
                    $scope.showFlashSuccess = false;
                }
            }, 1000);
        };

        $scope.loadHost = function(){
            $scope.lastLoadDate = Date.now();
            $http.get("/hosts/browser/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.mergedHost = result.data.mergedHost;
                $scope.mergedHost.Host.disabled = parseInt($scope.mergedHost.Host.disabled, 10);
                $scope.tags = $scope.mergedHost.Host.tags.split(',');
                $scope.hoststatus = result.data.hoststatus;
                $scope.hoststateForIcon = {
                    Hoststatus: $scope.hoststatus
                };

                $scope.mainContainer = result.data.mainContainer;
                $scope.sharedContainers = result.data.sharedContainers;
                $scope.hostStatusTextClass = getHoststatusTextColor();

                $scope.parenthosts = result.data.parenthosts;
                $scope.parentHoststatus = result.data.parentHostStatus;
                buildParentHostProblems();

                $scope.acknowledgement = result.data.acknowledgement;

                $scope.downtime = result.data.downtime;

                $scope.canSubmitExternalCommands = result.data.canSubmitExternalCommands;

                $scope.priorities = {
                    1: false,
                    2: false,
                    3: false,
                    4: false,
                    5: false
                };
                var priority = parseInt($scope.mergedHost.Host.priority, 10);
                for(var i = 1; i <= priority; i++){
                    $scope.priorities[i] = true;
                }

                $scope.load();
                $scope.loadGrafanaIframeUrl();

                $scope.init = false;
            });
        };

        $scope.loadTimezone = function(){
            $http.get("/angular/user_timezone.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.timezone = result.data.timezone;
            });
        };

        $scope.changeTab = function(tab){
            if(tab !== $scope.activeTab){
                $scope.services = [];
                $scope.activeTab = tab;

                SortService.setSort('Service.servicename');
                SortService.setDirection('asc');
                $scope.currentPage = 1;

                $scope.load();
            }

        };

        $scope.load = function(){
            switch($scope.activeTab){
                case 'active':
                    $scope.loadActiveServices();
                    break;

                case 'notMonitored':
                    $scope.loadNotMonitoredServices();
                    break;

                case 'disabled':
                    $scope.loadDisabledServices();
                    break;
            }
        };

        $scope.loadActiveServices = function(){
            var params = {
                'angular': true,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Host.id]': $scope.id,
                'filter[Service.servicename]': $scope.activeServiceFilter.Service.name,
                'filter[Servicestatus.output]': $scope.activeServiceFilter.Servicestatus.output,
                'filter[Servicestatus.current_state][]': $rootScope.currentStateForApi($scope.activeServiceFilter.Servicestatus.current_state),
                'filter[Service.disabled]': false
            };

            $http.get("/services/index.json", {
                params: params
            }).then(function(result){
                $scope.services = [];
                $scope.services = result.data.all_services;

                $scope.paging = result.data.paging;
            });
        };

        $scope.loadNotMonitoredServices = function(){
            var params = {
                'angular': true,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Host.id]': $scope.id
            };

            $http.get("/services/notMonitored.json", {
                params: params
            }).then(function(result){
                $scope.services = [];
                $scope.services = result.data.all_services;

                $scope.paging = result.data.paging;
            });
        };

        $scope.loadDisabledServices = function(){
            var params = {
                'angular': true,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Host.id]': $scope.id
            };

            $http.get("/services/disabled.json", {
                params: params
            }).then(function(result){
                $scope.services = [];
                $scope.services = result.data.all_services;

                $scope.paging = result.data.paging;
            });
        };

        $scope.getObjectForDelete = function(hostname, service){
            var object = {};
            object[service.Service.id] = hostname + '/' + service.Service.servicename;
            return object;
        };

        $scope.getObjectForDowntimeDelete = function(){
            var object = {};
            object[$scope.downtime.internalDowntimeId] = $scope.mergedHost.Host.name;
            return object;
        };

        $scope.getObjectsForExternalCommand = function(){
            return [$scope.mergedHost];
        };

        $scope.changepage = function(page){
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.mouseenter = function($event, hostUuid, service){
            $scope.isLoadingGraph = true;
            var offset = {
                top: $event.relatedTarget.offsetTop + 40,
                left: $event.relatedTarget.offsetLeft + 40
            };

            offset.top += $event.relatedTarget.offsetParent.offsetTop;

            var currentScrollPosition = $(window).scrollTop();

            var margin = 15;
            var $popupGraphContainer = $('#serviceGraphContainer');


            if((offset.top - currentScrollPosition + margin + $popupGraphContainer.height()) > $(window).innerHeight()){
                //There is no space in the window for the popup, we need to set it to an higher point
                $popupGraphContainer.css({
                    'top': parseInt(offset.top - $popupGraphContainer.height() - margin + 10),
                    'left': parseInt(offset.left + margin),
                    'padding': '6px'
                });
            }else{
                //Default Popup
                $popupGraphContainer.css({
                    'top': parseInt(offset.top + margin),
                    'left': parseInt(offset.left + margin),
                    'padding': '6px'
                });
            }

            $popupGraphContainer.show();
            loadGraph(hostUuid, service);
        };

        $scope.mouseleave = function(){
            $('#serviceGraphContainer').hide();
            $('#serviceGraphFlot').html('');
        };

        var loadGraph = function(hostUuid, service){
            var serverTime = new Date($scope.timezone.server_time);
            graphEnd = Math.floor(serverTime.getTime() / 1000);
            graphStart = graphEnd - (3600 * 4);

            $http.get('/Graphgenerators/getPerfdataByUuid.json', {
                params: {
                    angular: true,
                    host_uuid: hostUuid,
                    service_uuid: service.Service.uuid,
                    start: graphStart,
                    end: graphEnd,
                    jsTimestamp: 1
                }
            }).then(function(result){
                $scope.isLoadingGraph = false;
                renderGraph(result.data.performance_data);
            });
        };

        var renderGraph = function(performance_data){
            var graph_data = [];
            for(var dsCount in performance_data){
                graph_data[dsCount] = [];
                for(var timestamp in performance_data[dsCount].data){
                    var frontEndTimestamp = (parseInt(timestamp, 10) + ($scope.timezone.user_time_to_server_offset * 1000));
                    graph_data[dsCount].push([frontEndTimestamp, performance_data[dsCount].data[timestamp]]);
                }
                //graph_data.push(performance_data[key].data);
            }

            var GraphDefaultsObj = new GraphDefaults();
            var color_amount = performance_data.length < 3 ? 3 : performance_data.length;
            var colors = GraphDefaultsObj.getColors(color_amount);
            var options = GraphDefaultsObj.getDefaultOptions();
            options.colors = colors.border;
            options.xaxis.tickFormatter = function(val, axis){
                var fooJS = new Date(val);
                var fixTime = function(value){
                    if(value < 10){
                        return '0' + value;
                    }
                    return value;
                };
                return fixTime(fooJS.getDate()) + '.' + fixTime(fooJS.getMonth() + 1) + '.' + fooJS.getFullYear() + ' ' + fixTime(fooJS.getHours()) + ':' + fixTime(fooJS.getMinutes());
            };

            options.xaxis.min = (graphStart + $scope.timezone.user_time_to_server_offset) * 1000;
            options.xaxis.max = (graphEnd + $scope.timezone.user_time_to_server_offset) * 1000;
            self.plot = $.plot('#serviceGraphFlot', graph_data, options);
        };

        $scope.stateIsUp = function(){
            return parseInt($scope.hoststatus.currentState, 10) === 0;
        };

        $scope.stateIsDown = function(){
            return parseInt($scope.hoststatus.currentState, 10) === 1;
        };

        $scope.stateIsUnreachable = function(){
            return parseInt($scope.hoststatus.currentState, 10) === 2;
        };

        $scope.stateIsNotInMonitoring = function(){
            return !$scope.hoststatus.isInMonitoring;
        };

        $scope.startFlapping = function(){
            $scope.stopFlapping();
            flappingInterval = $interval(function(){
                if($scope.flappingState === 0){
                    $scope.flappingState = 1;
                }else{
                    $scope.flappingState = 0;
                }
            }, 750);
        };

        $scope.stopFlapping = function(){
            if(flappingInterval){
                $interval.cancel(flappingInterval);
            }
            flappingInterval = null;
        };

        $scope.ping = function(){
            $scope.pingResult = [];
            $scope.isPinging = true;
            $http.get("/hosts/ping.json", {
                params: {
                    'angular': true,
                    'id': $scope.id
                }
            }).then(function(result){
                $scope.pingResult = result.data.output;
                $scope.isPinging = false;
            });
        };

        var getHoststatusTextColor = function(){
            switch($scope.hoststatus.currentState){
                case 0:
                case '0':
                    return 'txt-color-green';

                case 1:
                case '1':
                    return 'txt-color-red';

                case 2:
                case '2':
                    return 'txt-color-blueLight';
            }
            return 'txt-primary';
        };

        var buildParentHostProblems = function(){
            $scope.hasParentHostProblems = false;
            for(var key in $scope.parenthosts){
                var parentHostUuid = $scope.parenthosts[key].uuid;
                if($scope.parentHoststatus.hasOwnProperty(parentHostUuid)){
                    if($scope.parentHoststatus[parentHostUuid].currentState > 0){
                        $scope.parentHostProblems[parentHostUuid] = {
                            id: $scope.parenthosts[key].id,
                            name: $scope.parenthosts[key].name,
                            state: $scope.parentHoststatus[parentHostUuid].currentState
                        };
                        $scope.hasParentHostProblems = true;
                    }
                }
            }
        };

        $scope.loadTimelineData = function(_properties){
            var properties = _properties || {};
            var start = properties.start || -1;
            var end = properties.end || -1;

            $scope.timelineIsLoading = true;

            if(start > $scope.visTimelineStart && end < $scope.visTimelineEnd){
                $scope.timelineIsLoading = false;
                //Zoom in data we already have
                return;
            }

            $http.get("/hosts/timeline/" + $scope.id + ".json", {
                params: {
                    'angular': true,
                    start: start,
                    end: end
                }
            }).then(function(result){

                var timelinedata = {
                    items: new vis.DataSet(result.data.statehistory),
                    groups: new vis.DataSet(result.data.groups)
                };
                timelinedata.items.add(result.data.downtimes);
                timelinedata.items.add(result.data.notifications);
                timelinedata.items.add(result.data.acknowledgements);
                timelinedata.items.add(result.data.timeranges);

                $scope.visTimelineStart = result.data.start;
                $scope.visTimelineEnd = result.data.end;

                var options = {
                    //orientation: "bottom",
                    orientation: "both",
                    //showCurrentTime: true,
                    start: new Date(result.data.start * 1000),
                    end: new Date(result.data.end * 1000),
                    min: new Date(new Date(result.data.start * 1000).setFullYear(new Date(result.data.start * 1000).getFullYear() - 1)), //May 1 year of zoom
                    max: new Date(result.data.end * 1000),    // upper limit of visible range
                    zoomMin: 1000 * 10 * 60 * 5,   // every 5 minutes
                    format: {
                        minorLabels: {
                            millisecond: 'SSS',
                            second: 's',
                            minute: 'H:mm',
                            hour: 'H:mm',
                            weekday: 'ddd D',
                            day: 'D',
                            week: 'w',
                            month: 'MMM',
                            year: 'YYYY'
                        },
                        majorLabels: {
                            millisecond: 'H:mm:ss',
                            second: 'D MMMM H:mm',
                            // minute:     'ddd D MMMM',
                            // hour:       'ddd D MMMM',
                            minute: 'DD.MM.YYYY',
                            hour: 'DD.MM.YYYY',
                            weekday: 'MMMM YYYY',
                            day: 'MMMM YYYY',
                            week: 'MMMM YYYY',
                            month: 'YYYY',
                            year: ''
                        }
                    }
                };
                renderTimeline(timelinedata, options);
                $scope.timelineIsLoading = false;
            });
        };

        var renderTimeline = function(timelinedata, options){
            var container = document.getElementById('visualization');
            if($scope.visTimeline === null){
                $scope.visTimeline = new vis.Timeline(container, timelinedata.items, timelinedata.groups, options);
                $scope.visTimeline.on('rangechanged', function(properties){
                    if($scope.visTimelineInit){
                        $scope.visTimelineInit = false;
                        return;
                    }

                    if($scope.timelineIsLoading){
                        console.warn('Timeline already loading date. Waiting for server result before sending next request.');
                        return;
                    }

                    if($scope.visTimeout){
                        clearTimeout($scope.visTimeout);
                    }
                    $scope.visTimeout = setTimeout(function(){
                        $scope.visTimeout = null;
                        $scope.loadTimelineData({
                            start: parseInt(properties.start.getTime() / 1000, 10),
                            end: parseInt(properties.end.getTime() / 1000, 10)
                        });
                    }, 500);
                });
            }else{
                //Update existing timeline
                $scope.visTimeline.setItems(timelinedata.items);
            }

            $scope.visTimeline.on('changed', function(){
                if($scope.visTimelineInit){
                    return;
                }
                if($scope.visChangeTimeout){
                    clearTimeout($scope.visChangeTimeout);
                }
                $scope.visChangeTimeout = setTimeout(function(){
                    $scope.visChangeTimeout = null;
                    var timeRange = $scope.visTimeline.getWindow();
                    var visTimelineStartAsTimestamp = new Date(timeRange.start).getTime();
                    var visTimelineEndAsTimestamp = new Date(timeRange.end).getTime();
                    var criticalItems = $scope.visTimeline.itemsData.get({
                        fields: ['start', 'end', 'className', 'group'],    // output the specified fields only
                        type: {
                            start: 'Date',
                            end: 'Date'
                        },
                        filter: function(item){
                            return (item.group == 5 &&
                                (item.className === 'bg-down' || item.className === 'bg-down-soft') &&
                                $scope.CheckIfItemInRange(
                                    visTimelineStartAsTimestamp,
                                    visTimelineEndAsTimestamp,
                                    item
                                )
                            );

                        }
                    });
                    $scope.failureDurationInPercent = $scope.calculateFailures(
                        (visTimelineEndAsTimestamp - visTimelineStartAsTimestamp), //visible time range
                        criticalItems,
                        visTimelineStartAsTimestamp,
                        visTimelineEndAsTimestamp
                    );
                    $scope.$apply();
                }, 500);

            });
        };

        $scope.showTimeline = function(){
            $scope.showTimelineTab = true;
            $scope.loadTimelineData();
        };

        $scope.hideTimeline = function(){
            $scope.showTimelineTab = false;
        };

        $scope.CheckIfItemInRange = function(start, end, item){
            var itemStart = item.start.getTime();
            var itemEnd = item.end.getTime();
            if(itemEnd < start){
                return false;
            }
            else if(itemStart > end){
                return false;
            }
            else if(itemStart >= start && itemEnd <= end){
                return true;
            }
            else if(itemStart >= start && itemEnd > end){ //item started behind the start and ended behind the end
                return true;
            }
            else if(itemStart < start && itemEnd > start && itemEnd < end){ //item started before the start and ended behind the end
                return true;
            }
            else if(itemStart < start && itemEnd >= end){ // item startet before the start and enden before the end
                return true;
            }
            return false;
        }

        $scope.calculateFailures = function(totalTime, criticalItems, start, end){
            var failuresDuration = 0;

            criticalItems.forEach(function(criticalItem){
                var itemStart = criticalItem.start.getTime();
                var itemEnd = criticalItem.end.getTime();
                failuresDuration += ((itemEnd > end) ? end : itemEnd) - ((itemStart < start) ? start : itemStart);
            });
            return (failuresDuration / totalTime * 100).toFixed(3);
        };

        $scope.loadGrafanaIframeUrl = function(){
            $http.get("/hosts/getGrafanaIframeUrlForDatepicker/.json", {
                params: {
                    'uuid': $scope.mergedHost.Host.uuid,
                    'angular': true,
                    'from': $scope.selectedGrafanaTimerange,
                    'refresh': $scope.selectedGrafanaAutorefresh
                }
            }).then(function(result){
                $scope.GrafanaDashboardExists = result.data.GrafanaDashboardExists;
                $scope.GrafanaIframeUrl = result.data.iframeUrl;
            });
        };

        $scope.grafanaTimepickerCallback = function(selectedTimerange, selectedAutorefresh){
            $scope.selectedGrafanaTimerange = selectedTimerange;
            $scope.selectedGrafanaAutorefresh = selectedAutorefresh;
            $scope.loadGrafanaIframeUrl();
        };


        $scope.loadHost();
        $scope.loadTimezone();
        SortService.setCallback($scope.load);

        $scope.$watch('activeServiceFilter', function(){
            if($scope.init){
                return;
            }
            $scope.currentPage = 1;
            $scope.load();
        }, true);

        $scope.$watch('hoststatus.isFlapping', function(){
            if($scope.hoststatus){
                if($scope.hoststatus.hasOwnProperty('isFlapping')){
                    if($scope.hoststatus.isFlapping === true){
                        $scope.startFlapping();
                    }

                    if($scope.hoststatus.isFlapping === false){
                        $scope.stopFlapping();
                    }

                }
            }
        });

    });
