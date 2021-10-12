angular.module('openITCOCKPIT')
    .controller('CommandsAddController', function($scope, $http, SudoService, $state, NotyService, RedirectService){
        $scope.post = {
            Command: {
                name: '',
                command_type: '1',
                command_line: '',
                description: '',
                commandarguments: []
            }
        };

        $scope.init = true;
        $scope.hasError = null;

        $scope.args = [];
        $scope.macros = [];

        $scope.load = function(){
            $http.get("/commands/add/.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.defaultMacros = result.data.defaultMacros;
                setTimeout($scope.highlightCommandLine, 250);

            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.removeArg = function(count){
            var args = [];
            for(var i in $scope.args){
                if($scope.args[i].count !== count){
                    args.push($scope.args[i])
                }
            }
            $scope.args = args.sort(function(a, b){
                return a.name.localeCompare(b.name, undefined, {
                    numeric: true,
                    sensitivity: 'base'
                });
            });
        };

        $scope.addArg = function(){
            var argsCount = 1;
            var count = 1;
            var allArgNumbersInUse = _.map($scope.args, function(arg){
                return arg.name.match(/\d+/g);
            });
            while(in_array(argsCount, allArgNumbersInUse)){
                argsCount++;
            }
            if($scope.args.length > 0){
                //check for max count values for internal counter
                var objectWithMaxCounter = _.maxBy($scope.args, function(arg){
                    return arg.count;
                });
                count = objectWithMaxCounter.count + 1;
            }
            $scope.args.push({
                name: '$ARG' + argsCount + '$',
                human_name: '',
                count: count
            });
            $scope.args = $scope.args.sort(function(a, b){
                return a.name.localeCompare(b.name, undefined, {
                    numeric: true,
                    sensitivity: 'base'
                });
            });
        };

        $scope.checkForMisingArguments = function(){
            var commandLine = $scope.post.Command.command_line;

            var usedCommandLineArgs = commandLine.match(/(\$ARG\d+\$)/g);
            if(usedCommandLineArgs !== null){
                usedCommandLineArgs = usedCommandLineArgs.length;
            }else{
                usedCommandLineArgs = 0;
            }

            $scope.usedCommandLineArgs = usedCommandLineArgs;
            $scope.definedCommandArguments = $scope.args.length;

            if($scope.usedCommandLineArgs === $scope.definedCommandArguments){
                $scope.submit();
            }else{
                $('#argumentMisMatchModal').modal('show');
            }

        };

        $scope.submit = function(){
            $('#argumentMisMatchModal').modal('hide');

            var index = 0;
            for(var i in $scope.args){
                if(!/\S/.test($scope.args[i].human_name)){
                    continue;
                }
                $scope.post.Command.commandarguments[index] = {
                    'name': $scope.args[i].name,
                    'human_name': $scope.args[i].human_name
                };
                index++;
            }
            $http.post("/commands/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('CommandsEdit', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });
                RedirectService.redirectWithFallback('CommandsIndex');
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    NotyService.genericError();
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.showMacros = function(){
            $http.get('/macros/index/.json', {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.macros = result.data.all_macros;
                $("#MacrosOverview").modal("show");
            });
        };

        $scope.showDefaultMacros = function(){
            $('#defaultMacrosOverview').modal('show');
        };

        $scope.highlightCommandLine = function(){
            var highlight = [
                {
                    highlight: /(\$ARG\d+\$)/g,
                    className: 'highlight-blue'
                },
                {
                    highlight: /(\$USER\d+\$)/g,
                    className: 'highlight-green'
                },
                {
                    highlight: /(\$_HOST.*?\$)/g,
                    className: 'highlight-purple'
                },
                {
                    highlight: /(\$_SERVICE.*?\$)/g,
                    className: 'highlight-purple'
                },
                {
                    highlight: /(\$_CONTACT.*?\$)/g,
                    className: 'highlight-purple'
                }
            ];

            var escapeDollar = new RegExp('\\$', 'g');

            for(var index in $scope.defaultMacros){
                for(var i in $scope.defaultMacros[index].macros){
                    var macroName = $scope.defaultMacros[index].macros[i].macro;
                    macroName = macroName.replace(escapeDollar, '\\$');
                    highlight.push({
                        highlight: new RegExp(macroName, "g"),
                        className: $scope.defaultMacros[index].class
                    });
                }
            }

            $('#commandLineTextArea').highlightWithinTextarea({
                highlight: [
                    highlight
                ]
            });
        };

        //Fire on page load
        $scope.load();
    });
