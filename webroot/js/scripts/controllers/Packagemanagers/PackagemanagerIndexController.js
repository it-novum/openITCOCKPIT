angular.module('openITCOCKPIT')
    .controller('PackagemanagerIndexController', function($scope, $http){

        $scope.newVersion = null;
        $scope.installedModules = {};
        $scope.modulesToInstall = [];
        $scope.modulesToCheckboxesInstall = {};

        $scope.isArray = angular.isArray;

        $scope.load = function(){
            $scope.selectedUserContainers = [];
            $scope.selectedUserContainerWithPermission = {};

            $http.get("/packetmanager/index.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.installedModules = result.data.installedModules;

                if(result.data.result.error === false){
                    $scope.modules = result.data.result.data.modules;
                    $scope.changelog = result.data.result.data.changelog;

                    var currentVersion = parseInt(result.data.OPENITCOCKPIT_VERSION.replace(/\D/g, ''), 10); //Remove all non numbers and parse to int
                    var newVersion = parseInt(result.data.result.data.changelog[0].Changelog.version.replace(/\D/g, ''), 10); //Remove all non numbers and parse to int

                    $scope.newVersion = currentVersion < newVersion;
                }

                $scope.error = result.data.result.error;
                $scope.error_msg = result.data.result.error_msg;

            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.installPackage = function(moduleAptName){
            var position = $scope.modulesToInstall.indexOf(moduleAptName);

            if(position !== -1){
                //Module is in array, so it got un-selected by the user
                $scope.modulesToInstall.splice(position, 1);
                $scope.modulesToCheckboxesInstall[moduleAptName] = false; //checkbox value
            }else{
                $scope.modulesToInstall.push(moduleAptName);
                $scope.modulesToCheckboxesInstall[moduleAptName] = true; //checkbox value
            }

            if($scope.modulesToInstall.length > 0){
                $('#installPackageModal').modal('show');
            }
        };

        /**
         * used for v3 License tags
         */
        $scope.splitTags = function(str){
            if(!angular.isString(str)){
                return false;
            }
            return str.split(',');
        };

        $scope.getCliCommand = function(){
            return $scope.modulesToInstall.join(' \\ <br>');
        };

        $scope.clipboardCommand = function(){
            // If you change this command please make also sure to change the command in the index.php template !!

            var command = 'sudo apt-get update && apt-get dist-upgrade && apt-get install ';
            command += $scope.modulesToInstall.join(' ');
            command += ' && echo "#########################################" && echo "Installation done. Please reload your openITCOCKPIT web interface."';

            navigator.clipboard.writeText(command);
        };

        jQuery(document).ready(function(){
            setTimeout(function(){
                jQuery(function(){
                    jQuery("[rel=tooltip]").tooltip();
                });
            }, 250);
        });

        jQuery(document).on('show.bs.tooltip', function(e){
            setTimeout(function(){
                jQuery('[data-toggle="tooltip"]').tooltip('hide');
            }, 1500);
        });

        //Fire on page load
        $scope.load();

    });
