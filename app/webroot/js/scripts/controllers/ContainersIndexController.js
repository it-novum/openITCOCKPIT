angular.module('openITCOCKPIT')
    .controller('ContainersIndexController', function($scope, $http, $timeout){

        $scope.init = true;
        $scope.selectedTenant = null;
        $scope.selectedTenantForNode = null;
        $scope.newNode_name = null;     //"name" e.g.
        $scope.newNode_parent = null;   //19 e.g.
        $scope.errors = null;


        $scope.load = function(){
            $scope.loadContainers();
            $scope.loadContainerlist();
        };

        $scope.saveNewNode = function(){
            $http.post("/containers/add.json?angular=true",
                {
                    Container: {
                        parent_id: $scope.newNode_parent,
                        name: $scope.newNode_name,
                        containertype_id: '5'
                    }
                }
            ).then(function(result){
                $('#nodeCreatedFlashMessage').show();
                $scope.newNode_name = null;
                $scope.load();
                $timeout(function(){
                    $('#nodeCreatedFlashMessage').hide();
                },3000);
                $scope.errors = null;
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });

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
            $http.get('/containers/byTenant/' + $scope.selectedTenant + '.json', {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.nest;
            });
        };

        $scope.loadContainerlist = function(){
            $http.get('/containers/byTenantForSelect/' + $scope.selectedTenant + '.json').then(function(result){
                $scope.containerlist = result.data.paths;
            });
        };

        $scope.loadTenants();

        $scope.$watch('selectedTenant', function(){
            if($scope.selectedTenant !== null){
                $scope.load();
            }
        });

    });