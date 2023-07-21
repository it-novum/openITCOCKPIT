angular.module('openITCOCKPIT')
    .controller('ConfigurationitemsExportController', function($scope, $http, NotyService){

        $scope.init = true;
        $scope.hasError = null;

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

        };


        //Fire on page load
        $scope.load();
    })
;
