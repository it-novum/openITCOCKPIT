angular.module('openITCOCKPIT')
    .controller('WizardsLinuxServerSshController', function($scope, $http){

        /** public vars **/
        $scope.init = true;
        $scope.post = {
            ssh: {
                login: null,
                id_rsa_path: null,
                port: null
            }
        };


        $scope.load = function(searchString, selected){
            $http.get("/wizards/loadServicetemplatesByWizardType.json", {
                params: {
                    'angular': true,
                    'type': 'linuxserverssh'
                }
            }).then(function(result){
                $scope.servicetemplates = result.data.servicetemplates;
                console.log($scope.servicetemplates);

            });
            console.log('load');
        };


        $scope.load();
    });
