angular.module('openITCOCKPIT')
    .controller('UsersAddController', function($scope, $http, $rootScope){
        $scope.load = function(){
            var params = {
                'angular': true,
            };

            $http.get("/users/add.json", {
                params: params
            }).then(function(result){
                console.log(result.data);
                $scope.Users = result.data.all_users;
                $scope.init = false;
            });
        };

        $scope.load();
    });

