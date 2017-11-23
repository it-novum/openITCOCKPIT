angular.module('openITCOCKPIT').directive('nestedListItem', function($http, $interval){
    return {
        restrict: 'A',
        templateUrl: '/angular/nested_list_item.html',
        scope: {
            'container': '='
        },

        controller: function($scope){
            //console.log($scope.container);

            $scope.deleteUrl="/containers/delete.json";

            $scope.getObjectForDelete = function(name){
                var object = {};
                object[1] = name;
                return object;
            };

        },

        link: function($scope, element, attr){

            $scope.getObjectForDelete = function(name){
                var object = {};
                object[1] = name;
                return object;
            };

        }
    };
});