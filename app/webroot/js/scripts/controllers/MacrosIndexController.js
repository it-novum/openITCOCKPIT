angular.module('openITCOCKPIT')
    .controller('MacrosIndexController', function($scope, $http, NotyService){

        $scope.availableMacros = [];
        $scope.post = {};

        $scope.load = function(){
            var params = {
                'angular': true
            };

            $http.get("/macros/index.json", {
                params: params
            }).then(function(result){
                $scope.macros = result.data.all_macros;
                $scope.init = false;
            });
        };

        $scope.loadAvailableMacroNames = function(macroToInclude, callback){
            $scope.availableMacros = [];

            var params = {
                'angular': true,
                'include': macroToInclude
            };

            $http.get("/macros/getAvailableMacroNames.json", {
                params: params
            }).then(function(result){
                $scope.availableMacros = result.data.availableMacroNames;
                callback(macroToInclude); //Fix strange name is null behavior
            });
        };

        $scope.saveMacro = function(){
            $http.post("/macros/add.json?angular=true",
                $scope.post
            ).then(function(result){
                $scope.errors = {};

                $('#addMacroModal').modal('hide');
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


        $scope.triggerAddModal = function(){
            $scope.post = {
                Macro: {
                    name: '',
                    value: '',
                    description: '',
                    password: 0
                }
            };

            $scope.loadAvailableMacroNames('', function(){
                $('#addMacroModal').modal('show');
            });
        };

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
            $http.post("/macros/delete/" + $scope.editPost.Macro.id + ".json?angular=true",
                $scope.editPost
            ).then(function(result){
                $scope.load();
                $('#editMacroModal').modal('hide');
                NotyService.deleteSuccess();
            }, function errorCallback(result){
                NotyService.deleteError();
            });
        };

        //Fire on page load
        $scope.load();

    });