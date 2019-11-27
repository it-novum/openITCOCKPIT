angular.module('openITCOCKPIT').directive('serviceDowntime', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/downtime_service.html',

        controller: function($scope){


            $scope.doDowntime = false;
            $scope.downtimeModal = {
                comment: '',
                from_date: '',
                from_time: '',
                to_date: '',
                to_time: ''
            };

            var objects = {};
            var author = '';

            var callbackName = false;

            $scope.setServiceDowntimeObjects = function(_objects){
                objects = _objects;
            };

            $scope.setServiceDowntimeAuthor = function(_author){
                author = _author;
            };

            $scope.setServiceDowntimeCallback = function(_callback){
                callbackName = _callback;
            };

            $scope.doServiceDowntime = function(){


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
                        SudoService.send(SudoService.toJson('submitServiceDowntime', [
                            object.Host.uuid,
                            object.Service.uuid,
                            $scope.downtimeModal.from_date + ' ' + $scope.downtimeModal.from_time,
                            $scope.downtimeModal.to_date + ' ' + $scope.downtimeModal.to_time,
                            $scope.downtimeModal.comment,
                            author
                        ]));
                    }
                    //Call callback function if given
                    if(callbackName){
                        $scope[callbackName]();
                    }
                    $timeout(function(){
                        $scope.doDowntime = false;
                        $scope.percentage = 0;
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

                if(attr.hasOwnProperty('callback')){
                    $scope.setServiceDowntimeCallback(attr.callback);
                }

                $scope.setServiceDowntimeObjects(objects);

                $scope.setServiceDowntimeAuthor(attr.author);

                $('#angularServiceDowntimeModal').modal('show');
            };
        }
    };
});