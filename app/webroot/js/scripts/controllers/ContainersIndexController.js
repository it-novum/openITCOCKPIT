angular.module('openITCOCKPIT')
    .controller('ContainersIndexController', function($scope, $http){


        $scope.init = true;
        $scope.selectedTenant = null;
        $scope.selectedTenantForNode = null;
        $scope.nested_list_counter = 0;
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
            $http.get('/containers/byTenant/'+$scope.selectedTenant+'.json', {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.nest;
            });
        };

        $scope.loadContainerlist = function(){
            $http.get('/containers/byTenantForSelect/'+$scope.selectedTenant+'.json').then(function(result){
                $scope.containerlist = result.data.paths;
            });
        };

        $scope.loadTenants();

        $scope.$watch('selectedTenant',function(){
            console.log($scope.selectedTenant);
            if($scope.selectedTenant !== null){
                $scope.loadContainers();
                $scope.loadContainerlist();
            }
        });

    });