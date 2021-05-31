angular.module('openITCOCKPIT').directive('messageWidget', function($http){
    return {
        restrict: 'E',
        templateUrl: '/dashboards/messageWidget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){


            $scope.load = function(){
                $http.get("/dashboards/messageWidget.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.messages = result.data.messages;
                    $scope.init = false;
                });
            };

            $scope.load();

        },

        link: function($scope, element, attr){

        }
    };
});
