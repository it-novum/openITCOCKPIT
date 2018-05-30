angular.module('openITCOCKPIT')
    .controller('ServicesBrowserController', function($scope, $http, QueryStringService, $interval){

        $scope.id = QueryStringService.getCakeId();

        $scope.showFlashSuccess = false;

        $scope.canSubmitExternalCommands = false;

        $scope.tags = [];

        $scope.init = true;

        $scope.serviceStatusTextClass = 'txt-primary';

        $scope.selectedGraphdataSource = null;

        $scope.isLoadingGraph = false;

        $scope.dataSources = [];

        var graphTimeSpan = 4;

        var flappingInterval;

        $scope.showFlashMsg = function(){
            $scope.showFlashSuccess = true;
            $scope.autoRefreshCounter = 5;
            var interval = $interval(function(){
                $scope.autoRefreshCounter--;
                if($scope.autoRefreshCounter === 0){
                    $scope.load();
                    $interval.cancel(interval);
                    $scope.showFlashSuccess = false;
                }
            }, 1000);
        };

        $scope.load = function(){
            $http.get("/services/browser/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.mergedService = result.data.mergedService;
                $scope.mergedService.Service.disabled = parseInt($scope.mergedService.Service.disabled, 10);
                $scope.contacts = result.data.contacts;
                $scope.contactgroups = result.data.contactgroups;
                $scope.host = result.data.host;
                $scope.tags = $scope.mergedService.Service.tags.split(',');
                $scope.hoststatus = result.data.hoststatus;
                $scope.servicestatus = result.data.servicestatus;
                $scope.servicestatusForIcon = {
                    Servicestatus: $scope.servicestatus
                };
                $scope.serviceStatusTextClass = getServicestatusTextColor();


                $scope.acknowledgement = result.data.acknowledgement;
                $scope.downtime = result.data.downtime;

                $scope.hostAcknowledgement = result.data.hostAcknowledgement;
                $scope.hostDowntime = result.data.hostDowntime;

                $scope.canSubmitExternalCommands = result.data.canSubmitExternalCommands;

                $scope.priorities = {
                    1: false,
                    2: false,
                    3: false,
                    4: false,
                    5: false
                };
                var priority = parseInt($scope.mergedService.Service.priority, 10);
                for(var i = 1; i <= priority; i++){
                    $scope.priorities[i] = true;
                }

                if($scope.mergedService.Service.has_graph){
                    loadGraph($scope.host.Host.uuid, $scope.mergedService.Service.uuid);
                }

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


        $scope.getObjectForDowntimeDelete = function(){
            var object = {};
            object[$scope.downtime.internalDowntimeId] = $scope.host.Host.name + ' / ' + $scope.mergedService.Service.name;
            return object;
        };

        $scope.getObjectForHostDowntimeDelete = function(){
            var object = {};
            object[$scope.hostDowntime.internalDowntimeId] = $scope.host.Host.name;
            return object;
        };

        $scope.getObjectsForExternalCommand = function(){
            return [{
                Service: {
                    id: $scope.mergedService.Service.id,
                    uuid: $scope.mergedService.Service.uuid,
                    name: $scope.mergedService.Service.name
                },
                Host: {
                    id: $scope.host.Host.id,
                    uuid: $scope.host.Host.uuid,
                    name: $scope.host.Host.name,
                    satelliteId: $scope.host.Host.satellite_id
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
            graphTimeSpan = timespan;
            loadGraph($scope.host.Host.uuid, $scope.mergedService.Service.uuid);
        };

        $scope.changeDataSource = function(dsId){
            $scope.selectedGraphdataSource = dsId;
            renderGraph($scope.perfdata)
        };

        var getServicestatusTextColor = function(){
            switch($scope.servicestatus.currentState){
                case 0:
                case '0':
                    return 'txt-color-green';

                case 1:
                case '1':
                    return 'warning';

                case 2:
                case '2':
                    return 'txt-color-red';

                case 3:
                case '3':
                    return 'txt-color-blueLight';
            }
            return 'txt-primary';
        };


        var loadGraph = function(hostUuid, serviceuuid){
            $scope.isLoadingGraph = true;
            $http.get('/Graphgenerators/getPerfdataByUuid.json', {
                params: {
                    angular: true,
                    host_uuid: hostUuid,
                    service_uuid: serviceuuid,
                    hours: graphTimeSpan,
                    jsTimestamp: 1
                }
            }).then(function(result){
                $scope.isLoadingGraph = false;
                $scope.dataSources = [];
                $scope.perfdata = result.data.performance_data;
                for(var dsKey in  $scope.perfdata){
                    $scope.dataSources.push($scope.perfdata[dsKey].datasource.label);
                }
                renderGraph($scope.perfdata);
            });
        };

        var initTooltip = function(){
            var previousPoint = null;
            var $graph_data_tooltip = $('#graph_data_tooltip');

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

                        showTooltip(item.pageX, item.pageY, tooltip_text, item.datapoint[0]);
                    }
                }else{
                    $("#graph_data_tooltip").hide();
                    previousPoint = null;
                }
            });
        };

        var showTooltip = function(x, y, contents, timestamp){
            var self = this;
            var $graph_data_tooltip = $('#graph_data_tooltip');

            var fooJS = new Date(timestamp + ($scope.timezone.server_timezone_offset * 1000));
            var fixTime = function(value){
                if(value < 10){
                    return '0' + value;
                }
                return value;
            };

            var humanTime = fixTime(fooJS.getUTCDate()) + '.' + fixTime(fooJS.getUTCMonth() + 1) + '.' + fooJS.getUTCFullYear() + ' ' + fixTime(fooJS.getUTCHours()) + ':' + fixTime(fooJS.getUTCMinutes());

            $graph_data_tooltip
                .html('<i class="fa fa-clock-o"></i> ' + humanTime + '<br /><strong>' + contents + '</strong>')
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
            if($scope.selectedGraphdataSource === null){
                $scope.selectedGraphdataSource = 0;
            }

            var defaultColor = 'green';

            if(performance_data[$scope.selectedGraphdataSource].datasource.warn !== "" && performance_data[$scope.selectedGraphdataSource].datasource.crit !== ""){
                var warn = parseFloat(performance_data[$scope.selectedGraphdataSource].datasource.warn);
                var crit = parseFloat(performance_data[$scope.selectedGraphdataSource].datasource.crit);

                //Add warning and critical line to chart
                thresholdLines.push({
                    color: '#FFFF00',
                    yaxis: {
                        from: warn,
                        to: warn
                    }
                });

                thresholdLines.push({
                    color: '#FF0000',
                    yaxis: {
                        from: crit,
                        to: crit
                    }
                });

                //Change color of the area chart for warning and critical
                if(warn > crit){
                    thresholdAreas.push({
                        below: warn,
                        color: '#FFFF00'
                    });
                    thresholdAreas.push({
                        below: crit,
                        color: '#FF0000'
                    });
                }else{

                    defaultColor = '#FF0000';
                    thresholdAreas.push({
                        below: crit,
                        color: '#FFFF00'
                    });
                    thresholdAreas.push({
                        below: warn,
                        color: 'green'
                    });
                }
            }

            var graph_data = [];
            for(var timestamp in performance_data[$scope.selectedGraphdataSource].data){
                graph_data.push([timestamp, performance_data[$scope.selectedGraphdataSource].data[timestamp]]);
            }

            var color_amount = 1;
            var color_generator = new ColorGenerator();
            var options = {
                width: '100%',
                height: '500px',
                colors: color_generator.generate(color_amount, 90, 120),
                legend: false,
                grid: {
                    hoverable: true,
                    markings: thresholdLines,
                    borderWidth: {
                        top: 1,
                        right: 1,
                        bottom: 1,
                        left: 1
                    },
                    borderColor: {
                        top: '#CCCCCC'
                    }
                },
                tooltip: true,
                tooltipOpts: {
                    defaultTheme: false
                },
                xaxis: {
                    mode: 'time',
                    timeformat: '%d.%m.%y %H:%M:%S', // This is handled by a plugin, if it is used -> jquery.flot.time.js
                    tickFormatter: function(val, axis){
                        var fooJS = new Date(val + ($scope.timezone.server_timezone_offset * 1000));
                        var fixTime = function(value){
                            if(value < 10){
                                return '0' + value;
                            }
                            return value;
                        };
                        return fixTime(fooJS.getUTCDate()) + '.' + fixTime(fooJS.getUTCMonth() + 1) + '.' + fooJS.getUTCFullYear() + ' ' + fixTime(fooJS.getUTCHours()) + ':' + fixTime(fooJS.getUTCMinutes());
                    }
                },
                lines: {
                    show: true,
                    lineWidth: 1,
                    fill: true,
                    steps: 0,
                    fillColor: {
                        colors: [{
                            opacity: 0.4
                        },
                            {
                                opacity: 0.3
                            },
                            {
                                opacity: 0.9
                            }]
                    }
                },
                points: {
                    show: false,
                    radius: 1
                },
                series: {
                    show: true,
                    labelFormatter: function(label, series){
                        // series is the series object for the label
                        return '<a href="#' + label + '">' + label + '</a>';
                    },
                    color: defaultColor,
                    threshold: thresholdAreas
                },
                selection: {
                    mode: "x"
                }
            };


            self.plot = $.plot('#graphCanvas', [graph_data], options);
        };


        $scope.load();
        $scope.loadTimezone();


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

    })
;