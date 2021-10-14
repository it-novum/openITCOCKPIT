angular.module('openITCOCKPIT').directive('todayWidget', function($http){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/todayWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){
            var $widget = $('#widget-' + $scope.widget.id);
            $scope.frontWidgetHeight = parseInt(($widget.height()), 10); //-50px header

            $scope.fontSize = parseInt($scope.frontWidgetHeight / 3.8, 10);

            $scope.calendarTimeout = null;

            $scope.load = function(){
                $http.get("/angular/statuscount.json", {
                    params: {
                        'angular': true,
                        'recursive': true
                    }
                }).then(function(result){
                    $scope.init = false;
                });
            };

            $scope.load();

            $widget.on('resize', function(event, items){
                hasResize();
            });

            var hasResize = function(){
                if($scope.init){
                    return;
                }
                $scope.frontWidgetHeight = parseInt(($widget.height()), 10); //-50px header

                $scope.fontSize = parseInt($scope.frontWidgetHeight / 3.8, 10);

                if($scope.calendarTimeout){
                    clearTimeout($scope.calendarTimeout);
                }
                $scope.calendarTimeout = setTimeout(function(){
                    $scope.load();
                }, 500);
            };

        },

        link: function($scope, element, attr){

        }
    };
});
