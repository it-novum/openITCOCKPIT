angular.module('openITCOCKPIT').directive('tachoItem', function($http){
    return {
        restrict: 'E',
        templateUrl: '/map_module/mapeditors_new/tacho.html',
        scope: {
            'item': '='
        },
        controller: function($scope){

            $scope.showLabel = parseInt($scope.item.show_label, 10) === 1;

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
                $http.get("/map_module/mapeditors_new/mapitem/.json", {
                    params: {
                        'angular': true,
                        'objectId': $scope.item.object_id,
                        'type': $scope.item.type
                    }
                }).then(function(result){
                    $scope.color = result.data.color;
                    var perfdata = result.data.perfdata;

                    if(perfdata !== null){
                        if(Object.keys(perfdata).length > 0){
                            var perfdataName = Object.keys(perfdata)[0];
                            perfdata = perfdata[perfdataName];

                            perfdata.current = parseFloat(perfdata.current);
                            perfdata.warning = parseFloat(perfdata.warning);
                            perfdata.critical = parseFloat(perfdata.critical);
                            perfdata.min = parseFloat(perfdata.min);
                            perfdata.max = parseFloat(perfdata.max);

                            renderGauge(perfdataName, perfdata);
                        }
                    }

                    $scope.init = false;
                });
            };

            var renderGauge = function(perfdataName, perfdata){
                if(perfdataName.length > 20){
                    perfdataName = perfdataName.substr(0, 20);
                    perfdataName += '...';
                }

                if(isNaN(perfdata.warning) || isNaN(perfdata.critical)){
                    perfdata.warning = null;
                    perfdata.critical = null;
                }

                if(isNaN(perfdata.max) && isNaN(perfdata.critical) === false){
                    perfdata.max = perfdata.critical;
                }

                if(isNaN(perfdata.min) || isNaN(perfdata.max)){
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
                    units: perfdata.unit,
                    strokeTicks: true,
                    title: perfdataName,
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
                var tickSize = parseInt((perfdataMax / numberOfTicks), 10);

                var tickArr = [];
                for(var i = 0; i < numberOfTicks; i++){
                    tickArr.push((i * tickSize));
                }
                tickArr.push(perfdataMax);
                return tickArr;
            };

            $scope.load();
        },

        link: function(scope, element, attr){

        }
    };
});
