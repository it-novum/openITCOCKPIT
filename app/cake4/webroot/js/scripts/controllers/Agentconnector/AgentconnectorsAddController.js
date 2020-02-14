angular.module('openITCOCKPIT')
    .controller('AgentconnectorsAddController', function($scope, $http, QueryStringService, $state, $stateParams, NotyService){

        $scope.pullMode = false;
        $scope.pushMode = false;
        $scope.installed = false;
        $scope.configured = false;

        $scope.resetAgentConfiguration = function(){
            $scope.pullMode = false;
            $scope.pushMode = false;
            $scope.configured = false;
            $scope.installed = false;

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

            $scope.agentconfigCustomchecks = {
                'max_worker_threads': 8
            };

            $scope.configTemplate = '';
            $scope.configTemplateCustomchecks = '';
            $scope.host.id = false;
            document.getElementById('AgentHost').disabled = false;
            $scope.updateConfigTemplate();
        };


        //delete pre filled uuid and address if loadHostById in HostsController works!
        $scope.host = {
            uuid: '91cebbcc-cbcc-46f7-a0c7-a21a5ed513d7',
            address: '172.16.166.5'
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
                        value = './oitc_customchecks.conf';
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
        };

        $scope.continueWithServiceConfiguration = function(){
            $scope.installed = true;
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

        $scope.load();
    });
