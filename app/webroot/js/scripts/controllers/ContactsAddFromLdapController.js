angular.module('openITCOCKPIT')
    .controller('ContactsAddFromLdapController', function($scope, $http){

        $scope.init = true;
        $scope.isPhp7Dot1 = false;

        $scope.selectedSamAccountName = '';
        $scope.errors = false;

        $scope.loadUsers = function(searchString){
            $http.get("/contacts/addFromLdap.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.users = result.data.usersForSelect;
                $scope.isPhp7Dot1 = result.data.isPhp7Dot1;
            });
        };

        $scope.loadUsersByString = function(searchString){
            $http.get("/contacts/loadLdapUserByString.json", {
                params: {
                    'angular': true,
                    'samaccountname': searchString
                }
            }).then(function(result){
                $scope.users = result.data.usersForSelect;
            });
        };

        $scope.loadUsers();

    });