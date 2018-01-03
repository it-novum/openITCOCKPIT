angular.module('openITCOCKPIT').directive('hoststatusicon', function($interval){
    return {
        restrict: 'E',
        templateUrl: '/hosts/icon.html',
        scope: {
            'host': '='
        },
        controller: function($scope){

            $scope.isFlapping = $scope.host.Hoststatus.isFlapping;
            $scope.flappingState = 0;
            var interval;

            $scope.setHostStatusColors = function(){
                $scope.currentstate = parseInt($scope.host.Hoststatus.currentState, 10);
                switch($scope.currentstate){
                    case 0:
                        $scope.btnColor =  'success';
                        $scope.flappingColor = 'txt-color-green';
                        return;
                    case 1:
                        $scope.btnColor = 'danger';
                        $scope.flappingColor = 'txt-color-red';
                        return;
                    default:
                        $scope.btnColor = 'default';
                        $scope.flappingColor = 'txt-color-blueDark';
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
