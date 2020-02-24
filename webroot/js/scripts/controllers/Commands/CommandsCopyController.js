angular.module('openITCOCKPIT')
    .controller('CommandsCopyController', function($scope, $http, $state, $stateParams, QueryStringService, NotyService, RedirectService){

        var ids = $stateParams.ids.split(',');

        if(ids.length === 0 || ids[0] === ''){
            //No ids to copy given - redirect
            RedirectService.redirectWithFallback('CommandsIndex');
            return;
        }


        $scope.load = function(){
            $http.get("/commands/copy/" + ids.join('/') + ".json", {
                params: {
                    'angular': true,
                }
            }).then(function(result){
                $scope.sourceCommands = [];
                for(var key in result.data.commands){
                    $scope.sourceCommands.push({
                        Source: {
                            id: result.data.commands[key].Command.id,
                            name: result.data.commands[key].Command.name,
                        },
                        Command: {
                            name: result.data.commands[key].Command.name,
                            command_line: result.data.commands[key].Command.command_line,
                            description: result.data.commands[key].Command.description
                        }
                    });
                }

                $scope.init = false;

            });
        };

        $scope.copy = function(){
            $http.post("/commands/copy/.json?angular=true",
                {
                    data: $scope.sourceCommands
                }
            ).then(function(result){
                NotyService.genericSuccess();
                RedirectService.redirectWithFallback('CommandsIndex');
            }, function errorCallback(result){
                //Print errors
                NotyService.genericError();
                $scope.sourceCommands = result.data.result;
            });
        };


        $scope.load();


    });