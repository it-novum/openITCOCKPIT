angular.module('openITCOCKPIT').directive('hoststatusicon', function($interval){
    return {
        restrict: 'E',
        templateUrl: '/hosts/icon.html',
        scope: {
            'host': '=?',
            'state': '=?',
            'humanState': '=?',
            'isHardstate': '=?'
        },
        controller: function($scope){

            if(typeof $scope.host === "undefined"){
                //Fake Servicestatus
                $scope.host = {
                    Hoststatus: {}
                };
            }

            $scope.isFlapping = $scope.host.Hoststatus.isFlapping;
            $scope.flappingState = 0;
            $scope.opacity = '';
            var interval;

            $scope.setHostStatusColors = function(){
                var currentstate = -1;
                if(typeof $scope.state === "undefined"){
                    currentstate = parseInt($scope.host.Hoststatus.currentState, 10);
                    if($scope.host.Hoststatus.currentState === null){
                        currentstate = -1; //Not found in monitoring
                    }
                }else{
                    currentstate = parseInt($scope.state, 10);
                }

                // If not passed directly into directive, check host for the humanState.
                if(typeof ($scope.humanState) !== "string"){
                    if(typeof ($scope.host.Hoststatus.humanState) === "string"){
                        $scope.humanState = $scope.host.Hoststatus.humanState;
                    }
                }

                // If not passed directly into directive, check host for the state type.
                if(typeof ($scope.isHardstate) !== "boolean"){
                    if(typeof ($scope.host.Hoststatus.isHardstate) === "boolean"){
                        $scope.isHardstate = $scope.host.Hoststatus.isHardstate;
                    }
                }

                // Switch state
                switch(currentstate){
                    case 0:
                        $scope.btnColor = 'success';
                        $scope.flappingColor = 'text-success';
                        $scope.humanState = $scope.humanState || 'up';
                        break;
                    case 1:
                        $scope.btnColor = 'danger';
                        $scope.flappingColor = 'text-danger';
                        $scope.humanState = $scope.humanState || 'down';
                        break;
                    case 2:
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

            $scope.setHostStatusColors();
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
