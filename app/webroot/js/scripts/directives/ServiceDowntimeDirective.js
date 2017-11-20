angular.module('openITCOCKPIT').directive('serviceDowntime', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/downtime_service.html',

        controller: function($scope){


            $scope.doDowntime = false;
            $scope.downtime = {
                comment: '',
                from_date: '',
                from_time: '',
                to_date: '',
                to_time: ''
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


                $http.post("/downtimes/validateDowntimeInputFromAngular.json?angular=true",
                    $scope.downtime
                ).then(function(result){
                    var count = Object.keys(objects).length;
                    var i = 0;
                    $scope.percentage = 0;
                    $scope.doDowntime = true;

                    $scope.percentage = Math.round(i / count * 100);
                    for(var id in objects){
                        var object = objects[id];
                        i++;
                        $scope.percentage = Math.round(i / count * 100);
                        SudoService.send(SudoService.toJson('submitServiceDowntime', [
                            object.Host.uuid,
                            object.Service.uuid,
                            $scope.downtime.from_date + ' ' + $scope.downtime.from_time,
                            $scope.downtime.to_date + ' ' + $scope.downtime.to_time,
                            $scope.downtime.comment,
                            author,
                        ]));
                    }
                    $timeout(function(){
                        $scope.doDowntime = false;
                        $('#angularServiceDowntimeModal').modal('hide');
                    }, 500);
                }, function errorCallback(result){
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });

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