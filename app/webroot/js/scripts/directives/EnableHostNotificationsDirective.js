angular.module('openITCOCKPIT').directive('enableHostNotifications', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/enable_host_notifications.html',

        controller: function($scope){

            var objects = {};
            $scope.isEnableingHostNotifications = false;
            $scope.enableHostNotificationsType = 'hostOnly';

            $scope.setEnableHostNotificationsObjects = function(_objects){
                objects = _objects;
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
                $('#angularEnableHostNotificationsModal').modal('show');
                $scope.setEnableHostNotificationsObjects(objects);
            };
        }
    };
});