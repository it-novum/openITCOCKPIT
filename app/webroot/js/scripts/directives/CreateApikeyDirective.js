angular.module('openITCOCKPIT').directive('createApikeyDirective', function($http){
    return {
        restrict: 'E',
        templateUrl: '/profile/create_apikey.html',

        controller: function($scope){

            $scope.post = {
                Apikey: {
                    apikey: '',
                    description: ''
                }
            };

            $scope.saveApiKey = function(){
                $http.post("/profile/create_apikey.json?angular=true", $scope.post)
                    .then(function(result){
                        $scope.post = {
                            Apikey: {
                                apikey: '',
                                description: ''
                            }
                        };
                        $scope.newApiKey = null;
                        $scope.load();
                        $('#angularCreateApiKeyModal').modal('hide');
                    }, function errorCallback(result){
                        if(result.data.hasOwnProperty('error')){
                            $scope.errors = result.data.error;
                        }
                    });
            };

            $scope.getNewApiKey = function(){
                $http.get("/profile/create_apikey.json?angular=true")
                    .then(function(result){
                        $scope.newApiKey = result.data.apikey;
                        $scope.post.Apikey.apikey = $scope.newApiKey;
                    });
            }

        },

        link: function($scope, element, attr){
            $scope.createApiKey = function(apiKeyId){
                $scope.getNewApiKey();
                $('#angularCreateApiKeyModal').modal('show');
            }
        }
    };
});
