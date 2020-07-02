angular.module('openITCOCKPIT').directive('versionCheck', function($http, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/version_check.html',

        controller: function($scope){
            $scope.newVersionAvailable = true;
            return;

            $scope.load = function(){
                $http.get("/angular/version_check.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.newVersionAvailable = result.data.newVersionAvailable;
                });
            };

            $scope.load();

        },

        link: function(scope, element, attr){
            jQuery(element).find("[rel=tooltip]").tooltip();
        }
    };
});
