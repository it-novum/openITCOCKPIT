angular.module('openITCOCKPIT').directive('hostAvailabilityPieChart', function($http, $timeout, AvailabilityColorCalculationService){
    return {
        restrict: 'E',
        templateUrl: '/instantreports/hostAvailabilityPieChart.html',
        scope: {
            'data': '='
        },
        controller: function($scope){
            $timeout(function(){
                var paper = new Raphael(document.getElementById('hostPieChart-' + $scope.data.Host.id), 360, 130),
                    pie = paper.pielicious(120, 60, 100, {
                        data: [70,20,20,3],
                        //colors: colors,
                        titles: ["Ok", "Warning", "Critical", "Unknown"],
                        colors: ["#3FC837","#DF8F1D","#C9302C", "#92A2A8"],
                        labels: ["Ok", "Warning", "Critical", "Unknown"],
                        handles: ["Ok", "Warning", "Critical", "Unknown"],
                        //hrefs: ['http://google.com', 'http://apple.com', 'http://yahoo.com', 'http://yahoo.com'],
                        gradient: {darkness: 5, lightness: 6, degrees: 180},
                    //  cursor: "pointer",
                        marker: "square",
                        threeD: {height: 10, tilt: 0.5},
                        //donut: {diameter: 0.4, tilt: 0.6},
                        legend: {
                            labels: [
                                "Ok (3%)",
                                "Warning (12%)",
                                "Critical (8%)",
                                "Unknown (0%)"
                            ],
                            x: 250,
                            y: 10,
                            fontSize: 12,
                            events: true
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
