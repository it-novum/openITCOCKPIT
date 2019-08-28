angular.module('openITCOCKPIT')
    .controller('ContactsEditController', function($scope, $http, SudoService, $state, $stateParams, NotyService, RedirectService){

        $scope.id = $stateParams.id;

        $scope.init = true;

        $scope.data = {
            areContainersChangeable: false
        };

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

        $scope.loadContact = function(){
            var params = {
                'angular': true
            };

            $http.get("/contacts/edit/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.post = result.data.contact;
                $scope.init = false;
                $scope.data.areContainersChangeable = result.data.areContainersChangeable;
                $scope.loadCommands();
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
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
                    containerIds: $scope.post.Contact.containers._ids
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

        $scope.addMacro = function(){
            $scope.post.Contact.customvariables.push({
                objecttype_id: 32, //OBJECT_CONTACT
                name: '',
                value: ''
            });
        };

        $scope.deleteMacroCallback = function(macro, index){
            $scope.post.Contact.customvariables.splice(index, 1);
        };

        $scope.getMacroErrors = function(index){
            if(typeof $scope.errors !== "undefined"){
                if(typeof $scope.errors.customvariables !== "undefined"){
                    if(typeof $scope.errors.customvariables[index] !== 'undefined'){
                        return $scope.errors.customvariables[index];
                    }
                }
            }
            return false;
        };

        $scope.submit = function(){
            $http.post("/contacts/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('ContactsEdit', {id: $scope.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('ContactsIndex');

                console.log('Data saved successfully');
            }, function errorCallback(result){

                NotyService.genericError();

                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;

                    if($scope.errors.hasOwnProperty('customvariables')){
                        if($scope.errors.customvariables.hasOwnProperty('custom')){
                            $scope.errors.customvariables_unique = [
                                $scope.errors.customvariables.custom
                            ];
                        }
                    }
                }
            });

        };

        var addHostBrowserPushCommand = function(){
            var addCommand = true;
            for(var i in $scope.post.Contact.host_commands._ids){
                if($scope.post.Contact.host_commands._ids[i] === $scope.hostPushComamndId){
                    addCommand = false;
                }
            }

            if(addCommand){
                $scope.post.Contact.host_commands._ids.push($scope.hostPushComamndId);
            }
        };

        var addServiceBrowserPushCommand = function(){
            var addCommand = true;
            for(var i in $scope.post.Contact.service_commands._ids){
                if($scope.post.Contact.service_commands._ids[i] === $scope.servicePushComamndId){
                    addCommand = false;
                }
            }

            if(addCommand){
                $scope.post.Contact.service_commands._ids.push($scope.servicePushComamndId);
            }
        };

        $scope.loadContainers();
        $scope.loadContact();

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

        $scope.$watch('post.Contact.host_push_notifications_enabled', function(){
            if($scope.init){
                return;
            }

            if($scope.post.Contact.host_push_notifications_enabled === 1){
                //Add browser push command
                addHostBrowserPushCommand();
            }

            if($scope.post.Contact.host_push_notifications_enabled === 0){
                //Remove browser push command
                for(var i in $scope.post.Contact.host_commands._ids){
                    if($scope.post.Contact.host_commands._ids[i] === $scope.hostPushComamndId){
                        $scope.post.Contact.host_commands._ids.splice(i, 1);
                        return;
                    }
                }
            }
        });

        $scope.$watch('post.Contact.service_push_notifications_enabled', function(){
            if($scope.init){
                return;
            }

            if($scope.post.Contact.service_push_notifications_enabled === 1){
                //Add browser push command
                addServiceBrowserPushCommand();
            }

            if($scope.post.Contact.service_push_notifications_enabled === 0){
                //Remove browser push command
                for(var i in $scope.post.Contact.service_commands._ids){
                    if($scope.post.Contact.service_commands._ids[i] === $scope.servicePushComamndId){
                        $scope.post.Contact.service_commands._ids.splice(i, 1);
                        return;
                    }
                }
            }
        });

        $scope.$watch('post.Contact.host_commands._ids', function(){
            if($scope.init){
                return;
            }

            var pushCommandSelected = false;
            for(var i in $scope.post.Contact.host_commands._ids){
                if($scope.post.Contact.host_commands._ids[i] === $scope.hostPushComamndId){
                    $scope.post.Contact.host_push_notifications_enabled = 1;
                    pushCommandSelected = true;
                }
            }

            if(pushCommandSelected === false){
                $scope.post.Contact.host_push_notifications_enabled = 0;
            }
        });

        $scope.$watch('post.Contact.service_commands._ids', function(){
            if($scope.init){
                return;
            }

            var pushCommandSelected = false;
            for(var i in $scope.post.Contact.service_commands._ids){
                if($scope.post.Contact.service_commands._ids[i] === $scope.servicePushComamndId){
                    $scope.post.Contact.service_push_notifications_enabled = 1;
                    pushCommandSelected = true;
                    return;
                }
            }

            if(pushCommandSelected === false){
                $scope.post.Contact.service_push_notifications_enabled = 0;
            }

        });


    });
