angular.module('openITCOCKPIT')
    .controller('TenantsEditController', function($scope, $http, $state, NotyService, $stateParams, RedirectService){

        $scope.id = $stateParams.id;

        $scope.load = function(){
            $http.get("/tenants/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post = result.data.tenant;

                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.submit = function(){
            $http.post("/tenants/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){

                var url = $state.href('TenantsEdit', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });
                RedirectService.redirectWithFallback('TenantsIndex');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.load();

    });
