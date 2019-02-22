angular.module('openITCOCKPIT')
    .controller('HosttemplatesAddController', function($scope, $http, SudoService, $state, NotyService){
        $scope.post = {
            Hosttemplate: {
                name: '',
                description: '',
                command_id: 0,
                eventhandler_command_id: 0,
                check_interval: 7200,
                retry_interval: 60,
                max_check_attempts: 3,
                first_notification_delay: 0,
                notification_interval: 7200,
                notify_on_down: 0,
                notify_on_unreachable: 1,
                notify_on_recovery: 1,
                notify_on_flapping: 0,
                notify_on_downtime: 0,
                flap_detection_enabled: 0,
                flap_detection_on_up: 0,
                flap_detection_on_down: 0,
                flap_detection_on_unreachable: 0,
                low_flap_threshold: 0,
                high_flap_threshold: 0,
                process_performance_data: 0,
                freshness_checks_enabled: 0,
                freshness_threshold: 0,
                passive_checks_enabled: 0,
                event_handler_enabled: 0,
                active_checks_enabled: 1,
                retain_status_information: 0,
                retain_nonstatus_information: 0,
                notifications_enabled: 0,
                notes: '',
                priority: 1,
                check_period_id: 0,
                notify_period_id: 0,
                tags: '',
                container_id: 0,
                host_url: '',
                check_command: {
                    id: 0
                },
                check_period: {
                    id: 0
                },
                notify_period: {
                    id: 0
                },
                contacts: {
                    _ids: []
                },
                contactgroups: {
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

        $scope.addMacro = function(){
            $scope.post.Hosttemplate.customvariables.push({
                objecttype_id: 512,
                name: '',
                value: ''
            });
        };

        $scope.deleteMacroCallback = function(macro, index){
            $scope.post.Hosttemplate.customvariables.splice(index, 1);
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
                NotyService.genericSuccess();
                $state.go('ContactsIndex');

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
        $scope.loadCommands();

        jQuery(function(){
            $('.tagsinput').tagsinput();
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
