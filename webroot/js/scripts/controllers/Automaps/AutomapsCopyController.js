angular.module('openITCOCKPIT')
    .controller('AutomapsCopyController', function($scope, $http, $state, $stateParams, QueryStringService, NotyService, RedirectService){

        var ids = $stateParams.ids.split(',');

        if(ids.length === 0 || ids[0] === ''){
            //No ids to copy given - redirect
            RedirectService.redirectWithFallback('AutomapsIndex');
            return;
        }


        $scope.load = function(){
            $http.get("/automaps/copy/" + ids.join('/') + ".json", {
                params: {
                    'angular': true,
                }
            }).then(function(result){
                $scope.sourceAutomaps = [];
                for(var key in result.data.automaps){
                    $scope.sourceAutomaps.push({
                        Source: {
                            id: result.data.automaps[key].id,
                            name: result.data.automaps[key].name,
                        },
                        Automap: {
                            name: result.data.automaps[key].name,
                            description: result.data.automaps[key].description,
                            host_regex: result.data.automaps[key].host_regex,
                            service_regex: result.data.automaps[key].service_regex
                        }
                    });
                }

                $scope.init = false;

            });
        };

        $scope.copy = function(){
            $http.post("/automaps/copy/.json?angular=true",
                {
                    data: $scope.sourceAutomaps
                }
            ).then(function(result){
                NotyService.genericSuccess();
                RedirectService.redirectWithFallback('AutomapsIndex');
            }, function errorCallback(result){
                //Print errors
                NotyService.genericError();
                $scope.sourceAutomaps = result.data.result;
            });
        };


        $scope.load();


    });
