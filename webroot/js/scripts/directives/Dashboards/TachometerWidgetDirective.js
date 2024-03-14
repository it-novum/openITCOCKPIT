angular.module('openITCOCKPIT').directive('tachometerWidget', function($http){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/tachoWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){
            // default data if no setup is passed whatsoever.
            $scope.defaultSetup = {
                scale: {
                    min: 0,
                    max: 100,
                    type: "O",
                },
                metric: {
                    value: 0,
                    unit: 'X',
                    name: 'No data available',
                },
                warn: {
                    low: null,
                    high: null,
                },
                crit: {
                    low: null,
                    high: null,
                }
            };


            /** public vars **/
            $scope.init = true;
            $scope.tacho = {
                service_id: null,
                show_label: false
            };

            // ITC-3037
            $scope.readOnly    = $scope.widget.isReadonly;

            /** private vars **/
            var $widget = $('#widget-' + $scope.widget.id);
            var $widgetContent = $('#widget-content-' + $scope.widget.id);

            //Calc dimensions
            var offset = 50;
            $scope.height = $widgetContent.height() - offset;
            $scope.width = $scope.height;

            $scope.load = function(){
                $http.get("/dashboards/tachoWidget.json", {
                    params: {
                        'angular': true,
                        'widgetId': $scope.widget.id
                    }
                }).then(function(result){
                    if(Object.keys(result.data.service.Service).length > 0){
                        $scope.Service = result.data.service.Service;
                        $scope.Host = result.data.service.Host;
                        $scope.ACL = result.data.ACL;


                        $scope.responsePerfdata = result.data.service.Perfdata;

                        $scope.tacho.show_label = result.data.config.show_label;
                        //User has permissions for this service / a services was selected
                        $scope.tacho.service_id = result.data.service.Service.id;
                        $scope.tacho.metric = result.data.config.metric;

                        $scope.tachoHref = getHref();
                        processPerfdata();
                        renderGauge();
                    }else{
                        //Avoid undefined errors
                        $scope.Service = {
                            hostname: 'Unknown host',
                            servicename: 'Unknown service'
                        }
                    }

                    //Do not trigger watch on page load
                    setTimeout(function(){
                        $scope.init = false;
                    }, 250);
                });
            };

            $scope.loadServices = function(searchString){
                $http.get("/services/loadServicesByString.json", {
                    params: {
                        'angular': true,
                        //'filter[Hosts.name]': searchString,
                        'filter[servicename]': searchString,
                        'selected[]': $scope.tacho.service_id
                    }
                }).then(function(result){
                    $scope.services = result.data.services;
                    if($scope.tacho.service_id){
                        loadMetrics();
                    }
                });
            };

            $scope.hideConfig = function(){
                $scope.$broadcast('FLIP_EVENT_IN');
                renderGauge();
            };
            $scope.showConfig = function(){
                $scope.$broadcast('FLIP_EVENT_OUT');
                $scope.loadServices('');
            };

            $scope.saveTacho = function(){
                $http.post("/dashboards/tachoWidget.json?angular=true",
                    {
                        Widget: {
                            id: $scope.widget.id,
                            service_id: $scope.tacho.service_id
                        },
                        show_label: $scope.tacho.show_label,
                        metric: $scope.tacho.metric
                    }
                ).then(function(result){
                    //Update status
                    $scope.load();
                    $scope.hideConfig();
                });
            };

            var getHref = function(){
                var url = 'javascript:void(0);';

                if($scope.Service.isEVCService){
                    if($scope.ACL.evc.view){
                        return '/eventcorrelation_module/eventcorrelations/view/' + $scope.Host.id
                    }

                    if($scope.ACL.services.index){
                        return '/#!/services/browser/' + $scope.Service.id
                    }
                }else{
                    if($scope.ACL.services.index){
                        return '/#!/services/browser/' + $scope.Service.id
                    }
                }


                return url;
            };

            $scope.getThresholdAreas = function(setup){
                var thresholdAreas = [];
                switch(setup.scale.type){
                    case "W<O":
                        thresholdAreas = [
                            {from: setup.crit.low, to: setup.warn.low, color: '#DF8F1D'},
                            {from: setup.warn.low, to: setup.scale.max, color: '#449D44'}
                        ];
                        break;
                    case "C<W<O":
                        thresholdAreas = [
                            {from: setup.scale.min, to: setup.crit.low, color: '#C9302C'},
                            {from: setup.crit.low, to: setup.warn.low, color: '#DF8F1D'},
                            {from: setup.warn.low, to: setup.scale.max, color: '#449D44'}
                        ];
                        break;
                    case "O<W":
                        thresholdAreas = [
                            {from: setup.scale.min, to: setup.warn.low, color: '#449D44'},
                            {from: setup.warn.low, to: setup.scale.max, color: '#DF8F1D'}
                        ];
                        break;
                    case "O<W<C":
                        thresholdAreas = [
                            {from: setup.scale.min, to: setup.warn.low, color: '#449D44'},
                            {from: setup.warn.low, to: setup.crit.low, color: '#DF8F1D'},
                            {from: setup.crit.low, to: setup.scale.max, color: '#C9302C'}
                        ];
                        break;
                    case "C<W<O<W<C":
                        thresholdAreas = [
                            {from: setup.scale.min, to: setup.crit.low, color: '#C9302C'},
                            {from: setup.crit.low, to: setup.warn.low, color: '#DF8F1D'},
                            {from: setup.warn.low, to: setup.warn.high, color: '#449D44'},
                            {from: setup.warn.high, to: setup.crit.high, color: '#DF8F1D'},
                            {from: setup.crit.high, to: setup.scale.max, color: '#C9302C'}
                        ];
                        break;
                    case "O<W<C<W<O":
                        thresholdAreas = [
                            {from: setup.scale.min, to: setup.crit.low, color: '#449D44'},
                            {from: setup.crit.low, to: setup.warn.low, color: '#DF8F1D'},
                            {from: setup.warn.low, to: setup.warn.high, color: '#C9302C'},
                            {from: setup.warn.high, to: setup.crit.high, color: '#DF8F1D'},
                            {from: setup.crit.high, to: setup.scale.max, color: '#449D44'}
                        ];
                        break;
                    case "O":
                    default:
                        break;
                }
                return thresholdAreas;
            }

            var renderGauge = function(){
                let setup = $scope.setup,
                    units = setup.metric.unit,
                    label = setup.metric.name;

                if(label.length > 20){
                    label = label.substr(0, 20);
                    label += '...';
                }

                if($scope.tacho.show_label === true){
                    if(units === null){
                        units = label;
                    }else{
                        units = label + ' in ' + units;
                    }
                    label = $scope.Service.hostname + '/' + $scope.Service.servicename;
                    if(label.length > 20){
                        label = label.substr(0, 20);
                        label += '...';
                    }
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

                var gauge = new RadialGauge({
                    renderTo: 'tacho-' + $scope.widget.id,
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
                    majorTicks: getMajorTicks(setup.scale.min, setup.scale.max, 5)
                });

                gauge.draw();

                //Update value
                //gauge.value = 1337;
            };

            var getMajorTicks = function(perfdataMin, perfdataMax, numberOfTicks){
                numberOfTicks = Math.abs(Math.ceil(numberOfTicks));
                let tickSize = Math.ceil((perfdataMax - perfdataMin) / numberOfTicks),
                    tickArr = [],
                    myTick = perfdataMin;

                for(let index = 0; index <= numberOfTicks; index++){
                    tickArr.push(myTick);

                    myTick += tickSize;
                }

                return tickArr;
            };

            var processPerfdata = function(){
                $scope.setup = $scope.defaultSetup;

                if($scope.responsePerfdata === null){
                    return;
                }
                if($scope.tacho.metric !== null && $scope.responsePerfdata.hasOwnProperty($scope.tacho.metric)){
                    $scope.setup = $scope.responsePerfdata[$scope.tacho.metric].datasource.setup || $scope.defaultSetup;
                }else{
                    //Use first metric.
                    for(let metricName in $scope.responsePerfdata){
                        $scope.setup = $scope.responsePerfdata[metricName].datasource.setup || $scope.defaultSetup;
                        break;
                    }
                }
            };

            var hasResize = function(){
                if($scope.init){
                    return;
                }

                $scope.height = $widgetContent.height() - offset;
                $scope.width = $scope.height;
                renderGauge();
            };

            var loadMetrics = function(){
                $http.get("/dashboards/getPerformanceDataMetrics/" + $scope.tacho.service_id + ".json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    var metrics = {};

                    var firstMetric = null;

                    for(var metricName in result.data.perfdata){
                        if(firstMetric === null){
                            firstMetric = metricName;
                        }

                        metrics[metricName] = metricName;
                    }

                    if($scope.tacho.metric === null){
                        $scope.tacho.metric = firstMetric;
                    }

                    $scope.metrics = metrics;
                });
            };


            /** Page load / widget get loaded **/

            //Add jQuery resize callback
            $widget.on('resize', function(event, items){
                hasResize();
            });

            $scope.load();

            $scope.$watch('tacho.service_id', function(){
                if($scope.init){
                    return;
                }

                loadMetrics();

            }, true);

        },

        link: function($scope, element, attr){

        }
    };
});
