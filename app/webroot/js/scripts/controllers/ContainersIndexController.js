angular.module('openITCOCKPIT')
    .controller('ContainersIndexController', function($scope, $http){


        $scope.init = true;
        $scope.selectedTenant = 2;
        $scope.loadTenants = function(){
            $http.get("/tenants/index.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.tenants = result.data.all_tenants;
                $scope.init = false;
            });
        };

        $scope.loadContainers = function(){
            $http.get('containers/byTenant/'+$scope.selectedTenant+'.json', {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.nest;
            });
        };




        $scope.loadTenants();

        $scope.$watch('selectedTenant',function(){
            console.log($scope.selectedTenant);
            if($scope.selectedTenant !== null){
                $scope.loadContainers();
            }
        });

    });