angular.module('openITCOCKPIT')
    .controller('StatisticsIndexController', function($scope, $http){

        $scope.post = {
            statistics: {
                decision: 2
            }
        };

        $scope.init = true;

        $scope.load = function(){
            $http.get("/statistics/index.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.settings = result.data.settings;
                $scope.settings.Systemsetting.value = parseInt($scope.settings.Systemsetting.value, 10);
                $scope.init = false;
            });
        };

        $scope.save = function(value){
            $scope.post.statistics.decision = value;
            $http.post("/statistics/saveStatisticDecision.json", $scope.post).then(function(result){
                $scope.load();
            });
        };

        $scope.load();

    });