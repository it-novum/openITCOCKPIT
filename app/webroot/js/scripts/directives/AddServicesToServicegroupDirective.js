angular.module('openITCOCKPIT').directive('addServicesToServicegroup', function($http, $filter, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/servicegroups/addServicesToServicegroup.html',

        controller: function($scope){

            $scope.objects = {};

            $scope.serviceIds = [];

            $scope.setObjectsForConfirmAddServicesToServicegroup = function(objects){
                $scope.objects = objects;
                $scope.serviceIds = Object.keys(objects);
            };
        },

        link: function($scope, element, attr){
            $scope.confirmAddServicesToServicegroup = function(objects){
                $scope.setObjectsForConfirmAddServicesToServicegroup(objects);
                $('#angularAddServicesToServicegroup').modal('show');
            };
        }
    };
});