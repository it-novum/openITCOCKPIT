angular.module('openITCOCKPIT').directive('columnsConfigExport', function($http, $window, NotyService, $state){
    return {
        restrict: 'E',
        templateUrl: '/angular/columns_config_export.html',
        scope: {
            fields: '=',
            callback: '=',
            stateName: '@'
        },
        controller: function($scope){
            $scope.init = true;
            $scope.FieldVals = $scope.fields;

            $scope.copy2Clipboard = function(){
                navigator.clipboard.writeText($scope.configString);
            };

            $scope.$watchCollection('fields', function(values){
                $scope.configString = JSON.stringify({
                    key: $scope.stateName,
                    value: values
                });
            });

        },

        link: function(scope, element, attr){

        }
    };
});
