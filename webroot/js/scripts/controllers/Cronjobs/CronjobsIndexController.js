angular.module('openITCOCKPIT')
    .controller('CronjobsIndexController', function($scope, $http, NotyService){

        $scope.post = {};
        $scope.editPost = {};

        $scope.load = function(){
            var params = {
                'angular': true
            };

            $http.get("/cronjobs/index.json", {
                params: params
            }).then(function(result){
                $scope.cronjobs = result.data.cronjobs;
                $scope.init = false;
            });
        };

        $scope.loadAvailableCronjobTasks = function(tasksToInclude, pluginName, callback){
            $scope.availableTasks = [];

            var params = {
                'angular': true,
                'include': tasksToInclude,
                'pluginName': pluginName
            };

            $http.get("/cronjobs/getTasks.json", {
                params: params
            }).then(function(result){
                $scope.availableTasks = result.data.coreTasks;
                callback(tasksToInclude);
            });
        };

        $scope.loadAvailablePlugins = function(pluginsToInclude, callback){
            $scope.availablePlugins = [];

            var params = {
                'angular': true,
                'include': pluginsToInclude
            };

            $http.get("/cronjobs/getPlugins.json", {
                params: params
            }).then(function(result){
                $scope.availablePlugins = result.data.plugins;
                callback(pluginsToInclude);
            });
        };

        $scope.saveCronjob = function(){
            $http.post("/cronjobs/add.json?angular=true",
                $scope.post
            ).then(function(result){
                $scope.errors = {};

                $('#addCronjobModal').modal('hide');
                $scope.post = {};

                $scope.load();
                NotyService.genericSuccess();
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
                NotyService.genericError();
            });
        };

        $scope.editCronjob = function(){
            $http.post("/cronjobs/edit/" + $scope.editPost.Cronjob.id + ".json?angular=true",
                $scope.editPost
            ).then(function(result){
                $scope.errors = {};

                $('#editCronjobModal').modal('hide');
                $scope.editPost = {};

                $scope.load();
                NotyService.genericSuccess();
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
                NotyService.genericError();
            });
        };

        $scope.triggerAddModal = function(){
            $scope.post = {
                Cronjob: {
                    plugin: '',
                    task: '',
                    interval: 0,
                    enabled: 0
                }
            };

            $scope.loadAvailablePlugins('', function(){

            });
            $('#addCronjobModal').modal('show');
        };

        $scope.triggerEditModal = function(cronjob){
            $scope.editPost = {
                Cronjob: JSON.parse(JSON.stringify(cronjob)) //Get clone not reference
            };

            $scope.loadAvailablePlugins(cronjob.plugin, function(){
                $scope.editPost.Cronjob.plugin = cronjob.plugin;
            });
            $scope.loadAvailableCronjobTasks(cronjob.task, cronjob.plugin, function(){
                $scope.editPost.Cronjob.task = cronjob.task;
            });

            $('#editCronjobModal').modal('show');
        };

        $scope.deleteCronjob = function(){
            $http.post("/cronjobs/delete/" + $scope.editPost.Cronjob.id + ".json?angular=true",
                $scope.editPost
            ).then(function(result){
                $scope.errors = {};

                $('#editCronjobModal').modal('hide');
                $scope.editPost = {};

                $scope.load();
                NotyService.genericSuccess();
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
                NotyService.genericError();
            });
        };

        //Fire on page load
        $scope.load();

        $scope.$watch('post.Cronjob.plugin', function(){
            if($scope.post.Cronjob != null){
                $scope.loadAvailableCronjobTasks('', $scope.post.Cronjob.plugin, function(){
                });
            }
        }, true);

    });