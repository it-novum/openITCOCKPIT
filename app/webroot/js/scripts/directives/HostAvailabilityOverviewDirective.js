angular.module('openITCOCKPIT').directive('hostAvailabilityOverview', function($http, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/downtimereports/hostAvailabilityOverview.html',
        scope: {
            'data': '='
        },
        controller: function($scope){
            $timeout(function(){
                var hostPieChart = new Chart('hostPieChart-' + $scope.data.Host.id, {
                    type: 'pie',
                    data: {
                        labels: $scope.data.pieChartData.labels,
                        datasets: [{
                            backgroundColor: [
                                '#449D44',
                                '#C9302C',
                                '#92A2A8'],
                            data: $scope.data.pieChartData.data,
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
                                text: $scope.data.pieChartData.availability + '%',
                                font: 20,
                                color: '#ffffff'
                            }
                        }
                    }
                });
            });
        },

        link: function($scope, element, attr){
            element.ready(function(){

            });
        }
    };
});
