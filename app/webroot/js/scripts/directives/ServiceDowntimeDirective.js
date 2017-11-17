angular.module('openITCOCKPIT').directive('serviceDowntime', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/downtime_service.html',

        controller: function($scope){

            var date = new Date();

            $scope.doDowntime = false;
            $scope.downtime = {
                comment: '',
                from_date: date.getDate()+'.'+date.getMonth()+date.getFullYear(),
                from_time: date.getHours()+':'+date.getMinutes(),
                error: false
            };

            var objects = {};
            var author = '';

            $scope.setObjects = function(_objects){
                objects = _objects;
            };

            $scope.setAuthor = function(_author){
                author = _author;
            };

            $scope.doServiceDowntime = function(){
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
                $scope.doDowntime = true;

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
                $timeout(function(){
                    $scope.doDowntime = false;
                    $('#angularServiceDowntimeModal').modal('hide');
                }, 500);
            };

        },

        link: function($scope, element, attr){
            $scope.serviceDowntime = function(objects){
                if(Object.keys(objects).length === 0){
                    return;
                }
                $scope.setObjects(objects);

                $scope.setAuthor(attr.author);

                $('#angularServiceDowntimeModal').modal('show');
            };
        }
    };
});