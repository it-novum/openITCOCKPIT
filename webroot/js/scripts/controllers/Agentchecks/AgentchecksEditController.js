angular.module('openITCOCKPIT')
    .controller('AgentchecksEditController', function($scope, $http, SudoService, $state, $stateParams, NotyService, RedirectService){

        $scope.id = $stateParams.id;

        $scope.init = true;

        $scope.loadServicetemplates = function(){
            $http.get("/agentchecks/loadServicetemplates.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.servicetemplates = result.data.servicetemplates;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.loadAgentcheck = function(){
            var params = {
                'angular': true
            };

            $http.get("/agentchecks/edit/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.post = {
                    Agentcheck: result.data.agentcheck
                };

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
            $http.post("/agentchecks/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('AgentchecksEdit', {id: $scope.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('AgentchecksIndex');

                console.log('Data saved successfully');
            }, function errorCallback(result){
                NotyService.genericError();
            });

        };

        $scope.loadServicetemplates();
        $scope.loadAgentcheck();


    });
