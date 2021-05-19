angular.module('openITCOCKPIT')
    .controller('ServicesAddController', function($scope, $http, SudoService, $state, NotyService, $stateParams, RedirectService){

        //Pre-select a host via URL or set hostid to 0 if not numeric or empty
        var hostId = parseInt($stateParams.hostId, 10);
        if(isNaN(hostId)){
            hostId = 0;
        }

        $scope.data = {
            createAnother: false,
            areContactsInheritedFromHosttemplate: false,
            areContactsInheritedFromHost: false,
            areContactsInheritedFromServicetemplate: false,
            disableInheritance: false
        };

        var clearForm = function(){
            $scope.post = {
                Service: {
                    host_id: hostId,
                    servicetemplate_id: 0,
                    name: '',
                    description: '',
                    command_id: 0,
                    eventhandler_command_id: 0,
                    check_interval: 60,
                    retry_interval: 60,
                    max_check_attempts: 3,
                    first_notification_delay: 0,
                    notification_interval: 7200,
                    notify_on_recovery: 1,
                    notify_on_warning: 1,
                    notify_on_critical: 1,
                    notify_on_unknown: 1,
                    notify_on_flapping: 0,
                    notify_on_downtime: 0,
                    flap_detection_enabled: 0,
                    flap_detection_on_ok: 0,
                    flap_detection_on_warning: 0,
                    flap_detection_on_critical: 0,
                    flap_detection_on_unknown: 0,
                    low_flap_threshold: 0,
                    high_flap_threshold: 0,
                    process_performance_data: 1,
                    freshness_threshold: 3600,
                    passive_checks_enabled: 1,
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
                    service_url: '',
                    is_volatile: 0,
                    freshness_checks_enabled: 0,
                    contacts: {
                        _ids: []
                    },
                    contactgroups: {
                        _ids: []
                    },
                    servicegroups: {
                        _ids: []
                    },
                    customvariables: [],
                    servicecommandargumentvalues: [],
                    serviceeventcommandargumentvalues: []
                }
            };
        };
        clearForm();

        $scope.init = true;
        $scope.showDuplicateName = false;

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
                    value: $scope.servicetemplate.Servicetemplate.customvariables[index].value,
                    password: $scope.servicetemplate.Servicetemplate.customvariables[index].password
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

        $scope.loadHosts = function(searchString, selected){
            if(typeof selected === "undefined"){
                selected = $scope.post.Service.host_id;
            }

            $http.get("/hosts/loadHostsByString/1.json", {
                params: {
                    'angular': true,
                    'filter[Hosts.name]': searchString,
                    'selected[]': selected,
                    'includeDisabled': 'false'
                }
            }).then(function(result){
                $scope.hosts = result.data.hosts;
            });
        };

        /*
        $scope.loadContainers = function(){
            var params = {
                'angular': true
            };

            $http.get("/services/loadContainers.json", {
                params: params
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };
        */

        $scope.loadCommands = function(){
            var params = {
                'angular': true
            };

            $http.get("/services/loadCommands.json", {
                params: params
            }).then(function(result){
                $scope.commands = result.data.commands;
                $scope.eventhandlerCommands = result.data.eventhandlerCommands;
                $scope.init = false;
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

            $http.get("/services/loadCommandArguments/" + commandId + ".json", {
                params: params
            }).then(function(result){
                $scope.post.Service.servicecommandargumentvalues = result.data.servicecommandargumentvalues;
                $scope.init = false;
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

            $http.get("/services/loadEventhandlerCommandArguments/" + eventHandlerCommandId + ".json", {
                params: params
            }).then(function(result){
                $scope.post.Service.serviceeventcommandargumentvalues = result.data.serviceeventhandlercommandargumentvalues;
                $scope.init = false;
            });
        };

        $scope.loadElements = function(){
            var hostId = $scope.post.Service.host_id;
            //May be triggered by watch from "Create another"
            if(hostId === 0 || hostId === null){
                return;
            }

            $http.post("/services/loadElementsByHostId/" + hostId + ".json?angular=true", {}).then(function(result){
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
            if(servicetemplateId === 0){
                //May be triggered by watch from "Create another"
                $scope.init = false;
                return;
            }

            var hostId = $scope.post.Service.host_id;
            //May be triggered by watch from "Create another"
            if(hostId === 0){
                return;
            }

            $http.post("/services/loadServicetemplate/" + servicetemplateId + "/" + hostId + ".json?angular=true", {}).then(function(result){
                $scope.servicetemplate = result.data.servicetemplate;
                setValuesFromServicetemplate();
                $scope.$watch('post.Service.name', function(){
                   if($scope.post.Service.name == $scope.servicetemplate.Servicetemplate.name){
                       $scope.showDuplicateName = true;
                   }else {
                       $scope.showDuplicateName = false;
                   }
                });
                //Services add. At this point all contacts must be inherited from somewhere because the service does not exists jet.
                $scope.data.disableInheritance = false;
                $scope.data.areContactsInheritedFromHosttemplate = result.data.areContactsInheritedFromHosttemplate;
                $scope.data.areContactsInheritedFromHost = result.data.areContactsInheritedFromHost;
                $scope.data.areContactsInheritedFromServicetemplate = result.data.areContactsInheritedFromServicetemplate;

                $scope.inheritedContactsAndContactgroups = result.data.contactsAndContactgroups;
                $scope.post.Service.contacts._ids = result.data.contactsAndContactgroups.contacts._ids;
                $scope.post.Service.contactgroups._ids = result.data.contactsAndContactgroups.contactgroups._ids;

                $('#ContactBlocker').block({
                    message: null,
                    overlayCSS: {
                        opacity: 0.5,
                        cursor: 'not-allowed',
                        'background-color': 'rgb(255, 255, 255)'
                    }
                });
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
            $http.post("/services/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('ServicesEdit', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                if($scope.data.createAnother === false){
                    RedirectService.redirectWithFallback('ServicesNotMonitored');
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

        if(hostId > 0){
            $scope.loadHosts('', hostId);
            $scope.loadElements();
        }else{
            $scope.loadHosts('');
        }
        $scope.loadCommands();


        jQuery(function(){
            $('.tagsinput').tagsinput();
        });

        $scope.$watch('post.Service.host_id', function(){
            if($scope.init){
                return;
            }
            $scope.loadElements();
        }, true);

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

                $scope.post.Service.contacts._ids = $scope.inheritedContactsAndContactgroups.contacts._ids;
                $scope.post.Service.contactgroups._ids = $scope.inheritedContactsAndContactgroups.contactgroups._ids;
            }
        });


    });
