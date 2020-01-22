angular.module('openITCOCKPIT').directive('hostAvailabilityPieChart', function($http, $timeout, AvailabilityColorCalculationService){
    return {
        restrict: 'E',
        templateUrl: '/instantreports/hostAvailabilityPieChart.html',
        scope: {
            'data': '='
        },
        controller: function($scope){
            $timeout(function(){
                var paper = new Raphael(document.getElementById('hostPieChart-' + $scope.data.Host.id), 400, 130),
                    pie = paper.pielicious(100, 50, 100, {
                        data: [
                            $scope.data.Host.reportData[0],
                            $scope.data.Host.reportData[1],
                            $scope.data.Host.reportData[2]
                        ],
                        colors: ["#3FC837", "#C9302C", "#92A2A8"],
                        gradient: {darkness: 14, lightness: 6, degrees: 180},
                        marker: "square",
                        threeD: {
                            height: 10,
                            tilt: 0.5
                        },
                        legend: {
                            labels: [
                                $scope.data.Host.reportData.percentage[0],
                                $scope.data.Host.reportData.percentage[1],
                                $scope.data.Host.reportData.percentage[2]
                            ],
                            x: 220,
                            y: 15,
                            fontSize: 12,
                            events: false
                        },

                        evolution: false,
                        orientation: 270,
                        animation: false//"shift-slow"
                    });
            });
        },

        link: function($scope, element, attr){
            element.ready(function(){

            });
        }
    };
});
