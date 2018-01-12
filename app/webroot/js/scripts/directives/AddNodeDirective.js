angular.module('openITCOCKPIT').directive('addNode', function($http, $interval){
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
                    containertype_id: '5'
                }
            };

            $scope.openModal = function(){
                $('#angularAddNode-'+$scope.container.Container.id).modal('show');
            };

            $scope.save = function(){
                //if($scope.post.Container.name){
                $http.post("/containers/add.json?angular=true", $scope.post).then(
                    function(result){
                        $scope.callback();
                        $('#angularAddNode-'+$scope.container.Container.id).modal('hide');
                    }, function errorCallback(result){
                        if(result.data.hasOwnProperty('error')){
                            $scope.errors = result.data.error;
                        }
                    }
                );
                //}
            };

        },

        link: function($scope, element, attr){

        }
    };
});
