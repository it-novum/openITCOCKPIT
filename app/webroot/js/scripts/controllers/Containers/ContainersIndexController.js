angular.module('openITCOCKPIT')
    .controller('ContainersIndexController', function($scope, $http, $timeout, $stateParams, $filter){

        $scope.init = true;

        var clearForm = function(){
            $scope.post = {
                Container: {
                    parent_id: null,
                    name: null,
                    containertype_id: null
                },
                Location: {
                    description: '',
                    latitude: null,
                    longitude: null,
                    timezone: null
                },
                Tenant: {
                    description: null,
                    firstname: null,
                    lastname: null,
                    street: null,
                    zipcode: null,
                    city: null,
                    is_active: 1,
                    max_users: 0
                }
            };
        };
        clearForm();

        //Objects gets passed as reference.
        //So we use an object here, to make the $watch trigger, if the chosen directive change the value for selectedContainer.id
        $scope.selectedContainer = {
            id: null
        };
        $scope.errors = null;
        if($stateParams.id != null){
            $scope.selectedContainer.id = parseInt($stateParams.id, 10);
        }

        $scope.load = function(){
            $http.get("/containers/loadContainers.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
                if($scope.selectedContainer.id !== null){
                    var objectExist = _.isObject(_.find($scope.containers, function(obj){
                        return obj.key === $scope.selectedContainer.id;
                    }));
                    if(objectExist){ // check after delete if selected container exists
                        $scope.loadContainers();
                    }else{
                        $scope.selectedContainer.id = null;
                        $scope.subcontainers = {};
                    }

                }
            });
        };

        $scope.loadContainers = function(){
            if($scope.selectedContainer.id !== null){
                $http.get('/containers/loadContainersByContainerId/' + $scope.selectedContainer.id + '.json', {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.subcontainers = result.data.nest;
                    $('#nestable').nestable({
                        noDragClass: 'dd-nodrag'
                    });
                });
            }
        };

        $scope.saveNode = function(){
            $http.post("/containers/add.json?angular=true", $scope.post).then(
                function(result){
                    $('#angularAddNodeModal').modal('hide');
                    clearForm();
                    //$scope.post = {};
                    NotyService.genericSuccess();
                    $scope.callback();
                }, function errorCallback(result){
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                    NotyService.genericError();
                }
            );
        };

        $scope.saveTenant = function(){
            $http.post("/tenants/add.json?angular=true", $scope.post).then(
                function(result){
                    $('#angularAddNodeModal').modal('hide');
                    clearForm();
                    //$scope.post = {};
                    NotyService.genericSuccess();
                    $scope.callback();
                }, function errorCallback(result){
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                    NotyService.genericError();
                }
            );
        };

        $scope.saveLocation = function(){
            $http.post("/locations/add.json?angular=true", $scope.post).then(
                function(result){
                    $('#angularAddNodeModal').modal('hide');
                    clearForm();
                    //$scope.post = {};
                    NotyService.genericSuccess();
                    $scope.callback();
                }, function errorCallback(result){
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                    NotyService.genericError();
                }
            );
        };

        $scope.updateNode = function(){
            $http.post("/containers/edit.json?angular=true", $scope.post).then(
                function(result){
                    $('#angularEditNodeModal').modal('hide');
                    NotyService.genericSuccess();
                    $scope.callback();
                }, function errorCallback(result){
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                    NotyService.genericError();
                }
            );
        };

        $scope.openAddNodeModal = function(container){
            clearForm();
            $scope.post.Container.parent_id = parseInt(container.parent_id);
            $('#angularAddNodeModal').modal('show');
        };

        $scope.openEditNodeModal = function(container){
            $scope.post = container;
            $('#angularEditNodeModal').modal('show');
        };


        $scope.deleteNode = function(){
            $scope.isDeleting = true;
            $http.post('/containers/delete/' + $scope.post.Container.id + '.json?angular=true').then(
                function(result){
                    $('#angularEditNodeModal').modal('hide');
                    NotyService.genericSuccess();
                    $scope.callback();
                }, function errorCallback(result){
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                    NotyService.genericError();
                }
            );
        };

        $scope.load();

        $scope.$watch('selectedContainer.id', function(){
            if($scope.init){
                return;
            }
            if($scope.selectedContainer.id !== null){
                $scope.loadContainers();
            }
        }, true);
    });
