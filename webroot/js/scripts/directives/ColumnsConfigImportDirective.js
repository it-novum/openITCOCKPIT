angular.module('openITCOCKPIT').directive('columnsConfigImport', function($http, $window, NotyService, $state){
    return {
        restrict: 'E',
        templateUrl: '/angular/columns_config_import.html',
        scope: {
            fields: '=',
            callback: '=',
            stateName: '@'
        },
        controller: function($scope){
            $scope.init = true;
            $scope.FieldVals = $scope.fields;
            $scope.importString = '';

            $scope.setConfig = function(){
                try{
                    var configObject = JSON.parse($scope.importString);
                    if(configObject.key == $scope.stateName && Array.isArray(configObject.value)){
                        $scope.callback(configObject.value)
                        $('#importFieldsModal').modal('hide');
                    }else if(configObject.key != $scope.stateName){
                        $scope.error = 'Column config is not for this table';
                        //NotyService.genericError();
                    }else{
                        $scope.error = 'Unable to import config';
                    }
                } catch(err) {
                    $scope.error = err.message;
                }
            }

        },

        link: function(scope, element, attr){

        }
    };
});
