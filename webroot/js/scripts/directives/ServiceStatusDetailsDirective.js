angular.module('openITCOCKPIT').directive('serviceStatusDetails', function($http, $interval, $timeout, $q){
    return {
        restrict: 'E',
        templateUrl: '/services/details.html',
        controller: function($scope){
            var graphStart = 0;
            var graphEnd = 0;
            $scope.currentServiceDetailsId = null;
            $scope.interval = null;


            $scope.showServiceDetailsFlashMsg = function(){
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
                        $scope.loadServicestatusDetails($scope.currentServiceDetailsId);
                        $interval.cancel($scope.interval);
                        $scope.showFlashSuccess = false;
                    }
                }, 1000);
            };

            $scope.loadServicestatusDetails = function(serviceId){
                $scope.isLoading = true;
                $scope.currentServiceDetailsId = serviceId;
                $q.all([
                    $http.get("/services/browser/" + serviceId + ".json", {
                        params: {
                            'angular': true
                        }
                    }),
                    $http.get("/angular/user_timezone.json", {
                        params: {
                            'angular': true
                        }
                    })
                ]).then(function(results){
                    $scope.mergedService = results[0].data.mergedService;
                    $scope.host = results[0].data.host;
                    $scope.mergedService.disabled = parseInt($scope.mergedService.disabled, 10);
                    $scope.tags = $scope.mergedService.tags.split(',');
                    $scope.hoststatus = results[0].data.hoststatus;
                    $scope.servicestatus = results[0].data.servicestatus;
                    $scope.servicestatusForIcon = {
                        Servicestatus: $scope.servicestatus
                    };


                    $scope.acknowledgement = results[0].data.acknowledgement;
                    $scope.downtime = results[0].data.downtime;

                    $scope.hostAcknowledgement = results[0].data.hostAcknowledgement;
                    $scope.hostDowntime = results[0].data.hostDowntime;

                    $scope.canSubmitExternalCommands = results[0].data.canSubmitExternalCommands;
                    $scope.timezone = results[1].data.timezone;


                    if($scope.mergedService.has_graph){
                        loadGraph($scope.host.Host.uuid, $scope.mergedService.uuid);
                    }

                    $timeout(function(){
                        $scope.isLoading = false;
                    }, 500);

                });
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
                        satelliteId: $scope.host.Host.satellite_id
                    }
                }];
            };

            $scope.getObjectForDowntimeDelete = function(){
                var object = {};
                object[$scope.downtime.internalDowntimeId] = $scope.host.Host.hostname + ' / ' + $scope.mergedService.name;
                return object;
            };

            //Disable interval if object gets removed from DOM.
            $scope.$on('$destroy', function(){
                if($scope.interval !== null){
                    $interval.cancel($scope.interval);
                }
            });

            var loadGraph = function(hostUuid, serviceUuid){
                var serverTime = new Date($scope.timezone.server_time_iso);
                graphEnd = Math.floor(serverTime.getTime() / 1000);
                graphStart = graphEnd - (3600 * 4);

                $http.get('/Graphgenerators/getPerfdataByUuid.json', {
                    params: {
                        angular: true,
                        host_uuid: hostUuid,
                        service_uuid: serviceUuid,
                        start: graphStart,
                        end: graphEnd,
                        jsTimestamp: 1
                    }
                }).then(function(result){
                    $scope.isLoadingGraph = false;
                    renderGraph(result.data.performance_data);
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
                var self = this;
                var $graph_data_tooltip = $('#graph_data_tooltip');

                var fooJS = new Date(timestamp);
                var fixTime = function(value){
                    if(value < 10){
                        return '0' + value;
                    }
                    return value;
                };

                var humanTime = fixTime(fooJS.getDate()) + '.' + fixTime(fooJS.getMonth() + 1) + '.' + fooJS.getFullYear() + ' ' + fixTime(fooJS.getHours()) + ':' + fixTime(fooJS.getMinutes());

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
                var graph_data = [];
                for(var dsCount in performance_data){
                    //graph_data[dsCount] = [];

                    var gaugeData = [];
                    for(var timestamp in performance_data[dsCount].data){
                        var frontEndTimestamp = parseInt(timestamp, 10);
                        gaugeData.push([frontEndTimestamp, performance_data[dsCount].data[timestamp]]);
                    }
                    graph_data.push({
                        label: performance_data[dsCount].datasource.label,
                        data: gaugeData,
                        unit: performance_data[dsCount].datasource.unit
                    });


                    //graph_data.push(performance_data[key].data);
                }

                var GraphDefaultsObj = new GraphDefaults();
                var color_amount = performance_data.length < 3 ? 3 : performance_data.length;
                var colors = GraphDefaultsObj.getColors(color_amount);
                var options = GraphDefaultsObj.getDefaultOptions();
                options.colors = colors.border;
                options.xaxis.tickFormatter = function(val, axis){
                    var date = luxon.DateTime.fromJSDate(new Date(val)).setZone($scope.timezone.user_timezone);
                    return date.toFormat('HH:mm:ss');
                };
                options.legend = {
                    show: true,
                    noColumns: 3,
                    container: $('#graph_legend') // container (as jQuery object) to put legend in, null means default on top of graph
                };
                options.tooltip = true;
                options.tooltipOpts = {
                    defaultTheme: false
                };
                options.points = {
                    show: false,
                    radius: 1
                };
                options.xaxis.min = graphStart * 1000;
                options.xaxis.max = graphEnd * 1000;

                self.plot = $.plot('#graphCanvas', graph_data, options);
            };

        },

        link: function($scope, element, attr){
            $scope.showServiceStatusDetails = function(serviceId){
                $scope.loadServicestatusDetails(serviceId);
                $('#angularServiceStatusDetailsModal').modal('show');
            };
        }
    };
});
