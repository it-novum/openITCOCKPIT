angular.module('openITCOCKPIT').directive('editApikeyDirective', function($http){
    return {
        restrict: 'E',
        templateUrl: '/profile/edit_apikey.html',

        controller: function($scope){

            var editApiKeyId = null;

            $scope.setEditApiKeyId = function(apiKeyId){
                editApiKeyId = apiKeyId;
            };

            $scope.loadApiKeyToEdit = function(){
                $http.get("/profile/apikey.json", {
                    params: {
                        'angular': true,
                        'id': editApiKeyId
                    }
                }).then(function(result){
                    $scope.currentApiKey = result.data.apikey;
                });
            };

            $scope.updateApiKey = function(){
                $http.post("/profile/apikey.json?angular=true", {
                    Apikey: {
                        id: $scope.currentApiKey.id,
                        description: $scope.currentApiKey.description
                    }
                }).then(function(result){

                    editApiKeyId = null;
                    $scope.currentApiKey = {};
                    $scope.load();
                    $('#angularEditApiKeyModal').modal('hide');

                }, function errorCallback(result){
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });
            };

            $scope.deleteApiKey = function(){
                $http.post("/profile/delete_apikey/" + $scope.currentApiKey.id + "/.json?angular=true")
                    .then(function(result){
                        editApiKeyId = null;
                        $scope.currentApiKey = {};
                        $scope.load();
                        $('#angularEditApiKeyModal').modal('hide');
                    });
            };

        },

        link: function($scope, element, attr){
            $scope.editApiKey = function(apiKeyId){
                $scope.setEditApiKeyId(apiKeyId);
                $('#angularEditApiKeyModal').modal('show');
                $scope.loadApiKeyToEdit();
            }
        }
    };
});
