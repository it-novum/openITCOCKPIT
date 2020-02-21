angular.module('openITCOCKPIT')
    .controller('AgentconnectorsAddController', function($scope, $http, QueryStringService, $state, $stateParams, NotyService, $interval){

        $scope.pullMode = false;
        $scope.pushMode = false;
        $scope.installed = false;
        $scope.configured = false;
        $scope.servicesConfigured = false;
        $scope.checkdataRequestInterval = null;
        $scope.checkFinishedStateInterval = null;

        $scope.resetAgentConfiguration = function(){
            $scope.pullMode = false;
            $scope.pushMode = false;
            $scope.configured = false;
            $scope.installed = false;
            $scope.servicesConfigured = false;
            $scope.finished = false;
            $scope.expectedServiceCreations = 0;
            $scope.processedServiceCreations = 0;

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
                'oitc_hostuuid': '',
                'oitc_url': '',
                'oitc_apikey': '',
                'oitc_interval': 60,
                'oitc_enabled': false
            };

            $scope.choosenServicesToMonitor = {
                cpu_percentage: true,
                system_load: true,
                memory: true,
                swap: true,
                disk_io: [],
                disks: [],
                sensors__Fan: [],
                sensors__Temperature: [],
                sensors__Battery: false,
                net_io: [],
                net_stats: [],
                processes: [],
                windows_services: [],
                dockerstats__DockerContainerRunning: [],
                dockerstats__DockerContainerCPU: [],
                dockerstats__DockerContainerMemory: [],
                qemustats__QemuVMRunning: [],
                customchecks: []
            };

            $scope.agentconfigCustomchecks = {
                'max_worker_threads': 8
            };

            if($scope.checkdataRequestInterval !== null){
                $interval.cancel($scope.checkdataRequestInterval);
            }
            if($scope.checkFinishedStateInterval !== null){
                $interval.cancel($scope.checkFinishedStateInterval);
            }

            $scope.checkdata = false;
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
            }).then(function(result){
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

                if(option.includes('oitc_')){
                    tmpOitcTemplate += option.replace('oitc_', '') + ' = ' + value + '\n';
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

        $scope.continueWithPullMode = function(){
            $scope.pullMode = true;
            $scope.pushMode = false;

            //$scope.agentconfig.address = $scope.host.address;
        };

        $scope.continueWithPushMode = function(){
            $scope.pushMode = true;
            $scope.pullMode = false;

            //$scope.agentconfig.address = $scope.host.address;
            $scope.agentconfig.oitc_hostuuid = $scope.host.uuid;
            $scope.agentconfig.oitc_enabled = true;
        };

        $scope.continueWithAgentInstallation = function(){
            $scope.configured = true;
            NotyService.scrollTop();
        };
        $scope.skipConfigurationGeneration = function(){
            $scope.pushMode = true;
            $scope.configured = true;
            $scope.installed = true;
        };
        $scope.saveAgentServices = function(){
            $scope.servicesConfigured = true;

            $http.get('/agentchecks/getAgentchecksForMapping.json').then(function(result){
                if(result.data.agentchecks_mapping && result.data.agentchecks_mapping !== ''){
                    $scope.agentchecksMapping = result.data.agentchecks_mapping;

                    for(var key in $scope.choosenServicesToMonitor){
                        const service = $scope.getServiceMappingForAgentKey(key);
                        if(service === false){
                            continue;
                        }
                        if(Array.isArray($scope.choosenServicesToMonitor[key]) && $scope.choosenServicesToMonitor[key].length > 0){
                            for(var i in $scope.choosenServicesToMonitor[key]){
                                if($scope.choosenServicesToMonitor[key].hasOwnProperty(i)){
                                    $scope.expectedServiceCreations++;
                                    $scope.createService($scope.updateServicecommandargumentvaluesForAgentKey(service, key, $scope.choosenServicesToMonitor[key][i]));
                                }
                            }
                        }else if(typeof ($scope.choosenServicesToMonitor[key]) === 'boolean' && $scope.choosenServicesToMonitor[key]){
                            $scope.expectedServiceCreations++;
                            $scope.createService(service);
                        }
                    }
                    $scope.checkFinishedState();
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

        $scope.checkFinishedState = function(){
            //seems that all needed services are created
            if($scope.processedServiceCreations === $scope.expectedServiceCreations){
                $scope.finished = true;
                if($scope.checkFinishedStateInterval !== null){
                    $interval.cancel($scope.checkFinishedStateInterval);
                }
            }else if($scope.checkFinishedStateInterval === null){
                $scope.checkFinishedStateInterval = $interval(function(){
                    $scope.checkFinishedState();
                }, 500);
            }
        };

        $scope.createService = function(service){
            $http.post('/services/add.json?angular=true',
                {
                    Service: service
                }
            ).then(function(result){
                $scope.processedServiceCreations++;
            }, function errorCallback(result){
                $scope.processedServiceCreations++;
                NotyService.genericError({
                    message: 'Error while saving service for '
                });
            });
        };

        $scope.updateServicecommandargumentvaluesForAgentKey = function(service, key, value){
            var customarguments = [];
            var myservice = JSON.parse(JSON.stringify(service));

            /*
                No customargument updates needed for:
                ['agent', 'cpu_percentage', 'system_load', 'memory', 'swap', 'sensors__Battery']
             */

            if(key === 'dockerstats'){
                customarguments[1] = value;
            }
            if(key === 'disk_io'){
                customarguments[2] = value;
            }
            if(key === 'disks'){
                customarguments[2] = value;
            }
            if(key === 'sensors__Fan'){
                customarguments[2] = value;
            }
            if(key === 'sensors__Temperature'){
                customarguments[3] = value;
            }
            if(key === 'net_io'){
                customarguments[6] = value;
            }
            if(key === 'net_stats'){
                customarguments[1] = value;
            }
            if(key === 'processes'){
                customarguments[6] = value;
            }
            if(key === 'dockerstats__DockerContainerRunning'){
                customarguments[1] = value;
            }
            if(key === 'dockerstats__DockerContainerCPU'){
                customarguments[1] = value;
            }
            if(key === 'dockerstats__DockerContainerMemory'){
                customarguments[1] = value;
            }
            if(key === 'qemustats__QemuVMRunning'){
                customarguments[0] = 'name';
                if(Number.isInteger(value)){
                    customarguments[0] = 'id';
                }else if($scope.isUuid(value)){
                    customarguments[0] = 'uuid';
                }
                customarguments[1] = value;
            }
            if(key === 'customchecks'){
                customarguments[0] = value;
            }
            if(key === 'windows_services'){
                customarguments[2] = value;
            }

            if($scope.countObj(customarguments) !== 0){
                for(var i = 0; i < myservice.servicecommandargumentvalues.length; i++){
                    if(customarguments[i]){
                        myservice.servicecommandargumentvalues[i].value = customarguments[i];
                    }
                }
            }

            return myservice;
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
            // start interval to check /agentconnector/getLatestCheckDataByHostUuid/$uuid.json
            // process results to agent service templates? or poller host config?

            if(!$scope.checkdata){
                $scope.checkdataRequestInterval = $interval(function(){
                    if($scope.checkdata){
                        $interval.cancel($scope.checkdataRequestInterval);
                    }else{
                        $scope.getLatestCheckDataByHostUuid();
                    }
                }, 5000);
            }
        };

        $scope.createCheckdataDependingPreselection = function(){
            if($scope.checkdata){
                if($scope.checkdata.disks && $scope.countObj($scope.checkdata.disks) > 0){
                    for(var i = 0; i < $scope.countObj($scope.checkdata.disks); i++){
                        if($scope.checkdata.disks[i].disk.mountpoint === '/'){
                            $scope.choosenServicesToMonitor.disks.push('/');
                        }
                        if($scope.checkdata.disks[i].disk.mountpoint === 'C:\\'){
                            $scope.choosenServicesToMonitor.disks.push('C:\\');
                        }
                    }
                }
                if($scope.checkdata.sensors.temperatures && $scope.countObj($scope.checkdata.sensors.temperatures) > 0){
                    for(var key in $scope.checkdata.sensors.temperatures){
                        if(key === 'coretemp'){
                            $scope.choosenServicesToMonitor.sensors__Temperature.push('coretemp');
                        }
                    }
                }
                if($scope.checkdata.customchecks && $scope.countObj($scope.checkdata.customchecks) > 0){
                    for(var key in $scope.checkdata.customchecks){
                        $scope.choosenServicesToMonitor.customchecks.push(key);
                    }
                }
            }
        };

        $scope.getLatestCheckDataByHostUuid = function(){
            if($scope.host.uuid !== 'undefined' && $scope.host.uuid !== ''){
                $http.get('/agentconnector/getLatestCheckDataByHostUuid/' + $scope.host.uuid + '.json').then(function(result){
                    if(result.data.checkdata && result.data.checkdata !== ''){
                        $scope.checkdata = result.data.checkdata;
                        $scope.createCheckdataDependingPreselection();
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
                        $scope.getLatestCheckDataByHostUuid();
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
            if($scope.checkdataRequestInterval !== null){
                $interval.cancel($scope.checkdataRequestInterval);
            }
            if($scope.checkFinishedStateInterval !== null){
                $interval.cancel($scope.checkFinishedStateInterval);
            }
        });

        $scope.load();
    });
