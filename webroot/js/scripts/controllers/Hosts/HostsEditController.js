angular.module('openITCOCKPIT')
    .controller('HostsEditController', function($scope, $http, SudoService, $state, NotyService, LocalStorageService, $stateParams, RedirectService){

        $scope.id = $stateParams.id;

        $scope.data = {
            dnsLookUp: LocalStorageService.getItemWithDefault('HostsDnsLookUpEnabled', 'false') === 'true',
            dnsHostnameNotFound: false,
            dnsAddressNotFound: false,
            isPrimaryContainerChangeable: false,
            allowSharing: false,
            isHostOnlyEditableDueToHostSharing: false,
            areContactsInheritedFromHosttemplate: false,
            disableInheritance: false
        };

        $scope.post = {
            Host: {}
        };

        $scope.init = true;

        var setValuesFromHosttemplate = function(){
            var fields = [
                'description',
                'hosttemplate_id',
                'address',
                'command_id',
                'eventhandler_command_id',
                'check_interval',
                'retry_interval',
                'max_check_attempts',
                'first_notification_delay',
                'notification_interval',
                'notify_on_down',
                'notify_on_unreachable',
                'notify_on_recovery',
                'notify_on_flapping',
                'notify_on_downtime',
                'flap_detection_enabled',
                'flap_detection_on_up',
                'flap_detection_on_down',
                'flap_detection_on_unreachable',
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
                'host_url'
            ];

            for(var index in fields){
                var field = fields[index];
                if($scope.hosttemplate.Hosttemplate.hasOwnProperty(field)){
                    $scope.post.Host[field] = $scope.hosttemplate.Hosttemplate[field];
                }
            }

            var hasManyAssociations = [
                'hostgroups', 'contacts', 'contactgroups', 'prometheus_exporters'
            ];
            for(index in hasManyAssociations){
                field = hasManyAssociations[index];
                if($scope.hosttemplate.Hosttemplate.hasOwnProperty(field)){
                    $scope.post.Host[field]._ids = $scope.hosttemplate.Hosttemplate[field]._ids;
                }
            }

            $scope.post.Host.customvariables = [];
            for(index in $scope.hosttemplate.Hosttemplate.customvariables){
                $scope.post.Host.customvariables.push({
                    objecttype_id: 512, //OBJECT_HOSTTEMPLATE because value from host template
                    name: $scope.hosttemplate.Hosttemplate.customvariables[index].name,
                    value: $scope.hosttemplate.Hosttemplate.customvariables[index].value
                });
            }

            $scope.post.Host.hostcommandargumentvalues = [];
            for(index in $scope.hosttemplate.Hosttemplate.hosttemplatecommandargumentvalues){
                $scope.post.Host.hostcommandargumentvalues.push({
                    commandargument_id: $scope.hosttemplate.Hosttemplate.hosttemplatecommandargumentvalues[index].commandargument_id,
                    value: $scope.hosttemplate.Hosttemplate.hosttemplatecommandargumentvalues[index].value,
                    commandargument: $scope.hosttemplate.Hosttemplate.hosttemplatecommandargumentvalues[index].commandargument
                });
            }

            $('#HostTagsInput').tagsinput('removeAll');
            $('#HostTagsInput').tagsinput('add', $scope.post.Host.tags);
        };

        $scope.submitSaveHostAndAssignMatchingServicetemplateGroups = function(){
            $scope.post.save_host_and_assign_matching_servicetemplate_groups = true;
            $scope.submit();
        };

        var highlight = function($selector){
            $selector = $selector.parent();
            var $div = $('<div class="highlight"></div>');
            $div.css({
                'width': $selector.width() + 'px',
                'height': $selector.height() + 'px',
                'left': $selector.css('padding-left')
            });
            $selector.append($div);
            $div.fadeOut(800, function(){
                $div.remove();
            });
        };

        $scope.loadContainers = function(){
            var params = {
                'angular': true
            };

            $http.get("/hosts/loadContainers.json", {
                params: params
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.loadHost();
            });
        };

        $scope.loadHost = function(){
            var params = {
                'angular': true
            };

            $http.get("/hosts/edit/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.commands = result.data.commands;
                $scope.post.Host = result.data.host.Host;
                $scope.hosttemplate = result.data.hosttemplate;
                $scope.hostType = result.data.hostType;

                $scope.data.isPrimaryContainerChangeable = result.data.isPrimaryContainerChangeable;
                $scope.data.allowSharing = result.data.allowSharing;
                $scope.data.isHostOnlyEditableDueToHostSharing = result.data.isHostOnlyEditableDueToHostSharing;
                $scope.data.areContactsInheritedFromHosttemplate = result.data.areContactsInheritedFromHosttemplate;

                if($scope.data.areContactsInheritedFromHosttemplate){
                    $('#ContactBlocker').block({
                        message: null,
                        overlayCSS: {
                            opacity: 0.5,
                            cursor: 'not-allowed',
                            'background-color': 'rgb(255, 255, 255)'
                        }
                    });
                }

                if(result.data.isHostOnlyEditableDueToHostSharing === true){
                    //User has only permissions to edit this host via host sharing.
                    //We fake the displayed primary container id for the user to not expose any container names
                    $scope.containers = result.data.fakeDisplayContainers;
                }

                jQuery(function(){
                    $('.tagsinput').tagsinput();
                });

                $scope.loadElements();
                $scope.loadParentHosts('');

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


        $scope.loadCommandArguments = function(){
            var params = {
                'angular': true
            };

            var commandId = $scope.post.Host.command_id;

            $http.get("/hosts/loadCommandArguments/" + commandId + "/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.post.Host.hostcommandargumentvalues = result.data.hostcommandargumentvalues;
            });
        };

        $scope.loadElements = function(){
            var containerId = $scope.post.Host.container_id;

            $http.post("/hosts/loadElementsByContainerId/" + containerId + "/" + $scope.id + ".json?angular=true", {
                empty: true
            }).then(function(result){
                $scope.hosttemplates = result.data.hosttemplates;
                $scope.timeperiods = result.data.timeperiods;
                $scope.checkperiods = result.data.checkperiods;
                $scope.contacts = result.data.contacts;
                $scope.contactgroups = result.data.contactgroups;
                $scope.hostgroups = result.data.hostgroups;
                $scope.satellites = result.data.satellites;
                $scope.sharingContainers = result.data.sharingContainers;
                $scope.exporters = result.data.exporters;
            });
        };

        $scope.loadParentHosts = function(searchString){
            var containerId = $scope.post.Host.container_id;

            $http.get("/hosts/loadParentHostsByString.json", {
                params: {
                    'angular': true,
                    'filter[Hosts.name]': searchString,
                    'selected[]': $scope.post.Host.parenthosts._ids,
                    'containerId': containerId,
                    'hostId': $scope.id
                }
            }).then(function(result){
                $scope.parenthosts = result.data.hosts;
            });
        };

        $scope.loadHosttemplate = function(){
            var hosttemplateId = $scope.post.Host.hosttemplate_id;

            $http.post("/hosts/loadHosttemplate/" + hosttemplateId + ".json?angular=true", {
                empty: true
            }).then(function(result){
                $scope.hosttemplate = result.data.hosttemplate;
                setValuesFromHosttemplate();
            });

            setTimeout(function(){
                //Enable post.Host.command_id $watch
                $scope.init = false;
            }, 500);
        };

        $scope.setPriority = function(priority){
            $scope.post.Host.priority = parseInt(priority, 10);
        };

        $scope.addMacro = function(){
            $scope.post.Host.customvariables.push({
                objecttype_id: 256, //OBJECT_HOST
                name: '',
                value: '',
                password: 0
            });
        };

        $scope.deleteMacroCallback = function(macro, index){
            $scope.post.Host.customvariables.splice(index, 1);
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

        $scope.runDnsLookup = function(lookupByHostname){
            $scope.data.dnsHostnameNotFound = false;
            $scope.data.dnsAddressNotFound = false;
            if($scope.data.dnsLookUp === false){
                return;
            }
            var data = {
                hostname: null,
                address: null
            };

            if(lookupByHostname){
                if($scope.post.Host.name == ''){
                    return;
                }
                data.hostname = $scope.post.Host.name;
            }else{
                if($scope.post.Host.address == ''){
                    return;
                }
                data.address = $scope.post.Host.address;
            }

            $http.post("/hosts/runDnsLookup.json?angular=true",
                data
            ).then(function(result){
                if(lookupByHostname){
                    var address = result.data.result.address;
                    if(address === null){
                        $scope.data.dnsHostnameNotFound = true;
                    }else{
                        $scope.data.dnsHostnameNotFound = false;
                        $scope.post.Host.address = address;
                        highlight($('#HostAddress'));
                    }
                }else{
                    var hostname = result.data.result.hostname;
                    if(hostname === null){
                        $scope.data.dnsAddressNotFound = true;
                    }else{
                        $scope.data.dnsAddressNotFound = false;
                        $scope.post.Host.name = hostname;
                        highlight($('#HostName'));
                    }
                }
            }, function errorCallback(result){
                NotyService.genericError({
                    message: 'Error while running DNS lookup'
                });
            });
        };

        $scope.restoreTemplateTags = function(){
            $scope.post.Host.tags = $scope.hosttemplate.Hosttemplate.tags;
            $('#HostTagsInput').tagsinput('removeAll');
            $('#HostTagsInput').tagsinput('add', $scope.hosttemplate.Hosttemplate.tags);
        };

        $scope.submit = function(){
            $http.post("/hosts/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('HostsEdit', {id: $scope.id});
                var showWarning = false;
                var timeout = 3500;
                var message = '<u><a href="' + url + '" class="txt-color-white"> '
                    + $scope.successMessage.objectName
                    + '</a></u> ' + $scope.successMessage.message;

                if($scope.post.hasOwnProperty('save_host_and_assign_matching_servicetemplate_groups')
                    && $scope.post.save_host_and_assign_matching_servicetemplate_groups){
                    if(!!result.data.services._ids){
                        message = '<u><a href="' + url + '" class="txt-color-white"> '
                            + $scope.successMessage.objectName
                            + '</a></u> ' + sprintf($scope.successMessage.allocate_message, result.data.services._ids.length);
                        timeout = 15000;
                    }
                    if(result.data.servicetemplategroups_removed_count > 0){
                        showWarning = true;
                        message += sprintf($scope.successMessage.allocate_warning, result.data.servicetemplategroups_removed_count);
                        timeout = 15000;
                    }
                    if(!!result.data.disabled_services._ids){
                        if(result.data.services_disabled_count > 0){
                            message += sprintf($scope.successMessage.disable_message, result.data.services_disabled_count);
                            timeout = 15000;
                        }
                    }
                }

                if(showWarning === true){
                    NotyService.genericWarning({
                        message: message,
                        timeout: 15000
                    });
                }else{
                    NotyService.genericSuccess({
                        message: message,
                        timeout: timeout
                    });
                }

                if($state.hasOwnProperty('previous') && $state.previous !== null && $state.previous.name !== "" && $state.previous.url !== "^"){
                    $state.go($state.previous.name, $state.previous.params).then(function(){
                        NotyService.scrollTop();
                    });
                }else{
                    RedirectService.redirectWithFallback('HostsIndex');
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

        $scope.$watch('post.Host.container_id', function(){
            if($scope.init){
                return;
            }
            $scope.loadElements();
            $scope.loadParentHosts('');
        }, true);

        $scope.$watch('post.Host.hosttemplate_id', function(){
            if($scope.init){
                return;
            }

            //$scope.init = true; //Disable post.Host.command_id $watch
            $scope.loadHosttemplate();
        }, true);

        $scope.$watch('post.Host.command_id', function(){
            if($scope.init){
                return;
            }
            $scope.loadCommandArguments();
        }, true);


        $scope.$watch('data.dnsLookUp', function(){
            if($scope.init){
                return;
            }

            if($scope.data.dnsLookUp === false){
                $scope.data.dnsHostnameNotFound = false;
                $scope.data.dnsAddressNotFound = false;
            }

            LocalStorageService.setItem('HostsDnsLookUpEnabled', $scope.data.dnsLookUp);
        }, true);

        $scope.$watch('data.disableInheritance', function(){
            if($scope.data.areContactsInheritedFromHosttemplate === false){
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

                if(typeof $scope.hosttemplate !== "undefined"){
                    $scope.post.Host.contacts._ids = $scope.hosttemplate.Hosttemplate.contacts._ids;
                    $scope.post.Host.contactgroups._ids = $scope.hosttemplate.Hosttemplate.contactgroups._ids;
                }
            }
        });


    });
