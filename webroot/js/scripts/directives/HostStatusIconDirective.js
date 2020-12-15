angular.module('openITCOCKPIT').directive('hoststatusicon', function($interval){
    return {
        restrict: 'E',
        templateUrl: '/hosts/icon.html',
        scope: {
            'host': '=?',
            'state': '=?'
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

                switch(currentstate){
                    case 0:
                        $scope.btnColor = 'success';
                        $scope.flappingColor = 'text-success';
                        return;
                    case 1:
                        $scope.btnColor = 'danger';
                        $scope.flappingColor = 'text-danger';
                        return;
                    case 2:
                        $scope.btnColor = 'secondary';
                        $scope.flappingColor = 'text-secondary';
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
