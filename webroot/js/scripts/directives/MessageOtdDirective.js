angular.module('openITCOCKPIT').directive('messageOtd', function($http, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/message_of_the_day.html',

        controller: function($scope){
            $scope.messageOtdAvailable = false;

            $scope.load = function(){
                $http.get("/angular/message_of_the_day.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.messageOtdAvailable = result.data.messageOtdAvailable;
                });
            };

            $scope.load();

        },

        link: function(scope, element, attr){
            jQuery(element).find("[rel=tooltip]").tooltip();
        }
    };
});
