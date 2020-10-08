angular.module('openITCOCKPIT').directive('changeLanguage', function($http, NotyService){
    return {
        restrict: 'A',

        controller: function($scope){

            $scope.changeLanguage = function(i18n){
                $http.post("/profile/updateI18n.json?angular=true",
                    {'i18n': i18n}
                ).then(function(result){
                    $scope.errors = {};
                    NotyService.genericSuccess();

                    location.reload();

                }, function errorCallback(result){
                    NotyService.genericError();
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });
            };

        },

        link: function(scope, element, attr){

        }
    };
});