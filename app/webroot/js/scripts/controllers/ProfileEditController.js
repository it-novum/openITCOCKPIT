angular.module('openITCOCKPIT')
    .controller('ProfileEditController', function($scope, $http, $state, $stateParams, NotyService){
        $scope.init = true;
        $scope.apikeys = [];

        $scope.post = {

        };
        $scope.isLdapAuth = false;

        $scope.load = function(){
            $http.get("/profile/edit.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post.User = result.data.user;
                if(result.data.user.samaccountname != null){
                    $scope.isLdapAuth = true;
                }
            });
        };

        $scope.loadApiKey = function(){
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

        $scope.loadDateformats = function(){
            $http.get("/users/loadDateformats.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.dateformats = result.data.dateformats;
                $scope.post.User.dateformat = result.data.defaultDateFormat;
            });
        };

        $scope.load();
        $scope.loadDateformats();
        $scope.loadApiKey();

    });
