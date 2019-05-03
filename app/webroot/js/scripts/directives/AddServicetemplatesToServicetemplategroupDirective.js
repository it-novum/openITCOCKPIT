angular.module('openITCOCKPIT').directive('addServicetemplatesToServicetemplategroup', function($http, $filter, $timeout){
    return {
        restrict: 'E',
        //replace: true,
        templateUrl: '/servicetemplates/addServicetemplatesToServicetemplategroup.html',

        controller: function($scope){

            $scope.objects = {};

            $scope.servicetemplateIds = [];

            $scope.setObjectsForConfirmAddServicetemplatesToServicetemplategroup = function(objects){
                $scope.objects = objects;
                $scope.servicetemplateIds = Object.keys(objects);
            };
        },

        link: function($scope, element, attr){
            $scope.confirmAddServicetemplatessToServicetemplategroup = function(objects){
                $scope.setObjectsForConfirmAddServicetemplatesToServicetemplategroup(objects);
                $('#angularAddServicetemplatesToServicetemplategroups').modal('show');
            };
        }
    };
});