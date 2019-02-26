angular.module('openITCOCKPIT')
    .controller('HosttemplatesEditController', function($scope, $http, SudoService, $state, $stateParams, NotyService){

        $scope.id = $stateParams.id;

        $scope.post = {
            Hosttemplate: {}
        };

        $scope.init = true;

        $scope.loadHosttemplate = function(){
            var params = {
                'angular': true
            };

            $http.get("/hosttemplates/edit/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.post.Hosttemplate = result.data.hosttemplate.Hosttemplate;
                $scope.commands = result.data.commands;

                jQuery(function(){
                    $('.tagsinput').tagsinput();
                });

                $scope.loadContainers();
                $scope.loadElements();
            });
        };

        $scope.loadContainers = function(){
            var params = {
                'angular': true
            };

            $http.get("/hosttemplates/loadContainers/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.areContainersRestricted = result.data.areContainersRestricted;
                $scope.init = false;
            });
        };

        $scope.loadCommandArguments = function(){
            var params = {
                'angular': true
            };

            var commandId = $scope.post.Hosttemplate.command_id;
            $http.get("/hosttemplates/loadCommandArguments/" + commandId + "/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.post.Hosttemplate.hosttemplatecommandargumentvalues = result.data.hosttemplatecommandargumentvalues;
            });
        };

        $scope.loadElements = function(){
            var containerId = $scope.post.Hosttemplate.container_id;
            $http.post("/hosttemplates/loadElementsByContainerId/" + containerId + ".json?angular=true", {}).then(function(result){
                $scope.timeperiods = result.data.timeperiods;
                $scope.checkperiods = result.data.checkperiods;
                $scope.contacts = result.data.contacts;
                $scope.contactgroups = result.data.contactgroups;
                $scope.hostgroups = result.data.hostgroups;
            });
        };

        $scope.setPriority = function(priority){
            $scope.post.Hosttemplate.priority = parseInt(priority, 10);
        };

        $scope.addMacro = function(){
            $scope.post.Hosttemplate.customvariables.push({
                objecttype_id: 512,
                name: '',
                value: ''
            });
        };

        $scope.deleteMacroCallback = function(macro, index){
            $scope.post.Hosttemplate.customvariables.splice(index, 1);
        };

        $scope.getMacroErrors = function(index){
            if(typeof $scope.errors !== "undefined"){
                if(typeof $scope.errors.customvariables !== "undefined"){
                    if(typeof $scope.errors.customvariables[index] !== 'undefined'){
                        return $scope.errors.customvariables[index];
                    }
                }
            }
            return false;
        };

        $scope.submit = function(){
            $http.post("/hosttemplates/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                NotyService.genericSuccess();
                $state.go('HosttemplatesIndex').then(function(){
                    NotyService.scrollTop();
                });

                console.log('Data saved successfully');
            }, function errorCallback(result){

                NotyService.genericError();

                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;

                    if($scope.errors.hasOwnProperty('customvariables')){
                        if($scope.errors.customvariables.hasOwnProperty('custom')){
                            $scope.errors.customvariables_unique = [
                                $scope.errors.customvariables.custom
                            ];
                        }
                    }
                }
            });

        };

        $scope.loadHosttemplate();

        $scope.$watch('post.Hosttemplate.container_id', function(){
            if($scope.init){
                return;
            }
            $scope.loadElements();
        }, true);

        $scope.$watch('post.Hosttemplate.command_id', function(){
            if($scope.init){
                return;
            }
            $scope.loadCommandArguments();
        }, true);


    });
