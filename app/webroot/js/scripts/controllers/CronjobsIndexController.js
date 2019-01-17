angular.module('openITCOCKPIT')
    .controller('CronjobsIndexController', function($scope, $http, NotyService){

        $scope.availableMacros = [];
        $scope.post = {};

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Macro: {
                    name: [],
                    description: ''
                }
            };
        };
        $scope.showFilter = false;
        /*** Filter end ***/

        $scope.triggerFilter = function(){
            $scope.showFilter = !$scope.showFilter === true;
        };

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

        $scope.loadAvailableCronjobTasks = function(tasksToInclude){
            $scope.availableTasks = [];

            var params = {
                'angular': true,
                'include': tasksToInclude
            };

            $http.get("/cronjobs/getTasks.json", {
                params: params
            }).then(function(result){
                $scope.availableTasks = result.data.coreTasks;
            });
        };

        $scope.loadAvailablePlugins = function(pluginsToInclude){
            $scope.availablePlugins = [];

            var params = {
                'angular': true,
                'include': pluginsToInclude
            };

            $http.get("/cronjobs/getPlugins.json", {
                params: params
            }).then(function(result){
                $scope.availablePlugins = result.data.plugins;
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
/*
        $scope.editMacro = function(){
            $http.post("/macros/edit/" + $scope.editPost.Macro.id + ".json?angular=true",
                $scope.editPost
            ).then(function(result){
                $scope.errors = {};

                $('#editMacroModal').modal('hide');
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

        $scope.resetFilter = function(){
            defaultFilter();
        };
*/
        $scope.triggerAddModal = function(){
            $scope.post = {
                Cronjob: {
                    plugin: '',
                    task: '',
                    interval: 0,
                    enabled: 0
                }
            };

            $scope.loadAvailablePlugins('');

            $scope.loadAvailableCronjobTasks('');



            $('#addCronjobModal').modal('show');
        };
/*
        $scope.triggerEditModal = function(macro){

            $scope.editPost = {
                Macro: JSON.parse(JSON.stringify(macro)) //Get clone not reference
            };


            $scope.loadAvailableMacroNames($scope.editPost.Macro.name, function(macroname){
                $scope.editPost.Macro.name = macroname; //Fix strange name is null behavior
                $('#editMacroModal').modal('show');
            });

        };

        $scope.deleteMacro = function(macro){
            $http.post("/macros/edit/" + $scope.editPost.Macro.id + ".json?angular=true",
                $scope.editPost
            ).then(function(result){
                $scope.errors = {};

                $('#editMacroModal').modal('hide');
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
*/
        //Fire on page load
    //    defaultFilter();
        $scope.load();

    });