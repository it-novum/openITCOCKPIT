angular.module('openITCOCKPIT').directive('tachoItem', function($http, $interval){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors/tacho.html',
        scope: {
            'item': '=',
            'refreshInterval': '='
        },
        controller: function($scope){
            $scope.init = true;
            $scope.statusUpdateInterval = null;

            $scope.item.size_x = parseInt($scope.item.size_x, 10);
            $scope.item.size_y = parseInt($scope.item.size_y, 10);

            $scope.width = 200;
            $scope.height = $scope.width;

            if($scope.item.size_x > 0){
                $scope.width = $scope.item.size_x;
                $scope.height = $scope.width;
            }
            if($scope.item.size_y > 0){
                $scope.width = $scope.item.size_y;
                $scope.height = $scope.width;
            }

            $scope.load = function(){
                $http.get("/map_module/mapeditors/mapitem/.json", {
                    params: {
                        'angular': true,
                        'disableGlobalLoader': true,
                        'objectId': $scope.item.object_id,
                        'mapId': $scope.item.map_id,
                        'type': $scope.item.type
                    }
                }).then(function(result){
                    $scope.color = result.data.data.color;
                    $scope.Host = result.data.data.Host;
                    $scope.Service = result.data.data.Service;
                    $scope.responsePerfdata = result.data.data.Perfdata;

                    processPerfdata();
                    renderGauge($scope.perfdataName, $scope.perfdata);

                    initRefreshTimer();
                    $scope.init = false;
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

            var renderGauge = function(perfdataName, perfdata){
                var units = perfdata.unit;
                var label = perfdataName;

                if(label.length > 20){
                    label = label.substr(0, 20);
                    label += '...';
                }

                if($scope.item.show_label === true){
                    if(units === null){
                        units = label;
                    }else{
                        units = label + ' in ' + units;
                    }
                    label = $scope.Host.hostname + '/' + $scope.Service.servicename;
                    if(label.length > 20){
                        label = label.substr(0, 20);
                        label += '...';
                    }
                }


                if(isNaN(perfdata.warning) || isNaN(perfdata.critical)){
                    perfdata.warning = null;
                    perfdata.critical = null;
                }

                if(isNaN(perfdata.max) && isNaN(perfdata.critical) === false){
                    perfdata.max = perfdata.critical;
                }

                if(isNaN(perfdata.min) || isNaN(perfdata.max) || perfdata.min === null || perfdata.max === null){
                    perfdata.min = 0;
                    perfdata.max = 100;
                }

                var thresholds = [];

                if(perfdata.warning !== null && perfdata.critical !== null){
                    thresholds = [
                        {from: perfdata.min, to: perfdata.warning, color: '#449D44'},
                        {from: perfdata.warning, to: perfdata.critical, color: '#DF8F1D'},
                        {from: perfdata.critical, to: perfdata.max, color: '#C9302C'}
                    ];

                    //HDD usage for example
                    if(perfdata.warning > perfdata.critical){
                        thresholds = [
                            {from: perfdata.min, to: perfdata.critical, color: '#C9302C'},
                            {from: perfdata.critical, to: perfdata.warning, color: '#DF8F1D'},
                            {from: perfdata.warning, to: perfdata.max, color: '#449D44'}
                        ];
                    }
                }

                var maxDecimalDigits = 3;
                var currentValueAsString = perfdata.current.toString();
                var intergetDigits = currentValueAsString.length;
                var decimalDigits = 0;

                if(currentValueAsString.indexOf('.') > 0){
                    var splited = currentValueAsString.split('.');
                    intergetDigits = splited[0].length;
                    decimalDigits = splited[1].length;
                    if(decimalDigits > maxDecimalDigits){
                        decimalDigits = maxDecimalDigits;
                    }
                }

                var showDecimalDigitsGauge = 0;
                if(decimalDigits > 0 || (perfdata.max - perfdata.min < 10)){
                    showDecimalDigitsGauge = 1;
                }

                var gauge = new RadialGauge({
                    renderTo: 'map-tacho-' + $scope.item.id,
                    height: $scope.height,
                    width: $scope.width,
                    value: perfdata.current,
                    minValue: perfdata.min,
                    maxValue: perfdata.max,
                    units: units,
                    strokeTicks: true,
                    title: label,
                    valueInt: intergetDigits,
                    valueDec: decimalDigits,
                    majorTicksDec: showDecimalDigitsGauge,
                    highlights: thresholds,
                    animationDuration: 700,
                    animationRule: 'elastic',
                    majorTicks: getMajorTicks(perfdata.max, 5)
                });

                gauge.draw();

                //Update value
                //gauge.value = 1337;
            };

            var getMajorTicks = function(perfdataMax, numberOfTicks){
                var tickSize = Math.ceil((perfdataMax / numberOfTicks));
                if(perfdataMax < numberOfTicks){
                    numberOfTicks = perfdataMax;
                }

                var tickArr = [];
                for(var i = 0; i < numberOfTicks; i++){
                    tickArr.push((i * tickSize));
                }
                tickArr.push(perfdataMax);
                return tickArr;
            };

            var processPerfdata = function(){
                //Dummy data if there are no performance data records available
                $scope.perfdata = {
                    current: 0,
                    warning: 80,
                    critical: 90,
                    min: 0,
                    max: 100,
                    unit: 'n/a'
                };
                $scope.perfdataName = 'No data available';


                if($scope.responsePerfdata !== null){
                    if($scope.item.metric !== null && $scope.responsePerfdata.hasOwnProperty($scope.item.metric)){
                        $scope.perfdataName = $scope.item.metric;
                        $scope.perfdata = $scope.responsePerfdata[$scope.item.metric];
                    }else{
                        //Use first metric.
                        for(var metricName in $scope.responsePerfdata){
                            $scope.perfdataName = metricName;
                            $scope.perfdata = $scope.responsePerfdata[metricName];
                            break;
                        }
                    }
                }

                $scope.perfdata.current = parseFloat($scope.perfdata.current);
                $scope.perfdata.warning = parseFloat($scope.perfdata.warning);
                $scope.perfdata.critical = parseFloat($scope.perfdata.critical);
                $scope.perfdata.min = parseFloat($scope.perfdata.min);
                $scope.perfdata.max = parseFloat($scope.perfdata.max);
            };

            var initRefreshTimer = function(){
                if($scope.refreshInterval > 0 && $scope.statusUpdateInterval === null){
                    $scope.statusUpdateInterval = $interval(function(){
                        $scope.load();
                    }, $scope.refreshInterval);
                }
            };

            $scope.load();

            $scope.$watchGroup(['item.size_x', 'item.show_label'], function(){
                if($scope.init){
                    return;
                }

                $scope.width = $scope.item.size_x;
                $scope.height = $scope.width;

                renderGauge($scope.perfdataName, $scope.perfdata);
            });

            $scope.$watch('item.metric', function(){
                if($scope.init){
                    return;
                }

                processPerfdata();
                renderGauge($scope.perfdataName, $scope.perfdata);
            });

            $scope.$watch('item.object_id', function(){
                if($scope.init || $scope.item.object_id === null){
                    //Avoid ajax error if user search a service in Gadget config modal
                    return;
                }

                $scope.load();
            });
        },

        link: function(scope, element, attr){

        }
    };
});
