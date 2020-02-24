angular.module('openITCOCKPIT').directive('serviceAvailabilityOverview', function($http, $timeout, AvailabilityColorCalculationService){
    return {
        restrict: 'E',
        templateUrl: '/downtimereports/serviceAvailabilityOverview.html',
        scope: {
            'data': '=',
            'dynamicColor': '='
        },
        controller: function($scope){
            $timeout(function(){
                $scope.color = 'transparent';
                if($scope.dynamicColor){
                    $scope.color = AvailabilityColorCalculationService.getBackgroundColor(
                        $scope.data.pieChartData.availability
                    );
                    $scope.color = 'linear-gradient(90deg, '+$scope.color+' 30%, rgba(76, 79, 83, 0.9) 40%)';
                }

                var chart = new Chart('servicePieChart-' + $scope.data.Service.id, {
                    type: 'pie',
                    data: {
                        labels: $scope.data.pieChartData.labels,
                        datasets: [{
                            backgroundColor: [
                                '#449D44',
                                '#DF8F1D',
                                '#C9302C',
                                '#92A2A8'
                            ],
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
                                color: ($scope.dynamicColor)?'#ffffff':'#000000', //Default black
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
