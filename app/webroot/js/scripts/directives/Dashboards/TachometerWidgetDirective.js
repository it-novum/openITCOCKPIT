angular.module('openITCOCKPIT').directive('tachometerWidget', function($http){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/tachoWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){

            /** public vars **/
            $scope.init = true;
            $scope.tacho = {
                service_id: null,
                show_label: false
            };

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
                        renderGauge($scope.perfdataName, $scope.perfdata);
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
                        'filter[Host.name]': searchString,
                        'filter[Service.servicename]': searchString,
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
                renderGauge($scope.perfdataName, $scope.perfdata);
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
                        return '/ng/#!/services/browser/' + $scope.Service.id
                    }
                }else{
                    if($scope.ACL.services.index){
                        return '/ng/#!/services/browser/' + $scope.Service.id
                    }
                }


                return url;
            };

            var renderGauge = function(perfdataName, perfdata){
                if(typeof perfdata === 'undefined'){
                    return;
                }

                var units = perfdata.unit;
                var label = perfdataName;

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
                    renderTo: 'tacho-' + $scope.widget.id,
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
                    if($scope.tacho.metric !== null && $scope.responsePerfdata.hasOwnProperty($scope.tacho.metric)){
                        $scope.perfdataName = $scope.tacho.metric;
                        $scope.perfdata = $scope.responsePerfdata[$scope.tacho.metric];
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


            var hasResize = function(){
                if($scope.init){
                    return;
                }

                $scope.height = $widgetContent.height() - offset;
                $scope.width = $scope.height;
                renderGauge($scope.perfdataName, $scope.perfdata);
            };

            var loadMetrics = function(){
                $http.get("/map_module/mapeditors/getPerformanceDataMetrics/" + $scope.tacho.service_id + ".json", {
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
