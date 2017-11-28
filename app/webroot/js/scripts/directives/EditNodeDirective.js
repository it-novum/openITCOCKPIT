angular.module('openITCOCKPIT').directive('editNode', function($http, $interval){
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
                    name: $scope.container.Container.name,
                    containertype_id: 5
                }
            };
            $scope.openModal = function(){
                $('#angularEditNode-'+$scope.container.Container.id).modal('show');
            };

            $scope.save = function(){
                if($scope.post.Container.name){
                    $http.post("/containers/edit.json?angular=true", $scope.post).then(
                        function(result){
                            $scope.callback();
                            $('#angularEditNode-'+$scope.container.Container.id).modal('hide');
                        }, function errorCallback(result){
                            console.error(result.data);
                        }
                    );
                }
            };

            $scope.delete = function(){
                $scope.isDeleting = true;

                $http.post('/containers/delete/'+$scope.container.Container.id).then(
                    function(result){
                        $scope.callback();
                        $('#angularEditNode-'+$scope.container.Container.id).modal('hide');
                    }, function errorCallback(result){
                        console.error(result.data);
                    }
                );
            };

        },

        link: function($scope, element, attr){
        }
    };
});
