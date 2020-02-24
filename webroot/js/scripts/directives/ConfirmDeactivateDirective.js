angular.module('openITCOCKPIT').directive('confirmDeactivate', function($http, $filter, $timeout, NotyService){
    return {
        restrict: 'E',
        templateUrl: '/angular/confirm_deactivate.html',

        controller: function($scope){

            var object;

            $scope.setObjectForDeactivate = function(_object){
                object = _object;
            };

            $scope.deactivate = function(){
                $scope.isDeactivating = true;
                $http.post($scope.deactivateUrl, {
                    empty: true
                }).then(
                    function(result){
                        NotyService.genericSuccess();
                        window.location.href = $scope.sucessUrl;
                    }, function errorCallback(result){
                        NotyService.genericError();
                        console.error(result.data);
                    });
            };

        },

        link: function($scope, element, attr){
            $scope.confirmDeactivate = function(object){
                $scope.setObjectForDeactivate(object);
                $('#angularConfirmDeactivate').modal('show');
            };
        }
    };
});
