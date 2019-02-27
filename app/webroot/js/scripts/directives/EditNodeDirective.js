angular.module('openITCOCKPIT').directive('editNode', function($http, $state, $stateParams, NotyService){
    return {
        restrict: 'E',
        templateUrl: '/containers/edit.html',
        scope: {
            'container': '=',
            'callback': '='
        },

        controller: function($scope){

            $scope.post = {
                Container: {
                    id: $scope.container.Container.id,
                    containertype_id: 5,
                    name: $scope.container.Container.name,
                    parent_id: $scope.container.Container.parent_id
                }
            };

            $scope.openModal = function(){
                $('#angularEditNode-' + $scope.container.Container.id).modal('show');
            };

            $scope.save = function(){
                $http.post("/containers/edit.json?angular=true", $scope.post).then(
                    function(result){
                        $('#angularEditNode-' + $scope.container.Container.id).modal('hide');
                        NotyService.genericSuccess();
                        $state.go('ContainersIndex', {'id': $scope.container.Container.parent_id}, {
                            location: false
                        });
                    }, function errorCallback(result){
                        if(result.data.hasOwnProperty('error')){
                            $scope.errors = result.data.error;
                        }
                        NotyService.genericError();
                    }
                );
            };

            $scope.delete = function(){
                $scope.isDeleting = true;

                $http.post('/containers/delete/' + $scope.container.Container.id).then(
                    function(result){
                        $('#angularEditNode-' + $scope.container.Container.id).modal('hide');
                        NotyService.genericSuccess();
                        $state.go('ContainersIndex', {'id': $scope.container.Container.parent_id}, {
                            location: false
                        });
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
