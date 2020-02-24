angular.module('openITCOCKPIT').directive('enableServiceFlapDetection', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/enable_service_flap_detection.html',

        controller: function($scope){

            var objects = {};
            var callbackName = false;

            $scope.isEnableingServiceFlapDetection = false;

            $scope.setEnableServiceFlapDetectionObjects = function(_objects){
                objects = _objects;
            };

            $scope.setEnableServiceFlapDetectionCallback = function(_callback){
                callbackName = _callback;
            };

            $scope.doEnableServiceFlapDetection = function(){
                var count = Object.keys(objects).length;
                var i = 0;
                $scope.percentage = 0;
                $scope.isEnableingServiceFlapDetection = true;


                $scope.percentage = Math.round(i / count * 100);
                for(var id in objects){
                    var object = objects[id];
                    i++;
                    $scope.percentage = Math.round(i / count * 100);
                    SudoService.send(SudoService.toJson('enableOrDisableServiceFlapdetection', [
                        object.Host.uuid,
                        object.Service.uuid,
                        1 //Enable flap detection
                    ]));
                }

                //Call callback function if given
                if(callbackName){
                    $scope[callbackName]();
                }
                $timeout(function(){
                    $scope.isEnableingServiceFlapDetection = false;
                    $scope.percentage = 0;
                    $('#angularEnableServiceFalpDetectionModal').modal('hide');
                }, 500);
            };

        },

        link: function($scope, element, attr){
            $scope.enableServiceFlapDetection = function(objects){
                if(Object.keys(objects).length === 0){
                    return;
                }

                if(attr.hasOwnProperty('callback')){
                    $scope.setEnableServiceFlapDetectionCallback(attr.callback);
                }

                $('#angularEnableServiceFalpDetectionModal').modal('show');
                $scope.setEnableServiceFlapDetectionObjects(objects);
            };
        }
    };
});