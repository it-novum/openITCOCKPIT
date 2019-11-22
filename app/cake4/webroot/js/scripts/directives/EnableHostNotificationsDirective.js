angular.module('openITCOCKPIT').directive('enableHostNotifications', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/enable_host_notifications.html',

        controller: function($scope){

            var objects = {};
            $scope.isEnableingHostNotifications = false;
            $scope.enableHostNotificationsType = 'hostOnly';

            var callbackName = false;

            $scope.setEnableHostNotificationsObjects = function(_objects){
                objects = _objects;
            };

            $scope.setEnableHostNotificationsCallback = function(_callback){
                callbackName = _callback;
            };

            $scope.doEnableHostNotifications = function(){
                var count = Object.keys(objects).length;
                var i = 0;
                $scope.percentage = 0;
                $scope.isEnableingHostNotifications = true;


                $scope.percentage = Math.round(i / count * 100);
                for(var id in objects){
                    var object = objects[id];
                    i++;
                    $scope.percentage = Math.round(i / count * 100);
                    SudoService.send(SudoService.toJson('submitEnableHostNotifications', [
                        object.Host.uuid,
                        $scope.enableHostNotificationsType
                    ]));
                }

                //Call callback function if given
                if(callbackName){
                    $scope[callbackName]();
                }
                $timeout(function(){
                    $scope.isEnableingHostNotifications = false;
                    $scope.percentage = 0;
                    $('#angularEnableHostNotificationsModal').modal('hide');
                }, 500);
            };

        },

        link: function($scope, element, attr){
            $scope.enableHostNotifications = function(objects){
                if(Object.keys(objects).length === 0){
                    return;
                }

                if(attr.hasOwnProperty('callback')){
                    $scope.setEnableHostNotificationsCallback(attr.callback);
                }

                $('#angularEnableHostNotificationsModal').modal('show');
                $scope.setEnableHostNotificationsObjects(objects);
            };
        }
    };
});