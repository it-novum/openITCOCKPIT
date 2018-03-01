angular.module('openITCOCKPIT').directive('sendHostNotification', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/send_host_notification.html',

        controller: function($scope){

            var objects = {};
            var author = 'Unknown';
            $scope.isSubmittingHostNotification = false;
            $scope.sendHostNotification = {
                comment: 'Test notification',
                force: true,
                broadcast: true
            };

            $scope.setHostNotificationObjects = function(_objects){
                objects = _objects;
            };

            $scope.setHostNotificationAuthor = function(_author){
                author = _author;
            };


            $scope.doSubmitHostNotification = function(){
                var count = Object.keys(objects).length;
                var i = 0;
                $scope.percentage = 0;
                $scope.isSubmittingHostNotification = true;


                $scope.percentage = Math.round(i / count * 100);
                for(var id in objects){
                    var object = objects[id];
                    i++;
                    $scope.percentage = Math.round(i / count * 100);

                    var type = 0;
                    if($scope.sendHostNotification.force){
                        type = 1;
                    }

                    if($scope.sendHostNotification.broadcast){
                        type = 2;
                    }

                    if($scope.sendHostNotification.force && $scope.sendHostNotification.broadcast){
                        type = 3;
                    }

                    SudoService.send(SudoService.toJson('sendCustomHostNotification', [
                        object.Host.uuid,
                        type,
                        author,
                        $scope.sendHostNotification.comment
                    ]));
                }
                $timeout(function(){
                    $scope.isSubmittingHostNotification = false;
                    $scope.percentage = 0;
                    $('#angularSubmitHostNotificationModal').modal('hide');
                }, 500);
            };

        },

        link: function($scope, element, attr){
            $scope.submitHostNotification = function(objects){
                if(Object.keys(objects).length === 0){
                    return;
                }
                $('#angularSubmitHostNotificationModal').modal('show');
                $scope.setHostNotificationObjects(objects);

                $scope.setHostNotificationAuthor(attr.author);

            };
        }
    };
});