angular.module('openITCOCKPIT')
    .controller('StatuspagesSetAliasController', function($scope, $http, SudoService, $state, $stateParams, NotyService) {

        $scope.id = $stateParams.id;


        $scope.post = {
            Statuspage: {},
        };

        $scope.init = true;
        $scope.hasError = null;
        $scope.errors = {};

        $scope.loadStatuspage = function() {
            var params = {
                'angular': true
            };

            $http.get("/statuspages/setAlias/" + $scope.id + ".json", {
                params: params
            }).then(function(result) {
                $scope.post.Statuspage = result.data.Statuspage;
                $scope.init = false;
            }, function errorCallback(result) {
                if (result.status === 403) {
                    $state.go('403');
                }

                if (result.status === 404) {
                    $state.go('404');
                }
            });
        };
        $scope.submit = function() {
            $http.post("/statuspages/setAlias/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result) {
                var url = $state.href('StatuspagesIndex', {id: $scope.id});
                NotyService.genericSuccess({
                    message: 'Alias update succesfull'
                });

                $state.go('StatuspagesIndex').then(function() {
                    NotyService.scrollTop();
                });

            }, function errorCallback(result) {
                if (result.data.hasOwnProperty('error')) {
                    NotyService.genericError();
                    $scope.errors = result.data.error;
                }
            });
        }

        //Fire on page load
        $scope.loadStatuspage();

    });
