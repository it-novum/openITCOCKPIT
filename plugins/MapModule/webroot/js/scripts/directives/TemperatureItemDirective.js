angular.module('openITCOCKPIT').directive('temperatureItem', function($http, $interval){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors/temperature.html',
        scope: {
            'item': '=',
            'refreshInterval': '='
        },
        controller: function($scope){
            $scope.init = true;
            $scope.statusUpdateInterval = null;

            $scope.item.size_x = parseInt($scope.item.size_x, 10);
            $scope.item.size_y = parseInt($scope.item.size_y, 10);

            $scope.width = 120;
            $scope.height = 400;

            if($scope.item.size_x > 0){
                $scope.width = $scope.item.size_x;
            }
            if($scope.item.size_y > 0){
                $scope.height = $scope.item.size_y;
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
                    renderGauge($scope.setup);

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


            $scope.getThresholdAreas = function(setup){
                var thresholdAreas = [];
                switch(setup.scale.type){
                    case "W<O":
                        thresholdAreas = [
                            {from: setup.crit.low,    to: setup.warn.low,  color: '#DF8F1D'},
                            {from: setup.warn.low,    to: setup.scale.max, color: '#449D44'}
                        ];
                        break;
                    case "C<W<O":
                        thresholdAreas = [
                            {from: setup.scale.min,   to: setup.crit.low,  color: '#C9302C'},
                            {from: setup.crit.low,    to: setup.warn.low,  color: '#DF8F1D'},
                            {from: setup.warn.low,    to: setup.scale.max, color: '#449D44'}
                        ];
                        break;
                    case "O<W":
                        thresholdAreas = [
                            {from: setup.scale.min, to: setup.warn.low,  color: '#449D44'},
                            {from: setup.warn.low,  to: setup.scale.max, color: '#DF8F1D'}
                        ];
                        break;
                    case "O<W<C":
                        thresholdAreas = [
                            {from: setup.scale.min,   to: setup.warn.low,  color: '#449D44'},
                            {from: setup.warn.low,    to: setup.crit.low,  color: '#DF8F1D'},
                            {from: setup.crit.low,    to: setup.scale.max, color: '#C9302C'}
                        ];
                        break;
                    case "C<W<O<W<C":
                        thresholdAreas = [
                            {from: setup.scale.min,   to: setup.crit.low,  color: '#C9302C'},
                            {from: setup.crit.low,    to: setup.warn.low,  color: '#DF8F1D'},
                            {from: setup.warn.low,    to: setup.warn.high, color: '#449D44'},
                            {from: setup.warn.high,   to: setup.crit.high, color: '#DF8F1D'},
                            {from: setup.crit.high,   to: setup.scale.max, color: '#C9302C'}
                        ];
                        break;
                    case "O<W<C<W<O":
                        console.warn(setup);
                        thresholdAreas = [
                            {from: setup.scale.min,   to: setup.crit.low,  color: '#449D44'},
                            {from: setup.crit.low,    to: setup.warn.low,  color: '#DF8F1D'},
                            {from: setup.warn.low,    to: setup.warn.high, color: '#C9302C'},
                            {from: setup.warn.high,   to: setup.crit.high, color: '#DF8F1D'},
                            {from: setup.crit.high,   to: setup.scale.max, color: '#449D44'}
                        ];
                        break;
                    case "O":
                    default:
                        break;
                }
                return thresholdAreas;
            }

            var renderGauge = function(setup){
                var label = setup.metric.name,
                    units = '';


                if($scope.item.show_label === true){
                    if(typeof(setup.metric.unit) !== "string" || setup.metric.unit.length === 0){
                        units = label;
                    }else{
                        units = label + ' in ' + setup.metric.unit;
                    }
                    label = $scope.Host.hostname + '/' + $scope.Service.servicename;

                    // ITC-3153: Strip hostname of too long
                    if(label.length > 20){
                        label = $scope.Service.servicename;
                    }
                }

                // shorten label if required.
                if(label.length > 20){
                    label = label.substr(0, 20);
                    label += '...';
                }

                if(isNaN(setup.scale.min) || isNaN(setup.scale.max) || setup.scale.min === null || setup.scale.max === null){
                    setup.scale.min = 0;
                    setup.scale.max = 100;
                }

                var thresholds = $scope.getThresholdAreas(setup);
                var maxDecimalDigits = 3;
                var currentValueAsString = setup.metric.value.toString();
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
                if(decimalDigits > 0 || (setup.scale.max - setup.scale.min < 10)){
                    showDecimalDigitsGauge = 1;
                }

                let settings ={
                    renderTo: 'map-temperature-' + $scope.item.id,
                    height: $scope.height,
                    width: $scope.width,
                    value: setup.metric.value,
                    minValue: setup.scale.min || 0,
                    maxValue: setup.scale.max || 100,
                    units: units,
                    strokeTicks: true,
                    title: label,
                    valueInt: intergetDigits,
                    valueDec: decimalDigits,
                    majorTicksDec: showDecimalDigitsGauge,
                    highlights: thresholds,
                    animationDuration: 700,
                    animationRule: 'elastic',
                    majorTicks: getMajorTicks(setup, 5)
                };


                var gauge = new LinearGauge(settings);

                gauge.draw();

                //Update value
                //gauge.value = 1337;
            };

            var getMajorTicks = function(setup, numberOfTicks){
                numberOfTicks = Math.abs(Math.ceil(numberOfTicks));
                let tickSize = Math.ceil((setup.scale.max - setup.scale.min) / numberOfTicks),
                    tickArr = [],
                    myTick = setup.scale.min;

                for(let index = 0; index <= numberOfTicks; index++){
                    tickArr.push(myTick);

                    myTick += tickSize;
                }

                return tickArr;
            };

            var processPerfdata = function(){
                $scope.setup = {};


                if($scope.responsePerfdata !== null){
                    if($scope.item.metric !== null && $scope.responsePerfdata.hasOwnProperty($scope.item.metric)){
                        $scope.setup    = $scope.responsePerfdata[$scope.item.metric].datasource.setup;
                    }else{
                        //Use first metric.
                        for(var metricName in $scope.responsePerfdata){
                            $scope.setup    = $scope.responsePerfdata[metricName].datasource.setup;
                            break;
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

            $scope.load();

            $scope.$watchGroup(['item.size_x', 'item.show_label'], function(){
                if($scope.init){
                    return;
                }

                $scope.width = $scope.item.size_x;
                $scope.height = $scope.item.size_y;

                renderGauge($scope.setup);
            });

            $scope.$watch('item.metric', function(){
                if($scope.init){
                    return;
                }

                processPerfdata();
                renderGauge($scope.setup);
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
