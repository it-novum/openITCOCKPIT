angular.module('openITCOCKPIT').directive('disableHostNotifications', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/disable_host_notifications.html',

        controller: function($scope){

            var objects = {};
            $scope.isDisableingHostNotifications = false;
            $scope.disableHostNotificationsType = 'hostOnly';

            var callbackName = false;

            $scope.setDisableHostNotificationsObjects = function(_objects){
                objects = _objects;
            };

            $scope.setDisableHostNotificationsCallback = function(_callback){
                callbackName = _callback;
            };

            $scope.doDisableHostNotifications = function(){
                var count = Object.keys(objects).length;
                var i = 0;
                $scope.percentage = 0;
                $scope.isDisableingHostNotifications = true;


                $scope.percentage = Math.round(i / count * 100);
                for(var id in objects){
                    var object = objects[id];
                    i++;
                    $scope.percentage = Math.round(i / count * 100);
                    SudoService.send(SudoService.toJson('submitDisableHostNotifications', [
                        object.Host.uuid,
                        $scope.disableHostNotificationsType
                    ]));
                }

                //Call callback function if given
                if(callbackName){
                    $scope[callbackName]();
                }
                $timeout(function(){
                    $scope.isDisableingHostNotifications = false;
                    $scope.percentage = 0;
                    $('#angularDisableHostNotificationsModal').modal('hide');
                }, 500);
            };

        },

        link: function($scope, element, attr){
            $scope.disableHostNotifications = function(objects){
                if(Object.keys(objects).length === 0){
                    return;
                }

                if(attr.hasOwnProperty('callback')){
                    $scope.setDisableHostNotificationsCallback(attr.callback);
                }

                $('#angularDisableHostNotificationsModal').modal('show');
                $scope.setDisableHostNotificationsObjects(objects);
            };
        }
    };
});