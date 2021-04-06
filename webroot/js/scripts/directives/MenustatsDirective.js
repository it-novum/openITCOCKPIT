angular.module('openITCOCKPIT').directive('menustats', function($http, $interval){
    return {
        restrict: 'E',
        templateUrl: '/angular/menustats.html',

        controller: function($scope){
            $scope.showstatsinmenu = false;
            $scope.hoststatusCount = {};
            $scope.servicestatusCount = {};

            $scope.load = function(){
                $http.get("/angular/menustats.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.showstatsinmenu = result.data.showstatsinmenu;
                    $scope.hoststatusCount = result.data.hoststatusCount;
                    $scope.servicestatusCount = result.data.servicestatusCount;
                });
            };

            $interval($scope.load, 30000);

            $scope.load();
 
        },

        link: function(scope, element, attr){

        }
    };
});
