angular.module('openITCOCKPIT').directive('massDeleteServiceDowntimes', function($http, $filter, $timeout){
    return {
        restrict: 'E',
        replace: true,
        templateUrl: '/angular/mass_delete_service_downtimes.html',

        controller: function($scope){

            $scope.includeServices = true;
            $scope.percentage = 0;
            $scope.isDeleting = false;

            var objects = {};
            $scope.myDeleteUrl = $scope.deleteUrl;

            $scope.setObjectsForMassServiceDowntimeDelete = function(_objects){
                objects = _objects;
            };

            $scope.setCallbackForMassServiceDowntimeDelete = function(_callback){
                callbackName = _callback;
            };


            $scope.doDeleteServiceDowntime = function(){
                $scope.isDeleting = true;
                var count = Object.keys(objects).length;
                var i = 0;

                for(var id in objects){
                    var data = {
                        type: 'service'
                    };
                    $http.post($scope.myDeleteUrl + id + ".json", data).then(
                        function(result){
                            i++;
                            $scope.percentage = Math.round(i / count * 100);

                            if(i === count){
                                $scope.isDeleting = false;
                                $scope.percentage = 0;
                                //Call callback function if given
                                if(callbackName){
                                    $scope[callbackName]();
                                }else{
                                    $scope.load();
                                }
                                $('#angularMassDeleteServiceDowntimes').modal('hide');
                            }
                        });
                }
            };

        },

        link: function($scope, element, attr){
            $scope.confirmServiceDowntimeDelete = function(objects){

                if(attr.hasOwnProperty('deleteUrl')){
                    $scope.myDeleteUrl = attr.deleteUrl;
                }

                if(attr.hasOwnProperty('callback')){
                    $scope.setCallbackForMassServiceDowntimeDelete(attr.callback);
                }

                $scope.setObjectsForMassServiceDowntimeDelete(objects);
                $('#angularMassDeleteServiceDowntimes').modal('show');
            };
        }
    };
});