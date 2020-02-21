angular.module('openITCOCKPIT').directive('massdelete', function($http, $filter, $timeout, $state){
    return {
        restrict: 'E',
        replace: true,
        templateUrl: '/angular/mass_delete.html',

        controller: function($scope){

            $scope.objects = {};
            $scope.percentage = 0;
            $scope.isDeleting = false;

            $scope.setObjectsForMassDelete = function(objects){
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
                                $scope.percentage = 0;
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
                                    var isAngular = result.data.usedBy[key].hasOwnProperty('state');
                                    var issue = {
                                        message: result.data.usedBy[key].message,
                                        url: result.data.usedBy[key].baseUrl + id,
                                        isAngular: isAngular,
                                        id: result.data.usedBy[key].id || id
                                    };

                                    if(isAngular){
                                        issue.state = result.data.usedBy[key].state;
                                    }
                                    $scope.issueObjects[id].push(issue);
                                }
                            }

                            issueCount = Object.keys($scope.issueObjects).length;
                            if(i === count && issueCount > 0){
                                $scope.isDeleting = false;
                                $scope.percentage = 0;
                                $scope.load();
                            }
                        });
                }
            };

            $scope.goToStateMassDelete = function(issue){
                $state.go(issue.state, {id: issue.id});
            }

        },

        link: function($scope, element, attr){
            $scope.confirmDelete = function(objects){
                $scope.setObjectsForMassDelete(objects);
                $('#angularMassDelete').modal('show');
            };
        }
    };
});
