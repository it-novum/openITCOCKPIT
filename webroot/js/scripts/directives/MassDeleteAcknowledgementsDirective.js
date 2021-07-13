angular.module('openITCOCKPIT').directive('massDeleteAcknowledgements', function($http, $filter, $timeout){
    return {
        restrict: 'E',
        replace: true,
        templateUrl: '/angular/mass_delete_acknowledgements.html',

        controller: function($scope){

            $scope.percentage = 0;
            $scope.isDeleting = false;

            $scope.myDeleteUrl = $scope.deleteUrl;

            var objects = {};
            var callbackName = false;

            $scope.setObjectsForMassAcknowledgementsDelete = function(_objects){
                objects = _objects;
                $scope.objects = objects;
            };

            $scope.setCallbackForMassAcknowledgementsDelete = function(_callback){
                callbackName = _callback;
            };

            $scope.doDeleteAcknowledgements = function(){
                $scope.isDeleting = true;
                var count = Object.keys(objects).length;
                var i = 0;

                for(var id in objects){
                    var data = objects[id];

                    $http.post($scope.myDeleteUrl + id + ".json", data).then(
                        function(result){
                            i++;
                            $scope.percentage = Math.round(i / count * 100);

                            if(i === count){
                                $scope.isDeleting = false;
                                $scope.percentage = 0;

                                $('#angularMassDeleteAcknowledgements').modal('hide');

                                //Call callback function if given
                                if(callbackName){
                                    $scope[callbackName]();
                                }else{
                                    $scope.load();
                                }
                            }
                        });
                }
            };

        },

        link: function($scope, element, attr){
            $scope.confirmAcknowledgementsDelete = function(objects){

                if(attr.hasOwnProperty('deleteUrl')){
                    $scope.myDeleteUrl = attr.deleteUrl;
                }

                if(attr.hasOwnProperty('callback')){
                    $scope.setCallbackForMassAcknowledgementsDelete(attr.callback);
                }

                $scope.setObjectsForMassAcknowledgementsDelete(objects);
                $('#angularMassDeleteAcknowledgements').modal('show');
            };
        }
    };
});
