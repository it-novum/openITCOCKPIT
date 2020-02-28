angular.module('openITCOCKPIT')
    .controller('ServicetemplatesEditController', function($scope, $http, SudoService, $state, $stateParams, NotyService, RedirectService){

        $scope.id = $stateParams.id;

        $scope.post = {
            Servicetemplate: {}
        };

        $scope.init = true;

        $scope.loadServicetemplate = function(){
            var params = {
                'angular': true
            };

            $http.get("/servicetemplates/edit/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.post.Servicetemplate = result.data.servicetemplate.Servicetemplate;
                $scope.commands = result.data.commands;
                $scope.eventhandlerCommands = result.data.eventhandlerCommands;
                $scope.servicetemplatetypes = result.data.types;

                jQuery(function(){
                    $('.tagsinput').tagsinput();
                });

                $scope.loadContainers();
                $scope.loadElements();
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.loadContainers = function(){
            var params = {
                'angular': true
            };

            $http.get("/servicetemplates/loadContainers/" + $scope.id + ".json", {
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

            var commandId = $scope.post.Servicetemplate.command_id;
            $http.get("/servicetemplates/loadCommandArguments/" + commandId + "/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.post.Servicetemplate.servicetemplatecommandargumentvalues = result.data.servicetemplatecommandargumentvalues;
            });
        };

        $scope.loadEventHandlerCommandArguments = function(){
            var params = {
                'angular': true
            };

            var eventHandlerCommandId = $scope.post.Servicetemplate.eventhandler_command_id;

            $http.get("/servicetemplates/loadEventhandlerCommandArguments/" + eventHandlerCommandId + "/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.post.Servicetemplate.servicetemplateeventcommandargumentvalues = result.data.servicetemplateeventhandlercommandargumentvalues;
            });
        };

        $scope.loadElements = function(){
            var containerId = $scope.post.Servicetemplate.container_id;
            $http.post("/servicetemplates/loadElementsByContainerId/" + containerId + ".json?angular=true", {}).then(function(result){
                $scope.timeperiods = result.data.timeperiods;
                $scope.checkperiods = result.data.checkperiods;
                $scope.contacts = result.data.contacts;
                $scope.contactgroups = result.data.contactgroups;
                $scope.servicegroups = result.data.servicegroups;
            });
        };

        $scope.setPriority = function(priority){
            $scope.post.Servicetemplate.priority = parseInt(priority, 10);
        };

        $scope.addMacro = function(){
            $scope.post.Servicetemplate.customvariables.push({
                objecttype_id: 4096, //OBJECT_SERVICETEMPLATE
                name: '',
                value: ''
            });
        };

        $scope.deleteMacroCallback = function(macro, index){
            $scope.post.Servicetemplate.customvariables.splice(index, 1);
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
            $http.post("/servicetemplates/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('ServicetemplatesEdit', {id: $scope.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('ServicetemplatesIndex');

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

        $scope.loadServicetemplate();

        $scope.$watch('post.Servicetemplate.container_id', function(){
            if($scope.init){
                return;
            }
            $scope.loadElements();
        }, true);

        $scope.$watch('post.Servicetemplate.command_id', function(){
            if($scope.init){
                return;
            }
            $scope.loadCommandArguments();
        }, true);

        $scope.$watch('post.Servicetemplate.eventhandler_command_id', function(){
            if($scope.init){
                return;
            }

            if($scope.post.Servicetemplate.eventhandler_command_id === 0){
                //"None" selected
                $scope.post.Servicetemplate.servicetemplateeventcommandargumentvalues = [];
                return;
            }

            $scope.loadEventHandlerCommandArguments();
        }, true);

    });
