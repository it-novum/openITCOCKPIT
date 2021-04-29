angular.module('openITCOCKPIT')
    .controller('WizardsIndexController', function($scope, $http){

        /** public vars **/
        $scope.init = true;
        $scope.filter = {
            Category: {
                linux: true,
                windows: true,
                database: true,
                mail: true,
                network: true,
                docker: true,
                macos: true,
                virtualization: true
            }
        };

        $scope.load = function(){
            
            $http.get("/wizards/index.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){

                $scope.wizards = result.data.wizards;
                $scope.possibleWizards = result.data.possibleWizards;
                $scope.init = false;
            });
        };

        $scope.filterByCategory = function(wizardCategory){
            for(var i in wizardCategory){
                if($scope.filter.Category.hasOwnProperty(wizardCategory[i]) && $scope.filter.Category[wizardCategory[i]] === true){
                    return true;
                }
            }
            return false;
        };

        //Fire on page load
        $scope.load();
    });
