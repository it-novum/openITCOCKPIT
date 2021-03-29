angular.module('openITCOCKPIT')
    .controller('WizardsEditController', function($scope, $http, $state, $stateParams, NotyService, RedirectService){
        $scope.uuid = $stateParams.uuid;
        $scope.typeId = $stateParams.typeId;

        /** public vars **/
        $scope.init = true;

        $scope.load = function(){
            $http.get("/wizards/edit/"+$scope.uuid+".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){

                $scope.wizardAssignments = result.data.wizardAssignments;
                $scope.servicetemplates = result.data.servicetemplates;
                $scope.init = false;
            });
        };


        $scope.submit = function(){
            $http.post("/wizards/edit/" + $scope.uuid + ".json?angular=true",
                $scope.wizardAssignments
            ).then(function(result){
                var url = $state.href('WizardsEdit', {
                    uuid: $scope.uuid,
                    typeId: $scope.typeId
                });
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('WizardsAssignments');

                console.log('Data saved successfully');
            }, function errorCallback(result){

                NotyService.genericError();

                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };


        //Fire on page load
        $scope.load();
    });
