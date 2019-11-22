angular.module('openITCOCKPIT').directive('disableServiceNotifications', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/disable_service_notifications.html',

        controller: function($scope){

            var objects = {};
            $scope.isDisableingServiceNotifications = false;

            var callbackName = false;

            $scope.setDisableServiceNotificationsObjects = function(_objects){
                objects = _objects;
            };

            $scope.setDisableServiceNotificationsCallback = function(_callback){
                callbackName = _callback;
            };

            $scope.doDisableServiceNotifications = function(){
                var count = Object.keys(objects).length;
                var i = 0;
                $scope.percentage = 0;
                $scope.isDisableingServiceNotifications = true;


                $scope.percentage = Math.round(i / count * 100);
                for(var id in objects){
                    var object = objects[id];
                    i++;
                    $scope.percentage = Math.round(i / count * 100);
                    SudoService.send(SudoService.toJson('submitDisableServiceNotifications', [
                        object.Host.uuid,
                        object.Service.uuid
                    ]));
                }

                //Call callback function if given
                if(callbackName){
                    $scope[callbackName]();
                }
                $timeout(function(){
                    $scope.isDisableingServiceNotifications = false;
                    $scope.percentage = 0;
                    $('#angularDisableServiceNotificationsModal').modal('hide');
                }, 500);
            };

        },

        link: function($scope, element, attr){
            $scope.disableServiceNotifications = function(objects){
                if(Object.keys(objects).length === 0){
                    return;
                }

                if(attr.hasOwnProperty('callback')){
                    $scope.setDisableServiceNotificationsCallback(attr.callback);
                }

                $('#angularDisableServiceNotificationsModal').modal('show');
                $scope.setDisableServiceNotificationsObjects(objects);
            };
        }
    };
});