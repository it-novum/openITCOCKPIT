angular.module('openITCOCKPIT')
    .controller('CommandsCopyController', function($scope, $http, QueryStringService, NotyService){

        var ids = QueryStringService.getCakeIds();


        if(ids.length === 0){
            //No ids to copy given - redirect
            window.location.href = '/commands/index';
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
                window.location.href = '/commands/index';
            }, function errorCallback(result){
                //Print errors
                NotyService.genericError();
                $scope.sourceCommands = result.data.result;
            });
        };


        $scope.load();


    });