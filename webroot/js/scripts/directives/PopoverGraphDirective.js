angular.module('openITCOCKPIT').directive('popoverGraphDirective', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/popover_graph.html',

        controller: function($scope){
            var startTimestamp = new Date().getTime();

            $scope.popoverPerfdata = {};

            $scope.popoverTimer = null;

            $scope.doLoadGraph = function(hostUuid, serviceUuid){
                var serverTime = new Date($scope.timezone.server_time);
                var compareTimestamp = new Date().getTime();
                var diffFromStartToNow = parseInt(compareTimestamp - startTimestamp, 10);

                graphEnd = Math.floor((serverTime.getTime() + diffFromStartToNow) / 1000);
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
                    $scope.popoverPerfdata = result.data.performance_data;

                    // Give the browser a few ms to render the template
                    setTimeout(renderGraphs, 100);
                });
            };

            var renderGraphs = function(){
                var GraphDefaultsObj = new GraphDefaults();

                for(var index in $scope.popoverPerfdata){
                    if(index > 3){
                        //Only render 4 gauges in popover...
                        continue;
                    }

                    graph_data = [];
                    var color = GraphDefaultsObj.getColorByIndex(index);
                    for(var timestamp in $scope.popoverPerfdata[index].data){
                        var frontEndTimestamp = (parseInt(timestamp, 10) + ($scope.timezone.user_time_to_server_offset * 1000));
                        graph_data.push([frontEndTimestamp, $scope.popoverPerfdata[index].data[timestamp]]);
                    }

                    //Render Chart
                    var options = GraphDefaultsObj.getDefaultOptions();
                    options.height = '500px';
                    options.colors = color.border;

                    options.xaxis.tickFormatter = function(val, axis){
                        var fooJS = new Date(val);
                        var fixTime = function(value){
                            if(value < 10){
                                return '0' + value;
                            }
                            return value;
                        };
                        return fixTime(fooJS.getHours()) + ':' + fixTime(fooJS.getMinutes());
                    };
                    options.xaxis.mode = 'time';
                    options.xaxis.timeformat = '%H:%M:%S';
                    options.xaxis.timeBase = 'milliseconds';
                    options.xaxis.min = (graphStart + $scope.timezone.user_time_to_server_offset) * 1000;
                    options.xaxis.max = (graphEnd + $scope.timezone.user_time_to_server_offset) * 1000;

                    if($scope.popoverPerfdata[index].datasource.unit){
                        options.yaxis = {
                            axisLabel: $scope.popoverPerfdata[index].datasource.unit
                        };
                    }

                    if(document.getElementById('serviceGraphFlot-' + index) && !$scope.mouseout){
                        try{
                            self.plot = $.plot('#serviceGraphFlot-' + index, [graph_data], options);
                        }catch(e){
                            console.error(e);
                        }
                    }
                }
            };

        },

        link: function($scope, element, attr){

            $scope.mouseenter = function($event, hostUuid, serviceUuid){
                if($scope.popoverTimer === null){
                    $scope.popoverTimer = setTimeout(function(){
                        $scope.mouseout = false;
                        $scope.isLoadingGraph = true;
                        var offset = {
                            top: $event.relatedTarget.offsetTop + 40,
                            left: $event.relatedTarget.offsetLeft + 40
                        };

                        if($event.relatedTarget.offsetParent && $event.relatedTarget.offsetParent.offsetTop){
                            offset.top += $event.relatedTarget.offsetParent.offsetTop;
                        }

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
                        $scope.doLoadGraph(hostUuid, serviceUuid);
                        $scope.popoverTimer = null;
                    }, 150);
                }


            };

            $scope.mouseleave = function(){
                $scope.mouseout = true;

                if($scope.popoverTimer !== null){
                    clearTimeout($scope.popoverTimer);
                    $scope.popoverTimer = null;
                }

                $('#serviceGraphContainer').hide();
                $('#serviceGraphFlot').html('');
            };

        }
    };
});
