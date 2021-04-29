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
                $scope.loadDowntimeDefaults();
            };

            $scope.setServiceDowntimeAuthor = function(_author){
                author = _author;
            };

            $scope.setServiceDowntimeCallback = function(_callback){
                callbackName = _callback;
            };

            $scope.loadDowntimeDefaults = function(){
                $http.get("/angular/getDowntimeData.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    var now = new Date(result.data.defaultValues.from_date_js);
                    var toDate = new Date(result.data.defaultValues.to_date_js);

                    $scope.downtimeModal.from_date = now;
                    $scope.downtimeModal.from_time = new Date(now.getFullYear(), now.getMonth(), now.getDate(), now.getHours(), now.getMinutes(), 0, 0);
                    $scope.downtimeModal.to_date = toDate;
                    $scope.downtimeModal.to_time = new Date(toDate.getFullYear(), toDate.getMonth(), toDate.getDate(), toDate.getHours(), toDate.getMinutes(), 0, 0);
                });
            };

            $scope.doServiceDowntime = function(){
                var downtimeModal = JSON.parse(JSON.stringify($scope.downtimeModal)); // Remove JS binding
                downtimeModal.from_date = date('d.m.Y', $scope.downtimeModal.from_date);
                downtimeModal.from_time = date('H:i', $scope.downtimeModal.from_time);
                downtimeModal.to_date = date('d.m.Y', $scope.downtimeModal.to_date);
                downtimeModal.to_time = date('H:i', $scope.downtimeModal.to_time);


                $http.post("/downtimes/validateDowntimeInputFromAngular.json?angular=true",
                    downtimeModal
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
                            result.data.start, //Converted user time to server time
                            result.data.end, //Converted user time to server time
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

                $(".page-inner").append($('#angularServiceDowntimeModal'));
                $('#angularServiceDowntimeModal').modal('show');
            };
        }
    };
});
