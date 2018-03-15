angular.module('openITCOCKPIT').directive('enableServiceNotifications', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/enable_service_notifications.html',

        controller: function($scope){

            var objects = {};
            $scope.isEnableingServiceNotifications = false;

            var callbackName = false;

            $scope.setEnableServiceNotificationsObjects = function(_objects){
                objects = _objects;
            };

            $scope.setEnableServiceNotificationsCallback = function(_callback){
                callbackName = _callback;
            };

            $scope.doEnableServiceNotifications = function(){
                var count = Object.keys(objects).length;
                var i = 0;
                $scope.percentage = 0;
                $scope.isEnableingServiceNotifications = true;


                $scope.percentage = Math.round(i / count * 100);
                for(var id in objects){
                    var object = objects[id];
                    i++;
                    $scope.percentage = Math.round(i / count * 100);
                    SudoService.send(SudoService.toJson('submitEnableServiceNotifications', [
                        object.Host.uuid,
                        object.Service.uuid
                    ]));
                }

                //Call callback function if given
                if(callbackName){
                    $scope[callbackName]();
                }
                $timeout(function(){
                    $scope.isEnableingServiceNotifications = false;
                    $scope.percentage = 0;
                    $('#angularEnableServiceNotificationsModal').modal('hide');
                }, 500);
            };

        },

        link: function($scope, element, attr){
            $scope.enableServiceNotifications = function(objects){
                if(Object.keys(objects).length === 0){
                    return;
                }

                if(attr.hasOwnProperty('callback')){
                    $scope.setEnableServiceNotificationsCallback(attr.callback);
                }

                $('#angularEnableServiceNotificationsModal').modal('show');
                $scope.setEnableServiceNotificationsObjects(objects);
            };
        }
    };
});