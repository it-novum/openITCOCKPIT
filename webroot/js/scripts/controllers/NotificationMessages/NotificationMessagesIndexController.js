angular.module('openITCOCKPIT')
    .controller('NotificationMessagesIndexController', function($scope, $http){

        $scope.init = true;
        console.log('Notification Messages index is loaded');
        // if('Notification' in window){
        //     if(window.Notification.permission == 'granted'){
        //
        //         new window.Notification('checkNotification');
        //         //console.log($scope.dateTime);
        //
        //
        //     }
        //
        // }

        $scope.load = function(){
            $http.get("/Notificationmessages/index.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.messages = result.data.messages;
                console.log($scope.messages);

            });
        }

        $scope.load();

    });
