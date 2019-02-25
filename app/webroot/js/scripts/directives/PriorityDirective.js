angular.module('openITCOCKPIT').directive('priorityDirective', function($http){
    return {
        restrict: 'E',
        templateUrl: '/angular/priority.html',
        scope: {
            'priority': '=',
            'callback': '='
        },

        controller: function($scope){

            $scope.priorityClass = [
                'text-muted',
                'text-muted',
                'text-muted',
                'text-muted',
                'text-muted'
            ];

            var colors = [
                'txt-color-greenLight',
                'txt-color-green',
                'text-primary',
                'txt-color-orange',
                'txt-color-red'
            ];

            var setPriorityClasses = function(value){
                var index = value-1;
                var color = colors[index];
                for(var i = 0; i <= index; i++){
                    $scope.priorityClass[i] = color;
                }

                if(value < 5){
                    i = 0;
                    for(i = value; i <= 5; i++){
                        $scope.priorityClass[i] = 'text-muted';
                    }
                }

            };

            $scope.setPriority = function(value){
                $scope.priority = value;
                $scope.callback(value);
            };

            $scope.hoverPriority = function(value){
                setPriorityClasses(value);
            };

            $scope.mouseleave = function(){
                setPriorityClasses($scope.priority);
            };

            setPriorityClasses($scope.priority);

        },

        link: function($scope, element, attr){

        }
    };
});
