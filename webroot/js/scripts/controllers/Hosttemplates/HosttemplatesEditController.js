angular.module('openITCOCKPIT')
    .controller('HosttemplatesEditController', function($scope, $http, SudoService, $state, $stateParams, NotyService, RedirectService){

        $scope.id = $stateParams.id;

        $scope.post = {
            Hosttemplate: {}
        };

        $scope.init = true;
        $scope.typeDetails = {};

        $scope.loadHosttemplate = function(){
            var params = {
                'angular': true
            };

            $http.get("/hosttemplates/edit/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.post.Hosttemplate = result.data.hosttemplate.Hosttemplate;
                $scope.commands = result.data.commands;
                $scope.hosttemplatetypes = result.data.types;
                $scope.setDetailsForType($scope.post.Hosttemplate.hosttemplatetype_id);

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
            $http.post("/hosttemplates/loadElementsByContainerId/" + containerId + ".json?angular=true", {
                empty: true
            }).then(function(result){
                $scope.timeperiods = result.data.timeperiods;
                $scope.checkperiods = result.data.checkperiods;
                $scope.contacts = result.data.contacts;
                $scope.contactgroups = result.data.contactgroups;
                $scope.hostgroups = result.data.hostgroups;
                $scope.exporters = result.data.exporters;
            });
        };

        $scope.setPriority = function(priority){
            $scope.post.Hosttemplate.priority = parseInt(priority, 10);
        };

        $scope.addMacro = function(){
            $scope.post.Hosttemplate.customvariables.push({
                objecttype_id: 512, //OBJECT_HOSTTEMPLATE
                name: '',
                value: '',
                password: 0
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

        $scope.setDetailsForType = function(){
            for(index in $scope.hosttemplatetypes){
                if($scope.hosttemplatetypes[index].key === $scope.post.Hosttemplate.hosttemplatetype_id){
                    $scope.typeDetails = $scope.hosttemplatetypes[index].value;
                    return;
                }
            }
        };

        $scope.submit = function(){
            $http.post("/hosttemplates/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('HosttemplatesEdit', {id: $scope.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('HosttemplatesIndex');

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

        $scope.$watch('post.Hosttemplate.hosttemplatetype_id', function(){
            if($scope.init){
                return;
            }
            $scope.setDetailsForType();
        }, true);

    });
