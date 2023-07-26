angular.module('openITCOCKPIT')
    .controller('ConfigurationitemsExportController', function($scope, $http, NotyService){

        $scope.init = true;
        $scope.hasError = null;
        $scope.errors = {};
        $scope.isGenerating = false;

        $scope.post = {
            Configurationitems: {
                commands: {
                    _ids: []
                },
                timeperiods: {
                    _ids: []
                },
                contacts: {
                    _ids: []
                },
                contactgroups: {
                    _ids: []
                },
                servicetemplates: {
                    _ids: []
                },
                servicetemplategroups: {
                    _ids: []
                }
            }
        }

        $scope.load = function(){
            $http.get("/configurationitems/loadElementsForExport.json?angular=true", {
                empty: true
            }).then(function(result){
                $scope.commands = result.data.commands;
                $scope.timeperiods = result.data.timeperiods;
                $scope.contacts = result.data.contacts;
                $scope.contactgroups = result.data.contactgroups;
                $scope.servicetemplates = result.data.servicetemplates;
                $scope.servicetemplategroups = result.data.servicetemplategroups;
            });
        };
        

        $scope.submit = function(){
            $scope.errors = {};
            $scope.isGenerating = true;
            $http.post("/configurationitems/export.json?angular=true",
                $scope.post
            ).then(function(response){
                NotyService.genericSuccess();
                $scope.isGenerating = false;

                jsonString = JSON.stringify({
                    export: response.data.export,
                    checksum: response.data.checksum
                });

                var file = new Blob([jsonString], {type: "application/json"});

                // Open save-as / download dialog
                var filename = "export-" + date('d-m-Y-H-i-s') + ".json";
                saveAs(file, filename);

            }, function errorCallback(result){
                NotyService.genericError();
                $scope.isGenerating = false;
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        //Fire on page load
        $scope.load();
    })
;
