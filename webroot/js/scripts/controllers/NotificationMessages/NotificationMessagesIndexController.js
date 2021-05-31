angular.module('openITCOCKPIT')
    .controller('NotificationMessagesIndexController', function($scope, $http){

        $scope.init = true;
        var genericError = function(){
            new Noty({
                theme: 'metroui',
                type: 'error',
                text: 'Error while deleting message',
                timeout: 3500
            }).show();
        };


        $scope.load = function(){
            $http.get("/Notificationmessages/index.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.messages = result.data.messages;

            });
        }

        // delete method
        $scope.deleteMessage = function(id){
            postData = {
                Message: {
                    id: id
                }
            };
            $http.post("/Notificationmessages/deleteMessage/.json?angular=true", postData).then(
                function(result){
                    if(result.data.hasOwnProperty('success')){
                        $scope.successMessage = result.data.success;
                        var genericSuccess = function(){
                            new Noty({
                                theme: 'metroui',
                                type: 'success',
                                text: $scope.successMessage,
                                timeout: 3500
                            }).show();
                        };
                        genericSuccess();
                        $scope.load();
                    }
                }, function errorCallback(result){
                    genericError();
                });
        };

        $scope.load();

    });
