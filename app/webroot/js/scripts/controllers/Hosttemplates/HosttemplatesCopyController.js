angular.module('openITCOCKPIT')
    .controller('HosttemplatesCopyController', function($scope, $http, $state, $stateParams, NotyService, RedirectService){

        var ids = $stateParams.ids.split(',');

        if(ids.length === 0 || ids[0] === ''){
            //No ids to copy given - redirect
            RedirectService.redirectWithFallback('HosttemplatesIndex');
            return;
        }

        var removeFieldsFromHosttemplatecommandargumentvalues = function(hosttemplatecommandargumentvalues){
            if(hosttemplatecommandargumentvalues.length === 0){
                return [];
            }

            for(var i in hosttemplatecommandargumentvalues){
                delete hosttemplatecommandargumentvalues[i].id;
                delete hosttemplatecommandargumentvalues[i].hosttemplate_id;
            }

            return hosttemplatecommandargumentvalues;
        };

        $scope.load = function(){
            $http.get("/hosttemplates/copy/" + ids.join('/') + ".json", {
                params: {
                    'angular': true,
                }
            }).then(function(result){
                $scope.sourceHosttemplates = [];
                $scope.commands = result.data.commands;
                for(var key in result.data.hosttemplates){
                    var sourceId = result.data.hosttemplates[key].id;
                    delete result.data.hosttemplates[key].id;

                    var hosttemplate = {
                        Source: {
                            id: sourceId,
                            name: result.data.hosttemplates[key].name
                        },
                        Hosttemplate: result.data.hosttemplates[key]
                    };
                    hosttemplate.Hosttemplate.hosttemplatecommandargumentvalues = removeFieldsFromHosttemplatecommandargumentvalues(hosttemplate.Hosttemplate.hosttemplatecommandargumentvalues);

                    $scope.sourceHosttemplates.push(hosttemplate);
                }

                $scope.init = false;

            });
        };

        $scope.loadCommandArguments = function(sourceTemplateId, commandId, $index){
            var params = {
                'angular': true
            };

            $http.get("/hosttemplates/loadCommandArguments/" + commandId + "/" + sourceTemplateId + ".json", {
                params: params
            }).then(function(result){
                $scope.sourceHosttemplates[$index].Hosttemplate.hosttemplatecommandargumentvalues = result.data.hosttemplatecommandargumentvalues;
            });
        };

        $scope.copy = function(){
            $http.post("/hosttemplates/copy/.json?angular=true",
                {
                    data: $scope.sourceHosttemplates
                }
            ).then(function(result){
                NotyService.genericSuccess();
                RedirectService.redirectWithFallback('HosttemplatesIndex');
            }, function errorCallback(result){
                //Print errors
                NotyService.genericError();
                $scope.sourceHosttemplates = result.data.result;
            });
        };


        $scope.load();


    });