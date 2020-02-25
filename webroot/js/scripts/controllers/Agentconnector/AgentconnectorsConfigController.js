angular.module('openITCOCKPIT')
    .controller('AgentconnectorsConfigController', function($scope, $http, QueryStringService, $state, $stateParams, NotyService, $interval){

        $scope.pullMode = false;
        $scope.pushMode = false;
        $scope.installed = false;
        $scope.configured = false;
        $scope.servicesConfigured = false;
        $scope.servicesToCreateRequestInterval = null;

        $scope.resetAgentConfiguration = function(){
            $scope.pullMode = false;
            $scope.pushMode = false;
            $scope.configured = false;
            $scope.installed = false;
            $scope.servicesConfigured = false;
            $scope.finished = false;
            $scope.agentconfigId = null;
            $scope.remoteAgentConfig = null;

            $scope.agentconfig = {
                address: '0.0.0.0',
                port: 3333,
                interval: 30,
                'try-autossl': false,
                verbose: false,
                stacktrace: false,
                'config-update-mode': false,
                auth: '',
                customchecks: false,
                'temperature-fahrenheit': false,
                dockerstats: false,
                qemustats: false,
                cpustats: true,
                sensorstats: true,
                processstats: true,
                'processstats-including-child-ids': false,
                netstats: true,
                diskstats: true,
                netio: true,
                diskio: true,
                winservices: true,
                'oitc-hostuuid': '',
                'oitc-url': '',
                'oitc-apikey': '',
                'oitc-interval': 60,
                'oitc-enabled': false
            };

            $scope.choosenServicesToMonitor = {
                CpuTotalPercentage: false,
                SystemLoad: false,
                MemoryUsage: false,
                SwapUsage: false,
                DiskUsage: [],
                DiskIO: [],
                Fan: [],
                Temperature: [],
                Battery: false,
                NetIO: [],
                NetStats: [],
                Process: [],
                WindowsService: [],
                DockerContainerRunning: [],
                DockerContainerCPU: [],
                DockerContainerMemory: [],
                QemuVMRunning: [],
                Customcheck: []
            };

            $scope.agentconfigCustomchecks = {
                'max_worker_threads': 8
            };

            if($scope.servicesToCreateRequestInterval !== null){
                $interval.cancel($scope.servicesToCreateRequestInterval);
            }

            $scope.serviceQueue = [];
            $scope.servicesToCreate = false;
            $scope.configTemplate = '';
            $scope.configTemplateCustomchecks = '';
            $scope.host = {
                id: false
            };
            document.getElementById('AgentHost').disabled = false;
            $scope.updateConfigTemplate();
        };


        $scope.hosts = {};

        $scope.load = function(){
            $scope.resetAgentConfiguration();
            $scope.host.id = QueryStringService.getStateValue($stateParams, 'hostId', false);
            $scope.loadHosts('');
        };

        $scope.loadHosts = function(searchString){
            $http.get('/hosts/loadHostsByString.json', {
                params: {
                    'angular': true,
                    'filter[Hosts.name]': searchString,
                    'selected[]': $scope.host.id ? $scope.host.id : null,
                    'includeDisabled': 'false'
                }
            }).then(function(result){
                $scope.hosts = result.data.hosts;
            });
        };

        $scope.changetrust = function(id, trust, singletrust){
            $http.post('/agentconnector/changetrust.json?angular=true', {
                'id': id,
                'trust': trust
            }).then(function(){
                if(singletrust){
                    NotyService.genericSuccess();
                    $scope.load();
                }
            }, function errorCallback(result){
                if(singletrust){
                    NotyService.genericError({message: result.error});
                }
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.updateConfigTemplate = function(){
            var tmpDefaultTemplateCustomchecks = '[default]\n';
            var tmpExampleTemplateCustomchecks = '\n[check_users]\n' +
                '#  command = /usr/lib/nagios/plugins/check_users -w 5 -c 10\n' +
                '#  interval = 30\n' +
                '#  timeout = 5\n' +
                '#  enabled = true';
            var tmpDefaultTemplate = '[default]\n';
            var tmpOitcTemplate = '\n[oitc]\n';
            for(var option in $scope.agentconfig){
                var value = $scope.agentconfig[option];

                if(option.includes('oitc-')){
                    tmpOitcTemplate += option.replace('oitc-', '') + ' = ' + value + '\n';
                    continue;
                }
                if(option === 'customchecks'){
                    value = '';
                    if($scope.agentconfig[option] === true){
                        value = '/etc/openitcockpit-agent/customchecks.cnf';
                    }
                }
                tmpDefaultTemplate += option + ' = ' + value + '\n';
            }
            for(var ccoption in $scope.agentconfigCustomchecks){
                tmpDefaultTemplateCustomchecks += ccoption + ' = ' + $scope.agentconfigCustomchecks[ccoption] + '\n';
            }
            $scope.configTemplate = tmpDefaultTemplate + tmpOitcTemplate;
            $scope.configTemplateCustomchecks = tmpDefaultTemplateCustomchecks + tmpExampleTemplateCustomchecks;
        };

        $scope.requestAgentconfig = function(){
            $http.get('/agentconfigs/config/' + $scope.host.id + '.json?angular=true').then(function(result){
                if(result.data.config && result.data.config !== ''){
                    $scope.agentconfig.port = result.data.config.port;
                    if(result.data.config.basic_auth === 1){
                        $scope.agentconfig.auth = result.data.config.username + ':' + result.data.config.password;
                    }
                    if(result.data.config.id){
                        $scope.agentconfigId = result.data.config.id;
                    }
                }
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }
                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.setAgentconfig = function(){
            var basicAuth = 0;
            var basicAuthUsername = '';
            var basicAuthPassword = '';
            if($scope.agentconfig.auth !== ''){
                basicAuth = 1;
                basicAuthUsername = $scope.agentconfig.auth.split(':')[0];
                basicAuthPassword = $scope.agentconfig.auth.split(':')[1];
            }
            if($scope.agentconfigId){   //update
                $http.post('/agentconfigs/edit/' + $scope.agentconfigId + '.json?angular=true',
                    {
                        Agentconfig: {
                            id: $scope.agentconfigId,
                            port: $scope.agentconfig.port,
                            use_https: $scope.agentconfig['try-autossl'],
                            basic_auth: basicAuth,
                            username: basicAuthUsername,
                            password: basicAuthPassword
                        }
                    }
                );
            }else{    //add
                $http.post('/agentconfigs/add.json?angular=true',
                    {
                        Agentconfig: {
                            host_id: $scope.host.id,
                            port: $scope.agentconfig.port,
                            use_https: $scope.agentconfig['try-autossl'],
                            basic_auth: basicAuth,
                            username: basicAuthUsername,
                            password: basicAuthPassword
                        }
                    }
                ).then(function(result){
                    if(result.data.id && result.data.id > 0){
                        $scope.agentconfigId = result.data.id;
                    }
                });
            }
        };

        $scope.continueWithPullMode = function(){
            $scope.requestAgentconfig();
            $scope.pullMode = true;
            $scope.pushMode = false;

            //$scope.agentconfig.address = $scope.host.address;
        };

        $scope.continueWithPushMode = function(){
            $scope.requestAgentconfig();
            $scope.pushMode = true;
            $scope.pullMode = false;

            //$scope.agentconfig.address = $scope.host.address;
            $scope.agentconfig['oitc-hostuuid'] = $scope.host.uuid;
            $scope.agentconfig['oitc-enabled'] = true;
        };

        $scope.continueWithAgentInstallation = function(){
            $scope.configured = true;
            NotyService.scrollTop();
            if($scope.pullMode){
                $scope.setAgentconfig();
            }
        };
        $scope.skipConfigurationGeneration = function(){
            if($scope.seemsPushMode === true){
                $scope.pushMode = true;
            }else if($scope.seemsPullMode === true){
                $scope.pullMode = true;
            }
            $scope.configured = true;
            $scope.installed = true;
        };
        $scope.saveAgentServices = function(){
            $scope.servicesConfigured = true;

            for(var key in $scope.choosenServicesToMonitor){    //kay could be 'Fan' / 'SystemLoad'
                if(Array.isArray($scope.choosenServicesToMonitor[key]) && $scope.choosenServicesToMonitor[key].length > 0){
                    for(let i in $scope.choosenServicesToMonitor[key]){ //i=key -> servicesToCreate.Fan[i]
                        const choosenkey = $scope.choosenServicesToMonitor[key][i];
                        if($scope.servicesToCreate.hasOwnProperty(key) && $scope.servicesToCreate[key].hasOwnProperty(choosenkey)){
                            $scope.enqueueServiceConfig($scope.servicesToCreate[key][choosenkey]);
                        }
                    }
                }else if(typeof ($scope.choosenServicesToMonitor[key]) === 'boolean' && $scope.choosenServicesToMonitor[key] && $scope.servicesToCreate[key]){
                    if($scope.servicesToCreate[key].name){
                        $scope.enqueueServiceConfig($scope.servicesToCreate[key]);
                    }else if($scope.servicesToCreate[key][0] && $scope.servicesToCreate[key][0].name){
                        $scope.enqueueServiceConfig($scope.servicesToCreate[key][0]);
                    }
                }
            }

            $scope.createServices();
        };

        $scope.enqueueServiceConfig = function(service){
            $scope.serviceQueue.push(service);
        };

        $scope.createServices = function(){
            $http.post('/agentconnector/createServices.json?angular=true',
                {
                    serviceConfigs: $scope.serviceQueue,
                    hostId: $scope.host.id
                }
            ).then(function(){
                $scope.finished = true;
                NotyService.genericSuccess({
                    message: 'Agent services successfully created'
                });
            }, function errorCallback(error){
                $scope.finished = true;
                console.warn(error);
                NotyService.genericError({
                    message: 'Error while saving service ' + service.name
                });
            });
        };

        $scope.isUuid = function(string){
            var s = '' + string;
            s = s.match('^[0-9a-f]{8}-[0-9a-f]{4}-[0-5][0-9a-f]{3}-[089ab][0-9a-f]{3}-[0-9a-f]{12}$');
            return s !== null;
        };

        $scope.getServiceMappingForAgentKey = function(name){
            var plugin = null;
            if(name.includes('__')){
                var arr = name.split('__');
                name = arr[0];
                plugin = arr[1];
            }
            for(var i = 0; i < $scope.agentchecksMapping.length; i++){
                if($scope.agentchecksMapping[i].name === name){
                    if(plugin !== null && $scope.agentchecksMapping[i].plugin_name !== plugin){
                        continue;
                    }
                    $scope.agentchecksMapping[i].service.host_id = $scope.host.id;
                    return $scope.agentchecksMapping[i].service;
                }
            }
            return false;
        };

        $scope.continueWithServiceConfiguration = function(){
            NotyService.scrollTop();
            $scope.installed = true;
            // start interval to check /agentconnector/getServicesToCreateByHostUuid/$uuid.json

            if(!$scope.servicesToCreate){
                $scope.getServicesToCreateByHostUuid();
                $scope.servicesToCreateRequestInterval = $interval(function(){
                    if($scope.servicesToCreate){
                        $interval.cancel($scope.servicesToCreateRequestInterval);
                    }else{
                        $scope.getServicesToCreateByHostUuid();
                    }
                }, 5000);
            }
        };

        $scope.createCheckdataDependingPreselection = function(){
            if($scope.servicesToCreate){
                $scope.choosenServicesToMonitor.CpuTotalPercentage = true;
                $scope.choosenServicesToMonitor.SystemLoad = true;
                $scope.choosenServicesToMonitor.MemoryUsage = true;
                $scope.choosenServicesToMonitor.SwapUsage = true;

                if($scope.servicesToCreate.DiskUsage && $scope.countObj($scope.servicesToCreate.DiskUsage) > 0){
                    for(let i = 0; i < $scope.countObj($scope.servicesToCreate.DiskUsage); i++){
                        if($scope.servicesToCreate.DiskUsage[i].agent_wizard_option_description === '/'){
                            $scope.choosenServicesToMonitor.DiskUsage.push(i.toString());
                        }
                        if($scope.servicesToCreate.DiskUsage[i].agent_wizard_option_description === 'C:\\'){
                            $scope.choosenServicesToMonitor.DiskUsage.push(i.toString());
                        }
                    }
                }
                if($scope.servicesToCreate.Temperature && $scope.countObj($scope.servicesToCreate.Temperature) > 0){
                    for(let i = 0; i < $scope.countObj($scope.servicesToCreate.Temperature); i++){
                        if($scope.servicesToCreate.Temperature[i].agent_wizard_option_description === 'coretemp'){
                            $scope.choosenServicesToMonitor.Temperature.push(i.toString());
                        }
                    }
                }
                if($scope.servicesToCreate.Customcheck && $scope.countObj($scope.servicesToCreate.Customcheck) > 0){
                    for(let i = 0; i < $scope.countObj($scope.servicesToCreate.Customcheck); i++){
                        $scope.choosenServicesToMonitor.Customcheck.push(i.toString());
                    }
                }
            }
        };

        $scope.runRemoteConfigUpdate = function(){
            $http.post('/agentconnector/sendNewAgentConfig/' + $scope.host.uuid + '.json?angular=true',
                {
                    config: $scope.agentconfig
                }
            ).then(function(result){
                if(result.data.success && (result.data.success === true || result.data.success === 'true')){
                    NotyService.genericSuccess({
                        message: 'Configuration successfully updated'
                    });
                }else{
                    NotyService.genericError({
                        message: 'Error while trying to remote update agent configuration'
                    });
                }
            }, function errorCallback(){
                NotyService.genericError({
                    message: 'Error while trying to remote update agent configuration'
                });
            });
        };

        $scope.getServicesToCreateByHostUuid = function(){
            if($scope.host.uuid !== 'undefined' && $scope.host.uuid !== ''){
                $http.get('/agentconnector/getServicesToCreateByHostUuid/' + $scope.host.uuid + '.json').then(function(result){
                    if(result.data.servicesToCreate && result.data.servicesToCreate !== ''){
                        $scope.servicesToCreate = result.data.servicesToCreate;
                        $scope.createCheckdataDependingPreselection();
                        if(result.data.mode && result.data.mode !== ''){
                            if(result.data.mode === 'push'){
                                $scope.seemsPushMode = true;
                                $scope.seemsPullMode = false;
                            }
                            if(result.data.mode === 'push'){
                                $scope.seemsPushMode = false;
                                $scope.seemsPullMode = true;
                            }
                        }

                        if(result.data.config && result.data.config !== ''){
                            $scope.remoteAgentConfig = result.data.config;
                            for(var option in $scope.remoteAgentConfig.config){
                                var tmpVal = $scope.remoteAgentConfig.config[option];
                                if(option.includes('interval') || option === 'port'){
                                    tmpVal = Number.parseInt(tmpVal);
                                }else if(tmpVal === 'true'){
                                    tmpVal = true;
                                }else if(tmpVal === 'false'){
                                    tmpVal = false;
                                }
                                $scope.agentconfig[option] = tmpVal;
                            }
                        }
                    }
                }, function errorCallback(result){
                    if(result.status === 403){
                        $state.go('403');
                    }
                    if(result.status === 404){
                        $state.go('404');
                    }
                });
            }

        };

        $scope.countObj = function(obj){
            if(obj && obj instanceof Object && obj !== 'undefined'){
                return Object.keys(obj).length;
            }
        };

        $scope.$watch('host.id', function(){
            if($scope.host.id !== 'undefined' && $scope.host.id > 0){
                document.getElementById('AgentHost').disabled = true;

                const params = {
                    'angular': true
                };

                $http.get('/hosts/loadHostById/' + $scope.host.id + '.json', {
                    params: params
                }).then(function(result){
                    if(result.data.host && result.data.host.uuid){
                        $scope.host = result.data.host;
                        $scope.getServicesToCreateByHostUuid();
                    }
                }, function errorCallback(result){
                    if(result.status === 403){
                        $state.go('403');
                    }
                    if(result.status === 404){
                        $state.go('404');
                    }
                });
            }
        }, true);

        $scope.$watch('agentconfig', function(){
            $scope.updateConfigTemplate();
        }, true);

        $scope.$watch('agentconfigCustomchecks', function(){
            $scope.updateConfigTemplate();
        }, true);

        //Disable interval if object gets removed from DOM.
        $scope.$on('$destroy', function(){
            if($scope.servicesToCreateRequestInterval !== null){
                $interval.cancel($scope.servicesToCreateRequestInterval);
            }
        });

        $scope.load();
    });
