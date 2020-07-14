angular.module('openITCOCKPIT')
    .controller('AdministratorsDebugController', function($scope, $http, $interval, NotyService){

        $scope.init = true;

        $scope.graph = {
            keepHistory: false
        };

        //Avoid false error messages on page load
        $scope.processInformation = {
            gearmanReachable: true,
            isGearmanWorkerRunning: true
        };

        $scope.sparklines = {};

        var reloadInterval = null;

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

            var fooJS = new Date(timestamp + ($scope.timezone.user_offset * 1000));
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

        var renderGraph = function(cpuLoadHistoryInformation){
            initTooltip();

            var GraphDefaultsObj = new GraphDefaults();

            var defaultColor = GraphDefaultsObj.defaultFillColor;

            var chart1 = [];
            var chart2 = [];
            var chart3 = [];
            for(var timestamp in cpuLoadHistoryInformation[1]){
                chart1.push([timestamp, cpuLoadHistoryInformation[1][timestamp]]);
                chart2.push([timestamp, cpuLoadHistoryInformation[5][timestamp]]);
                chart3.push([timestamp, cpuLoadHistoryInformation[15][timestamp]]);
            }

            var options = GraphDefaultsObj.getDefaultOptions();

            options.height = '300';
            options.tooltip = true;
            options.tooltipOpts = {
                defaultTheme: false
            };
            options.xaxis.tickFormatter = function(val, axis){
                var fooJS = new Date(val + ($scope.timezone.user_offset * 1000));
                var fixTime = function(value){
                    if(value < 10){
                        return '0' + value;
                    }
                    return value;
                };
                return fixTime(fooJS.getUTCDate()) + '.' + fixTime(fooJS.getUTCMonth() + 1) + '.' + fooJS.getUTCFullYear() + ' ' + fixTime(fooJS.getUTCHours()) + ':' + fixTime(fooJS.getUTCMinutes());
            };
            options.colors = ['#6595B4', '#7E9D3A', '#E24913'];
            options.lines.fill = false;

            options.points = {
                show: true,
                radius: 1
            };

            options.yaxis.axisLabel = 'CPU load';

            plot = $.plot('#graphCanvas', [chart1, chart2, chart3], options);
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

        $scope.load = function(){
            $http.get("/Administrators/debug.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.interfaceInformation = result.data.interfaceInformation;
                $scope.processInformation = result.data.processInformation;
                $scope.renderGraph = result.data.renderGraph;
                $scope.currentCpuLoad = result.data.currentCpuLoad;
                $scope.serverInformation = result.data.serverInformation;
                $scope.memory = result.data.memory;
                $scope.diskUsage = result.data.diskUsage;
                $scope.gearmanStatus = result.data.gearmanStatus;
                $scope.emailInformation = result.data.emailInformation;
                $scope.userInformation = result.data.userInformation;

                if($scope.init){
                    setTimeout(function(){
                        jQuery(function(){
                            jQuery("[rel=tooltip]").tooltip();
                        });
                    }, 250);

                    //Only save CPU history on first load and update it later with current values
                    $scope.cpuLoadHistoryInformation = result.data.cpuLoadHistoryInformation;
                }

                if($scope.renderGraph){
                    //Add current values
                    var currentTime = new Date().getTime();

                    if($scope.graph.keepHistory === false){
                        //Drop the first (oldest) record from history and append new value
                        var oldestTimestamp = Object.keys($scope.cpuLoadHistoryInformation[1]);
                        oldestTimestamp = oldestTimestamp.sort().shift();

                        delete $scope.cpuLoadHistoryInformation[1][oldestTimestamp];
                        delete $scope.cpuLoadHistoryInformation[5][oldestTimestamp];
                        delete $scope.cpuLoadHistoryInformation[15][oldestTimestamp]
                    }

                    $scope.cpuLoadHistoryInformation[1][currentTime] = $scope.currentCpuLoad[1];
                    $scope.cpuLoadHistoryInformation[5][currentTime] = $scope.currentCpuLoad[5];
                    $scope.cpuLoadHistoryInformation[15][currentTime] = $scope.currentCpuLoad[15];

                    setTimeout(function(){
                        renderGraph($scope.cpuLoadHistoryInformation);
                    }, 250);
                }

                for(var queueName in $scope.gearmanStatus){
                    var jobs = parseInt($scope.gearmanStatus[queueName].jobs, 10);

                    if(!$scope.sparklines.hasOwnProperty(queueName)){
                        $scope.sparklines[queueName] = {
                            values: []
                        };
                    }

                    if($scope.sparklines[queueName].values.length > 50){
                        $scope.sparklines[queueName].values.shift(1, 1);
                    }
                    $scope.sparklines[queueName].values.push(jobs);
                }
                $scope.updateSparklines();

                if(reloadInterval === null){
                    $scope.startReloadInterval();
                }

                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.sendTestMail = function(){
            $http.post("/Administrators/testMail.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                if(result.data.success){
                    NotyService.genericSuccess({
                        message: result.data.message
                    });
                    return;
                }

                NotyService.genericError({
                    message: result.data.message
                });

            }, function errorCallback(result){
                NotyService.genericError({
                    message: "Unknown error"
                });
            });
        };

        $scope.getGearmanStatusClass = function(jobs, worker){
            jobs = parseInt(jobs, 10);
            worker = parseInt(worker, 10);

            var cssClass = 'txt-color-green';

            if(jobs > 5){
                cssClass = 'text-primary';
            }

            if(jobs > 50){
                cssClass = 'txt-color-orangeDark';
            }

            if(jobs > 500){
                cssClass = 'txt-color-white bg-critical';
            }

            if(worker === 0){
                cssClass = 'txt-color-white bg-warning';
            }
            return cssClass;
        };

        $scope.updateSparklines = function(){
            for(var key in $scope.sparklines){
                var sparkline = $('#' + key + '_sparkline').sparkline($scope.sparklines[key].values, {type: 'line'});
            }
        };

        $scope.startReloadInterval = function(){
            $scope.stop();
            reloadInterval = $interval(function(){
                $scope.load();
            }, 10000);
        };

        $scope.stop = function(){
            if(reloadInterval){
                $interval.cancel(reloadInterval);
            }
            reloadInterval = null;
        };

        //Disable status update interval, if the object gets removed from DOM.
        $scope.$on('$destroy', function(){
            $scope.stop();
        });

        //On page load
        $scope.loadTimezone();
        $scope.load();

    });
