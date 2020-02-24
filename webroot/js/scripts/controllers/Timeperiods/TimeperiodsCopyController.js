angular.module('openITCOCKPIT')
    .controller('TimeperiodsCopyController', function($scope, $http, $state, $stateParams, NotyService, RedirectService){

        var ids = $stateParams.ids.split(',');

        if(ids.length === 0 || ids[0] === ''){
            //No ids to copy given - redirect
            RedirectService.redirectWithFallback('TimeperiodsIndex');
            return;
        }


        $scope.load = function(){
            $http.get("/timeperiods/copy/" + ids.join('/') + ".json", {
                params: {
                    'angular': true,
                }
            }).then(function(result){
                $scope.sourceTimeperiods = [];
                for(var key in result.data.timeperiods){
                    $scope.sourceTimeperiods.push({
                        Source: {
                            id: result.data.timeperiods[key].Timeperiod.id,
                            name: result.data.timeperiods[key].Timeperiod.name,
                        },
                        Timeperiod: {
                            name: result.data.timeperiods[key].Timeperiod.name,
                            description: result.data.timeperiods[key].Timeperiod.description
                        }
                    });
                }

                $scope.init = false;

            });
        };

        $scope.copy = function(){
            $http.post("/timeperiods/copy/.json?angular=true",
                {
                    data: $scope.sourceTimeperiods
                }
            ).then(function(result){
                NotyService.genericSuccess();
                RedirectService.redirectWithFallback('TimeperiodsIndex');
            }, function errorCallback(result){
                //Print errors
                NotyService.genericError();
                $scope.sourceTimeperiods = result.data.result;
            });
        };


        $scope.load();


    });