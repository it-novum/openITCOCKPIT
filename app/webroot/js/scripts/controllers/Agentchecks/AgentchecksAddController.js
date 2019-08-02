angular.module('openITCOCKPIT')
    .controller('AgentchecksAddController', function($scope, $http, $state, NotyService, RedirectService){

        $scope.data = {
            createAnother: false
        };

        var clearForm = function(){
            $scope.post = {
                Agentcheck: {
                    name: '',
                    servicetemplate_id: ''
                }
            };
        };
        clearForm();

        $scope.init = true;
        $scope.hasError = null;
        $scope.errors = {};

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


        $scope.submit = function(){
            $http.post("/agentchecks/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('AgentchecksEdit', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });


                if($scope.data.createAnother === false){
                    if(typeof redirectState === "undefined"){
                        RedirectService.redirectWithFallback('AgentchecksIndex');
                    }else{
                        $state.go(redirectState, {
                            hostId: result.data.id
                        }).then(function(){
                            NotyService.scrollTop();
                        });
                    }

                }else{
                    clearForm();
                    $scope.errors = {};
                    NotyService.scrollTop();
                }
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    NotyService.genericError();
                    $scope.errors = result.data.error;
                }
            });
        };


        $scope.loadServicetemplates();
    });
