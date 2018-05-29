angular.module('openITCOCKPIT').directive('servicecumulatedstatusicon', function(){
    return {
        restrict: 'E',
        templateUrl: '/services/servicecumulatedstatusicon.html',
        scope: {
            'state': '=?'
        },
        controller: function($scope){
            //$scope.state = 0;

            $scope.setServiceCumulatedStatusColors = function(){
                var currentServiceCumulatedState = -1;
                if(typeof $scope.state === "undefined" && $scope.state === null){
                    currentServiceCumulatedState = -1;
                }else{
                    currentServiceCumulatedState = parseInt($scope.state, 10);
                }

                switch(currentServiceCumulatedState){
                    case 0:
                        $scope.iconColor =  'text-success';
                        return;
                    case 1:
                        $scope.iconColor = 'text-warning';
                        return;
                    case 2:
                        $scope.iconColor = 'text-danger';
                        return;
                    case 3:
                        $scope.iconColor = 'text-default';
                        return;
                    default:
                        $scope.iconColor = 'text-primary';
                }
            };

            $scope.setServiceCumulatedStatusColors();
        },
        link: function(scope, element, attr){
        }
    };
});
