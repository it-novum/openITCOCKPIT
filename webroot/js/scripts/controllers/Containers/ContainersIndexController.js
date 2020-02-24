angular.module('openITCOCKPIT')
    .controller('ContainersIndexController', function($scope, $http, $timeout, $stateParams, NotyService){

        $scope.init = true;
        $scope.selectedContainerTypeId = null;

        var clearForm = function(){
            $scope.post = {
                Container: {
                    parent_id: null,
                    name: null,
                },
                Location: {
                    description: '',
                    latitude: null,
                    longitude: null,
                    timezone: null,
                    container: {
                        name: '',
                        parent_id: null
                    }
                },
                Tenant: {
                    description: '',
                    firstname: '',
                    lastname: '',
                    street: '',
                    zipcode: null,
                    city: '',
                    container: {
                        name: ''
                    }
                }
            };
            $scope.errors = null;
        };

        clearForm();

        //Objects gets passed as reference.
        //So we use an object here, to make the $watch trigger, if the chosen directive change the value for selectedContainer.id
        $scope.selectedContainer = {
            id: null
        };

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
                    //$scope.post = {};
                    clearForm();
                    NotyService.genericSuccess();
                    $scope.load();
                }, function errorCallback(result){
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                    NotyService.genericError();
                }
            );
        };

        $scope.saveTenant = function(){
            $http.post("/tenants/add.json?angular=true", $scope.post.Tenant).then(
                function(result){
                    $('#angularAddNodeModal').modal('hide');
                    clearForm();
                    NotyService.genericSuccess();
                    $scope.load();
                }, function errorCallback(result){
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                    NotyService.genericError();
                }
            );
        };

        $scope.saveLocation = function(){
            $scope.post.Location.container.parent_id = $scope.post.Container.parent_id;
            $http.post("/locations/add.json?angular=true", $scope.post.Location).then(
                function(result){
                    $('#angularAddNodeModal').modal('hide');
                    clearForm();
                    NotyService.genericSuccess();
                    $scope.load();
                }, function errorCallback(result){
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                    NotyService.genericError();
                }
            );
        };

        $scope.updateNode = function(){
            if(typeof $scope.post.Container.name === "undefined" || $scope.post.Container.name === ''){
                $scope.errors = {
                    name: [
                        'This field cannot be left blank.'
                    ]
                };
                return;
            }

            $http.post("/containers/edit.json?angular=true", $scope.post).then(
                function(result){
                    $('#angularEditNodeModal').modal('hide');
                    NotyService.genericSuccess();
                    //$scope.callback();
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
            $scope.selectedContainerTypeId = parseInt(container.containertype_id, 10);
            //Set init value for select box 2 ==> Tenant ; 5 ==> Node
            $scope.post.Container.containertype_id = ($scope.selectedContainerTypeId === 1) ? '2' : '5';
            $scope.post.Container.parent_id = parseInt(container.id, 10);
            $scope.post.Location.timezone = 'Europe/Berlin'; //set initial value for timezone list
            $('#angularAddNodeModal').modal('show');
        };

        $scope.openEditNodeModal = function(container){
            clearForm();
            $scope.post.Container = container;
            $('#angularEditNodeModal').modal('show');
        };


        $scope.deleteNode = function(){
            $scope.isDeleting = true;
            $http.post('/containers/delete/' + $scope.post.Container.id + '.json?angular=true').then(
                function(result){
                    $('#angularEditNodeModal').modal('hide');
                    NotyService.genericSuccess();
                    //$scope.callback();
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
