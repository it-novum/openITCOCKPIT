angular.module('openITCOCKPIT')
    .controller('CommandsEditController', function($scope, $http, SudoService, QueryStringService, $stateParams, $state, NotyService){
        $scope.post = {
            Command: {
                name: '',
                command_type: '1',
                command_line: '',
                description: '',
                commandarguments: []
            }
        };
        //$scope.id = $stateParams.id;
        $scope.id = QueryStringService.getCakeId();

        $scope.init = true;
        $scope.hasError = null;
        $scope.hasWebSocketError = false;

        $scope.args = [];
        $scope.jqConsole = null;

        $scope.load = function(){
            $http.get("/commands/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.command = result.data.command;


                for(key in $scope.command.commandarguments){
                    $scope.args.push({
                        id: $scope.command.commandarguments[key].id,
                        name: $scope.command.commandarguments[key].name,
                        human_name: $scope.command.commandarguments[key].human_name
                    });
                }
                $scope.post.Command.name = $scope.command.name;
                $scope.post.Command.command_type = String($scope.command.command_type);
                $scope.post.Command.command_line = $scope.command.command_line;
                $scope.post.Command.description = $scope.command.description;
                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 404){
                    window.location.href = '/angular/not_found';
                }
            });
        }
        ;

        $scope.removeArg = function(indexToDelete){
            var args = [];
            for(var i in $scope.args){
                if(i != indexToDelete){
                    args.push($scope.args[i])
                }
            }
            $scope.args = _.sortBy(args, 'name');
        };

        $scope.addArg = function(){
            var argsCount = 1;
            var allArgNumbersInUse = _.map($scope.args, function(arg){
                return arg.name.match(/\d+/g);
            });
            while(in_array(argsCount, allArgNumbersInUse)){
                argsCount++;
            }
            $scope.args.push({
                name: '$ARG' + argsCount,
                human_name: ''
            });
            $scope.args = _.sortBy($scope.args, 'name');
        };


        $scope.submit = function(){
            var index = 0;
            $scope.post.Command.commandarguments = [];
            for(var i in $scope.args){
                if(!/\S/.test($scope.args[i].human_name)){
                    continue;
                }
                if($scope.args[i].hasOwnProperty('id')){
                    $scope.post.Command.commandarguments[index] = {
                        'id': $scope.args[i].id,
                        'name': $scope.args[i].name,
                        'human_name': $scope.args[i].human_name
                    };
                } else{
                    $scope.post.Command.commandarguments[index] = {
                        'name': $scope.args[i].name,
                        'human_name': $scope.args[i].human_name
                    };
                }
                index++;
            }
            $http.post("/commands/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                window.location.href = '/commands/index';
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.createJQConsole = function(){
            $scope.jqConsole = $('#console').jqconsole('', 'nagios$ ');
            $http.get('/commands/getConsoleWelcome/.json', {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.jqConsole.Write(result.data.welcomeMessage);
            });

            SudoService.onResponse(function(response){
                if(typeof response.data !== 'undefined'){
                    var payload = JSON.parse(response.data);
                    $scope.jqConsole.Write(payload.payload, 'jqconsole-output');
                }
            });
            SudoService.onError(function(){
                $scope.hasWebSocketError = true;
                $('#console').block({
                    fadeIn: 1000,
                    message: '<i class="fa fa-minus-circle fa-5x"></i>',
                    theme: false
                });
                $('.blockElement').css({
                    'background-color': '',
                    'border': 'none',
                    'color': '#FFFFFF'
                });
            });

            var newLineInPromt = function(){
                $scope.jqConsole.Prompt(true, function(input){
                    SudoService.send(SudoService.toJson('execute_nagios_command', input));
                    newLineInPromt();
                });
            };
            newLineInPromt();
        };

        $scope.load();
        setTimeout($scope.createJQConsole, 250);
    })
;
