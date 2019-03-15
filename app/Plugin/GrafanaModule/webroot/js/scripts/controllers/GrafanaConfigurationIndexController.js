angular.module('openITCOCKPIT')
    .controller('Grafana_configurationIndexController', function($scope, $http, NotyService, $state){

        $scope.post = {
            GrafanaConfiguration: {
                id: 1, //its 1 every time
                api_url: '',
                api_key: '',
                graphite_prefix: '',
                use_https: false, //number
                use_proxy: true, //number
                ignore_ssl_certificate: false, //number
                dashboard_style: '', //light / dark
                Hostgroup: [],
                Hostgroup_excluded: []
            }
        };

        $scope.hasError = null;

        $scope.load = function(){
            $http.get("/grafana_module/grafana_configuration/index.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.config = result.data.grafanaConfiguration;
                var selectedHostgroups = [];
                var selectedHostgroupsExcluded = [];

                for(var key in $scope.config.GrafanaConfiguration.Hostgroup){
                    selectedHostgroups.push(parseInt($scope.config.GrafanaConfiguration.Hostgroup[key], 10));
                }

                for(var key in $scope.config.GrafanaConfiguration.Hostgroup_excluded){
                    selectedHostgroupsExcluded.push(parseInt($scope.config.GrafanaConfiguration.Hostgroup_excluded[key], 10));
                }

                $scope.post.GrafanaConfiguration.api_url = $scope.config.GrafanaConfiguration.api_url;
                $scope.post.GrafanaConfiguration.api_key = $scope.config.GrafanaConfiguration.api_key;
                $scope.post.GrafanaConfiguration.graphite_prefix = $scope.config.GrafanaConfiguration.graphite_prefix;
                $scope.post.GrafanaConfiguration.use_https = parseInt($scope.config.GrafanaConfiguration.use_https, 10) === 1;
                $scope.post.GrafanaConfiguration.use_proxy = parseInt($scope.config.GrafanaConfiguration.use_proxy, 10) === 1;
                $scope.post.GrafanaConfiguration.ignore_ssl_certificate = parseInt($scope.config.GrafanaConfiguration.ignore_ssl_certificate, 10) === 1;
                $scope.post.GrafanaConfiguration.dashboard_style = $scope.config.GrafanaConfiguration.dashboard_style;
                $scope.post.GrafanaConfiguration.Hostgroup = selectedHostgroups;
                $scope.post.GrafanaConfiguration.Hostgroup_excluded = selectedHostgroupsExcluded;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.loadHostgroups = function(){
            $http.get("/grafana_module/grafana_configuration/loadHostgroups.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.hostgroups = result.data.hostgroups;
            });
        };

        $scope.checkGrafanaConnection = function(){
            $http.post("/grafana_module/grafana_configuration/testGrafanaConnection.json?angular=true",
                $scope.post
            ).then(function(result){
                $scope.hasError = false;

                console.log(result);

                if(result.data.status.status === false){
                    $scope.hasError = true;
                    $scope.grafanaErrors = {
                        status: 400,
                        statusText: 'Bad Request',
                        message: result.data.status.msg.message
                    };
                }

            }, function errorCallback(result){
                $scope.hasError = true;
                $scope.grafanaErrors = {
                    status: result.status,
                    statusText: result.statusText,
                    message: result.data.message
                };
            });
        };

        $scope.submit = function(){
            $http.post("/grafana_module/grafana_configuration/index.json?angular=true",
                $scope.post
            ).then(function(result){
                NotyService.genericSuccess();
                $scope.errors = null;
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });

        };

        $scope.load();
        $scope.loadHostgroups();
    });