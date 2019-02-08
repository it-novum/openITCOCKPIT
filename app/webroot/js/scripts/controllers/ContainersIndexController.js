angular.module('openITCOCKPIT')
    .controller('ContainersIndexController', function($scope, $http, $timeout, $stateParams){

        $scope.init = true;

        //Objects gets passed as reference.
        //So we use an object here, to make the $watch trigger, if the chosen directive change the value for selectedTenant.id
        $scope.selectedTenant = {
            id: null
        };
        $scope.selectedTenantForNode = null;
        $scope.errors = null;

        console.log($stateParams.id);
        if($stateParams.id != null){
            $scope.selectedTenant.id = $stateParams.id;
        }

        $scope.post = {
            Container: {
                parent_id: null,
                name: null,
                containertype_id: '5'
            }
        };

        $scope.load = function(){
            $scope.loadContainers();
            $scope.loadContainerlist();
        };

        $scope.saveNewNode = function(){
            $http.post("/containers/add.json?angular=true", $scope.post).then(function(result){
                $('#nodeCreatedFlashMessage').show();
                $scope.post.Container.name = null;
                $scope.load();
                $timeout(function(){
                    $('#nodeCreatedFlashMessage').hide();
                }, 3000);
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
            $http.get('/containers/byTenant/' + $scope.selectedTenant.id + '.json', {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.nest;
                $('#nestable').nestable({
                    noDragClass: 'dd-nodrag'
                });
            });
        };

        $scope.loadContainerlist = function(){
            $http.get('/containers/byTenantForSelect/' + $scope.selectedTenant.id + '.json').then(function(result){
                $scope.containerlist = result.data.paths;
            });
        };

        $scope.loadTenants();

        $scope.$watch('selectedTenant.id', function(){
            if($scope.selectedTenant.id !== null){

                for(var key in $scope.tenants){
                    if($scope.tenants[key].Tenant.container_id == $scope.selectedTenant.id){
                        $scope.tenant = $scope.tenants[key];
                    }
                }

                $scope.load();
            }
        });

    });