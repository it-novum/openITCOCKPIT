angular.module('openITCOCKPIT').directive('grafanaMetric', function($http, $sce){
    return {
        restrict: 'E',
        templateUrl: '/grafana_module/grafana_userdashboards/grafanaMetric.html',
        scope: {
            'metric': '='
        },
        controller: function($scope){

        },

        link: function($scope, element, attr){
        }
    };
});
