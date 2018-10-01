angular.module('openITCOCKPIT').directive('grafanaRow', function(){
    return {
        restrict: 'E',
        templateUrl: '/grafana_module/grafana_userdashboards/grafanaRow.html',
        scope: {
            'id': '=',
            'row': '='
        },
        controller: function($scope){

            $scope.panelClass = 'col-md-3';

            $scope.$watch('row', function(){
                switch(Object.keys($scope.row).length){
                    case 1:
                        $scope.panelClass = 'col-md-12';
                        break;

                    case 2:
                        $scope.panelClass = 'col-md-6';
                        break;

                    case 3:
                        $scope.panelClass = 'col-md-4';
                        break;

                    case 3:
                        $scope.panelClass = 'col-md-3';
                        break;
                }
            });

        },

        link: function($scope, element, attr){
        }
    };
});
