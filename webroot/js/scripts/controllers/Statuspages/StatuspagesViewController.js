angular.module('openITCOCKPIT')
    .controller('StatuspagesViewController', function($scope, $rootScope, $stateParams, $http){
        $scope.id = $stateParams.id;
        $scope.init = true;

        $scope.Statuspage = {};


        $scope.load = function(){
            var params = {
                'angular': true
            };

            $http.get("/statuspages/view/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.Statuspage = result.data.Statuspage;
                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        // Fire on page load
        $scope.load();

    });
