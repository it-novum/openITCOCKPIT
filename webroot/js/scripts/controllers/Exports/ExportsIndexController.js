angular.module('openITCOCKPIT')
    .controller('ExportsIndexController', function($scope, $http, NotyService){
        $scope.init = true;
        $scope.exportSuccessfully = true;
        $scope.selectedElements = 0;
        $scope.showLog = false;

        $scope.post = {
            create_backup: 1
        };

        var interval = null;

        var resetVerificationErrors = function(){
            $scope.verificationErrors = {
                nagios: {
                    hasError: false,
                    output: ''
                },
                prometheus: {
                    hasError: false,
                    output: ''
                },
            };
        };
        resetVerificationErrors();


        $scope.load = function(){
            var params = {
                'angular': true
            };

            $http.get('/exports/index.json', {
                params: params
            }).then(function(result){
                $scope.tasks = result.data.tasks;
                $scope.exportRunning = result.data.exportRunning;
                $scope.gearmanReachable = result.data.gearmanReachable;
                $scope.satellites = result.data.satellites;
                $scope.useSingleInstanceSync = result.data.useSingleInstanceSync;
                $scope.init = false;
            });
        };

        $scope.loadStatus = function(){
            $http.get('/exports/broadcast.json', {
                params: {
                    'angular': true,
                    'disableGlobalLoader': true
                }
            }).then(function(result){
                $scope.tasks = result.data.tasks;
                $scope.exportRunning = !result.data.exportFinished;


                if(result.data.exportFinished === true){
                    clearInterval(interval);
                    interval = null;

                    if(result.data.exportSuccessfully === false){
                        $scope.exportSuccessfully = false;
                        for(var index in result.data.tasks){
                            var task = result.data.tasks[index];
                            if(
                                (task.task === 'export_verify_new_configuration' && task.finished === 1 && task.successfully === 0) ||
                                (task.task === 'export_verify_new_prometheus_configuration' && task.finished === 1 && task.successfully === 0)
                            ){
                                //No monitoring configuration is not valid
                                $scope.verifyConfig();
                            }
                        }
                    }

                    if(result.data.exportSuccessfully === true){
                        $scope.exportSuccessfully = true;
                        NotyService.genericSuccess({
                            message: result.data.successMessage
                        });
                    }
                }

            });
        };

        $scope.verifyConfig = function(){
            $http.post("/exports/verifyConfig.json?angular=true", {
                    empty: true
                }
            ).then(function(result){
                $scope.verificationErrors.nagios.hasError = result.data.result.nagios.hasError;
                $scope.verificationErrors.nagios.output = result.data.result.nagios.output.join("\n");

                $scope.verificationErrors.prometheus.hasError = result.data.result.prometheus.hasError;
                $scope.verificationErrors.prometheus.output = result.data.result.prometheus.output.join("\n");
            });


            var params = {
                'angular': true
            };

            $http.get('/exports/index.json', {
                params: params
            }).then(function(result){
                $scope.tasks = result.data.tasks;
                $scope.exportRunning = result.data.exportRunning;
                $scope.gearmanReachable = result.data.gearmanReachable;
                $scope.init = false;
            });
        };

        $scope.saveInstanceConfigSyncSelection = function(){
            if($scope.useSingleInstanceSync === false){
                return;
            }

            if(typeof $scope.satellites === "undefined"){
                return;
            }

            $http.post("/exports/saveInstanceConfigSyncSelection.json?angular=true", {
                    'satellites': $scope.satellites
                }
            ).then(function(result){
                NotyService.genericSuccess();
            });
        };

        $scope.launchExport = function(){
            $scope.exportFinished = false;
            $scope.exportSuccessfully = true;
            resetVerificationErrors();
            $scope.exportRunning = true;
            $scope.showLog = true;
            NotyService.scrollTop();
            if(interval !== null){
                clearInterval(interval);
                interval = null;
            }

            var post = {
                'empty': true,
                'create_backup': $scope.post.create_backup
            };
            if(typeof $scope.satellites !== "undefined" && $scope.useSingleInstanceSync === true){
                post.satellites = $scope.satellites;
            }

            $http.post("/exports/launchExport.json?angular=true", post).then(
                function(result){
                    $scope.loadStatus();
                    interval = setInterval($scope.loadStatus, 1000);
                });
        };

        $scope.selectAll = function(){
            if(typeof $scope.satellites !== "undefined"){
                $scope.selectedElements = 0;
                for(var index in $scope.satellites){
                    $scope.satellites[index].sync_instance = 1;
                    $scope.selectedElements++;
                }
            }
        };

        $scope.undoSelection = function(){
            if(typeof $scope.satellites !== "undefined"){
                for(var index in $scope.satellites){
                    $scope.satellites[index].sync_instance = 0;
                }
                $scope.selectedElements = 0;
            }
        };

        //Fire on page load
        $scope.load();

        $scope.$watch('satellites', function(){
            if(typeof $scope.satellites !== "undefined"){
                $scope.selectedElements = 0;
                for(var index in $scope.satellites){
                    if($scope.satellites[index].sync_instance === 1){
                        $scope.selectedElements++;
                    }
                }
            }
        }, true);

    });
