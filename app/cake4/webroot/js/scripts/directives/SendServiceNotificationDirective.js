angular.module('openITCOCKPIT').directive('sendServiceNotification', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/send_service_notification.html',

        controller: function($scope){

            var objects = {};
            var author = 'Unknown';
            var callbackName = false;

            $scope.isSubmittingServiceNotification = false;
            $scope.sendServiceNotification = {
                comment: 'Test notification',
                force: true,
                broadcast: true
            };

            $scope.setServiceNotificationObjects = function(_objects){
                objects = _objects;
            };

            $scope.setServiceNotificationAuthor = function(_author){
                author = _author;
            };

            $scope.setServiceNotificationCallback = function(_callback){
                callbackName = _callback;
            };


            $scope.doSubmitServiceNotification = function(){
                var count = Object.keys(objects).length;
                var i = 0;
                $scope.percentage = 0;
                $scope.isSubmittingServiceNotification = true;


                $scope.percentage = Math.round(i / count * 100);
                for(var id in objects){
                    var object = objects[id];
                    i++;
                    $scope.percentage = Math.round(i / count * 100);

                    var type = 0;
                    if($scope.sendServiceNotification.force){
                        type = 1;
                    }

                    if($scope.sendServiceNotification.broadcast){
                        type = 2;
                    }

                    if($scope.sendServiceNotification.force && $scope.sendServiceNotification.broadcast){
                        type = 3;
                    }

                    SudoService.send(SudoService.toJson('sendCustomServiceNotification', [
                        object.Host.uuid,
                        object.Service.uuid,
                        type,
                        author,
                        $scope.sendServiceNotification.comment
                    ]));
                }
                //Call callback function if given
                if(callbackName){
                    $scope[callbackName]();
                }
                $timeout(function(){
                    $scope.isSubmittingServiceNotification = false;
                    $scope.percentage = 0;
                    $('#angularSubmitServiceNotificationModal').modal('hide');
                }, 500);
            };

        },

        link: function($scope, element, attr){
            $scope.submitServiceNotification = function(objects){
                if(Object.keys(objects).length === 0){
                    return;
                }

                if(attr.hasOwnProperty('callback')){
                    $scope.setServiceNotificationCallback(attr.callback);
                }


                $('#angularSubmitServiceNotificationModal').modal('show');
                $scope.setServiceNotificationObjects(objects);

                $scope.setServiceNotificationAuthor(attr.author);

            };
        }
    };
});