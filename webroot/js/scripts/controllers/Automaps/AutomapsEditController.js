angular.module('openITCOCKPIT')
    .controller('AutomapsEditController', function($scope, $http, SudoService, $state, NotyService, RedirectService, $stateParams){

        $scope.id = $stateParams.id;

        $scope.init = true;

        var fontSizes = {
            1: 'xx-small',
            2: 'x-small',
            3: 'small',
            4: 'medium',
            5: 'large',
            6: 'x-large',
            7: 'xx-large'
        };

        $scope.data = {
            hostCount: 0,
            serviceCount: 0
        };

        $scope.loadContainers = function(){
            var params = {
                'angular': true
            };

            $http.get("/automaps/loadContainers.json", {
                params: params
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.loadAutomap();
            });
        };

        $scope.loadAutomap = function(){
            var params = {
                'angular': true
            };

            $http.get("/automaps/edit/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.post = {
                    Automap: result.data.automap
                };

                $scope.getMatchingHostAndServices();
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
            $http.post("/automaps/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('AutomapsEdit', {id: result.data.automap.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('AutomapsIndex');

                console.log('Data saved successfully');
            }, function errorCallback(result){

                NotyService.genericError();

                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.getFontsize = function(){
            return {
                'font-size': fontSizes[$scope.post.Automap.font_size]
            };
        };

        $scope.getMatchingHostAndServices = function(){
            $http.post("/automaps/getMatchingHostAndServices.json?angular=true",
                $scope.post
            ).then(function(result){
                $scope.data.hostCount = result.data.hostCount;
                $scope.data.serviceCount = result.data.serviceCount;
                $scope.init = false;
            });
        };

        $scope.$watch('post.Automap.host_regex', function(){
            if($scope.init){
                return;
            }

            if($scope.post.Automap.host_regex != '' && $scope.post.Automap.container_id > 0){
                $scope.getMatchingHostAndServices();
            }

        });

        $scope.$watch('post.Automap.service_regex', function(){
            if($scope.init){
                return;
            }

            if($scope.post.Automap.service_regex != '' && $scope.post.Automap.container_id > 0){
                $scope.getMatchingHostAndServices();
            }
        });

        $scope.$watch('post.Automap.container_id', function(){
            if($scope.init){
                return;
            }

            if($scope.post.Automap.host_regex != '' && $scope.post.Automap.service_regex != '' && $scope.post.Automap.container_id > 0){
                $scope.getMatchingHostAndServices();
            }
        });

        $scope.$watch('post.Automap.recursive', function(){
            if($scope.init){
                return;
            }

            if($scope.post.Automap.host_regex != '' && $scope.post.Automap.service_regex != '' && $scope.post.Automap.container_id > 0){
                $scope.getMatchingHostAndServices();
            }
        });

        // Fire on page
        $scope.loadContainers();

    });
