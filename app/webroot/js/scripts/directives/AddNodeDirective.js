angular.module('openITCOCKPIT').directive('addNode', function($http, $state, NotyService){
    return {
        restrict: 'E',
        templateUrl: '/containers/add.html',
        scope: {
            'container': '=',
            'tenant': '=',
            'callback': '='
        },

        controller: function($scope){

            $scope.post = {
                Container: {
                    parent_id: $scope.container.Container.id,
                    name: null,
                    containertype_id: '5'
                }
            };

            $scope.openModal = function(){
                $('#angularAddNode-' + $scope.container.Container.id).modal('show');
            };

            $scope.save = function(){
                $http.post("/containers/add.json?angular=true", $scope.post).then(
                    function(result){
                        $('#angularAddNode-' + $scope.container.Container.id).modal('hide');
                        $scope.post = {};
                        NotyService.genericSuccess();
                        $state.go('ContainersIndex', {'id': $scope.container.Container.parent_id});
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
