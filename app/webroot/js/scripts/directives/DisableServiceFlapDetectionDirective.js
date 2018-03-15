angular.module('openITCOCKPIT').directive('disableServiceFlapDetection', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/disable_service_flap_detection.html',

        controller: function($scope){

            var objects = {};
            var callbackName = false;

            $scope.isDisableingServiceFlapDetection = false;

            $scope.setDisableServiceFlapDetectionObjects = function(_objects){
                objects = _objects;
            };

            $scope.setDisableServiceFlapDetectionCallback = function(_callback){
                callbackName = _callback;
            };


            $scope.doDisableServiceFlapDetection = function(){
                var count = Object.keys(objects).length;
                var i = 0;
                $scope.percentage = 0;
                $scope.isDisableingServiceFlapDetection = true;


                $scope.percentage = Math.round(i / count * 100);
                for(var id in objects){
                    var object = objects[id];
                    i++;
                    $scope.percentage = Math.round(i / count * 100);
                    SudoService.send(SudoService.toJson('enableOrDisableServiceFlapdetection', [
                        object.Host.uuid,
                        object.Service.uuid,
                        0 //Disable flap detection
                    ]));
                }

                //Call callback function if given
                if(callbackName){
                    $scope[callbackName]();
                }
                $timeout(function(){
                    $scope.isDisableingServiceFlapDetection = false;
                    $scope.percentage = 0;
                    $('#angularDisableServiceFalpDetectionModal').modal('hide');
                }, 500);
            };

        },

        link: function($scope, element, attr){
            $scope.disableServiceFlapDetection = function(objects){
                if(Object.keys(objects).length === 0){
                    return;
                }

                if(attr.hasOwnProperty('callback')){
                    $scope.setDisableServiceFlapDetectionCallback(attr.callback);
                }

                $('#angularDisableServiceFalpDetectionModal').modal('show');
                $scope.setDisableServiceFlapDetectionObjects(objects);
            };
        }
    };
});