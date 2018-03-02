angular.module('openITCOCKPIT').directive('enableHostFlapDetection', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/enable_host_flap_detection.html',

        controller: function($scope){

            var objects = {};
            var callbackName = false;

            $scope.isEnableingHostFlapDetection = false;

            $scope.setEnableHostFlapDetectionObjects = function(_objects){
                objects = _objects;
            };

            $scope.setEnableHostFlapDetectionCallback = function(_callback){
                callbackName = _callback;
            };

            $scope.doEnableHostFlapDetection = function(){
                var count = Object.keys(objects).length;
                var i = 0;
                $scope.percentage = 0;
                $scope.isEnableingHostFlapDetection = true;


                $scope.percentage = Math.round(i / count * 100);
                for(var id in objects){
                    var object = objects[id];
                    i++;
                    $scope.percentage = Math.round(i / count * 100);
                    SudoService.send(SudoService.toJson('enableOrDisableHostFlapdetection', [
                        object.Host.uuid,
                        1 //Enable flap detection
                    ]));
                }

                //Call callback function if given
                if(callbackName){
                    $scope[callbackName]();
                }
                $timeout(function(){
                    $scope.isEnableingHostFlapDetection = false;
                    $scope.percentage = 0;
                    $('#angularEnableHostFalpDetectionModal').modal('hide');
                }, 500);
            };

        },

        link: function($scope, element, attr){
            $scope.enableHostFlapDetection = function(objects){
                if(Object.keys(objects).length === 0){
                    return;
                }

                if(attr.hasOwnProperty('callback')){
                    $scope.setEnableHostFlapDetectionCallback(attr.callback);
                }

                $('#angularEnableHostFalpDetectionModal').modal('show');
                $scope.setEnableHostFlapDetectionObjects(objects);
            };
        }
    };
});