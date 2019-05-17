angular.module('openITCOCKPIT')
    .controller('ServicetemplatesCopyController', function($scope, $http, $state, $stateParams, NotyService, RedirectService){

        var ids = $stateParams.ids.split(',');

        if(ids.length === 0 || ids[0] === ''){
            //No ids to copy given - redirect
            RedirectService.redirectWithFallback('ServicetemplatesIndex');
            return;
        }

        var removeFieldsFromServicetemplatecommandargumentvalues = function(servicetemplatecommandargumentvalues){
            if(servicetemplatecommandargumentvalues.length === 0){
                return [];
            }

            for(var i in servicetemplatecommandargumentvalues){
                delete servicetemplatecommandargumentvalues[i].id;
                delete servicetemplatecommandargumentvalues[i].servicetemplate_id;
            }

            return servicetemplatecommandargumentvalues;
        };

        $scope.load = function(){
            $http.get("/servicetemplates/copy/" + ids.join('/') + ".json", {
                params: {
                    'angular': true,
                }
            }).then(function(result){
                $scope.sourceServicetemplates = [];
                $scope.commands = result.data.commands;
                for(var key in result.data.servicetemplates){
                    var sourceId = result.data.servicetemplates[key].id;
                    delete result.data.servicetemplates[key].id;

                    var servicetemplate = {
                        Source: {
                            id: sourceId,
                            template_name: result.data.servicetemplates[key].template_name
                        },
                        Servicetemplate: result.data.servicetemplates[key]
                    };
                    servicetemplate.Servicetemplate.servicetemplatecommandargumentvalues = removeFieldsFromServicetemplatecommandargumentvalues(servicetemplate.Servicetemplate.servicetemplatecommandargumentvalues);

                    $scope.sourceServicetemplates.push(servicetemplate);
                }

                $scope.init = false;

            });
        };

        $scope.loadCommandArguments = function(sourceTemplateId, commandId, $index){
            var params = {
                'angular': true
            };

            $http.get("/servicetemplates/loadCommandArguments/" + commandId + "/" + sourceTemplateId + ".json", {
                params: params
            }).then(function(result){
                $scope.sourceServicetemplates[$index].Servicetemplate.servicetemplatecommandargumentvalues = result.data.servicetemplatecommandargumentvalues;
            });
        };

        $scope.copy = function(){
            $http.post("/servicetemplates/copy/.json?angular=true",
                {
                    data: $scope.sourceServicetemplates
                }
            ).then(function(result){
                NotyService.genericSuccess();
                RedirectService.redirectWithFallback('ServicetemplatesIndex');

            }, function errorCallback(result){
                //Print errors
                NotyService.genericError();
                $scope.sourceServicetemplates = result.data.result;
            });
        };


        $scope.load();


    });