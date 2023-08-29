angular.module('openITCOCKPIT')
    .controller('StatuspagesAddController', function($scope, $http, SudoService, $state, NotyService, RedirectService){

        var clearForm = function(){
            $scope.post = {
                Statuspage: {
                    name: '',
                    description: '',
                    public: 0,
                    show_comments: 0,
                    containers: {
                        _ids: []
                    }
                }
            };
        };
        clearForm();
        $scope.init = true;
        $scope.loadContainers = function(){
            var params = {
                'angular': true
            };

            $http.get("/statuspages/loadContainers.json", {
                params: params
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };


        $scope.submit = function(){
            $http.post("/statuspages/add.json?angular=true",
                $scope.post
            ).then(function(result) {
                var url = $state.href('StatuspagesEdit', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });
                RedirectService.redirectWithFallback('MapsIndex');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        //Fire on page load


        $scope.loadContainers();
    });
