angular.module('openITCOCKPIT').directive('hostAvailabilityOverview', function($http, $timeout, AvailabilityColorCalculationService){
    return {
        restrict: 'E',
        templateUrl: '/downtimereports/hostAvailabilityOverview.html',
        scope: {
            'data': '=',
            'dynamicColor': '='
        },
        controller: function($scope){
            $timeout(function(){

                $scope.color = 'rgba(76, 79, 83, 0.9)';
                if($scope.dynamicColor){
                    $scope.color = AvailabilityColorCalculationService.getBackgroundColor(
                        $scope.data.pieChartData.availability
                    );
                }

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
                                    return data.labels[tooltipItem.index] + ':' +
                                        data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] + '%';
                                }
                            },
                            bodyFontSize: 8,
                            caretSize: 1
                        },
                        responsive: true,
                        legend: false,
                        cutoutPercentage: 50, //inner cut circle
                        elements: {
                            center: {
                                text: $scope.data.pieChartData.availability + '%',
                                fontSize: 12,
                                fontFixed: true
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
