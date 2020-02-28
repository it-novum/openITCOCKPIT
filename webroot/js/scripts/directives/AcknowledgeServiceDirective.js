angular.module('openITCOCKPIT').directive('acknowledgeService', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/acknowledge_service.html',

        controller: function($scope){

            $scope.doAck = false;
            $scope.ack = {
                comment: '',
                sticky: false,
                error: false
            };

            var objects = {};
            var author = '';

            var callbackName = false;

            $scope.setServiceAckObjects = function(_objects){
                objects = _objects;
            };

            $scope.setServiceAckAuthor = function(_author){
                author = _author;
            };

            $scope.setServiceAckCallback = function(_callback){
                callbackName = _callback;
            };

            $scope.doAcknowledgeService = function(){
                $scope.ack.error = false;
                if($scope.ack.comment === ''){
                    $scope.ack.error = true;
                    return false;
                }

                var sticky = 0;
                if($scope.ack.sticky === true){
                    sticky = 2;
                }

                var count = Object.keys(objects).length;
                var i = 0;
                $scope.percentage = 0;
                $scope.doAck = true;

                $scope.percentage = Math.round(i / count * 100);
                for(var id in objects){
                    var object = objects[id];
                    i++;
                    $scope.percentage = Math.round(i / count * 100);
                    SudoService.send(SudoService.toJson('submitServicestateAck', [
                        object.Host.uuid,
                        object.Service.uuid,
                        $scope.ack.comment,
                        author,
                        sticky
                    ]));
                }

                //Call callback function if given
                if(callbackName){
                    $scope[callbackName]();
                }
                $timeout(function(){
                    $scope.doAck = false;
                    $scope.percentage = 0;
                    $('#angularacknowledgeServiceModal').modal('hide');
                }, 500);
            };

        },

        link: function($scope, element, attr){
            $scope.acknowledgeService = function(objects){
                if(Object.keys(objects).length === 0){
                    return;
                }

                if(attr.hasOwnProperty('callback')){
                    $scope.setServiceAckCallback(attr.callback);
                }

                $scope.setServiceAckObjects(objects);

                $scope.setServiceAckAuthor(attr.author);

                $('#angularacknowledgeServiceModal').modal('show');
            };
        }
    };
});
