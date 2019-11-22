angular.module('openITCOCKPIT').directive('rescheduleService', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/executing.html?id=angularRescheduleServiceModal',

        controller: function($scope){

            var callbackName = false;

            $scope.setServiceRescheduleCallback = function(_callback){
                callbackName = _callback;
            };

            $scope.doReschedule = function(objects){
                var count = Object.keys(objects).length;
                var i = 0;
                $scope.percentage = 0;

                $scope.percentage = Math.round(i / count * 100);
                for(var id in objects){
                    var object = objects[id];
                    i++;
                    $scope.percentage = Math.round(i / count * 100);
                    SudoService.send(SudoService.toJson('rescheduleService', [
                        object.Host.uuid,
                        object.Service.uuid,
                        object.Host.satelliteId
                    ]));
                }
                //Call callback function if given
                if(callbackName){
                    $scope[callbackName]();
                }
                $timeout(function(){
                    $scope.percentage = 0;
                    $('#angularRescheduleServiceModal').modal('hide');
                }, 500);
            };

        },

        link: function($scope, element, attr){
            $scope.reschedule = function(objects){
                if(Object.keys(objects).length === 0){
                    return;
                }

                if(attr.hasOwnProperty('callback')){
                    $scope.setServiceRescheduleCallback(attr.callback);
                }
                $('#angularRescheduleServiceModal').modal('show');
                $scope.doReschedule(objects);
            };
        }
    };
});