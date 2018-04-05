angular.module('openITCOCKPIT')
    .controller('ProfileEditController', function($scope, $http){

        $scope.init = true;
        $scope.apikeys = [];

        $scope.load = function(){
            var params = {
                'angular': true
            };

            $http.get("/profile/apikey.json", {
                params: params
            }).then(function(result){
                $scope.apikeys = result.data.apikeys;
                $scope.init = false;
            });
        };

        $scope.load();

    });
