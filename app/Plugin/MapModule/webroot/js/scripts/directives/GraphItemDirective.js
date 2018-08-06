angular.module('openITCOCKPIT').directive('graphItem', function($http){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors_new/graph.html',
        scope: {
            'item': '='
        },
        controller: function($scope){
            $scope.selectedGraphdataSource = null;

            $scope.width = 400;
            $scope.height = 200;

            $scope.item.size_x = parseInt($scope.item.size_x, 10);
            $scope.item.size_y = parseInt($scope.item.size_y, 10);

            if($scope.item.size_x > 0){
                $scope.width = $scope.item.size_x;
            }
            if($scope.item.size_y > 0 && ($scope.item.size_y - 48) > 0){
                $scope.height = $scope.item.size_y;
            }

            //Original height of rrd graph in old style MapModule...
            $scope.height = $scope.height - 48;


            $scope.load = function(){
                $http.get("/map_module/mapeditors_new/graph/.json", {
                    params: {
                        'angular': true,
                        'serviceId': $scope.item.object_id,
                        'type': $scope.item.type
                    }
                }).then(function(result){
                    $scope.host = result.data.host;
                    $scope.service = result.data.service;
                    $scope.allowView = result.data.allowView;
                    $scope.init = false;

                    loadGraph($scope.host.uuid, $scope.service.uuid);
                });
            };

            $scope.loadTimezone = function(){
                $http.get("/angular/user_timezone.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.timezone = result.data.timezone;
                    $scope.load();
                });
            };

            var loadGraph = function(hostUuid, serviceuuid){
                $scope.isLoadingGraph = true;
                $http.get('/Graphgenerators/getPerfdataByUuid.json', {
                    params: {
                        angular: true,
                        host_uuid: hostUuid,
                        service_uuid: serviceuuid,
                        hours: 1,
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
                    transition: 'all 1s',
                    'z-index': 5040
                });

                $('#mapgraph-'+$scope.item.id).bind('plothover', function(event, pos, item){
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
                var graph_data = [];
                for(var dsCount in performance_data){
                    //graph_data[dsCount] = [];

                    var gaugeData = [];
                    for(var timestamp in performance_data[dsCount].data){
                        //graph_data[dsCount].push([timestamp, performance_data[dsCount].data[timestamp]]);
                        gaugeData.push([timestamp, performance_data[dsCount].data[timestamp]]);
                    }
                    graph_data.push({
                        label: performance_data[dsCount].datasource.label,
                        data: gaugeData,
                        unit: performance_data[dsCount].datasource.unit
                    });


                    //graph_data.push(performance_data[key].data);
                }
                var color_amount = performance_data.length < 3 ? 3 : performance_data.length;
                var color_generator = new ColorGenerator();
                var options = {
                    width: '100%',
                    height: $scope.height+'px',
                    colors: color_generator.generate(color_amount, 90, 120),
                    legend: {
                        show: true,
                        noColumns: 3,
                        container: $('#graph_legend-'+$scope.item.id) // container (as jQuery object) to put legend in, null means default on top of graph
                    },
                    grid: {
                        hoverable: true,
                        markings: [],
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
                                opacity: 0.5
                            },
                                {
                                    opacity: 0.3
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
                        }
                    },
                    selection: {
                        mode: "x"
                    }
                };

                self.plot = $.plot('#mapgraph-'+$scope.item.id, graph_data, options);
            };


            $scope.loadTimezone();
        },

        link: function(scope, element, attr){

        }
    };
});
