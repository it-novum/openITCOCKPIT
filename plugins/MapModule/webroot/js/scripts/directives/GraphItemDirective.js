angular.module('openITCOCKPIT').directive('graphItem', function($http, $q, $timeout, $interval){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors/graph.html',
        scope: {
            'item': '=',
            'refreshInterval': '='
        },
        controller: function($scope){
            $scope.init = true;
            $scope.statusUpdateInterval = null;

            $scope.selectedGraphdataSource = null;

            $scope.width = 400;
            $scope.height = 200; //200;

            $scope.item.size_x = parseInt($scope.item.size_x, 10);
            $scope.item.size_y = parseInt($scope.item.size_y, 10);

            if($scope.item.size_x > 0){
                $scope.width = $scope.item.size_x;
            }
            if($scope.item.size_y > 0){
                $scope.height = $scope.item.size_y;
            }

            $scope.serverTimeDateObject = null;

            var graphStart = 0;
            var graphEnd = 0;


            $scope.load = function(){
                $q.all([
                    $http.get("/map_module/mapeditors/graph/.json", {
                        params: {
                            'angular': true,
                            'disableGlobalLoader': true,
                            'serviceId': $scope.item.object_id,
                            'type': $scope.item.type
                        }
                    }),
                    $http.get("/angular/user_timezone.json", {
                        params: {
                            'angular': true,
                            'disableGlobalLoader': true
                        }
                    })
                ]).then(function(results){
                    $scope.host = results[0].data.host;
                    $scope.service = results[0].data.service;
                    $scope.allowView = results[0].data.allowView;
                    $scope.timezone = results[1].data.timezone;
                    $scope.serverTimeDateObject = new Date($scope.timezone.server_time_iso);

                    initRefreshTimer();

                    loadGraph($scope.host.uuid, $scope.service.uuid);
                });
            };

            $scope.stop = function(){
                if($scope.statusUpdateInterval !== null){
                    $interval.cancel($scope.statusUpdateInterval);
                }
            };

            //Disable status update interval, if the object gets removed from DOM.
            //E.g in Map rotations
            $scope.$on('$destroy', function(){
                $scope.stop();
            });

            var loadGraph = function(hostUuid, serviceuuid){
                graphEnd = parseInt(new Date($scope.timezone.server_time_iso).getTime() / 1000, 10);
                graphStart = (parseInt(new Date($scope.timezone.server_time_iso).getTime() / 1000, 10) - (1 * 3600));

                $scope.isLoadingGraph = true;
                $http.get('/Graphgenerators/getPerfdataByUuid.json', {
                    params: {
                        angular: true,
                        disableGlobalLoader: true,
                        host_uuid: hostUuid,
                        service_uuid: serviceuuid,
                        start: graphStart,
                        end: graphEnd,
                        jsTimestamp: 1
                    }
                }).then(function(result){
                    $scope.isLoadingGraph = false;
                    $scope.responsePerfdata = result.data.performance_data;

                    processPerfdata();
                    renderGraph($scope.perfdata);
                    $scope.init = false;
                });
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
                    transition: 'all 1s',
                    'z-index': 5040
                });

                $('#mapgraph-' + $scope.item.id).bind('plothover', function(event, pos, item){
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
                            if(item.series['unit']){
                                tooltip_text += ' ' + item.series.unit;
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
                if(!performance_data){
                    return;
                }
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

                var gaugeData = [];
                for(var timestamp in performance_data.data){
                    var frontEndTimestamp = parseInt(timestamp, 10);
                    gaugeData.push([frontEndTimestamp, performance_data.data[timestamp]]);
                }

                var label = $scope.service.servicename + ' "' + performance_data.datasource.label + '"';
                if(performance_data.datasource.unit){
                    label = label + ' in ' + performance_data.datasource.unit;
                }

                label = htmlspecialchars(label);

                graph_data.push({
                    label: label,
                    data: gaugeData,
                    unit: performance_data.datasource.unit,
                    // https://github.com/MichaelZinsmaier/CurvedLines
                    curvedLines: {
                        apply: true,
                        //monotonicFit: true
                    }
                });
                var options = GraphDefaultsObj.getDefaultOptions();
                options.height = $scope.height + 'px';
                options.colors = [GraphDefaultsObj.defaultBorderColor];

                options.legend = {
                    show: $scope.item.show_label,
                    position: 'nw',
                    backgroundOpacity: 0
                };

                options.tooltip = true;
                options.tooltipOpts = {
                    defaultTheme: false
                };

                options.xaxis.tickFormatter = function(val, axis){
                    var date = luxon.DateTime.fromJSDate(new Date(val)).setZone($scope.timezone.user_timezone);
                    return date.toFormat('HH:mm:ss');
                };

                options.series.color = defaultColor;
                options.series.threshold = thresholdAreas;
                options.grid.markings = thresholdLines;
                //options.lines.fillColor.colors = [{opacity: 0.4}, {brightness: 1, opacity: 1}];
                options.lines.fillColor.colors = [{ brightness: 1, opacity: 0.2 }, { brightness: 1, opacity: 0.2}];
                options.points = {
                    show: false,
                    radius: 2.5
                };

                if($scope.height < 130){
                    options.xaxis = {
                        ticks: false
                    };
                }

                options.xaxis.min = graphStart * 1000;
                options.xaxis.max = graphEnd * 1000;
                options.selection.mode = null;

                $scope.plot = $.plot('#mapgraph-' + $scope.item.id, graph_data, options);
            };

            var processPerfdata = function(){
                if($scope.responsePerfdata !== null){
                    if($scope.item.metric === null){
                        //Use the first metric
                        $scope.perfdata = $scope.responsePerfdata[0];
                    }else{
                        for(var metricNo in $scope.responsePerfdata){
                            if($scope.responsePerfdata[metricNo].datasource.name === $scope.item.metric){
                                $scope.perfdata = $scope.responsePerfdata[metricNo];
                            }
                        }
                    }
                }
            };

            var initRefreshTimer = function(){
                if($scope.refreshInterval > 0 && $scope.statusUpdateInterval === null){
                    $scope.statusUpdateInterval = $interval(function(){
                        $scope.load();
                    }, $scope.refreshInterval);
                }
            };

            $scope.$watchGroup(['item.size_x', 'item.show_label'], function(){
                if($scope.init){
                    return;
                }

                if($scope.item.size_x > 0){
                    $scope.width = $scope.item.size_x; //The view adds 10px
                }

                if($scope.item.size_y > 0){
                    $scope.height = $scope.item.size_y;
                }

                //Let AngularJS update the template and rerender graph
                $timeout(function(){
                    renderGraph($scope.perfdata)
                }, 250);
            });

            $scope.$watch('item.object_id', function(){
                if($scope.init || $scope.item.object_id === null){
                    //Avoid ajax error if user search a service in Gadget config modal
                    return;
                }

                $scope.load();
            });

            $scope.$watch('item.metric', function(){
                if($scope.init){
                    return;
                }

                processPerfdata();
                renderGraph($scope.perfdata);
            });

            $scope.load();
        },

        link: function(scope, element, attr){

        }
    };
});
