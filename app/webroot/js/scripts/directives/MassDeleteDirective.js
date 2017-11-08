angular.module('openITCOCKPIT').directive('massdelete', function($http, $filter, $timeout){
    return {
        restrict: 'E',
        replace: true,
        templateUrl: '/angular/mass_delete.html',

        controller: function($scope){

            $scope.objects = {};
            $scope.percentage = 0;
            $scope.isDeleting = false;

            $scope.setObjects = function(objects){
                $scope.objects = objects;
            };

            $scope.issueObjects = {};

            $scope.delete = function(){
                $scope.isDeleting = true;
                var count = Object.keys($scope.objects).length;
                var i = 0;
                var issueCount = 0;

                for(var id in $scope.objects){
                    $http.post($scope.deleteUrl + id + ".json").then(
                        function(result){
                            i++;
                            $scope.percentage = Math.round(i / count * 100);
                            issueCount = Object.keys($scope.issueObjects).length;

                            if(i === count && issueCount === 0){
                                $scope.isDeleting = false;
                                $scope.load();
                                $('#angularMassDelete').modal('hide');
                            }
                        }, function errorCallback(result){
                            i++;
                            $scope.percentage = Math.round(i / count * 100);

                            if(result.data.hasOwnProperty('success') && result.data.hasOwnProperty('usedBy')){
                                var id = result.data.id;
                                $scope.issueObjects[id] = [];
                                for(var key in result.data.usedBy){
                                    $scope.issueObjects[id].push({
                                        message: result.data.usedBy[key].message,
                                        url: result.data.usedBy[key].baseUrl + id
                                    });
                                }
                            }

                            issueCount = Object.keys($scope.issueObjects).length;
                            if(i === count && issueCount > 0){
                                $scope.isDeleting = false;
                                $scope.load();
                            }
                        });
                }
            };

        },

        link: function($scope, element, attr){
            $scope.confirmDelete = function(objects){
                $scope.setObjects(objects);
                $('#angularMassDelete').modal('show');
            };
        }
    };
});