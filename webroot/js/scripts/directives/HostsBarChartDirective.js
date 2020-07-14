angular.module('openITCOCKPIT').directive('hostsBarChart', function($http, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/downtimereports/hostsBarChart.html',
        scope: {
            'chartId': '=',
            'barChartData': '='
        },
        controller: function($scope){

            var renderHostBarChart = function(){
                var hostChart = new Chart('chart-' + $scope.chartId, {
                    type: 'bar',
                    data: {
                        labels: $scope.barChartData.labels,
                        datasets: [{
                            type: 'line',
                            label: $scope.barChartData.datasets['availability'].label,
                            borderColor: '#317ABF',
                            backgroundColor: '#3688D8',
                            borderWidth: 2,
                            fill: false,
                            data: $scope.barChartData.datasets['availability'].data
                        }, {
                            type: 'bar',
                            label: $scope.barChartData.datasets[0].label,
                            data: $scope.barChartData.datasets[0].data,
                            backgroundColor: '#00C851',
                            borderColor: '#ffffff',
                            borderWidth: 1
                        }, {
                            type: 'bar',
                            label: $scope.barChartData.datasets[1].label,
                            data: $scope.barChartData.datasets[1].data,
                            backgroundColor: '#CC0000',
                            borderColor: '#ffffff',
                            borderWidth: 1
                        }, {
                            type: 'bar',
                            label: $scope.barChartData.datasets[2].label,
                            data: $scope.barChartData.datasets[2].data,
                            backgroundColor: '#727b84',
                            borderColor: '#ffffff',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        tooltips: {
                            mode: 'label',
                            position: 'nearest',
                            callbacks: {
                                title: function(tooltipItem, data){
                                    return data.labels[tooltipItem[0]['index']];
                                },
                                label: function(tooltipItem, data){
                                    return data.datasets[tooltipItem.datasetIndex].label + ": " + parseFloat(tooltipItem.yLabel).toFixed(3);
                                }
                            },
                            caretSize: 0,
                            bodyFontSize: 11,
                            bodySpacing: 2,
                            xPadding: 2,
                            yPadding: 2,
                            cornerRadius: 2,
                            titleMarginBottom: 5
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
            };

            $timeout(function(){
                renderHostBarChart();
            });
        },

        link: function($scope, element, attr){
            element.ready(function(){

            });
        }
    };
});
