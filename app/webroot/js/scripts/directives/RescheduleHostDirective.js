angular.module('openITCOCKPIT').directive('rescheduleHost', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/reschedule_host.html',

        controller: function($scope){

            var objects = {};
            $scope.isReschedulingHosts = false;
            $scope.hostReschedulingType = 'hostAndServices';

            $scope.setHostRescheduleObjects = function(_objects){
                objects = _objects;
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
                $('#angularRescheduleHostModal').modal('show');
                $scope.setHostRescheduleObjects(objects);
            };
        }
    };
});