angular.module('openITCOCKPIT')
    .controller('ProfileEditController', function($scope, $http, $state, $stateParams, NotyService){
        $scope.init = true;
        $scope.apikeys = [];

        $scope.post = {
            User:{},
            Picture:{},
            Password:{}
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
            });
        };

        $scope.submitUser = function(){
            console.log($scope.post);
            $http.post("/profile/edit.json?angular=true",
                {User:$scope.post.User}
            ).then(function(result){
                NotyService.genericSuccess();
                $state.go('ProfileEdit');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.submitPicture = function(){
            console.log($scope.post);
            $http.post("/profile/edit.json?angular=true",
                {Picture:$scope.post.Picture}
            ).then(function(result){
                NotyService.genericSuccess();
                $state.go('ProfileEdit');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.submitPassword = function(){
            console.log($scope.post);
            $http.post("/profile/edit.json?angular=true",
                {Password:$scope.post.Password}
            ).then(function(result){
                NotyService.genericSuccess();
                $state.go('ProfileEdit');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.load();
        $scope.loadDateformats();
        $scope.loadApiKey();

    });
