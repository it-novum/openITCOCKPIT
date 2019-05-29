angular.module('openITCOCKPIT').directive('queryHandlerDirective', function($http){
    return {
        restrict: 'E',
        templateUrl: '/angular/queryhandler.html',
        scope: {},

        controller: function($scope){

            $scope.queryHandler = {
                exists: true,
                path: ''
            };

            $scope.checkQueryHandler = function(){
                $http.get("/angular/queryhandler.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.queryHandler = result.data.QueryHandler;
                });
            }(); // define and call

        },

        link: function($scope, element, attr){

        }
    };
});
