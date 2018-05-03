angular.module('openITCOCKPIT').directive('servicestatusiconAutomap', function($interval){
    return {
        restrict: 'E',
        templateUrl: '/automaps/icon.html',
        scope: {
            'servicestatus': '='
        },
        controller: function($scope){
            $scope.setServiceStatusColors = function(){
                var currentstate = parseInt($scope.servicestatus.currentState, 10);

                $scope.icon = 'fa-square';
                if($scope.servicestatus.problemHasBeenAcknowledged){
                    $scope.icon = 'fa-user';
                }

                if($scope.servicestatus.scheduledDowntimeDepth > 0){
                    $scope.icon = 'fa-power-off';
                }

                switch(currentstate){
                    case 0:
                        $scope.iconColor =  'ok';
                        return;
                    case 1:
                        $scope.iconColor = 'warning';
                        return;
                    case 2:
                        $scope.iconColor =  'critical';
                        return;
                    case 3:
                        $scope.iconColor = 'unknown';
                        return;
                    default:
                        $scope.iconColor = 'primary';
                }
            };

            $scope.setServiceStatusColors();

        },

        link: function(scope, element, attr){

        }
    };
});
