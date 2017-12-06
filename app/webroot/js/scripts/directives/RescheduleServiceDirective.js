angular.module('openITCOCKPIT').directive('rescheduleService', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/executing.html?id=angularRescheduleServiceModal',

        controller: function($scope){

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
                $timeout(function(){
                    $('#angularRescheduleServiceModal').modal('hide');
                }, 500);
            };

        },

        link: function($scope, element, attr){
            $scope.reschedule = function(objects){
                if(Object.keys(objects).length === 0){
                    return;
                }
                $('#angularRescheduleServiceModal').modal('show');
                $scope.doReschedule(objects);
            };
        }
    };
});