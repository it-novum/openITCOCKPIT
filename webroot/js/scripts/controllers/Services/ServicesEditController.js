angular.module('openITCOCKPIT')
    .controller('ServicesEditController', function($scope, $http, SudoService, $state, NotyService, $stateParams, RedirectService){
        $scope.init = true;

        $scope.id = $stateParams.id;

        $scope.data = {
            isHostOnlyEditableDueToHostSharing: false,
            areContactsInheritedFromHosttemplate: false,
            areContactsInheritedFromHost: false,
            areContactsInheritedFromServicetemplate: false,
            disableInheritance: false
        };

        $scope.post = {
            Service: {}
        };

        var setValuesFromServicetemplate = function(){
            var fields = [
                'name',
                'description',
                'servicetemplate_id',
                'command_id',
                'eventhandler_command_id',
                'check_interval',
                'retry_interval',
                'max_check_attempts',
                'first_notification_delay',
                'notification_interval',
                'notify_on_recovery',
                'notify_on_warning',
                'notify_on_critical',
                'notify_on_unknown',
                'notify_on_flapping',
                'notify_on_downtime',
                'flap_detection_enabled',
                'flap_detection_on_ok',
                'flap_detection_on_warning',
                'flap_detection_on_critical',
                'flap_detection_on_unknown',
                'low_flap_threshold',
                'high_flap_threshold',
                'process_performance_data',
                'freshness_checks_enabled',
                'freshness_threshold',
                'passive_checks_enabled',
                'event_handler_enabled',
                'active_checks_enabled',
                'retain_status_information',
                'retain_nonstatus_information',
                'notifications_enabled',
                'notes',
                'priority',
                'check_period_id',
                'notify_period_id',
                'tags',
                'service_url',
                'is_volatile'
            ];

            for(var index in fields){
                var field = fields[index];
                if($scope.servicetemplate.Servicetemplate.hasOwnProperty(field)){
                    $scope.post.Service[field] = $scope.servicetemplate.Servicetemplate[field];
                }
            }

            var hasManyAssociations = [
                'servicegroups', 'contacts', 'contactgroups'
            ];
            for(index in hasManyAssociations){
                field = hasManyAssociations[index];
                if($scope.servicetemplate.Servicetemplate.hasOwnProperty(field)){
                    $scope.post.Service[field]._ids = $scope.servicetemplate.Servicetemplate[field]._ids;
                }
            }

            $scope.post.Service.customvariables = [];
            for(index in $scope.servicetemplate.Servicetemplate.customvariables){
                $scope.post.Service.customvariables.push({
                    objecttype_id: 4096, //OBJECT_SERVICETEMPLATE because value from service template
                    name: $scope.servicetemplate.Servicetemplate.customvariables[index].name,
                    value: $scope.servicetemplate.Servicetemplate.customvariables[index].value
                });
            }

            $scope.post.Service.servicecommandargumentvalues = [];
            for(index in $scope.servicetemplate.Servicetemplate.servicetemplatecommandargumentvalues){
                $scope.post.Service.servicecommandargumentvalues.push({
                    commandargument_id: $scope.servicetemplate.Servicetemplate.servicetemplatecommandargumentvalues[index].commandargument_id,
                    value: $scope.servicetemplate.Servicetemplate.servicetemplatecommandargumentvalues[index].value,
                    commandargument: $scope.servicetemplate.Servicetemplate.servicetemplatecommandargumentvalues[index].commandargument
                });
            }

            $scope.post.Service.serviceeventcommandargumentvalues = [];
            for(index in $scope.servicetemplate.Servicetemplate.servicetemplateeventcommandargumentvalues){
                $scope.post.Service.serviceeventcommandargumentvalues.push({
                    commandargument_id: $scope.servicetemplate.Servicetemplate.servicetemplateeventcommandargumentvalues[index].commandargument_id,
                    value: $scope.servicetemplate.Servicetemplate.servicetemplateeventcommandargumentvalues[index].value,
                    commandargument: $scope.servicetemplate.Servicetemplate.servicetemplateeventcommandargumentvalues[index].commandargument
                });
            }

            $('#ServiceTagsInput').tagsinput('removeAll');
            $('#ServiceTagsInput').tagsinput('add', $scope.post.Service.tags);
        };


        $scope.loadCommands = function(){
            var params = {
                'angular': true
            };

            $http.get("/services/loadCommands.json", {
                params: params
            }).then(function(result){
                $scope.commands = result.data.commands;
                $scope.eventhandlerCommands = result.data.eventhandlerCommands;
            });
        };

        $scope.loadCommandArguments = function(){
            var params = {
                'angular': true
            };

            var commandId = $scope.post.Service.command_id;
            //May be triggered by watch from "Create another"
            if(commandId === 0){
                return;
            }

            $http.get("/services/loadCommandArguments/" + commandId + "/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.post.Service.servicecommandargumentvalues = result.data.servicecommandargumentvalues;
            });
        };

        $scope.loadEventHandlerCommandArguments = function(){
            var params = {
                'angular': true
            };

            var eventHandlerCommandId = $scope.post.Service.eventhandler_command_id;

            //May be triggered by watch from "Create another"
            if(eventHandlerCommandId === 0){
                return;
            }

            $http.get("/services/loadEventhandlerCommandArguments/" + eventHandlerCommandId + "/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.post.Service.serviceeventcommandargumentvalues = result.data.serviceeventhandlercommandargumentvalues;
            });
        };

        $scope.loadService = function(){
            $http.get("/services/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post.Service = result.data.service.Service;
                $scope.servicetemplate = result.data.servicetemplate;
                $scope.host = result.data.host;
                $scope.serviceType = result.data.serviceType;

                $scope.hostContactsAndContactgroups = result.data.hostContactsAndContactgroups;
                $scope.hosttemplateContactsAndContactgroups = result.data.hosttemplateContactsAndContactgroups;

                $scope.data.areContactsInheritedFromHosttemplate = result.data.areContactsInheritedFromHosttemplate;
                $scope.data.areContactsInheritedFromHost = result.data.areContactsInheritedFromHost;
                $scope.data.areContactsInheritedFromServicetemplate = result.data.areContactsInheritedFromServicetemplate;

                if(
                    $scope.data.areContactsInheritedFromHosttemplate ||
                    $scope.data.areContactsInheritedFromHost ||
                    $scope.data.areContactsInheritedFromServicetemplate
                ){
                    $('#ContactBlocker').block({
                        message: null,
                        overlayCSS: {
                            opacity: 0.5,
                            cursor: 'not-allowed',
                            'background-color': 'rgb(255, 255, 255)'
                        }
                    });
                }

                jQuery(function(){
                    $('.tagsinput').tagsinput();
                });

                $scope.loadElements();

                setTimeout(function(){
                    $scope.init = false;
                }, 250);

            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.loadElements = function(){
            var hostId = $scope.post.Service.host_id;
            var serviceId = $scope.id;
            //May be triggered by watch from "Create another"
            if(hostId === 0){
                return;
            }

            $http.post("/services/loadElementsByHostId/" + hostId + "/" + serviceId + ".json?angular=true", {}).then(function(result){
                $scope.servicetemplates = result.data.servicetemplates;
                $scope.timeperiods = result.data.timeperiods;
                $scope.checkperiods = result.data.checkperiods;
                $scope.contacts = result.data.contacts;
                $scope.contactgroups = result.data.contactgroups;
                $scope.servicegroups = result.data.servicegroups;
            });
        };


        $scope.loadServicetemplate = function(){
            var servicetemplateId = $scope.post.Service.servicetemplate_id;

            $http.post("/services/loadServicetemplate/" + servicetemplateId + ".json?angular=true", {}).then(function(result){
                $scope.servicetemplate = result.data.servicetemplate;
                setValuesFromServicetemplate();
            });

            setTimeout(function(){
                //Enable post.Service.command_id $watch
                $scope.init = false;
            }, 500);
        };

        $scope.setPriority = function(priority){
            $scope.post.Service.priority = parseInt(priority, 10);
        };

        $scope.addMacro = function(){
            $scope.post.Service.customvariables.push({
                objecttype_id: 2048, //OBJECT_SERVICE
                name: '',
                value: '',
                password: 0
            });
        };

        $scope.deleteMacroCallback = function(macro, index){
            $scope.post.Service.customvariables.splice(index, 1);
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


        $scope.restoreTemplateTags = function(){
            $scope.post.Service.tags = $scope.servicetemplate.Servicetemplate.tags;
            $('#ServiceTagsInput').tagsinput('removeAll');
            $('#ServiceTagsInput').tagsinput('add', $scope.servicetemplate.Servicetemplate.tags);
        };

        $scope.submit = function(){
            $http.post("/services/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('ServicesEdit', {id: $scope.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('ServicesIndex');
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

        $scope.loadCommands();
        $scope.loadService();

        $scope.$watch('post.Service.servicetemplate_id', function(){
            if($scope.init){
                return;
            }

            $scope.init = true; //Disable post.Service.command_id $watch
            $scope.loadServicetemplate();
        }, true);

        $scope.$watch('post.Service.command_id', function(){
            if($scope.init){
                return;
            }
            $scope.loadCommandArguments();
        }, true);

        $scope.$watch('post.Service.eventhandler_command_id', function(){
            if($scope.init){
                return;
            }

            if($scope.post.Service.eventhandler_command_id === 0){
                //"None" selected
                $scope.post.Service.serviceeventcommandargumentvalues = [];
                return;
            }

            $scope.loadEventHandlerCommandArguments();
        }, true);

        $scope.$watch('data.disableInheritance', function(){
            if(
                $scope.data.areContactsInheritedFromHosttemplate === false &&
                $scope.data.areContactsInheritedFromHost === false &&
                $scope.data.areContactsInheritedFromServicetemplate === false){
                return;
            }

            if($scope.data.disableInheritance === true){
                //Overwrite with own contacts
                $('#ContactBlocker').unblock();
            }else{
                //Inherit contacts
                $('#ContactBlocker').block({
                    message: null,
                    overlayCSS: {
                        opacity: 0.5,
                        cursor: 'not-allowed',
                        'background-color': 'rgb(255, 255, 255)'
                    }
                });

                if($scope.data.areContactsInheritedFromServicetemplate === true){
                    if(typeof $scope.servicetemplate !== "undefined"){
                        $scope.post.Service.contacts._ids = $scope.servicetemplate.Servicetemplate.contacts._ids;
                        $scope.post.Service.contactgroups._ids = $scope.servicetemplate.Servicetemplate.contactgroups._ids;
                    }
                    return;
                }

                if($scope.data.areContactsInheritedFromServicetemplate === true){
                    if(typeof $scope.servicetemplate !== "undefined"){
                        $scope.post.Service.contacts._ids = $scope.servicetemplate.Servicetemplate.contacts._ids;
                        $scope.post.Service.contactgroups._ids = $scope.servicetemplate.Servicetemplate.contactgroups._ids;
                    }
                    return;
                }

                if($scope.data.areContactsInheritedFromHost === true){
                    if(typeof $scope.hostContactsAndContactgroups !== "undefined"){
                        $scope.post.Service.contacts._ids = $scope.hostContactsAndContactgroups.contacts._ids;
                        $scope.post.Service.contactgroups._ids = $scope.hostContactsAndContactgroups.contactgroups._ids;
                    }
                    return;
                }

                //Contact information got inherited from host template
                if(typeof $scope.hosttemplateContactsAndContactgroups !== "undefined"){
                    $scope.post.Service.contacts._ids = $scope.hosttemplateContactsAndContactgroups.contacts._ids;
                    $scope.post.Service.contactgroups._ids = $scope.hosttemplateContactsAndContactgroups.contactgroups._ids;
                }

            }
        });


    });
