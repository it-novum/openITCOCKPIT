angular.module('openITCOCKPIT')
    .controller('ContactgroupsAddController', function($scope, $http, SudoService, $state, NotyService, RedirectService){

        $scope.data = {
            createAnother: false
        };

        var clearForm = function(){
            $scope.post = {
                Contactgroup: {
                    description: '',
                    container: {
                        parent_id: null
                    },
                    contacts: {
                        _ids: []
                    }
                }
            };
        };
        clearForm();

        $scope.init = true;


        $scope.loadContainers = function(){
            var params = {
                'angular': true
            };

            $http.get("/contactgroups/loadContainers.json", {
                params: params
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };


        $scope.loadContacts = function(){
            var id = $scope.post.Contactgroup.container.parent_id;
            $http.post("/contactgroups/loadContacts/" + id + ".json?angular=true", {}).then(function(result){
                $scope.contacts = result.data.contacts;
            });
        };

        $scope.submit = function(){
            $http.post("/contactgroups/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('ContactgroupsEdit', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                if($scope.data.createAnother === false){
                    RedirectService.redirectWithFallback('ContactgroupsIndex');
                }else{
                    clearForm();
                    $scope.errors = {};
                    NotyService.scrollTop();
                }

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

        $scope.loadContainers();

        $scope.$watch('post.Contactgroup.container.parent_id', function(){
            if($scope.init){
                return;
            }

            if(!$scope.post.Contactgroup.container.parent_id){
                //Create another
                return;
            }

            $scope.loadContacts();
        }, true);

    });
