angular.module('openITCOCKPIT').directive('popoverGraphDirective', function($http, UuidService){
    return {
        restrict: 'E',
        templateUrl: '/angular/popover_graph.html', // This template is also used by PopoverPrometheusGraphDirective

        controller: function($scope){
            var startTimestamp = new Date().getTime();

            $scope.popoverPerfdata = {};

            $scope.popoverTimer = null;

            $scope.graphPopoverId = UuidService.v4();

            $scope.doLoadGraph = function(hostUuid, serviceUuid){
                var serverTime = new Date($scope.timezone.server_time_iso);
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
                        jsTimestamp: 0
                    }
                }).then(function(result){
                    $scope.isLoadingGraph = false;
                    $scope.popoverPerfdata = result.data.performance_data;

                    // Give the browser a few ms to render the template
                    setTimeout(renderGraphs, 100);
                });
            };

            var renderGraphs = function(){
                var uPlotGraphDefaultsObj = new uPlotGraphDefaults();

                for(var index in $scope.popoverPerfdata){
                    if(index > 3){
                        //Only render 4 gauges in popover...
                        continue;
                    }

                    // https://github.com/leeoniya/uPlot/tree/master/docs#data-format
                    var data = [];
                    var xData = []
                    var yData = []
                    for(var timestamp in $scope.popoverPerfdata[index].data){
                        xData.push(timestamp); // Timestamps
                        yData.push($scope.popoverPerfdata[index].data[timestamp]); // values
                    }
                    data.push(xData);
                    data.push(yData);


                    //Render Chart
                    var $elm = $('#serviceGraphFlot-' + $scope.graphPopoverId + '-' + index);

                    var colors = uPlotGraphDefaultsObj.getColorByIndex(index);
                    var options = uPlotGraphDefaultsObj.getDefaultOptions({
                        unit: $scope.popoverPerfdata[index].datasource.unit,
                        showLegend: false,
                        timezone: $scope.timezone.user_timezone,
                        lineWidth: 2,
                        thresholds: {
                            show: true,
                            warning: $scope.popoverPerfdata[index].datasource.warn,
                            critical: $scope.popoverPerfdata[index].datasource.crit
                        },
                        // X-Axis min / max
                        start: graphStart,
                        end: graphEnd,
                        //Fallback if no thresholds exists
                        strokeColor: colors.stroke,
                        fillColor: colors.fill
                    });
                    options.height = $elm.height() - 25; // 27px for headline
                    options.width = $elm.width();
                    options.title = $scope.popoverPerfdata[index].datasource.name;

                    if(document.getElementById('serviceGraphFlot-' + $scope.graphPopoverId + '-' + index) && !$scope.mouseout){
                        try{
                            var elm = document.getElementById('serviceGraphFlot-' + $scope.graphPopoverId + '-' + index);
                            self.plot = new uPlot(options, data, elm);
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
                        var $popupGraphContainer = $('#serviceGraphContainer-' + $scope.graphPopoverId);

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
                return; // todo remove this
                $scope.mouseout = true;

                if($scope.popoverTimer !== null){
                    clearTimeout($scope.popoverTimer);
                    $scope.popoverTimer = null;
                }

                $('#serviceGraphContainer-' + $scope.graphPopoverId).hide();
            };

        }
    };
});
