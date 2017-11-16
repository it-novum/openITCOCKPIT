angular.module('openITCOCKPIT').directive('rescheduleService', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/executing.html',

        controller: function($scope){

            var objects;

            $scope.setObjects = function(_objects){
                objects = _objects;
            };

            $scope.doReschedule = function(){
                var count = Object.keys(objects).length;
                var i = 0;

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
                $timeout(function(){
                    $('#angularExecutingModal').modal('hide');
                }, 500);
            };

        },

        link: function($scope, element, attr){
            $scope.reschedule = function(objects){
                $scope.setObjects(objects);
                $('#angularExecutingModal').modal('show');
                $scope.doReschedule();
            };
        }
    };
});