angular.module('openITCOCKPIT')
    .controller('ContactsLdapController', function($scope, $http, SudoService, $state, NotyService, RedirectService){
        $scope.data = {
            selectedSamAccountNameIndex: null,
            createAnother: false
        };

        var clearForm = function(){
            $scope.data.selectedSamAccountNameIndex = null;

            $scope.post = {
                Contact: {
                    name: '',
                    description: '',
                    email: '',

                    host_notifications_enabled: 1,
                    service_notifications_enabled: 1,

                    notify_host_recovery: 1,
                    notify_host_down: 1,
                    notify_host_unreachable: 1,
                    notify_host_flapping: 0,
                    notify_host_downtime: 0,

                    notify_service_recovery: 1,
                    notify_service_warning: 1,
                    notify_service_critical: 1,
                    notify_service_unknown: 1,
                    notify_service_flapping: 0,
                    notify_service_downtime: 0,

                    host_push_notifications_enabled: 0,
                    service_push_notifications_enabled: 0,
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
        };
        clearForm();

        $scope.init = true;

        $scope.loadLdapConfig = function(){
            var params = {
                'angular': true
            };

            $http.get("/angular/ldap_configuration.json", {
                params: params
            }).then(function(result){
                $scope.ldapConfig = result.data.ldapConfig;
            });
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
                objecttype_id: 32,
                name: '',
                value: '',
                password: 0
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
            $http.post("/contacts/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('ContactsEdit', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });


                if($scope.data.createAnother === false){
                    RedirectService.redirectWithFallback('ContactsIndex');
                }else{
                    clearForm();
                    $scope.errors = {};
                    NotyService.scrollTop();
                }

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

        $scope.loadLdapUsersByString = function(searchString){
            $scope.data.selectedSamAccountNameIndex = null;
            $http.get("/contacts/loadLdapUserByString.json", {
                params: {
                    'angular': true,
                    'samaccountname': searchString
                }
            }).then(function(result){
                $scope.ldapUsers = result.data.ldapUsers;
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
        $scope.loadLdapConfig();
        $scope.loadCommands();
        $scope.loadLdapUsersByString('');

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

        $scope.$watch('data.selectedSamAccountNameIndex', function(){
            if($scope.init){
                return;
            }

            var index = parseInt($scope.data.selectedSamAccountNameIndex, 10);
            if(typeof $scope.ldapUsers[index] !== "undefined"){
                $scope.post.Contact.email = $scope.ldapUsers[index].email;
                $scope.post.Contact.name = $scope.ldapUsers[index].samaccountname;
                $scope.post.Contact.description = $scope.ldapUsers[index].display_name;
            }
        })


    });

