angular.module('openITCOCKPIT')
    .controller('ServicesUsedByController', function($scope, $http, $stateParams, $state){
        $scope.id = $stateParams.id;
        $scope.total = 0;
        $scope.load = function(){
            $http.get("/services/usedBy/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.objects = result.data.objects;
                $scope.total = result.data.total;
                $scope.service = result.data.service;

            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };


        $scope.load();
    });
