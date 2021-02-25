angular.module('openITCOCKPIT')
    .controller('AgentconnectorsConfigController', function($scope, $http, $state, $stateParams, NotyService, RedirectService){

        $scope.hostId = $stateParams.hostId;
        $scope.pushAgentId = $stateParams.pushAgentId;
        $scope.connection_type = 'autotls';
        $scope.webserver_type = 'https';

        var urlMode = $stateParams.mode || null;

        // Load current agent config if any exists
        $scope.load = function(searchString, selected){
            $http.get("/agentconnector/config.json", {
                params: {
                    hostId: $scope.hostId,
                    'angular': true
                }
            }).then(function(result){
                $scope.config = result.data.config;
                $scope.host = result.data.host;
                if($scope.config.bool.use_autossl === false && $scope.config.bool.use_https === true){
                    $scope.connection_type = 'https';
                }
                if($scope.config.bool.use_autossl === false && $scope.config.bool.use_https === false){
                    $scope.connection_type = 'http';
                }

                $scope.webserver_type = 'https';
                if($scope.config.bool.push_webserver_use_https === false){
                    $scope.webserver_type = 'http';
                }

                if(urlMode !== null){
                    // The current AngularJS state has an "mode"
                    // User came from the first wizard page

                    $scope.config.bool.enable_push_mode = urlMode === 'push';
                }
            });
        };

        $scope.changeOs = function(os){
            $scope.config.string.operating_system = os;
        };


        // Validate and save agent config
        $scope.submit = function(){
            cleanupConnectionTypes();
            $scope.config.bool.push_webserver_use_https = $scope.webserver_type === 'https';
            $http.post("/agentconnector/config.json", {
                    config: $scope.config,
                    hostId: $scope.hostId,
                pushAgentId: $scope.pushAgentId
                }
            ).then(function(result){
                $state.go('AgentconnectorsInstall', {
                    hostId: $scope.hostId
                }).then(function(){
                    NotyService.scrollTop();
                });
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    NotyService.genericError();
                    $scope.errors = result.data.error;
                }
            });
        };

        var cleanupConnectionTypes = function(){
            // This function cleanup if the user clicked around and enabled auto-tls and than switched to http plaintext etc

            if($scope.config.bool.enable_push_mode === false){
                // Agent in PULL Mode
                $scope.config.bool.push_enable_webserver = false;

                if($scope.connection_type === 'http'){
                    // Plaintext connection
                    $scope.config.bool.use_autossl = false;
                    $scope.config.bool.use_https = false;
                    $scope.config.bool.use_https_verify = false;
                    $scope.config.string.ssl_certfile = '';
                    $scope.config.string.ssl_keyfile = '';
                }

                if($scope.connection_type === 'https'){
                    // HTTPS connection
                    $scope.config.bool.use_https = true;
                    $scope.config.bool.use_autossl = false;
                }

                if($scope.connection_type === 'autotls'){
                    // Auto-TLS connection
                    $scope.config.bool.use_autossl = true;
                    $scope.config.bool.use_https = false;
                    $scope.config.bool.use_https_verify = false;
                    $scope.config.string.ssl_certfile = '';
                    $scope.config.string.ssl_keyfile = '';
                }
            }else{
                // Agent in PUSH Mode
                $scope.config.bool.use_autossl = false; // no autotls in push mode
                $scope.config.bool.use_https = false; // the agent push the data to oitc this variable is for pull mode
                $scope.config.bool.use_https_verify = false; // the agent push the data to oitc this variable is for pull mode
            }
        };

        //Fire on page load
        $scope.load();
    });
