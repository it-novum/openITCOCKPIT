angular.module('openITCOCKPIT')
    .controller('AutomapsViewController', function($scope, $http, QueryStringService){

        $scope.id = QueryStringService.getCakeId();
        $scope.init = true;


        $scope.load = function(){
            var params = {
                'angular': true
            };

            $http.get("/automaps/view/"+$scope.id+".json", {
                params: params
            }).then(function(result){
                $scope.automap = result.data.automap.Automap;
                $scope.hostAndServices = result.data.hostAndServices;
                $scope.init = false;
            });
        };

        $scope.load();
    });