angular.module('openITCOCKPIT').directive('enableNotifications', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/executing.html?id=angularEnableNotificationsModal',

        controller: function($scope){

            var callbackName = false;

            $scope.setEnableNotificationsCallback = function(_callback){
                callbackName = _callback;
            };

            $scope.doEnableNotifications = function(objects){
                var count = Object.keys(objects).length;
                var i = 0;
                $scope.percentage = 0;

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
                    $scope.percentage = 0;
                    $('#angularEnableNotificationsModal').modal('hide');
                }, 500);
            };

        },

        link: function($scope, element, attr){
            $scope.enableNotifications = function(objects){
                if(Object.keys(objects).length === 0){
                    return;
                }

                if(attr.hasOwnProperty('callback')){
                    $scope.setEnableNotificationsCallback(attr.callback);
                }

                $('#angularEnableNotificationsModal').modal('show');
                $scope.doEnableNotifications(objects);
            };
        }
    };
});