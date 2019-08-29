angular.module('openITCOCKPIT')
    .controller('DowntimereportsIndexController', function($rootScope, $scope, $http, $timeout, NotyService, QueryStringService, $httpParamSerializer){
        $scope.init = true;
        $scope.errors = null;
        $scope.hasEntries = null;
        $scope.setColorDynamically = false;
        var now = new Date();

        $scope.tabName='reportConfig';

        $scope.post = {
            evaluation_type: 0,
            report_format: 2,
            reflection_state: 1,
            timeperiod_id: null,
            from_date: date('d.m.Y', now.getTime() / 1000 - (3600 * 24 * 30)),
            to_date: date('d.m.Y', now.getTime() / 1000)
        };

        $scope.colors = {
            green: '#449D44',
            orange: '#eaba0b',
            red: '#C9302C'
        };

        $scope.timeperiods = {};
        $scope.reportData = {
            hostsWithOutages: null,
            hostsWithoutOutages: null,
            downtimes: null
        };

        $scope.loadTimeperiods = function(searchString){
            $http.get("/timeperiods/index.json", {
                params: {
                    'angular': true,
                    'filter[Timeperiod.name]': searchString
                }
            }).then(function(result){
                $scope.timeperiods = result.data.all_timeperiods;
            });
        };

        $scope.createDowntimeReport = function(){
            if($scope.post.report_format === '1'){
                //PDF Report
                var GETParams = {
                    'angular': true,
                    'data[from_date]': $scope.post.from_date,
                    'data[to_date]': $scope.post.to_date,
                    'data[evaluation_type]': $scope.post.evaluation_type,
                    'data[reflection_state]': $scope.post.reflection_state,
                    'data[timeperiod_id]': $scope.post.timeperiod_id
                };

                $http.get("/downtimereports/createPdfReport.json", {
                        params: GETParams
                    }
                ).then(function(result){
                    window.location = '/downtimereports/createPdfReport.pdf?' + $httpParamSerializer(GETParams);
                }, function errorCallback(result){
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });

            }else{
                //HTML Report
                $http.post("/downtimereports/index.json", $scope.post
                ).then(function(result){
                    NotyService.genericSuccess({
                        message: $scope.reportMessage.successMessage
                    });
                    $scope.errors = null;
                    $scope.reportData.downtimes = result.data.downtimeReport.downtimes;
                    $scope.reportData.hostsWithOutages = result.data.downtimeReport.hostsWithOutages;
                    $scope.reportData.hostsWithoutOutages = result.data.downtimeReport.hostsWithoutOutages;
                    $scope.tabName='calendarOverview';

                }, function errorCallback(result){
                    NotyService.genericError({
                        message: $scope.reportMessage.errorMessage
                    });
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });
            }
        };

        var getBackgroundColor = function getColor(currentAvailabilityInPercent){
            var currentAvailabilityInPercentFloat = (currentAvailabilityInPercent / 100).toFixed(3);
            var weight1 = (1 - currentAvailabilityInPercentFloat).toFixed(3);
            var weight2 = currentAvailabilityInPercentFloat;

            if(currentAvailabilityInPercent >= 50){
                var colorFrom = hexToRgb($scope.colors.orange);
                var colorTo = hexToRgb($scope.colors.green);

            }else{
                var colorFrom = hexToRgb($scope.colors.red);
                var colorTo = hexToRgb($scope.colors.orange);
            }


            var colors = [Math.round(colorFrom[0] * weight1 + colorTo[0] * weight2),
                Math.round(colorFrom[1] * weight1 + colorTo[1] * weight2),
                Math.round(colorFrom[2] * weight1 + colorTo[2] * weight2)]

            return '#' + rgbToHex(colors[0], colors[1], colors[2]);

        };

        //from #ff0000 to array [255, 0, 0]
        var hexToRgb = function(hex){
            var red = parseInt(hex.substr(1, 2), 16);
            var green = parseInt(hex.substr(3, 2), 16);
            var blue = parseInt(hex.substr(5, 2), 16);
            return [red, green, blue];
        };

        var rgbToHex = function(red, green, blue){
            red = Number(red).toString(16);
            if(red.length < 2){
                red = "0" + red;
            }
            green = Number(green).toString(16);
            if(green.length < 2){
                green = "0" + green;
            }
            blue = Number(blue).toString(16);
            if(blue.length < 2){
                blue = "0" + blue;
            }
            return red + green + blue;
        };


        $scope.$watch('setColorDynamically', function(){
            if($scope.init){
                return;
            }
            $scope.$apply();
        });

        $scope.loadTimeperiods();
    });
Chart.pluginService.register({
    beforeDraw: function(chart){
        if(chart.config.options.elements.center){
            //Get ctx from string
            var ctx = chart.chart.ctx;

            //Get options from the center object in options
            var centerConfig = chart.config.options.elements.center;
            var fontStyle = centerConfig.fontStyle || 'Arial';
            var txt = centerConfig.text;
            var color = centerConfig.color || '#000';
            var sidePadding = centerConfig.sidePadding || 20;
            var sidePaddingCalculated = (sidePadding / 100) * (chart.innerRadius * 2)
            //Start with a base font of 30px
            //ctx.font = "30px " + fontStyle;

            //Get the width of the string and also the width of the element minus 10 to give it 5px side padding
            var stringWidth = ctx.measureText(txt).width;
            var elementWidth = (chart.innerRadius * 2) - sidePaddingCalculated;

            // Find out how much the font can grow in width.
            var widthRatio = elementWidth / stringWidth;
            var newFontSize = Math.floor(30 * widthRatio);
            var elementHeight = (chart.innerRadius * 2);

            // Pick a new font size so it will not be larger than the height of label.
            var fontSizeToUse = Math.min(newFontSize, elementHeight);

            //Set font settings to draw it correctly.
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            var centerX = ((chart.chartArea.left + chart.chartArea.right) / 2);
            var centerY = ((chart.chartArea.top + chart.chartArea.bottom) / 2);
            ctx.font = "20px " + fontStyle;
            ctx.fillStyle = color;

            //Draw text in center
            ctx.fillText(txt, centerX, centerY);
        }
    }
});

