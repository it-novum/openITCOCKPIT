angular.module('openITCOCKPIT').directive('pushNotifications', function($http, PushNotificationsService, $state){
    return {
        restrict: 'E',

        controller: function($scope){

            $scope.userId = null;

            $scope.Notification = null;

            $scope.hasPermission = false;

            var checkBrowserSupport = function(){
                if(!("Notification" in window)){
                    console.warn('Browser does not support Notifications');
                    return false;
                }
                return true;
            };

            var checkPermissions = function(){
                if(Notification.permission === "granted"){
                    $scope.hasPermission = true;
                    return true;
                }

                if(Notification.permission !== 'denied'){
                    Notification.requestPermission(function(permission){
                        // If the user accepts, let's create a notification
                        if(permission === "granted"){
                            $scope.hasPermission = true;
                        }
                    });
                }

            };


            $scope.connectToNotificationPushServer = function(){
                $http.get("/angular/push_configuration.json", {
                    params: {
                        'angular': true,
                        'includeUser': true
                    }
                }).then(function(result){
                    $scope.userId = result.data.user.id;

                    $scope.websocketConfig = result.data.websocket;

                    //Only connect, if the user has >= 1 contacts using browser push notifications
                    if(result.data.user.hasPushContact){
                        PushNotificationsService.setUrl($scope.websocketConfig['PUSH_NOTIFICATIONS.URL']);
                        PushNotificationsService.setApiKey($scope.websocketConfig['SUDO_SERVER.API_KEY']);

                        PushNotificationsService.setUserId($scope.userId);
                        PushNotificationsService.onResponse($scope.gotMessage);

                        PushNotificationsService.connect();
                    }
                });

            };

            if(checkBrowserSupport()){
                checkPermissions();
            }

            $scope.$watch('hasPermission', function(){
                if($scope.hasPermission === true){
                    $scope.connectToNotificationPushServer();
                }
            });

            $scope.gotMessage = function(event){
                if(typeof event.data !== "undefined"){
                    var data = JSON.parse(event.data);

                    var options = {
                        body: data.message
                    };

                    if(data.data.icon !== null){
                        options['icon'] = data.data.icon;
                    }

                    var notification = new Notification(data.data.title, options);

                    var url = $state.href('HostsBrowser', {id: data.data.hostUuid});
                    if(data.data.type === 'service'){
                        url = $state.href('ServicesBrowser', {id: data.data.serviceUuid});
                    }

                    notification.onclick = function(event){
                        event.preventDefault(); // prevent the browser from focusing the Notification's tab
                        window.open(url, '_blank');
                    }
                }
            }


        }
    };
});
