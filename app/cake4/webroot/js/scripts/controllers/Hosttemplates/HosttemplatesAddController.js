angular.module('openITCOCKPIT')
    .controller('HosttemplatesAddController', function($scope, $http, SudoService, $state, NotyService, RedirectService){

        $scope.data = {
            createAnother: false
        };

        var clearForm = function(){
            $scope.post = {
                Hosttemplate: {
                    name: '',
                    description: '',
                    command_id: 0,
                    eventhandler_command_id: 0,
                    check_interval: 3600,
                    retry_interval: 60,
                    max_check_attempts: 3,
                    first_notification_delay: 0,
                    notification_interval: 7200,
                    notify_on_down: 1,
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
                    host_url: '',
                    contacts: {
                        _ids: []
                    },
                    contactgroups: {
                        _ids: []
                    },
                    hostgroups: {
                        _ids: []
                    },
                    customvariables: [],
                    hosttemplatecommandargumentvalues: []
                }
            };
        };
        clearForm();

        $scope.init = true;


        $scope.loadContainers = function(){
            var params = {
                'angular': true
            };

            $http.get("/hosttemplates/loadContainers.json", {
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

            $http.get("/hosttemplates/loadCommands.json", {
                params: params
            }).then(function(result){
                $scope.commands = result.data.commands;
                $scope.init = false;
            });
        };

        $scope.loadCommandArguments = function(){
            var params = {
                'angular': true
            };

            var commandId = $scope.post.Hosttemplate.command_id;

            //May be triggered by watch from "Create another"
            if(commandId === 0){
                return;
            }

            $http.get("/hosttemplates/loadCommandArguments/" + commandId + ".json", {
                params: params
            }).then(function(result){
                $scope.post.Hosttemplate.hosttemplatecommandargumentvalues = result.data.hosttemplatecommandargumentvalues;
                $scope.init = false;
            });
        };

        $scope.loadElements = function(){
            var containerId = $scope.post.Hosttemplate.container_id;

            //May be triggered by watch from "Create another"
            if(containerId === 0){
                return;
            }

            $http.post("/hosttemplates/loadElementsByContainerId/" + containerId + ".json?angular=true", {}).then(function(result){
                $scope.timeperiods = result.data.timeperiods;
                $scope.checkperiods = result.data.checkperiods;
                $scope.contacts = result.data.contacts;
                $scope.contactgroups = result.data.contactgroups;
                $scope.hostgroups = result.data.hostgroups;
            });
        };

        $scope.setPriority = function(priority){
            $scope.post.Hosttemplate.priority = parseInt(priority, 10);
        };

        $scope.addMacro = function(){
            $scope.post.Hosttemplate.customvariables.push({
                objecttype_id: 512, //OBJECT_HOSTTEMPLATE
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
            $http.post("/hosttemplates/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('HosttemplatesEdit', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                if($scope.data.createAnother === false){
                    RedirectService.redirectWithFallback('HosttemplatesIndex');
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

        $scope.loadContainers();
        $scope.loadCommands();

        jQuery(function(){
            $('.tagsinput').tagsinput();
        });

        $scope.$watch('post.Hosttemplate.container_id', function(){
            if($scope.init){
                return;
            }
            $scope.loadElements();
        }, true);

        $scope.$watch('post.Hosttemplate.command_id', function(){
            if($scope.init){
                return;
            }
            $scope.loadCommandArguments();
        }, true);


    });
