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