angular.module('openITCOCKPIT').directive('addNode', function($http, NotyService){
    return {
        restrict: 'E',
        templateUrl: '/containers/add.html',
        scope: {
            'container': '=',
            'callback': '='
        },
        controller: function($scope){
            $scope.post = {
                Container: {
                    parent_id: $scope.container.Container.id,
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
            $scope.openModal = function(){
                $('#angularAddNode-' + $scope.container.Container.id).modal('show');
            };
            $scope.saveNode = function(){
                $http.post("/containers/add.json?angular=true", $scope.post).then(
                    function(result){
                        $('#angularAddNode-' + $scope.container.Container.id).modal('hide');
                        $scope.post = {};
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
                        $('#angularAddNode-' + $scope.container.Container.id).modal('hide');
                        $scope.post = {};
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
                        $('#angularAddNode-' + $scope.container.Container.id).modal('hide');
                        $scope.post = {};
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
        },
        link: function($scope, element, attr){
        }
    };
});
