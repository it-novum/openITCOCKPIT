angular.module('openITCOCKPIT').directive('noticeWidget', function($http){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/noticeWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){

            $scope.load = function(){

            };

            $scope.load();

            $scope.buttonClickedOn = function() {
                $scope.$broadcast('FLIP_EVENT_IN');
            }
            $scope.buttonClickedOff = function() {
                $scope.$broadcast('FLIP_EVENT_OUT');
            }

            $scope.$watch('widget.WidgetNotice.note', function(){
                if($scope.ready === true){
                    //$scope.saveNotice();
                }
            });

        },

        link: function($scope, element, attr){

        }
    };
});
