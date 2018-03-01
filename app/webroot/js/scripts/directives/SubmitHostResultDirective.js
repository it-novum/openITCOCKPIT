angular.module('openITCOCKPIT').directive('submitHostResult', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/submit_host_result.html',

        controller: function($scope){

            var objects = {};
            var maxCheckAttempts = 10;
            $scope.isSubmittingHostResult = false;
            $scope.passiveHostState = "0";
            $scope.passiveHostResult = {
                output: 'Test alert',
                hardStateForce: false
            };

            $scope.hostReschedulingType = 'hostAndServices';

            $scope.setHostResultObjects = function(_objects){
                objects = _objects;
            };

            $scope.setMaxCheckAttemptsHost = function(_maxCheckAttempts){
                maxCheckAttempts = _maxCheckAttempts;
            };


            $scope.doSubmitHostResult = function(){
                var count = Object.keys(objects).length;
                var i = 0;
                $scope.percentage = 0;
                $scope.isSubmittingHostResult = true;


                $scope.percentage = Math.round(i / count * 100);
                for(var id in objects){
                    var object = objects[id];
                    i++;
                    $scope.percentage = Math.round(i / count * 100);

                    var sendMaxCheckAttempts = 1;
                    if($scope.passiveHostResult.hardStateForce){
                        sendMaxCheckAttempts = maxCheckAttempts;
                    }

                    SudoService.send(SudoService.toJson('commitPassiveResult', [
                        object.Host.uuid,
                        $scope.passiveHostResult.output,
                        $scope.passiveHostState,
                        $scope.passiveHostResult.hardStateForce,
                        sendMaxCheckAttempts
                    ]));
                }
                $timeout(function(){
                    $scope.isSubmittingHostResult = false;
                    $scope.percentage = 0;
                    $('#angularSubmitHostResultModal').modal('hide');
                }, 500);
            };

        },

        link: function($scope, element, attr){
            $scope.submitHostResult = function(objects){
                if(Object.keys(objects).length === 0){
                    return;
                }
                $('#angularSubmitHostResultModal').modal('show');
                $scope.setHostResultObjects(objects);

                if(attr.hasOwnProperty('maxCheckAttempts')){
                    $scope.setMaxCheckAttemptsHost(attr.maxCheckAttempts);
                }

            };
        }
    };
});