angular.module('openITCOCKPIT').directive('rescheduleHost', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/reschedule_host.html',

        controller: function($scope){

            var objects = {};

            var callbackName = false;

            $scope.isReschedulingHosts = false;
            $scope.hostReschedulingType = 'hostAndServices';

            $scope.setHostRescheduleObjects = function(_objects){
                objects = _objects;
            };

            $scope.setHostRescheduleCallback = function(_callback){
                callbackName = _callback;
            };

            $scope.doHostReschedule = function(){
                var count = Object.keys(objects).length;
                var i = 0;
                $scope.percentage = 0;
                $scope.isReschedulingHosts = true;


                $scope.percentage = Math.round(i / count * 100);
                for(var id in objects){
                    var object = objects[id];
                    i++;
                    $scope.percentage = Math.round(i / count * 100);
                    SudoService.send(SudoService.toJson('rescheduleHost', [
                        object.Host.uuid,
                        $scope.hostReschedulingType,
                        object.Host.satelliteId
                    ]));
                }

                //Call callback function if given
                if(callbackName){
                    $scope[callbackName]();
                }

                $timeout(function(){
                    $scope.isReschedulingHosts = false;
                    $scope.percentage = 0;
                    $('#angularRescheduleHostModal').modal('hide');
                }, 500);
            };

        },

        link: function($scope, element, attr){
            $scope.rescheduleHost = function(objects){
                if(Object.keys(objects).length === 0){
                    return;
                }

                if(attr.hasOwnProperty('callback')){
                    $scope.setHostRescheduleCallback(attr.callback);
                }

                $('#angularRescheduleHostModal').modal('show');
                $scope.setHostRescheduleObjects(objects);
            };
        }
    };
});