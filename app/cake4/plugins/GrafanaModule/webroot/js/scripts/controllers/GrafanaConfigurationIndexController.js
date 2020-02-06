angular.module('openITCOCKPIT')
    .controller('Grafana_configurationIndexController', function($scope, $http, NotyService, $state){

        $scope.post = {
            id: 1, //its 1 every time
            api_url: '',
            api_key: '',
            graphite_prefix: '',
            use_https: 0, //number
            use_proxy: 1, //number
            ignore_ssl_certificate: 0, //number
            dashboard_style: '', //light / dark
            Hostgroup: [],
            Hostgroup_excluded: []
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

                for(var key in $scope.config.Hostgroup){
                    selectedHostgroups.push(parseInt($scope.config.Hostgroup[key], 10));
                }

                for(var key in $scope.config.Hostgroup_excluded){
                    selectedHostgroupsExcluded.push(parseInt($scope.config.Hostgroup_excluded[key], 10));
                }

                $scope.post.api_url = $scope.config.api_url;
                $scope.post.api_key = $scope.config.api_key;
                $scope.post.graphite_prefix = $scope.config.graphite_prefix;
                $scope.post.use_https = parseInt($scope.config.use_https, 10);
                $scope.post.use_proxy = parseInt($scope.config.use_proxy, 10);
                $scope.post.ignore_ssl_certificate = parseInt($scope.config.ignore_ssl_certificate, 10);
                $scope.post.dashboard_style = $scope.config.dashboard_style;
                $scope.post.Hostgroup = selectedHostgroups;
                $scope.post.Hostgroup_excluded = selectedHostgroupsExcluded;
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
                $scope.hostgroups_excluded = JSON.parse(JSON.stringify(result.data.hostgroups)); //WO DONT WANT A REFERENCE!!
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

        $scope.processChosenHostgroups = function(){
            for(var key in $scope.hostgroups){
                if(in_array($scope.hostgroups[key].key, $scope.post.Hostgroup_excluded)){
                    $scope.hostgroups[key].disabled = true;
                }else{
                    $scope.hostgroups[key].disabled = false;
                }
            }
        };

        $scope.processChosenExcludedHostgroups = function(){
            for(var key in $scope.hostgroups_excluded){
                if(in_array($scope.hostgroups_excluded[key].key, $scope.post.Hostgroup)){
                    $scope.hostgroups_excluded[key].disabled = true;
                }else{
                    $scope.hostgroups_excluded[key].disabled = false;
                }
            }
        };

        $scope.$watch('post.Hostgroup', function(){
            if($scope.init){
                return;
            }
            $scope.processChosenExcludedHostgroups();
        }, true);

        $scope.$watch('post.Hostgroup_excluded', function(){
            if($scope.init){
                return;
            }
            $scope.processChosenHostgroups();
        }, true);

        $scope.load();
        $scope.loadHostgroups();
    });
