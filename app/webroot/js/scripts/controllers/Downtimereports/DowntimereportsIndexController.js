angular.module('openITCOCKPIT')
    .controller('DowntimereportsIndexController', function($rootScope, $scope, $http, $timeout, NotyService, QueryStringService, $httpParamSerializer){
        $scope.init = true;
        $scope.errors = null;
        $scope.hasEntries = null;
        $scope.setColorDynamically = false;
        var now = new Date();

        $scope.post = {
            evaluation_type: 0,
            report_format: 2,
            reflection_state: 1,
            timeperiod_id: null,
            from_date: date('d.m.Y', now.getTime() / 1000 - (3600 * 24 * 30)),
            to_date: date('d.m.Y', now.getTime() / 1000)
        };
        $scope.timeperiods = {};

        $('#infoButton').popover({
            boundary: 'window',
            trigger: 'hover',
            placement: 'left'
        });

        $scope.loadTimeperiods = function(searchString){
            $http.get("/timeperiods/index.json", {
                params: {
                    'angular': true,
                    'filter[Timeperiod.name]': searchString
                }
            }).then(function(result){
                $scope.timeperiods = result.data.all_timeperiods;
            });
            var hostChart = new Chart('hostChart', {
                type: 'bar',
                data: {
                    labels: ['Host1.fjdkdgjlsdg.fjkdsljfkdls.jfkdslf', 'Host2', 'Host3', 'Host4', 'Host5', 'Host6', '', '', '', ''],
                    datasets: [{
                        type: 'line',
                        label: 'Availability in %',
                        borderColor: '#317ABF',
                        backgroundColor: '#3688D8',
                        borderWidth: 2,
                        fill: false,
                        data: [85, 69, 33.456, 25, 12, 0.000]
                    }, {
                        type: 'bar',
                        label: 'Up',
                        data: [85, 69, 33, 25, 12, 3, 0, 0, 0, 0],
                        backgroundColor: '#449D44',
                        borderColor: '#ffffff',
                        borderWidth: 1
                    }, {
                        type: 'bar',
                        label: 'Down',
                        data: [15, 29, 43, 70, 80, 73, 0, 0, 0, 0],
                        backgroundColor: '#C9302C',
                        borderColor: '#ffffff',
                        borderWidth: 1
                    }, {
                        type: 'bar',
                        label: 'Unreachable',
                        data: [0, 2, 24, 5, 8, 24, 0, 0, 0, 0],
                        backgroundColor: '#92a2a8',
                        borderColor: '#ffffff',
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    tooltips: {
                        mode: 'label',
                        callbacks: {
                            title: function(tooltipItem, data){
                                return data.labels[tooltipItem[0]['index']];
                            },
                            label: function(tooltipItem, data){
                                return data.datasets[tooltipItem.datasetIndex].label + ": " + parseFloat(tooltipItem.yLabel).toFixed(3);
                            }
                        }
                    },
                    legend: {
                        display: true,
                        position: 'left'
                    },
                    scales: {
                        yAxes: [{
                            stacked: true,
                            ticks: {
                                beginAtZero: true,
                                min: 0,
                                max: 100
                            },
                            scaleLabel: {
                                display: true,
                                labelString: '%',
                            }
                        }],
                        xAxes: [{
                            stacked: true,
                            ticks: {
                                min: 10,
                                max: 10,
                                suggestedMin: 10,
                                maxTicksLimit: 10,
                                callback: function(value){
                                    if(value.length > 20){
                                        return value.substr(0, 20) + '...'; //truncate
                                    }else{
                                        return value;
                                    }
                                }
                            }
                        }]
                    }
                }
            });

            var hostPieChart = new Chart('hostPieChart', {
                type: 'pie',
                data: {
                    labels: ['Up', 'Down', 'Unreachable'],
                    datasets: [{
                        backgroundColor: [
                            '#449D44',
                            '#C9302C',
                            '#92A2A8'],
                        data: [6.598, 4.001, 80.676],
                        datalabels: {
                            display: false //<---- values in chart
                        },
                        borderWidth: 1 //<---- REALLLLLLYYYYY BORDER
                    }]
                },
                options: {
                    aspectRatio: 1,
                    layout: {
                        padding: {
                            top: 5,
                            right: 5,
                            left: 0,
                            bottom: 5
                        }
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data){
                                return data.labels[tooltipItem.index] + ': ' +
                                    data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] + '%';
                            }
                        }
                    },
                    responsive: true,
                    legend: false,
                    cutoutPercentage: 50, //inner cut circle
                    elements: {
                        center: {
                            text: '79%',
                            font: 20,
                            color: '#ffffff'
                        }
                    }
                }
            });


            var chart = new Chart('myChart', {
                type: 'pie',
                data: {
                    labels: ['Ok', 'Warning', 'Critical', 'Unknown'],
                    datasets: [{
                        backgroundColor: ['#449D44',
                            '#DF8F1D',
                            '#C9302C',
                            '#92A2A8'],
                        data: [2.123, 6.598, 4.001, 80.676],
                        datalabels: {
                            display: false //<---- values in chart
                        },
                        borderWidth: 1 //<---- REALLLLLLYYYYY BORDER
                    }]
                },
                options: {
                    aspectRatio: 1,
                    layout: {
                        padding: {
                            top: 5,
                            right: 5,
                            left: 0,
                            bottom: 5
                        }
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data){
                                return data.labels[tooltipItem.index] + ': ' +
                                    data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] + '%';
                            }
                        }
                    },
                    responsive: true,
                    legend: false,
                    cutoutPercentage: 50, //inner cut circle
                    elements: {
                        center: {
                            text: '99%',
                            font: 20,
                            color: '#ffffff'
                        }
                    }
                }
            });
            var chart2 = new Chart('myChart2', {
                type: 'pie',
                data: {
                    labels: ['ok', 'warning', 'critical', 'unknown'],
                    datasets: [{
                        backgroundColor: ['#449D44',
                            '#DF8F1D',
                            '#C9302C',
                            '#92A2A8'],
                        data: [28.123, 6.598, 94.001, 40.676],
                        datalabels: {
                            display: false //<---- values in chart
                        },
                        borderWidth: 1 //<---- REALLLLLLYYYYY BORDER
                    }]
                },
                options: {
                    aspectRatio: 1,
                    layout: {
                        padding: {
                            top: 5,
                            right: 5,
                            left: 0,
                            bottom: 5
                        }
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, chart){
                                var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                return datasetLabel + ': $ ---- >' + tooltipItem.yLabel;
                            }
                        }
                    },
                    responsive: true,
                    legend: false,
                    cutoutPercentage: 50, //inner cut circle
                    elements: {
                        center: {
                            text: '10%',
                            font: 20,
                            color: '#ffffff'
                        }
                    }
                }
            });
            var chart3 = new Chart('myChart3', {
                type: 'pie',
                data: {
                    labels: ['ok', 'warning', 'critical', 'unknown'],
                    datasets: [{
                        backgroundColor: ['#449D44',
                            '#DF8F1D',
                            '#C9302C',
                            '#92A2A8'],
                        data: [28.123, 6.598, 4.001, 10.676],
                        datalabels: {
                            display: false //<---- values in chart
                        },
                        borderWidth: 1 //<---- REALLLLLLYYYYY BORDER
                    }]
                },
                options: {
                    aspectRatio: 1,
                    layout: {
                        padding: 0
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data){
                                return data.labels[tooltipItem.index] + ': ' +
                                    data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] + '%';
                            }
                        }
                    },
                    legend: false,
                    responsive: true,
                    cutoutPercentage: 50, //inner cut circle
                    elements: {
                        center: {
                            text: '30%',
                            font: 20,
                            color: '#ffffff'
                        }
                    }
                }
            });
            var chart4 = new Chart('myChart4', {
                type: 'pie',
                data: {
                    labels: ['ok', 'warning', 'critical', 'unknown'],
                    datasets: [{
                        backgroundColor: ['#449D44',
                            '#DF8F1D',
                            '#C9302C',
                            '#92A2A8'],
                        data: [28.123, 86.598, 4.001, 40.676],
                        datalabels: {
                            display: false //<---- values in chart
                        },
                        borderWidth: 1 //<---- REALLLLLLYYYYY BORDER
                    }]
                },
                options: {
                    aspectRatio: 1,
                    layout: {
                        padding: {
                            top: 5,
                            right: 5,
                            left: 0,
                            bottom: 5
                        }
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data){
                                return data.labels[tooltipItem.index] + ': ' +
                                    data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] + '%';
                            }
                        }
                    },
                    responsive: true,
                    legend: false,
                    cutoutPercentage: 50, //inner cut circle
                    elements: {
                        center: {
                            text: '80%',
                            font: 20,
                            color: '#ffffff'
                        }
                    }
                }
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

        var getBackgroundColor = function getColor(value, minimalAvailability){
            //value from 0 to 100
            var colorLightness = 40;
            var hue = 120;
            if(value < 100 && value < minimalAvailability){
                hue = 0;
            }else if(value < 100 && value >= minimalAvailability){
                hue = parseInt(((value - minimalAvailability) / (100 - minimalAvailability)) * 120, 10);
                if(hue > 120){
                    hue = 120;
                }
            }
            return ['hsl(', hue, ',100%,' + colorLightness + '%)'].join('');
        };

        $scope.$watch('setColorDynamically', function() {
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

