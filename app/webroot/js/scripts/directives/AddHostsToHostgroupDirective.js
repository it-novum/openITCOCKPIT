angular.module('openITCOCKPIT').directive('addHostsToHostgroup', function($http, $filter, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/hostgroups/addHostsToHostgroup.html',

        controller: function($scope){

            $scope.objects = {};

            $scope.hostIds = [];

            $scope.setObjectsForConfirmAddHostsToHostgroup = function(objects){
                $scope.objects = objects;
                $scope.hostIds = Object.keys(objects);
            };
        },

        link: function($scope, element, attr){
            $scope.confirmAddHostsToHostgroup = function(objects){
                $scope.setObjectsForConfirmAddHostsToHostgroup(objects);
                $('#angularAddHostsToHostgroup').modal('show');
            };
        }
    };
});