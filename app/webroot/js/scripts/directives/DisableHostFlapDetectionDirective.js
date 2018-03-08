angular.module('openITCOCKPIT').directive('disableHostFlapDetection', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/disable_host_flap_detection.html',

        controller: function($scope){

            var objects = {};
            var callbackName = false;

            $scope.isDisableingHostFlapDetection = false;

            $scope.setDisableHostFlapDetectionObjects = function(_objects){
                objects = _objects;
            };

            $scope.setDisableHostFlapDetectionCallback = function(_callback){
                callbackName = _callback;
            };


            $scope.doDisableHostFlapDetection = function(){
                var count = Object.keys(objects).length;
                var i = 0;
                $scope.percentage = 0;
                $scope.isDisableingHostFlapDetection = true;


                $scope.percentage = Math.round(i / count * 100);
                for(var id in objects){
                    var object = objects[id];
                    i++;
                    $scope.percentage = Math.round(i / count * 100);
                    SudoService.send(SudoService.toJson('enableOrDisableHostFlapdetection', [
                        object.Host.uuid,
                        0 //Disable flap detection
                    ]));
                }

                //Call callback function if given
                if(callbackName){
                    $scope[callbackName]();
                }
                $timeout(function(){
                    $scope.isDisableingHostFlapDetection = false;
                    $scope.percentage = 0;
                    $('#angularDisableHostFalpDetectionModal').modal('hide');
                }, 500);
            };

        },

        link: function($scope, element, attr){
            $scope.disableHostFlapDetection = function(objects){
                if(Object.keys(objects).length === 0){
                    return;
                }

                if(attr.hasOwnProperty('callback')){
                    $scope.setDisableHostFlapDetectionCallback(attr.callback);
                }

                $('#angularDisableHostFalpDetectionModal').modal('show');
                $scope.setDisableHostFlapDetectionObjects(objects);
            };
        }
    };
});