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
                $http.get("/angular/getDowntimeData.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    var fromDate = $scope.parseDateTime(result.data.defaultValues.js_from);
                    $scope.downtimeModal.from_date = fromDate;
                    $scope.downtimeModal.from_time = fromDate;
                    var toDate = $scope.parseDateTime(result.data.defaultValues.js_to);
                    $scope.downtimeModal.to_date = toDate;
                    $scope.downtimeModal.to_time = toDate;
                    $scope.downtimeModal.hostDowntimeType = String(result.data.defaultValues.downtimetype_id);
                });
            };
            $scope.parseDateTime = function(jsStringData) {
                var splitData = jsStringData.split(',');
                var date = new Date(splitData[0], splitData[1] - 1, splitData[2]);
                date.setHours(splitData[3], splitData[4], 0);
                return date;
            };

            $scope.doHostDowntime = function(){

                var POSTParams = {
                    comment: $scope.downtimeModal.comment,
                    from_date: date('d.m.Y',$scope.downtimeModal.from_date.getTime() / 1000),
                    from_time: date('H:i', $scope.downtimeModal.from_time.getTime() / 1000),
                    to_date: date('d.m.Y',$scope.downtimeModal.to_date.getTime() / 1000),
                    to_time: date('H:i', $scope.downtimeModal.to_time.getTime() / 1000),
                    hostDowntimeType: $scope.downtimeModal.hostDowntimeType
                };
                $http.post("/downtimes/validateDowntimeInputFromAngular.json?angular=true",
                    POSTParams
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
                            result.data.start, //Converted user time to server time
                            result.data.end, //Converted user time to server time
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
