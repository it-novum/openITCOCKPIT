angular.module('openITCOCKPIT')
    .controller('HostsAddwizardoverviewController', function($scope, $http, QueryStringService){
        $scope.id = QueryStringService.getCakeId();

        $scope.loadHostInfo = function(){
            $http.get("/hosts/loadHostInfo/"+$scope.id+".json", {
                params: {
                    'angular': true,
                }
            }).then(function(result){
                $scope.host = result.data.host.Host;
            });
        };

        $scope.loadServices = function(){
            $http.get("/services/loadServicesByHostId.json", {
                params: {
                    'angular': true,
                    'filter[Host.id]': $scope.id,
                }
            }).then(function(result){
                $scope.services = result.data.services;
                console.log($scope.services);
            });
        };

        $scope.loadHostInfo();
        $scope.loadServices();
    });