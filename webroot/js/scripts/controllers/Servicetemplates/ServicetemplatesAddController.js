angular.module('openITCOCKPIT')
    .controller('ServicetemplatesAddController', function($scope, $http, SudoService, $state, NotyService, RedirectService, $stateParams){

        var servicetemplateTypeId = 1; // GENERIC_SERVICE
        if(typeof $stateParams.servicetemplateTypeId !== "undefined"){
            if($stateParams.servicetemplateTypeId !== null){
                servicetemplateTypeId = parseInt($stateParams.servicetemplateTypeId, 10);
            }
        }

        $scope.data = {
            createAnother: false
        };

        $scope.typeDetails = {};

        var clearForm = function(){
            $scope.post = {
                Servicetemplate: {
                    name: '',
                    template_name: '',
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
                    servicetemplatetype_id: servicetemplateTypeId,
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
                    servicetemplatecommandargumentvalues: [],
                    servicetemplateeventcommandargumentvalues: []
                }
            };
        };
        clearForm();

        $scope.init = true;


        $scope.loadContainers = function(){
            var params = {
                'angular': true
            };

            $http.get("/servicetemplates/loadContainers.json", {
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

            $http.get("/servicetemplates/loadCommands.json", {
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

            var commandId = $scope.post.Servicetemplate.command_id;

            //May be triggered by watch from "Create another"
            if(commandId === 0){
                return;
            }

            $http.get("/servicetemplates/loadCommandArguments/" + commandId + ".json", {
                params: params
            }).then(function(result){
                $scope.post.Servicetemplate.servicetemplatecommandargumentvalues = result.data.servicetemplatecommandargumentvalues;
                $scope.init = false;
            });
        };

        $scope.loadEventHandlerCommandArguments = function(){
            var params = {
                'angular': true
            };

            var eventHandlerCommandId = $scope.post.Servicetemplate.eventhandler_command_id;

            //May be triggered by watch from "Create another"
            if(eventHandlerCommandId === 0){
                return;
            }

            $http.get("/servicetemplates/loadEventhandlerCommandArguments/" + eventHandlerCommandId + ".json", {
                params: params
            }).then(function(result){
                $scope.post.Servicetemplate.servicetemplateeventcommandargumentvalues = result.data.servicetemplateeventhandlercommandargumentvalues;
                $scope.init = false;
            });
        };

        $scope.loadElements = function(){
            var containerId = $scope.post.Servicetemplate.container_id;

            //May be triggered by watch from "Create another"
            if(containerId === 0){
                return;
            }

            $http.post("/servicetemplates/loadElementsByContainerId/" + containerId + ".json?angular=true", {}).then(function(result){
                $scope.timeperiods = result.data.timeperiods;
                $scope.checkperiods = result.data.checkperiods;
                $scope.contacts = result.data.contacts;
                $scope.contactgroups = result.data.contactgroups;
                $scope.servicegroups = result.data.servicegroups;
            });
        };

        $scope.loadServicetemplateTypes = function(){
            var params = {
                'angular': true
            };

            $http.get("/servicetemplates/add.json", {
                params: params
            }).then(function(result){
                $scope.servicetemplatetypes = result.data.types;
                $scope.setDetailsForType($scope.post.Servicetemplate.servicetemplatetype_id);

            });
        };


        $scope.setPriority = function(priority){
            $scope.post.Servicetemplate.priority = parseInt(priority, 10);
        };

        $scope.addMacro = function(){
            $scope.post.Servicetemplate.customvariables.push({
                objecttype_id: 4096, //OBJECT_SERVICETEMPLATE
                name: '',
                value: '',
                password: 0
            });
        };

        $scope.deleteMacroCallback = function(macro, index){
            $scope.post.Servicetemplate.customvariables.splice(index, 1);
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

        $scope.setDetailsForType = function(){
            for(index in $scope.servicetemplatetypes){
                if($scope.servicetemplatetypes[index].key === $scope.post.Servicetemplate.servicetemplatetype_id){
                    $scope.typeDetails = $scope.servicetemplatetypes[index].value;
                    return;
                }
            }
        };

        $scope.submit = function(){
            $http.post("/servicetemplates/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('ServicetemplatesEdit', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                if($scope.data.createAnother === false){
                    RedirectService.redirectWithFallback('ServicetemplatesIndex');
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

        //Fire on page load

        $scope.loadContainers();
        $scope.loadCommands();
        $scope.loadServicetemplateTypes();

        jQuery(function(){
            $('.tagsinput').tagsinput();
        });

        $scope.$watch('post.Servicetemplate.container_id', function(){
            if($scope.init){
                return;
            }
            $scope.loadElements();
        }, true);

        $scope.$watch('post.Servicetemplate.command_id', function(){
            if($scope.init){
                return;
            }
            $scope.loadCommandArguments();
        }, true);

        $scope.$watch('post.Servicetemplate.eventhandler_command_id', function(){
            if($scope.init){
                return;
            }

            if($scope.post.Servicetemplate.eventhandler_command_id === 0){
                //"None" selected
                $scope.post.Servicetemplate.servicetemplateeventcommandargumentvalues = [];
                return;
            }

            $scope.loadEventHandlerCommandArguments();
        }, true);

        $scope.$watch('post.Servicetemplate.servicetemplatetype_id', function(){
            if($scope.init){
                return;
            }
            $scope.setDetailsForType();
        }, true);

    });
