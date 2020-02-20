angular.module('openITCOCKPIT')
    .controller('AgentconnectorsAddController', function($scope, $http, QueryStringService, $state, $stateParams, NotyService, $interval){

        $scope.pullMode = false;
        $scope.pushMode = false;
        $scope.installed = false;
        $scope.configured = false;
        $scope.servicesConfigured = false;
        $scope.checkdataRequestInterval = null;

        $scope.resetAgentConfiguration = function(){
            $scope.pullMode = false;
            $scope.pushMode = false;
            $scope.configured = false;
            $scope.installed = false;
            $scope.servicesConfigured = false;

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
                fans: [],
                temperatures: [],
                battery: false,
                net_io: [],
                net_stats: [],
                processes: [],
                windows_services: [],
                docker_running: [],
                docker_cpu: [],
                docker_memory: [],
                qemu_running: [],
                customchecks: []
            };

            $scope.agentconfigCustomchecks = {
                'max_worker_threads': 8
            };

            if($scope.checkdataRequestInterval !== null){
                $interval.cancel($scope.checkdataRequestInterval);
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
            console.log($scope.choosenServicesToMonitor);


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
                            $scope.choosenServicesToMonitor.temperatures.push('coretemp');
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

                var params = {
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
        });

        $scope.load();
    });
