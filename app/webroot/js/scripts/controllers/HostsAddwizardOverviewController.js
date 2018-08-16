angular.module('openITCOCKPIT')
    .controller('HostsAddwizardoverviewController', function($scope, $http, QueryStringService){
        $scope.id = QueryStringService.getCakeId();

        $scope.loadServices = function(){
            $http.get("/services/loadServicesByHostId.json", {
                params: {
                    'angular': true,
                    'filter[Host.id]': $scope.id,
                }
            }).then(function(result){
                $scope.services = result.data.services;
            });
        };

        $scope.loadServices();
    });