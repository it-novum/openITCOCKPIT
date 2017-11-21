angular.module('openITCOCKPIT')
    .controller('ContainersIndexController', function($scope, $http){


        $scope.init = true;
        $scope.selectedTenant = null;
        $scope.selectedTenantForNode = null;
        $scope.newNode_name = null;
        $scope.newNode_parent = null;
        $scope.nested_list_counter = 0;
        $scope.errors = null;



        $scope.saveNewNode = function(){
            if($scope.newNode_name && $scope.newNode_parent){

                console.log($scope.newNode_name);
                console.log($scope.newNode_parent);
                $http.post("/containers/add.json",
                    {
                        Container: {
                            parent_id: $scope.newNode_parent,
                            name: $scope.newNode_name,
                            containertype_id: '5'
                        },
                    }
                ).then(function(result){
                    //console.log(result);
                    $scope.newNode_name = null;
                    $scope.loadContainers();
                    $scope.loadContainerlist();
                }, function errorCallback(result){
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });
            }
        };

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