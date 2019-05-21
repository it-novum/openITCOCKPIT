angular.module('openITCOCKPIT')
    .controller('ContainersIndexController', function($scope, $http, $timeout){

        $scope.init = true;
        $scope.selectedTenant = null;
        $scope.selectedTenantForNode = null;
        $scope.errors = null;
        $scope.isDeleting = false;

        $scope.resetAddEditFields = function(){
            $scope.edit = {
                Container: {
                    id: null,
                    containertype_id: 5,
                    name: null,
                    parent_id: null
                }
            };

            $scope.add = {
                Container: {
                    parent_id: null,
                    name: null,
                    containertype_id: '5'
                }
            };
        };

        $scope.load = function(){
            $scope.resetAddEditFields();
            $scope.loadContainers();
        };

        $scope.saveNewNode = function(){
            $http.post("/containers/add.json?angular=true", $scope.add).then(function(result){
                $('#angularAddNodeModal').modal('hide');
                $scope.load();
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
                $('#nestable').nestable({
                    noDragClass: 'dd-nodrag'
                });
            });
        };

        $scope.updateNode = function(){
            $http.post("/containers/edit.json?angular=true", $scope.edit).then(
                function(result){
                    $scope.load();
                    $('#angularEditNodeModal').modal('hide');
                }, function errorCallback(result){
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                }
            );
        };

        $scope.deleteNode = function(){
            $scope.isDeleting = true;

            $http.post('/containers/delete/' + $scope.edit.Container.id).then(
                function(result){
                    $scope.load();
                    $('#angularEditNodeModal').modal('hide');
                    $scope.isDeleting = false;
                }, function errorCallback(result){
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                    $scope.isDeleting = false;
                }
            );
        };

        $scope.openEditNode = function(container){
            $scope.edit.Container.id = parseInt(container.id);
            $scope.edit.Container.parent_id = parseInt(container.parent_id);
            $scope.edit.Container.name = container.name;
            $('#angularEditNodeModal').modal('show');
        };

        $scope.openAddNode = function(parent_id){
            $scope.add.Container.parent_id = parseInt(parent_id);
            $('#angularAddNodeModal').modal('show');
        };

        $scope.loadTenants();

        $scope.$watch('selectedTenant', function(){
            if($scope.selectedTenant !== null){
                for(var key in $scope.tenants){
                    if($scope.tenants[key].Tenant.container_id == $scope.selectedTenant){
                        $scope.tenant = $scope.tenants[key];
                    }
                }
                $scope.load();
            }
        });

    });