angular.module('openITCOCKPIT').directive('enableNotifications', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/executing.html?id=angularEnableNotificationsModal',

        controller: function($scope){

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
                $timeout(function(){
                    $('#angularEnableNotificationsModal').modal('hide');
                }, 500);
            };

        },

        link: function($scope, element, attr){
            $scope.enableNotifications = function(objects){
                if(Object.keys(objects).length === 0){
                    return;
                }
                $('#angularEnableNotificationsModal').modal('show');
                $scope.doEnableNotifications(objects);
            };
        }
    };
});