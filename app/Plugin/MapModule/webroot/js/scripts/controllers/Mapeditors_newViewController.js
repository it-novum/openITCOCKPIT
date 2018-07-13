angular.module('openITCOCKPIT')
    .controller('Mapeditors_newViewController', function($scope, $http, QueryStringService, $timeout){

        $scope.init = true;
        $scope.id = QueryStringService.getCakeId();

        $scope.load = function(){
            $http.get("/map_module/mapeditors_new/view/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.map = result.data.map;
                $scope.init = false;
            });
        };

        $scope.showSummaryStateDelayed = function(item){
            var timer = $timeout(function(){
                //Method is in MapSummaryDirective
                $scope.showSummaryState(item);
            }, 500);
        };

        $scope.load();

    });