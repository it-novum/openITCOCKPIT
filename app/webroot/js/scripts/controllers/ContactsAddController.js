angular.module('openITCOCKPIT')
    .controller('ContactsAddController', function($scope, $http, SudoService, $state, NotyService){
        $scope.post = {
            Contact: {
                name: '',
                description: '',
                email: '',
                containers: {
                    _ids: []
                },
                host_commands: {
                    _ids: []
                },
                service_commands: {
                    _ids: []
                },
                customvariables: []
            }
        };

        $scope.init = true;


        $scope.loadContainers = function(){
            var params = {
                'angular': true
            };

            $http.get("/contacts/loadContainers.json", {
                params: params
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };

        $scope.loadCommands = function(){
            var params = {
                'angular': true
            };

            $http.get("/contacts/loadCommands.json", {
                params: params
            }).then(function(result){
                $scope.commands = result.data.notificationCommands;
                $scope.hostPushComamndId = result.data.hostPushComamndId;
                $scope.servicePushComamndId = result.data.servicePushComamndId;
                $scope.init = false;
            });
        };

        $scope.loadUsers = function(){
            $http.post("/contacts/loadUsersByContainerId.json?angular=true",
                {
                    container_ids: $scope.post.Contact.containers._ids
                }
            ).then(function(result){
                $scope.users = result.data.users;
            });
        };

        $scope.loadTimeperiods = function(){
            $http.post("/contacts/loadTimeperiods.json?angular=true",
                {
                    container_ids: $scope.post.Contact.containers._ids
                }
            ).then(function(result){
                $scope.timeperiods = result.data.timeperiods;
            });
        };


        $scope.submit = function(){
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
                NotyService.genericSuccess();
                $state.go('CommandsIndex');
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    NotyService.genericError();
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.addMacro = function(){
            $scope.post.Contact.customvariables.push({
                objecttype_id: 32,
                name: '',
                value: ''
            });
        };

        $scope.deleteMacroCallback = function(macro){
            console.log(macro);
        };

        $scope.loadContainers();
        $scope.loadCommands();

        jQuery(function(){
            jQuery("[rel=tooltip]").tooltip();
        });

        $scope.$watch('post.Contact.containers._ids', function(){
            if($scope.init){
                return;
            }
            $scope.loadUsers();
            $scope.loadTimeperiods();
        }, true);

    });
