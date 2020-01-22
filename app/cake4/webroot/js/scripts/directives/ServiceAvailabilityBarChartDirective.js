angular.module('openITCOCKPIT').directive('serviceAvailabilityBarChart', function($http, $timeout, AvailabilityColorCalculationService){
    return {
        restrict: 'E',
        templateUrl: '/instantreports/serviceAvailabilityBarChart.html',
        scope: {
            'data': '='
        },
        controller: function($scope){
            $timeout(function(){
                var paper = Raphael('serviceBarChart-' + $scope.data.Service.id);
                bar = paper.hbarchart(3, 4, 350, 26, [[55], [10], [20], [10]], {
                    colors: ["#3FC837", "#DF8F1D", "#C9302C", "#92A2A8"],
                    values: [55, 10, 20, 10],
                    labels: ["Ok", "Warning", "Critical", "Unknown"],
                    stacked: true,
                    legend: true,
                    show3d: true,

                    legendContainerCssClass: "barchart-legend-container",
                    legendLineColor: "#FF0000",
                    legendTextColor: "#cccccc",
                    legendFont: "12px Arial",
                    legendLabelCssClass: "barchart-legend-label",
                    legendShowValues: "percentage",
                    legendMarker: "square",
                    size3d: 6
                });
            });
        },

        link: function($scope, element, attr){
            element.ready(function(){
            });
        }
    };
});
