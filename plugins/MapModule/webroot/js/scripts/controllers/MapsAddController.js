angular.module('openITCOCKPIT')
    .controller('MapsAddController', function($scope, $http, $state, NotyService, RedirectService){

        $scope.data = {
            createAnother: false
        };

        var clearForm = function(){
            $scope.post = {
                Map: {
                    name: '',
                    title: '',
                    refresh_interval: 90,
                    containers: {
                        _ids: []
                    }
                }
            };
        };
        clearForm();

        $scope.init = true;
        $scope.load = function(){
            $http.get("/map_module/maps/loadContainers.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };

        $scope.submit = function(){
            $http.post("/map_module/maps/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('MapsEdit', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });


                if($scope.data.createAnother === false){
                    RedirectService.redirectWithFallback('MapsIndex');
                }else{
                    clearForm();
                    $scope.errors = {};
                    NotyService.scrollTop();
                }

                console.log('Data saved successfully');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });

        };

        $scope.load();
    });
