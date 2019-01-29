angular.module('openITCOCKPIT')
    .controller('CommandsAddController', function($scope, $http){
        $scope.post = {
            Command: {
                name: '',
                command_type: '1',
                command_line: '',
                description: '',
                Commandargument: []
            }
        };

        $scope.init = true;
        $scope.hasError = null;

        $scope.args= [];

        $scope.removeArg = function(arg){
            var args = [];
            for(var i in $scope.args){
                if($scope.args[i].id !== arg.id){
                    args.push($scope.args[i])
                }
            }

            $scope.args = args;
        }

        $scope.addArg = function(){
            var argsCount = 1;
            var allIds = _.map($scope.args, 'id');
            while(in_array(argsCount, allIds)){
                argsCount++;
            }
            $scope.args.push({
                id: argsCount,
                name: '$ARG'+argsCount,
                value: ''
            });
        }


        $scope.submit = function(){
            $http.post("/commands/add.json?angular=true",
                $scope.post
            ).then(function(result){
                console.log('Data saved successfully');
                window.location.href = '/commands/index';
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };
    });
