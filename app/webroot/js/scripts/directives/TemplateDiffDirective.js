angular.module('openITCOCKPIT').directive('templateDiff', function($http, $interval){
    return {
        restrict: 'E',
        templateUrl: '/angular/template_diff.html',
        replace: true,
        scope: {
            'value': '=',
            'templateValue': '=',
            'callback': '=?' //Optional callback method
        },

        controller: function($scope){

            $scope.hasDiff = false;

            $scope.restoreDefault = function(){
                $scope.value = $scope.templateValue;

                if(typeof $scope.callback === "function"){
                    $scope.callback();
                }
            };

            $scope.$watch('value', function(){
                if($scope.templateValue === null){
                    return;
                }
                switch(typeof $scope.templateValue){
                    case "undefined":
                        break;

                    case "object":
                        $scope.hasDiff = false;
                        if(Array.isArray($scope.templateValue) === false){
                            //Compare keys for objects {}
                            for(var key in $scope.value){
                                if(!$scope.templateValue.hasOwnProperty(key)){
                                    $scope.hasDiff = true;
                                }
                            }
                        }else{
                            var resultOne = _.difference($scope.value, $scope.templateValue);
                            if(resultOne.length > 0){
                                $scope.hasDiff = true;
                            }else{
                                var resultTow = _.difference($scope.templateValue, $scope.value);
                                if(resultTow.length > 0){
                                    $scope.hasDiff = true;
                                }
                            }
                        }
                        if($scope.hasDiff === false){
                            var sizeOne = Object.keys($scope.value).length;
                            var sizeTow = Object.keys($scope.templateValue).length;

                            $scope.hasDiff = sizeOne !== sizeTow;
                        }
                        break;

                    default:
                        //string
                        $scope.hasDiff = $scope.value != $scope.templateValue;
                }
            });

        },

        link: function(scope, element, attr){
        }
    };
});