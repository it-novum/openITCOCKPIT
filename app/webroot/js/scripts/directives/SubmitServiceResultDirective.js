angular.module('openITCOCKPIT').directive('submitServiceResult', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/submit_service_result.html',

        controller: function($scope){

            var objects = {};
            var maxCheckAttempts = 10;
            var callbackName = false;

            $scope.isSubmittingServiceResult = false;
            $scope.passiveServiceState = "0";
            $scope.passiveServiceResult = {
                output: 'Test alert',
                hardStateForce: false
            };

            $scope.setServiceResultObjects = function(_objects){
                objects = _objects;
            };

            $scope.setMaxCheckAttemptsService = function(_maxCheckAttempts){
                maxCheckAttempts = _maxCheckAttempts;
            };

            $scope.setServiceResultCallback = function(_callback){
                callbackName = _callback;
            };


            $scope.doSubmitServiceResult = function(){
                var count = Object.keys(objects).length;
                var i = 0;
                $scope.percentage = 0;
                $scope.isSubmittingServiceResult = true;


                $scope.percentage = Math.round(i / count * 100);
                for(var id in objects){
                    var object = objects[id];
                    i++;
                    $scope.percentage = Math.round(i / count * 100);

                    var sendMaxCheckAttempts = 1;
                    if($scope.passiveServiceResult.hardStateForce){
                        sendMaxCheckAttempts = maxCheckAttempts;
                    }

                    SudoService.send(SudoService.toJson('commitPassiveServiceResult', [
                        object.Host.uuid,
                        object.Service.uuid,
                        $scope.passiveServiceResult.output,
                        $scope.passiveServiceState,
                        $scope.passiveServiceResult.hardStateForce,
                        sendMaxCheckAttempts
                    ]));
                }

                //Call callback function if given
                if(callbackName){
                    $scope[callbackName]();
                }
                $timeout(function(){
                    $scope.isSubmittingServiceResult = false;
                    $scope.percentage = 0;
                    $('#angularSubmitServiceResultModal').modal('hide');
                }, 500);
            };

        },

        link: function($scope, element, attr){
            $scope.submitServiceResult = function(objects){
                if(Object.keys(objects).length === 0){
                    return;
                }

                if(attr.hasOwnProperty('callback')){
                    $scope.setServiceResultCallback(attr.callback);
                }

                $('#angularSubmitServiceResultModal').modal('show');
                $scope.setServiceResultObjects(objects);

                if(attr.hasOwnProperty('maxCheckAttempts')){
                    $scope.setMaxCheckAttemptsService(attr.maxCheckAttempts);
                }

            };
        }
    };
});