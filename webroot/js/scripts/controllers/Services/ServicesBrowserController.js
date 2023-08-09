angular.module('openITCOCKPIT')
    .controller('ServicesBrowserController', function($scope, $http, $q, QueryStringService, $interval, $stateParams, UuidService, $state, LocalStorageService){

        $scope.id = $stateParams.id;

        $scope.selectedTab = 'tab1';

        $scope.showFlashSuccess = false;

        $scope.canSubmitExternalCommands = false;

        $scope.tags = [];

        $scope.init = true;

        $scope.serviceStatusTextClass = 'txt-primary';

        $scope.isLoadingGraph = false;

        $scope.dataSources = [];
        $scope.currentDataSource = null;
        $scope.currentDataSourceKey = null;

        $scope.serverTimeDateObject = null;

        $scope.availableTimeranges = {
            1: '1 hour',
            2: '2 hours',
            3: '3 hours',
            4: '4 hours',
            8: '8 hours',
            24: '1 day',
            48: '2 days',
            120: '5 days',
            168: '7 days',
            720: '30 days',
            2160: '90 days',
            4464: '6 months',
            8760: '1 year'
        };
        $scope.currentSelectedTimerange = 3;
        $scope.currentAggregation = 'avg';

        $scope.visTimeline = null;
        $scope.visTimelineInit = true;
        $scope.visTimelineStart = -1;
        $scope.visTimelineEnd = -1;
        $scope.visTimeout = null;
        $scope.visChangeTimeout = null;
        $scope.showTimelineTab = false;
        $scope.timelineIsLoading = false;
        $scope.timelineMoveTo = false;
        $scope.failureDurationInPercent = null;
        $scope.lastLoadDate = Date.now();       //required for status color update in service-browser-menu

        $scope.visTimelineRange = {
            visTimelineStartAsTimestamp: null,
            visTimelineEndAsTimestamp: null
        };

        $scope.synchronizeTimes = false;
        $scope.graphHasBeenChanged = false;
        $scope.timelineHasBeenChanged = false;

        $scope.graph = {
            graphAutoRefresh: true,
            showDatapoints: false,
            smoothInterpolation: LocalStorageService.getItemWithDefault('smoothGraphInterpolation', 'false') === 'true',
        };
        $scope.graphAutoRefreshInterval = 0;

        $scope.currentGraphUnit = null;
        $scope.interval = null;

        $scope.start = null;
        $scope.end = null;

        var flappingInterval;
        var zoomCallbackWasBind = false;
        var graphAutoRefreshIntervalId = null;
        var lastGraphStart = 0;
        var lastGraphEnd = 0;
        var graphRenderEnd = 0;

        $scope.showFlashMsg = function(){

            new Noty({
                theme: 'metroui',
                type: 'success',
                layout: 'topCenter',
                text: $scope.flashMshStr,
                timeout: 4000
            }).show();

            $scope.showFlashSuccess = true;
            $scope.autoRefreshCounter = 5;
            $scope.interval = $interval(function(){
                $scope.autoRefreshCounter--;
                if($scope.autoRefreshCounter === 0){
                    $scope.load();
                    $interval.cancel($scope.interval);
                    $scope.showFlashSuccess = false;
                }
            }, 1000);
        };

        $scope.serviceBrowserMenuReschedulingCallback = function(){
            $scope.reschedule($scope.getObjectsForExternalCommand());
        };

        $scope.load = function(){
            $scope.lastLoadDate = Date.now();
            $q.all([
                $http.get("/services/browser/" + $scope.id + ".json", {
                    params: {
                        'angular': true
                    }
                }), $http.get("/angular/user_timezone.json", {
                    params: {
                        'angular': true
                    }
                })
            ]).then(function(results){
                $scope.mergedService = results[0].data.mergedService;
                $scope.serviceType = results[0].data.serviceType;
                $scope.checkCommand = results[0].data.checkCommand;
                $scope.areContactsFromService = results[0].data.areContactsFromService;
                $scope.areContactsInheritedFromHosttemplate = results[0].data.areContactsInheritedFromHosttemplate;
                $scope.areContactsInheritedFromHost = results[0].data.areContactsInheritedFromHost;
                $scope.areContactsInheritedFromServicetemplate = results[0].data.areContactsInheritedFromServicetemplate;
                $scope.checkPeriod = results[0].data.checkPeriod;
                $scope.notifyPeriod = results[0].data.notifyPeriod;
                $scope.host = results[0].data.host;
                $scope.mergedService.disabled = parseInt($scope.mergedService.disabled, 10);
                $scope.tags = $scope.mergedService.tags.split(',');
                $scope.hoststatus = results[0].data.hoststatus;
                $scope.servicestatus = results[0].data.servicestatus;
                $scope.servicestatusForIcon = {
                    Servicestatus: $scope.servicestatus
                };
                $scope.serviceStatusTextClass = getServicestatusTextColor();


                $scope.acknowledgement = results[0].data.acknowledgement;
                $scope.downtime = results[0].data.downtime;

                $scope.hostAcknowledgement = results[0].data.hostAcknowledgement;
                $scope.hostDowntime = results[0].data.hostDowntime;

                $scope.canSubmitExternalCommands = results[0].data.canSubmitExternalCommands;
                $scope.objects = results[0].data.objects;

                //Host container info
                $scope.mainContainer = results[0].data.mainContainer;
                $scope.sharedContainers = results[0].data.sharedContainers;

                $scope.priorities = {
                    1: false,
                    2: false,
                    3: false,
                    4: false,
                    5: false
                };
                var priority = parseInt($scope.mergedService.priority, 10);
                for(var i = 1; i <= priority; i++){
                    $scope.priorities[i] = true;
                }

                $scope.graphAutoRefreshInterval = parseInt($scope.mergedService.check_interval, 10) * 1000;
                $scope.timezone = results[1].data.timezone;

                $scope.serverTimeDateObject = new Date($scope.timezone.server_time_iso);
                //$scope.serverTimeDateObject = luxon.DateTime.fromISO($scope.timezone.server_time_iso).setZone($scope.timezone.user_timezone);
                graphStart = (parseInt($scope.serverTimeDateObject.getTime() / 1000, 10) - ($scope.currentSelectedTimerange * 3600));
                //graphStart = (parseInt($scope.serverTimeDateObject.ts / 1000, 10) - ($scope.currentSelectedTimerange * 3600));
                graphEnd = parseInt($scope.serverTimeDateObject.getTime() / 1000, 10);
                //graphEnd = parseInt($scope.serverTimeDateObject.ts / 1000, 10);

                $scope.dataSources = [];
                for(var dsKey in results[0].data.mergedService.Perfdata){
                    var dsDisplayName = results[0].data.mergedService.Perfdata[dsKey].metric
                    $scope.dataSources.push({
                        key: dsKey, // load this datasource - this is important for Prometheus metrics which have no __name__ like rate() or sum(). We can than load metric 0, 1 or 2...
                        displayName: dsDisplayName // Name of the metric to display in select
                    });
                }

                if($scope.dataSources.length > 0){
                    if($scope.currentDataSource === null){
                        $scope.currentDataSource = $scope.dataSources[0].displayName;
                        $scope.currentDataSourceKey = $scope.dataSources[0].key;
                    }
                }

                if($scope.mergedService.has_graph){
                    loadGraph($scope.host.Host.uuid, $scope.mergedService.uuid, false, graphStart, graphEnd, true);
                }
                if($scope.selectedTab === 'tab3'){
                    $scope.timelineMoveTo = true;
                    $scope.loadTimelineData();
                }

                $scope.loadCustomAlerts();
                $scope.loadSlaInformation();

                if(typeof $scope.serviceBrowserMenuConfig === "undefined"){
                    $scope.serviceBrowserMenuConfig = {
                        autoload: true,
                        serviceId: $scope.mergedService.id,
                        includeServicestatus: true,
                        showReschedulingButton: $scope.mergedService.service_type !== 32, //do not show for Prometheus Services
                        rescheduleCallback: $scope.serviceBrowserMenuReschedulingCallback,
                        showBackButton: false
                    };
                }

                $scope.init = false;

                setTimeout(function(){
                    jQuery(function(){
                        jQuery("[rel=tooltip]").tooltip();
                    });
                }, 250);
            }, function errorCallback(results){
                if(results.status === 403){
                    $state.go('403');
                }

                if(results.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.getObjectForDowntimeDelete = function(){
            var object = {};
            object[$scope.downtime.internalDowntimeId] = $scope.host.Host.hostname + ' / ' + $scope.mergedService.name;
            return object;
        };

        $scope.getObjectForHostDowntimeDelete = function(){
            var object = {};
            object[$scope.hostDowntime.internalDowntimeId] = $scope.host.Host.hostname;
            return object;
        };

        $scope.getObjectForServiceAcknowledgementDelete = function(){
            var object = {};
            object[$scope.mergedService.id] = {
                name: $scope.host.Host.hostname + ' / ' + $scope.mergedService.name,
                hostId: $scope.mergedService.host_id,
                serviceId: $scope.mergedService.id
            };
            return object;
        };

        $scope.getObjectForHostAcknowledgementDelete = function(){
            var object = {};
            object[$scope.host.Host.id] = {
                name: $scope.host.Host.hostname,
                hostId: $scope.host.Host.id,
                serviceId: null
            };
            return object;
        };

        $scope.getObjectsForExternalCommand = function(){
            return [{
                Service: {
                    id: $scope.mergedService.id,
                    uuid: $scope.mergedService.uuid,
                    name: $scope.mergedService.name
                },
                Host: {
                    id: $scope.host.Host.id,
                    uuid: $scope.host.Host.uuid,
                    name: $scope.host.Host.hostname,
                    satelliteId: $scope.host.Host.satelliteId
                }
            }];
        };


        $scope.stateIsOk = function(){
            return parseInt($scope.servicestatus.currentState, 10) === 0;
        };

        $scope.stateIsWarning = function(){
            return parseInt($scope.servicestatus.currentState, 10) === 1;
        };

        $scope.stateIsCritical = function(){
            return parseInt($scope.servicestatus.currentState, 10) === 2;
        };

        $scope.stateIsUnknown = function(){
            return parseInt($scope.servicestatus.currentState, 10) === 3;
        };

        $scope.stateIsNotInMonitoring = function(){
            return !$scope.servicestatus.isInMonitoring;
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

        $scope.changeGraphTimespan = function(timespan){
            $scope.currentSelectedTimerange = timespan;
            var start = parseInt(new Date(new Date($scope.timezone.server_time_iso)).getTime() / 1000, 10) - (timespan * 3600);
            var end = parseInt(new Date(new Date($scope.timezone.server_time_iso)).getTime() / 1000, 10);

            //graphTimeSpan = timespan;
            loadGraph($scope.host.Host.uuid, $scope.mergedService.uuid, false, start, end, true);
            if($scope.synchronizeTimes === true && $scope.timelineHasBeenChanged === false){
                $scope.graphHasBeenChanged = true;
                $scope.loadTimelineData({
                    start: start,
                    end: end
                });
            }
        };

        $scope.changeDataSource = function(gaugeName){
            $scope.currentDataSourceKey = gaugeName;

            //Reset unit - new datasource new unit - maybe
            $scope.currentGraphUnit = null;
            loadGraph($scope.host.Host.uuid, $scope.mergedService.uuid, false, lastGraphStart, lastGraphEnd, false);
        };

        $scope.changeAggregation = function(aggregation){
            $scope.currentAggregation = aggregation;
            loadGraph($scope.host.Host.uuid, $scope.mergedService.uuid, true, lastGraphStart, lastGraphEnd, false);
        };

        //Disable interval if object gets removed from DOM.
        $scope.$on('$destroy', function(){
            if($scope.interval !== null){
                $interval.cancel($scope.interval);
            }
            disableGraphAutorefresh();
            jQuery('#graph_data_tooltip').remove();  //removed all tooltips from DOM
        });

        var getServicestatusTextColor = function(){
            switch($scope.servicestatus.currentState){
                case 0:
                case '0':
                    return 'ok';

                case 1:
                case '1':
                    return 'warning';

                case 2:
                case '2':
                    return 'critical';

                case 3:
                case '3':
                    return 'unknown';
            }
            return 'txt-primary';
        };


        var loadGraph = function(hostUuid, serviceuuid, appendData, start, end, saveStartAndEnd){
            if(saveStartAndEnd){
                lastGraphStart = start;
                lastGraphEnd = end;
            }

            //The last timestamp in the y-axe
            graphRenderEnd = end;

            if($scope.dataSources.length > 0){
                $scope.isLoadingGraph = true;

                var params = {
                    angular: true,
                    host_uuid: hostUuid,
                    service_uuid: serviceuuid,
                    start: start,
                    end: end,
                    jsTimestamp: 1,
                    gauge: $scope.currentDataSourceKey,
                    aggregation: $scope.currentAggregation
                };

                if($scope.currentGraphUnit !== null){
                    params.forcedUnit = $scope.currentGraphUnit;
                }

                $http.get('/Graphgenerators/getPerfdataByUuid.json', {
                    params: params
                }).then(function(result){
                    $scope.isLoadingGraph = false;
                    if(appendData === false){
                        //Did we got date from Server?
                        if(result.data.performance_data.length > 0){
                            //Use the first metrics the server gave us.
                            $scope.currentDataSource = result.data.performance_data[0].datasource.metric;
                            $scope.perfdata = {
                                datasource: result.data.performance_data[0].datasource,
                                data: {}
                            };
                            //Convert Servertime into user time

                            for(var timestamp in result.data.performance_data[0].data){
                                var frontEndTimestamp = (parseInt(timestamp, 10));
                                $scope.perfdata.data[frontEndTimestamp] = result.data.performance_data[0].data[timestamp];
                            }
                        }else{
                            $scope.perfdata = {
                                data: {},
                                datasource: {
                                    ds: null,
                                    name: null,
                                    label: null,
                                    unit: null,
                                    act: null,
                                    warn: null,
                                    crit: null,
                                    min: null,
                                    max: null
                                }
                            };
                        }
                    }

                    if(appendData === true){
                        if(result.data.performance_data.length > 0){
                            //Append new data to current graph
                            $scope.currentDataSource = result.data.performance_data[0].datasource.metric;
                            for(var timestamp in result.data.performance_data[0].data){
                                var frontEndTimestamp = (parseInt(timestamp, 10));
                                $scope.perfdata.data[frontEndTimestamp] = result.data.performance_data[0].data[timestamp];
                            }
                        }
                    }
                    if($scope.graph.graphAutoRefresh === true && $scope.graphAutoRefreshInterval > 1000){
                        enableGraphAutorefresh();
                    }

                    //Save current unit for auto refresh
                    $scope.currentGraphUnit = $scope.perfdata.datasource.unit;
                    renderGraph($scope.perfdata);
                });
            }
        };

        var initTooltip = function(){
            var previousPoint = null;
            var $graph_data_tooltip = $('#graph_data_tooltip');
            var hideTimeout = null;

            $graph_data_tooltip.css({
                position: 'absolute',
                display: 'none',
                //border: '1px solid #666',
                'border-top-left-radius': '5px',
                'border-top-right-radius': '0',
                'border-bottom-left-radius': '0',
                'border-bottom-right-radius': '5px',
                padding: '2px 4px',
                'background-color': '#f2f2f2',
                'border-radius': '5px',
                opacity: 0.9,
                'box-shadow': '2px 2px 3px #888',
                transition: 'all 1s'
            });

            $('#graphCanvas').bind('plothover', function(event, pos, item){
                $('#x').text(pos.pageX.toFixed(2));
                $('#y').text(pos.pageY.toFixed(2));

                if(item){
                    if(previousPoint != item.dataIndex){
                        if(hideTimeout !== null){
                            clearTimeout(hideTimeout);
                            hideTimeout = null;
                        }

                        previousPoint = item.dataIndex;

                        $('#graph_data_tooltip').hide();

                        var value = item.datapoint[1];
                        if(!isNaN(value) && isFinite(value)){
                            value = value.toFixed(4);
                        }
                        var tooltip_text = value;
                        if($scope.currentGraphUnit){
                            tooltip_text += ' ' + $scope.currentGraphUnit;
                        }

                        //Hide the tooltip after 5 seconds
                        hideTimeout = setTimeout(function(){
                            $('#graph_data_tooltip').hide();
                        }, 5000);

                        showTooltip(item.pageX, item.pageY, tooltip_text, item.datapoint[0]);
                    }
                }
            });
        };

        var showTooltip = function(x, y, contents, timestamp){
            var $graph_data_tooltip = $('#graph_data_tooltip');

            var date = luxon.DateTime.fromJSDate(new Date(timestamp)).setZone($scope.timezone.user_timezone);
            var humanTime = date.toFormat('dd.LL.yyyy HH:mm:ss');

            $graph_data_tooltip
                .html('<i class="fa fa-clock-o"></i> ' + humanTime + '<br /><b>' + contents + '</b>')
                .css({
                    top: y,
                    left: x + 10
                })
                .appendTo('body')
                .fadeIn(200);
        };

        var renderGraph = function(performance_data){
            initTooltip();

            var thresholdLines = [];
            var thresholdAreas = [];

            var GraphDefaultsObj = new GraphDefaults();

            var defaultColor = GraphDefaultsObj.defaultFillColor;
            if(performance_data.datasource.warn !== "" &&
                performance_data.datasource.crit !== "" &&
                performance_data.datasource.warn !== null &&
                performance_data.datasource.crit !== null){

                var warn = parseFloat(performance_data.datasource.warn);
                var crit = parseFloat(performance_data.datasource.crit);

                //Add warning and critical line to chart
                thresholdLines.push({
                    color: GraphDefaultsObj.warningBorderColor,
                    yaxis: {
                        from: warn,
                        to: warn
                    }
                });

                thresholdLines.push({
                    color: GraphDefaultsObj.criticalBorderColor,
                    yaxis: {
                        from: crit,
                        to: crit
                    }
                });

                //Change color of the area chart for warning and critical
                if(warn > crit){
                    defaultColor = GraphDefaultsObj.okFillColor;
                    thresholdAreas.push({
                        below: warn,
                        color: GraphDefaultsObj.warningFillColor
                    });
                    thresholdAreas.push({
                        below: crit,
                        color: GraphDefaultsObj.criticalFillColor
                    });
                }else{
                    defaultColor = GraphDefaultsObj.criticalFillColor;
                    thresholdAreas.push({
                        below: crit,
                        color: GraphDefaultsObj.warningFillColor
                    });
                    thresholdAreas.push({
                        below: warn,
                        color: GraphDefaultsObj.okFillColor
                    });
                }
            }

            var graph_data = [];
            for(var timestamp in performance_data.data){
                graph_data.push([timestamp, performance_data.data[timestamp]]);
            }

            var options = GraphDefaultsObj.getDefaultOptions();

            options.height = '300';

            options.colors = defaultColor;
            options.tooltip = true;
            options.tooltipOpts = {
                defaultTheme: false
            };

            options.xaxis.tickFormatter = function(val, axis){
                var date = luxon.DateTime.fromJSDate(new Date(val)).setZone($scope.timezone.user_timezone);
                return date.toFormat('dd.LL.yyyy HH:mm:ss');
            };

            options.series.color = defaultColor;
            options.series.threshold = thresholdAreas;
            options.grid.markings = thresholdLines;
            //options.lines.fillColor.colors = [{opacity: 0.4}, {brightness: 1, opacity: 1}];
            options.lines.fillColor.colors = [{brightness: 1, opacity: 0.2}, {brightness: 1, opacity: 0.2}];

            //options.points = {
            //    show: $scope.graph.showDatapoints,
            //    radius: 2.5
            //};

            options.xaxis.min = (lastGraphStart * 1000);
            options.xaxis.max = (graphRenderEnd * 1000);
            $scope.start = options.xaxis.min;
            $scope.end = options.xaxis.max;

            options.yaxis.axisLabel = performance_data.datasource.unit;

            //$scope.currentGraphUnit = null;
            //if(performance_data.datasource.unit){
            //    $scope.currentGraphUnit = performance_data.datasource.unit;
            //}

            if($scope.graph.smoothInterpolation === true){
                // Enable curved lines
                plot = $.plot('#graphCanvas', [{
                    data: graph_data, // Pass data for curved lines
                    // https://github.com/MichaelZinsmaier/CurvedLines
                    curvedLines: {
                        apply: true,
                        monotonicFit: true,
                        //tension: 1
                    },
                    points: {
                        show: false
                    },
                },
                    {
                        //original data points
                        data: graph_data,
                        points: {
                            show: $scope.graph.showDatapoints,
                            radius: 2.5
                        },
                        lines: {
                            show: false
                        }
                    }
                ], options);
            }else{
                // Use default flot chart
                plot = $.plot('#graphCanvas', [{
                    data: graph_data,
                    curvedLines: {
                        apply: false
                    },
                    points: {
                        show: $scope.graph.showDatapoints,
                        radius: 2.5
                    },
                }], options);
            }


            if(zoomCallbackWasBind === false){
                $("#graphCanvas").bind("plotselected", function(event, ranges){
                    var start = parseInt(ranges.xaxis.from / 1000, 10);
                    var end = parseInt(ranges.xaxis.to / 1000, 10);

                    //Zoomed from right to left?
                    if(start > end){
                        var tmpStart = end;
                        end = start;
                        start = tmpStart;
                    }

                    var currentTimestamp = Math.floor($scope.serverTimeDateObject.getTime() / 1000);
                    var graphAutoRefreshIntervalInSeconds = $scope.graphAutoRefreshInterval / 1000;

                    //Only enable autorefresh, if graphEnd timestamp is near to now
                    //We dont need to autorefresh data from yesterday
                    if((end + graphAutoRefreshIntervalInSeconds + 120) < currentTimestamp){
                        disableGraphAutorefresh();
                    }
                    loadGraph($scope.host.Host.uuid, $scope.mergedService.uuid, false, start, end, true);
                    if($scope.synchronizeTimes === true && $scope.timelineHasBeenChanged === false){
                        $scope.graphHasBeenChanged = true;
                        $scope.loadTimelineData({
                            start: start + $scope.timezone.user_time_to_server_offset,
                            end: end + $scope.timezone.user_time_to_server_offset
                        });
                    }
                });
            }

            zoomCallbackWasBind = true;
        };

        $scope.loadTimelineData = function(_properties){
            var properties = _properties || {};
            var start = properties.start || -1;
            var end = properties.end || -1;

            $scope.timelineIsLoading = true;

            if(start > $scope.visTimelineStart && end < $scope.visTimelineEnd){
                $scope.timelineIsLoading = false;
                //Zoom in data we already have
                if($scope.synchronizeTimes === false){
                    return;
                }
            }


            $http.get("/services/timeline/" + $scope.id + ".json", {
                params: {
                    'angular': true,
                    start: start,
                    end: end
                }
            }).then(function(result){
                var timelinedata = {
                    items: new vis.DataSet(result.data.servicestatehistory),
                    groups: new vis.DataSet(result.data.groups)
                };
                timelinedata.items.add(result.data.statehistory);
                timelinedata.items.add(result.data.downtimes);
                timelinedata.items.add(result.data.notifications);
                timelinedata.items.add(result.data.acknowledgements);
                timelinedata.items.add(result.data.timeranges);

                $scope.visTimelineStart = result.data.start;
                $scope.visTimelineEnd = result.data.end;

                var options = {
                    showCurrentTime: true,
                    orientation: "both",
                    xss: {
                        disabled: false,
                        filterOptions: {
                            whiteList: {
                                i: ['class', 'not-xss-filtered-html'],
                                b: ['class', 'not-xss-filtered-html']
                            },
                        },
                    },
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
            if($scope.visTimeline === null){
                $scope.visTimeline = new vis.Timeline($scope.visContainer, timelinedata.items, timelinedata.groups, options);
                $scope.visTimeline.on('rangechanged', function(properties){
                    if($scope.visTimelineInit){
                        $scope.visTimelineInit = false;
                        return;
                    }
                    if($scope.timelineIsLoading){
                        return;
                    }
                    if(properties.byUser === false){
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
                $scope.visTimeline.setItems(timelinedata.items);
            }
            if($scope.synchronizeTimes === true && $scope.graphHasBeenChanged === true){
                $scope.visTimeline.setWindow(
                    options.start.getTime(),
                    options.end.getTime()
                );
            }

            function timeLinkeOnMouseWheel(event){
                if($scope.synchronizeTimes === false){
                    return;
                }
                $scope.graphHasBeenChanged = false;
                $scope.timelineHasBeenChanged = true;
                event.preventDefault();
            }

            function timelineHandleDown(event){
                if($scope.synchronizeTimes === false){
                    return;
                }
                $scope.graphHasBeenChanged = false;
                $scope.timelineHasBeenChanged = true;
                event.preventDefault();
            }

            $scope.visContainer.addEventListener('wheel', timeLinkeOnMouseWheel);
            $scope.visContainer.onpointerdown = timelineHandleDown;


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
                    $scope.visTimelineRange.visTimelineStartAsTimestamp = new Date(timeRange.start).getTime();
                    $scope.visTimelineRange.visTimelineEndAsTimestamp = new Date(timeRange.end).getTime();


                    if($scope.visTimelineRange.visTimelineEndAsTimestamp < options.end.getTime()){
                        $scope.visTimelineRange.visTimelineEndAsTimestamp = options.end.getTime();
                    }

                    if($scope.synchronizeTimes === true && $scope.timelineMoveTo === false && ($scope.graphHasBeenChanged === false && $scope.timelineHasBeenChanged === true)){
                        loadGraph(
                            $scope.host.Host.uuid,
                            $scope.mergedService.uuid,
                            false,
                            parseInt($scope.visTimelineRange.visTimelineStartAsTimestamp / 1000 - $scope.timezone.user_time_to_server_offset, 10),
                            parseInt($scope.visTimelineRange.visTimelineEndAsTimestamp / 1000 - $scope.timezone.user_time_to_server_offset, 10),
                            true
                        );
                        $scope.graphHasBeenChanged = false;
                        $scope.timelineHasBeenChanged = false;
                    }

                    if($scope.timelineMoveTo === true){
                        $scope.visTimeline.moveTo(options.end.getTime(), {animation: true});
                        $scope.timelineMoveTo = false;
                    }

                    var criticalItems = $scope.visTimeline.itemsData.get({
                        fields: ['start', 'end', 'className', 'group'],    // output the specified fields only
                        type: {
                            start: 'Date',
                            end: 'Date'
                        },
                        filter: function(item){
                            return (item.group == 4 &&
                                (item.className === 'bg-critical' || item.className === 'bg-critical-soft') &&
                                $scope.CheckIfItemInRange(
                                    $scope.visTimelineRange.visTimelineStartAsTimestamp,
                                    $scope.visTimelineRange.visTimelineEndAsTimestamp,
                                    item
                                )
                            );
                        }
                    });
                    $scope.failureDurationInPercent = $scope.calculateFailures(
                        ($scope.visTimelineRange.visTimelineEndAsTimestamp - $scope.visTimelineRange.visTimelineStartAsTimestamp), //visible time range
                        criticalItems,
                        $scope.visTimelineRange.visTimelineStartAsTimestamp,
                        $scope.visTimelineRange.visTimelineEndAsTimestamp
                    );
                    $scope.$apply();
                }, 500);
            });
        };

        $scope.showTimeline = function(){
            $scope.showTimelineTab = true;
            $scope.loadTimelineData();
            $scope.visContainer = document.getElementById('visualization');
        };

        $scope.hideTimeline = function(){
            $scope.showTimelineTab = false;
        };

        $scope.CheckIfItemInRange = function(start, end, item){
            var itemStart = item.start.getTime();
            var itemEnd = item.end.getTime();
            if(itemEnd < start){
                return false;
            }else if(itemStart > end){
                return false;
            }else if(itemStart >= start && itemEnd <= end){
                return true;
            }else if(itemStart >= start && itemEnd > end){ //item started behind the start and ended behind the end
                return true;
            }else if(itemStart < start && itemEnd > start && itemEnd < end){ //item started before the start and ended behind the end
                return true;
            }else if(itemStart < start && itemEnd >= end){ // item startet before the start and enden before the end
                return true;
            }
            return false;
        };

        $scope.calculateFailures = function(totalTime, criticalItems, start, end){
            var failuresDuration = 0;

            criticalItems.forEach(function(criticalItem){
                var itemStart = criticalItem.start.getTime();
                var itemEnd = criticalItem.end.getTime();
                failuresDuration += ((itemEnd > end) ? end : itemEnd) - ((itemStart < start) ? start : itemStart);
            });
            return (failuresDuration / totalTime * 100).toFixed(3);
        };

        $scope.loadIdOrUuid = function(){
            if(UuidService.isUuid($scope.id)){
                // UUID was passed via URL
                $http.get("/services/byUuid/" + $scope.id + ".json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.id = result.data.service.id;
                    $scope.load();
                }, function errorCallback(result){
                    if(result.status === 403){
                        $state.go('403');
                    }

                    if(result.status === 404){
                        $state.go('404');
                    }
                });

            }else{
                // Integer id was passed via URL
                $scope.load();
            }
        };

        $scope.loadCustomAlerts = function(){
            $http.get("/services/loadCustomalerts/.json", {
                params: {
                    'id': $scope.id,
                    'angular': true
                }
            }).then(function(result){
                $scope.CustomalertsExists = result.data.CustomalertsExists;
            });
        };

        $scope.loadSlaInformation = function(){
            $http.get("/services/loadSlaInformation/.json", {
                params: {
                    'id': $scope.mergedService.id,
                    'angular': true
                }
            }).then(function(result){
                $scope.slaOverview = result.data.slaOverview;
            });
        };

        var enableGraphAutorefresh = function(){
            $scope.graph.graphAutoRefresh = true;

            if(graphAutoRefreshIntervalId === null){
                graphAutoRefreshIntervalId = $interval(function(){
                    //Find last timestamp to only load new data and keep the existing
                    var lastTimestampInCurrentData = 0;
                    for(var timestamp in $scope.perfdata.data){
                        timestamp = parseInt(timestamp, 10);
                        if(timestamp > lastTimestampInCurrentData){
                            lastTimestampInCurrentData = timestamp;
                        }
                    }

                    // Get back to server time
                    var start = lastTimestampInCurrentData / 1000;
                    $scope.serverTimeDateObject = new Date($scope.serverTimeDateObject.getTime() + $scope.graphAutoRefreshInterval);

                    var end = Math.floor($scope.serverTimeDateObject.getTime() / 1000);
                    if(start > 0){
                        loadGraph($scope.host.Host.uuid, $scope.mergedService.uuid, true, start, end, false);
                    }
                }, $scope.graphAutoRefreshInterval);
            }
        };

        var disableGraphAutorefresh = function(){
            $scope.graph.graphAutoRefresh = false;

            if(graphAutoRefreshIntervalId !== null){
                $interval.cancel(graphAutoRefreshIntervalId);
            }
            graphAutoRefreshIntervalId = null;
        };


        // Fire on page load

        $scope.loadIdOrUuid();

        $scope.$watch('servicestatus.isFlapping', function(){
            if($scope.servicestatus){
                if($scope.servicestatus.hasOwnProperty('isFlapping')){
                    if($scope.servicestatus.isFlapping === true){
                        $scope.startFlapping();
                    }

                    if($scope.servicestatus.isFlapping === false){
                        $scope.stopFlapping();
                    }

                }
            }
        });

        $scope.$watch('graph.graphAutoRefresh', function(){
            if($scope.init){
                return;
            }

            if($scope.graph.graphAutoRefresh === true){
                enableGraphAutorefresh();
            }else{
                disableGraphAutorefresh();
            }
        });

        $scope.$watch('graph.showDatapoints', function(){
            if($scope.init){
                return;
            }
            loadGraph($scope.host.Host.uuid, $scope.mergedService.uuid, false, lastGraphStart, lastGraphEnd, false);
        });

        $scope.$watch('graph.smoothInterpolation', function(){
            if($scope.init){
                return;
            }

            if($scope.graph.smoothInterpolation){
                LocalStorageService.setItem('smoothGraphInterpolation', 'true');
            }else{
                LocalStorageService.setItem('smoothGraphInterpolation', 'false');
            }

            loadGraph($scope.host.Host.uuid, $scope.mergedService.uuid, false, lastGraphStart, lastGraphEnd, false);
        });


        $scope.$watch('synchronizeTimes', function(){
            if($scope.init){
                return;
            }
            if($scope.synchronizeTimes === false){
                return;
            }
            loadGraph(
                $scope.host.Host.uuid,
                $scope.mergedService.uuid,
                false,
                $scope.visTimelineRange.visTimelineStartAsTimestamp / 1000 - $scope.timezone.user_time_to_server_offset,
                $scope.visTimelineRange.visTimelineEndAsTimestamp / 1000 - $scope.timezone.user_time_to_server_offset,
                true
            );
        }, true);

        jQuery(document).on('show.bs.tooltip', function(e){
            setTimeout(function(){
                jQuery('[data-toggle="tooltip"]').tooltip('hide');
            }, 1500);
        });
    });
