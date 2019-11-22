angular.module('openITCOCKPIT').directive('servicestatusicon', function($interval){
    return {
        restrict: 'E',
        templateUrl: '/services/icon.html',
        scope: {
            'service': '=?',
            'state': '=?'
        },
        controller: function($scope){

            if(typeof $scope.service === "undefined"){
                //Fake Servicestatus
                $scope.service = {
                    Servicestatus: {}
                };
            }


            $scope.isFlapping = $scope.service.Servicestatus.isFlapping || false;
            $scope.flappingState = 0;
            var interval;

            $scope.setServiceStatusColors = function(){
                var currentstate = -1;
                if(typeof $scope.state === "undefined"){
                    currentstate = parseInt($scope.service.Servicestatus.currentState, 10);
                    if($scope.service.Servicestatus.currentState === null){
                        currentstate = -1; //Not found in monitoring
                    }
                }else{
                    currentstate = parseInt($scope.state, 10);
                }

                switch(currentstate){
                    case 0:
                        $scope.btnColor = 'success';
                        $scope.flappingColor = 'txt-color-green';
                        return;
                    case 1:
                        $scope.btnColor = 'warning';
                        $scope.flappingColor = 'warning';
                        return;
                    case 2:
                        $scope.btnColor = 'danger';
                        $scope.flappingColor = 'txt-color-red';
                        return;
                    case 3:
                        $scope.btnColor = 'default';
                        $scope.flappingColor = 'txt-color-blueDark';
                        return;
                    default:
                        $scope.btnColor = 'primary';
                        $scope.flappingColor = 'text-primary';
                }
            };

            $scope.startFlapping = function(){
                $scope.stopFlapping();
                interval = $interval(function(){
                    if($scope.flappingState === 0){
                        $scope.flappingState = 1;
                    }else{
                        $scope.flappingState = 0;
                    }
                }, 750);
            };

            $scope.stopFlapping = function(){
                if(interval){
                    $interval.cancel(interval);
                }
                interval = null;
            };

            $scope.setServiceStatusColors();
            if($scope.isFlapping){
                $scope.startFlapping();
            }else{
                $scope.stopFlapping();
            }
        },

        link: function(scope, element, attr){

        }
    };
});
