angular.module('openITCOCKPIT').directive('serviceAvailabilityPieChart', function($http, $timeout, AvailabilityColorCalculationService){
    return {
        restrict: 'E',
        templateUrl: '/instantreports/serviceAvailabilityPieChart.html',
        scope: {
            'data': '=',
            'chartId': '='
        },
        controller: function($scope){
            $timeout(function(){
                var paper = new Raphael(document.getElementById('servicePieChart-' + $scope.chartId), 400, 130)
                    .pielicious(100, 50, 100, {
                        data: [
                            $scope.data.reportData[0],
                            $scope.data.reportData[1],
                            $scope.data.reportData[2],
                            $scope.data.reportData[3]
                        ],
                        colors: ["#00C851", "#ffbb33", "#CC0000", "#727b84"],
                        gradient: {darkness: 14, lightness: 6, degrees: 180},
                        marker: "square",
                        threeD: {
                            height: 10,
                            tilt: 0.5
                        },
                        legend: {
                            labels: [
                                $scope.data.reportData.percentage[0],
                                $scope.data.reportData.percentage[1],
                                $scope.data.reportData.percentage[2],
                                $scope.data.reportData.percentage[3]
                            ],
                            x: 220,
                            y: 0,
                            fontSize: 12,
                            events: false
                        },

                        evolution: false,
                        orientation: 270,
                        animation: false//"shift-slow"
                    });
            });
        },

        link: function(){
        }
    };
});
