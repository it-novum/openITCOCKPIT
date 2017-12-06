angular.module('openITCOCKPIT').directive('disableNotifications', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/executing.html?id=angularDisableNotificationsModal',

        controller: function($scope){

            $scope.doDisableNotifications = function(objects){
                var count = Object.keys(objects).length;
                var i = 0;
                $scope.percentage = 0;

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
                $timeout(function(){
                    $('#angularDisableNotificationsModal').modal('hide');
                }, 500);
            };

        },

        link: function($scope, element, attr){
            $scope.disableNotifications = function(objects){
                if(Object.keys(objects).length === 0){
                    return;
                }
                $('#angularDisableNotificationsModal').modal('show');
                $scope.doDisableNotifications(objects);
            };
        }
    };
});