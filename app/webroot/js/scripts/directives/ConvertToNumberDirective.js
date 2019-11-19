angular.module('openITCOCKPIT').directive('convertToNumber', function(){
    return {
        require: 'ngModel',
        link: function(scope, element, attrs, ngModel){
            //why is this never called ?
            ngModel.$parsers.push(function(val){
                return val != null ? parseInt(val, 10) : null;
            });
            ngModel.$formatters.push(function(val){
                //original line
                //return val != null ? '' + val : null;
                return val != null ? parseInt(val, 10) : null;
            });
        }
    };
});