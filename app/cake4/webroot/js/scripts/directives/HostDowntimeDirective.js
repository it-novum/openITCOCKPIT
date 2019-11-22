angular.module('openITCOCKPIT').directive('hostDowntime', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/downtime_host.html',

        controller: function($scope){


            $scope.doDowntime = false;
            $scope.downtimeModal = {
                comment: '',
                from_date: '',
                from_time: '',
                to_date: '',
                to_time: '',
                hostDowntimeType: "1"
            };

            var objects = {};
            var author = '';
            var callbackName = false;

            $scope.setHostDowntimeObjects = function(_objects){
                objects = _objects;
            };

            $scope.setHostDowntimeAuthor = function(_author){
                author = _author;
            };

            $scope.setHostDowntimeCallback = function(_callback){
                callbackName = _callback;
            };

            $scope.loadHostdowntimeDefaultSelection = function(){
                $http.get("/angular/downtime_host.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.downtimeModal.hostDowntimeType = String(result.data.preselectedDowntimetype);
                });
            };

            $scope.doHostDowntime = function(){


                $http.post("/downtimes/validateDowntimeInputFromAngular.json?angular=true",
                    $scope.downtimeModal
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
                            $scope.downtimeModal.from_date + ' ' + $scope.downtimeModal.from_time,
                            $scope.downtimeModal.to_date + ' ' + $scope.downtimeModal.to_time,
                            $scope.downtimeModal.comment,
                            author,
                            $scope.downtimeModal.hostDowntimeType
                        ]));
                    }

                    //Call callback function if given
                    if(callbackName){
                        $scope[callbackName]();
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

                if(attr.hasOwnProperty('callback')){
                    $scope.setHostDowntimeCallback(attr.callback);
                }

                $scope.setHostDowntimeObjects(objects);

                $scope.setHostDowntimeAuthor(attr.author);

                $scope.loadHostdowntimeDefaultSelection();

                $('#angularHostDowntimeModal').modal('show');
            };
        }
    };
});