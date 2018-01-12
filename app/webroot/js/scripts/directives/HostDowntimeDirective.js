angular.module('openITCOCKPIT').directive('hostDowntime', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/downtime_host.html',

        controller: function($scope){


            $scope.doDowntime = false;
            $scope.downtime = {
                comment: '',
                from_date: '',
                from_time: '',
                to_date: '',
                to_time: '',
                hostDowntimeType: "1"
            };

            var objects = {};
            var author = '';

            $scope.setObjects = function(_objects){
                objects = _objects;
            };

            $scope.setAuthor = function(_author){
                author = _author;
            };

            $scope.loadHostdowntimeDefaultSelection = function(){
                $http.get("/angular/downtime_host.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.downtime.hostDowntimeType = String(result.data.preselectedDowntimetype);
                });
            };

            $scope.doHostDowntime = function(){


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
                        SudoService.send(SudoService.toJson('submitHostDowntime', [
                            object.Host.uuid,
                            $scope.downtime.from_date + ' ' + $scope.downtime.from_time,
                            $scope.downtime.to_date + ' ' + $scope.downtime.to_time,
                            $scope.downtime.comment,
                            author,
                            $scope.downtime.hostDowntimeType
                        ]));
                    }
                    $timeout(function(){
                        $scope.doDowntime = false;
                        $scope.percentage = 0;
                        $('#angularHostDowntimeModal').modal('hide');
                    }, 500);
                }, function errorCallback(result){
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });

            };

        },

        link: function($scope, element, attr){
            $scope.hostDowntime = function(objects){
                if(Object.keys(objects).length === 0){
                    return;
                }
                $scope.setObjects(objects);

                $scope.setAuthor(attr.author);

                $scope.loadHostdowntimeDefaultSelection();

                $('#angularHostDowntimeModal').modal('show');
            };
        }
    };
});