angular.module('openITCOCKPIT').directive('hostsPiechart180Widget', function($http){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/hostsPiechart180Widget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){

            $scope.load = function(){
                $http.get("/angular/statuscount.json", {
                    params: {
                        'angular': true,
                        'recursive': true
                    }
                }).then(function(result){
                    $scope.hoststatusCount = result.data.hoststatusCount;
                    $scope.hoststatusCountPercentage = result.data.hoststatusCountPercentage;
                    $scope.init = false;
                });
            };

            $scope.load();

        },

        link: function($scope, element, attr){

        }
    };
});
