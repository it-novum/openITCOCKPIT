angular.module('openITCOCKPIT').directive('servicestatusicon', function($interval){
    return {
        restrict: 'E',
        templateUrl: '/services/icon.html',
        scope: {
            'service': '=?',
            'state': '=?',
            'humanState': '=?',
            'isHardstate': '=?'
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
            $scope.opacity = '';
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

                // If not passed directly into directive, check service for the humanState.
                if(typeof ($scope.humanState) !== "string"){
                    if(typeof ($scope.service.Servicestatus.humanState) === "string"){
                        $scope.humanState = $scope.service.Servicestatus.humanState;
                    }
                }

                // If not passed directly into directive, check service for the state type.
                if(typeof ($scope.isHardstate) !== "boolean"){
                    if(typeof ($scope.service.Servicestatus.isHardstate) === "boolean"){
                        $scope.isHardstate = $scope.service.Servicestatus.isHardstate;
                    }
                }

                switch(currentstate){
                    case 0:
                        $scope.btnColor = 'success';
                        $scope.flappingColor = 'text-success';
                        $scope.humanState = $scope.humanState || 'up';
                        break;
                    case 1:
                        $scope.btnColor = 'warning';
                        $scope.flappingColor = 'text-warning';
                        $scope.humanState = $scope.humanState || 'warning';
                        break;
                    case 2:
                        $scope.btnColor = 'danger';
                        $scope.flappingColor = 'text-danger';
                        $scope.humanState = $scope.humanState || 'down';
                        break;
                    case 3:
                        $scope.btnColor = 'secondary';
                        $scope.flappingColor = 'text-secondary';
                        $scope.humanState = $scope.humanState || 'unreachable';
                        break;
                    default:
                        $scope.btnColor = 'primary';
                        $scope.flappingColor = 'text-primary';
                        $scope.humanState = $scope.humanState || 'unknown';
                }

                // Ouptut no state type by default.
                $scope.title = $scope.humanState;
                $scope.opacity = '';

                // Switch state type
                if(typeof ($scope.isHardstate) === "boolean"){
                    if($scope.isHardstate){
                        $scope.title = $scope.title + ' (HARD)';
                    }else{
                        $scope.title = $scope.title + ' (SOFT)';
                        $scope.opacity = 'opacity-50 ';
                    }
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
