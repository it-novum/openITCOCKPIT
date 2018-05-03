angular.module('openITCOCKPIT')
    .controller('UsersAddFromLdapController', function($scope, $http){


        $scope.init = true;
        $scope.isPhp7Dot1 = false;

        $scope.selectedSamAccountName = '';
        $scope.errors = false;

        $scope.loadUsers = function(searchString){
            $http.get("/users/addFromLdap.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.users = result.data.usersForSelect;
                $scope.isPhp7Dot1 = result.data.isPhp7Dot1;
            });
        };

        $scope.loadUsersByString = function(searchString){
            $http.get("/users/loadLdapUserByString.json", {
                params: {
                    'angular': true,
                    'samaccountname': searchString
                }
            }).then(function(result){
                $scope.users = result.data.usersForSelect;
            });
        };

        $scope.submit = function(){
            if($scope.selectedSamAccountName.length === 0){
                $scope.errors = [
                    'Please select one user'
                ];
                return false;
            }

            window.location.href = '/users/add/ldap:1/samaccountname:'+encodeURI($scope.selectedSamAccountName)+'/fix:1';
        };

        $scope.loadUsers();

    });