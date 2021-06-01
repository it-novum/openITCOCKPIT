angular.module('openITCOCKPIT')
    .controller('NotificationMessagesAddController', function($scope, $http, RedirectService){

        $scope.init = true;

        var clearForm = function(){
            var datetime = new Date();
            $scope.post = {
                messages : {
                    name : '',
                    message : '',
                    date: new Date(),
                    time:new Date(datetime.getFullYear(), datetime.getMonth(), datetime.getDate(), datetime.getHours(), datetime.getMinutes(), 0, 0)
                }
            }
        }
        clearForm();

        $scope.submit = function(){
            var post = JSON.parse(JSON.stringify($scope.post));
            post.messages.date = date('d.m.Y', $scope.post.messages.date);
            post.messages.time = date('H:i', $scope.post.messages.time);
            $http.post("/Notificationmessages/add.json?angular=true", post.messages).then(function(result){
                clearForm();
                RedirectService.redirectWithFallback('NotificationMessagesIndex');

            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }

            });
        }

    });
