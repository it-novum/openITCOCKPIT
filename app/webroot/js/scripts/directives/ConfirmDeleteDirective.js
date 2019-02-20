angular.module('openITCOCKPIT').directive('confirmDelete', function($http, $state, $filter, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/confirm_delete.html',

        controller: function($scope){

            var object;

            $scope.setObject = function(_object){
                object = _object;
            };

            $scope.delete = function(){
                $scope.isDeleting = true;
                $http.post($scope.deleteUrl).then(
                    function(result){
                        if($scope.successState != null){
                            $state.go($scope.successState);
                        } else {
                            window.location.href = $scope.sucessUrl;
                        }
                    }, function errorCallback(result){
                        console.error(result.data);
                    });
            };

        },

        link: function($scope, element, attr){
            $scope.confirmDelete = function(object){
                $scope.setObject(object);
                $('#angularConfirmDelete').modal('show');
            };
        }
    };
});